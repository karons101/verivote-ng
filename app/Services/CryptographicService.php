<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Signature;
use RuntimeException;

/**
 * Handles cryptographic integrity operations for VeriVote NG results.
 *
 * Produces deterministic SHA-256 fingerprints, creates Ed25519 signatures,
 * and persists cryptographic evidence required for later verification.
 */
class CryptographicService
{
    /**
     * Generate a deterministic SHA-256 hash for a result payload.
     *
     * Only result values and candidate vote entries that define the
     * verifiable result payload are included in the hash.
     */
    public function generatePayloadHash(Result $result): string
    {
        $result->loadMissing('resultEntries');

        $payload = [
            'election_id' => $result->election_id,
            'polling_unit_id' => $result->polling_unit_id,
            'submission_channel' => $result->submission_channel,
            'accredited_voters' => $result->accredited_voters,
            'ballots_issued' => $result->ballots_issued,
            'unused_ballots' => $result->unused_ballots,
            'spoiled_ballots' => $result->spoiled_ballots,
            'rejected_votes' => $result->rejected_votes,
            'total_valid_votes' => $result->total_valid_votes,
            'entries' => $result->resultEntries
                ->sortBy('candidate_id')
                ->values()
                ->map(fn ($entry) => [
                    'candidate_id' => $entry->candidate_id,
                    'vote_count' => $entry->vote_count,
                ])
                ->all(),
        ];

        return hash(
            'sha256',
            json_encode(
                $payload,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
    }

    /**
     * Store the generated payload hash on the result.
     */
    public function fingerprint(Result $result): Result
    {
        $result->payload_hash = $this->generatePayloadHash($result);
        $result->save();

        return $result->refresh();
    }

    /**
     * Generate an Ed25519 signing key pair.
     *
     * The secret key must remain protected and must never be persisted
     * in the signatures table or exposed through application responses.
     */
    public function generateKeyPair(): array
    {
        $keyPair = sodium_crypto_sign_keypair();

        return [
            'public_key' => sodium_crypto_sign_publickey($keyPair),
            'secret_key' => sodium_crypto_sign_secretkey($keyPair),
        ];
    }

    /**
     * Create an Ed25519 detached signature for a result payload hash.
     */
    public function signPayloadHash(
        string $payloadHash,
        string $secretKey
    ): string {
        if (strlen($secretKey) !== SODIUM_CRYPTO_SIGN_SECRETKEYBYTES) {
            throw new RuntimeException('Invalid Ed25519 secret key.');
        }

        return sodium_crypto_sign_detached($payloadHash, $secretKey);
    }

    /**
     * Persist a result signature and its public verification key.
     *
     * Binary cryptographic values are encoded as hexadecimal strings so
     * they can be stored safely in the database text columns.
     */
    public function signResult(
        Result $result,
        string $signerRole,
        string $secretKey,
        string $publicKey
    ): Signature {
        $payloadHash = $result->payload_hash
            ?? $this->generatePayloadHash($result);

        if ($result->payload_hash !== $payloadHash) {
            $result->payload_hash = $payloadHash;
            $result->save();
        }

        $signature = $this->signPayloadHash($payloadHash, $secretKey);

        return Signature::create([
            'result_id' => $result->id,
            'signer_role' => $signerRole,
            'public_key' => bin2hex($publicKey),
            'signature' => bin2hex($signature),
            'signing_timestamp' => now(),
        ]);
    }

    /**
     * Verify a persisted Ed25519 signature against a result payload hash.
     */
    public function verifySignature(
        string $payloadHash,
        string $signature,
        string $publicKey
    ): bool {
        if (strlen($signature) !== SODIUM_CRYPTO_SIGN_BYTES) {
            return false;
        }

        if (strlen($publicKey) !== SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
            return false;
        }

        return sodium_crypto_sign_verify_detached(
            $signature,
            $payloadHash,
            $publicKey
        );
    }

    /**
     * Verify the persisted signature associated with a result.
     */
    public function verifyResultSignature(Result $result): bool
    {
        $result->loadMissing('signature');

        if (!$result->signature || !$result->payload_hash) {
            return false;
        }

        $signature = hex2bin($result->signature->signature);
        $publicKey = hex2bin($result->signature->public_key);

        if ($signature === false || $publicKey === false) {
            return false;
        }

        return $this->verifySignature(
            $result->payload_hash,
            $signature,
            $publicKey
        );
    }
}