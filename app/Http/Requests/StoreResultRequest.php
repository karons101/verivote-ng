<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates polling-unit result submissions for VeriVote NG.
 *
 * Ensures the submitted result contains valid election, polling-unit,
 * ballot-accounting, candidate vote, and source-evidence data before processing.
 */
class StoreResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'election_id' => ['required', 'integer', 'exists:elections,id'],
            'polling_unit_id' => ['required', 'integer', 'exists:polling_units,id'],
            'accredited_voters' => ['required', 'integer', 'min:0'],
            'ballots_issued' => ['required', 'integer', 'min:0'],
            'unused_ballots' => ['required', 'integer', 'min:0'],
            'spoiled_ballots' => ['required', 'integer', 'min:0'],
            'rejected_votes' => ['required', 'integer', 'min:0'],
            'total_valid_votes' => ['required', 'integer', 'min:0'],
            'entries' => ['required', 'array', 'min:1'],
            'entries.*.candidate_id' => ['required', 'integer', 'exists:candidates,id'],
            'entries.*.vote_count' => ['required', 'integer', 'min:0'],
            'evidence' => ['required', 'file', 'max:10240'],
        ];
    }
}