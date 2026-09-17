<?php

namespace App\Services;

use App\Models\Evidence;
use App\Models\Result;
use Illuminate\Http\UploadedFile;

/**
 * Handles evidence storage and integrity fingerprinting for VeriVote NG.
 *
 * Stores uploaded result evidence and records its SHA-256 fingerprint so
 * the original evidence can be checked for changes during verification.
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
}