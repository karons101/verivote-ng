<?php

namespace App\Services;

use App\Models\Evidence;
use App\Models\Result;
use App\Models\Signature;
use RuntimeException;

/**
 * Handles cryptographic integrity operations for VeriVote NG results.
 *
 * Produces deterministic SHA-256 fingerprints, binds result payloads to
 * their evidence fingerprints, creates Ed25519 signatures, and verifies
 * the resulting cryptographic record.
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
     * Build the deterministic message signed by Ed25519.
     *
     * The message binds the result payload fingerprint to the fingerprint
     * of its source evidence.
     */
    public function buildSigningMessage(
        string $payloadHash,
        string $evidenceHash
    ): string {
        return $payloadHash . ':' . $evidenceHash;
    }

    /**
     * Create an Ed25519 detached signature for a result and evidence pair.
     */
    public function signPayloadHash(
        string $payloadHash,
        string $secretKey,
        ?string $evidenceHash = null
    ): string {
        if (strlen($secretKey) !== SODIUM_CRYPTO_SIGN_SECRETKEYBYTES) {
            throw new RuntimeException('Invalid Ed25519 secret key.');
        }

        $message = $evidenceHash === null
            ? $payloadHash
            : $this->buildSigningMessage($payloadHash, $evidenceHash);

        return sodium_crypto_sign_detached($message, $secretKey);
    }

    /**
     * Persist a result signature and its public verification key.
     *
     * When evidence is supplied, the signature cryptographically binds the
     * result payload hash to the evidence file hash.
     *
     * Binary cryptographic values are encoded as hexadecimal strings so
     * they can be stored safely in the database text columns.
     */
    public function signResult(
        Result $result,
        string $signerRole,
        string $secretKey,
        string $publicKey,
        ?Evidence $evidence = null
    ): Signature {
        $payloadHash = $this->generatePayloadHash($result);

        if ($result->payload_hash !== $payloadHash) {
            $result->payload_hash = $payloadHash;
            $result->save();
        }

        $evidenceHash = $evidence?->file_hash;

        $signature = $this->signPayloadHash(
            $payloadHash,
            $secretKey,
            $evidenceHash
        );

        return Signature::create([
            'result_id' => $result->id,
            'signer_role' => $signerRole,
            'public_key' => bin2hex($publicKey),
            'signature' => bin2hex($signature),
            'signing_timestamp' => now(),
        ]);
    }

    /**
     * Verify an Ed25519 signature against a result payload and evidence hash.
     */
    public function verifySignature(
        string $payloadHash,
        string $signature,
        string $publicKey,
        ?string $evidenceHash = null
    ): bool {
        if (strlen($signature) !== SODIUM_CRYPTO_SIGN_BYTES) {
            return false;
        }

        if (strlen($publicKey) !== SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
            return false;
        }

        $message = $evidenceHash === null
            ? $payloadHash
            : $this->buildSigningMessage($payloadHash, $evidenceHash);

        return sodium_crypto_sign_verify_detached(
            $signature,
            $message,
            $publicKey
        );
    }

    /**
     * Verify the persisted signature associated with a result.
     *
     * When evidence is present, its stored fingerprint is included in the
     * cryptographic verification boundary.
     */
    public function verifyResultSignature(Result $result): bool
    {
        $result->loadMissing([
            'signature',
            'evidence',
        ]);

        if (!$result->signature || !$result->payload_hash) {
            return false;
        }

        $signature = hex2bin($result->signature->signature);
        $publicKey = hex2bin($result->signature->public_key);

        if ($signature === false || $publicKey === false) {
            return false;
        }

        $evidenceHash = $result->evidence->first()?->file_hash;

        return $this->verifySignature(
            $result->payload_hash,
            $signature,
            $publicKey,
            $evidenceHash
        );
    }
}