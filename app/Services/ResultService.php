<?php

namespace App\Services;

use App\Models\Result;
use Illuminate\Support\Facades\DB;

/**
 * Handles persistence of polling-unit results for VeriVote NG.
 *
 * Keeps result creation and related database operations outside the HTTP
 * controller so business logic can be tested and reused independently.
 */
class ResultService
{
    /**
     * Create a polling-unit result and its candidate vote entries.
     *
     * The operation is wrapped in a database transaction so the result and
     * its entries are persisted together or not persisted at all.
     */
    public function create(array $data): Result
    {
        return DB::transaction(function () use ($data): Result {
            $entries = $data['entries'];
            unset($data['entries']);

            $result = Result::create($data);

            $result->resultEntries()->createMany($entries);

            return $result->load('resultEntries');
        });
    }
}