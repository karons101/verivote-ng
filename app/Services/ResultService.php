<?php

namespace App\Services;

use App\Models\Result;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Handles persistence of polling-unit results for VeriVote NG.
 *
 * Keeps result creation, candidate entries, evidence storage, cryptographic
 * signing, and deterministic verification inside a transactional workflow.
 */
class ResultService
{
    /**
     * Create a polling-unit result, candidate vote entries, source evidence,
     * its evidence-bound cryptographic signature, and verification checks.
     *
     * The complete operation is wrapped in a database transaction so the
     * result, entries, evidence, signature, and verification outcomes are
     * persisted together or not persisted at all.
     */
    public function create(array $data, UploadedFile $evidence): Result
    {
        return DB::transaction(function () use ($data, $evidence): Result {
            $entries = $data['entries'];
            unset($data['entries']);

            $result = Result::create($data);

            $result->resultEntries()->createMany($entries);

            $evidenceRecord = app(EvidenceService::class)->store(
                $result,
                $evidence
            );

            app(CryptographicService::class)->signResult(
                $result->fresh(['resultEntries']),
                'polling_official',
                $evidenceRecord
            );

            app(VerificationCheckService::class)->run(
                $result->fresh([
                    'resultEntries',
                    'evidence',
                    'signature',
                    'pollingUnit',
                ])
            );

            return $result->load([
                'resultEntries',
                'evidence',
                'signature',
                'verificationChecks',
            ]);
        });
    }
}