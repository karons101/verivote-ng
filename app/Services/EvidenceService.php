<?php

namespace App\Services;

use App\Models\Evidence;
use App\Models\Result;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Handles evidence storage and integrity fingerprinting for VeriVote NG.
 *
 * Stores uploaded result evidence, records its SHA-256 fingerprint, and
 * verifies the stored evidence against its original fingerprint.
 */
class EvidenceService
{
    /**
     * Store evidence associated with a result.
     *
     * The file is stored on the configured public disk and its SHA-256
     * fingerprint is calculated from the uploaded file contents.
     */
    public function store(
        Result $result,
        UploadedFile $file,
        ?string $capturedAt = null
    ): Evidence {
        $storagePath = $file->store('evidence', 'public');

        return Evidence::create([
            'result_id' => $result->id,
            'file_type' => $file->getClientMimeType(),
            'storage_path' => $storagePath,
            'file_hash' => hash_file('sha256', $file->getRealPath()),
            'captured_at' => $capturedAt,
        ]);
    }

    /**
     * Verify the integrity of stored evidence.
     *
     * Recalculates the SHA-256 fingerprint from the currently stored file
     * and compares it with the original fingerprint recorded in the database.
     *
     * A missing file or changed file contents results in a failed check.
     */
    public function verify(Evidence $evidence): array
    {
        $disk = Storage::disk('public');

        if (!$disk->exists($evidence->storage_path)) {
            return [
                'passed' => false,
                'details' => 'The stored evidence file could not be found.',
                'expected_hash' => $evidence->file_hash,
                'actual_hash' => null,
            ];
        }

        $filePath = $disk->path($evidence->storage_path);
        $actualHash = hash_file('sha256', $filePath);

        $passed = hash_equals(
            $evidence->file_hash,
            $actualHash
        );

        return [
            'passed' => $passed,
            'details' => $passed
                ? 'The stored evidence file matches its recorded SHA-256 fingerprint.'
                : 'The stored evidence file does not match its recorded SHA-256 fingerprint.',
            'expected_hash' => $evidence->file_hash,
            'actual_hash' => $actualHash,
        ];
    }
}