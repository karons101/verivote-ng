{{--
    VeriVote NG Result Submission and Verification

    Provides the observer interface for recording polling-unit result data
    and reviewing the deterministic verification outcome for a recorded result.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriVote NG — Result Verification</title>
</head>
<body>
    <main>
        <h1>Record Polling Unit Result</h1>

        <p>
            Record election result data for a polling unit and submit it
            for deterministic integrity verification.
        </p>

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <section>
                <h2>Validation Errors</h2>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section>
            <h2>Submit Result</h2>

            <form method="POST" action="{{ route('results.store') }}">
                @csrf

                <div>
                    <label for="election_id">Election ID</label>
                    <input
                        type="number"
                        id="election_id"
                        name="election_id"
                        value="{{ old('election_id') }}"
                        required
                    >
                </div>

                <div>
                    <label for="polling_unit_id">Polling Unit ID</label>
                    <input
                        type="number"
                        id="polling_unit_id"
                        name="polling_unit_id"
                        value="{{ old('polling_unit_id') }}"
                        required
                    >
                </div>

                <div>
                    <label for="accredited_voters">Accredited Voters</label>
                    <input
                        type="number"
                        id="accredited_voters"
                        name="accredited_voters"
                        value="{{ old('accredited_voters') }}"
                        min="0"
                        required
                    >
                </div>

                <div>
                    <label for="ballots_issued">Ballots Issued</label>
                    <input
                        type="number"
                        id="ballots_issued"
                        name="ballots_issued"
                        value="{{ old('ballots_issued') }}"
                        min="0"
                        required
                    >
                </div>

                <div>
                    <label for="unused_ballots">Unused Ballots</label>
                    <input
                        type="number"
                        id="unused_ballots"
                        name="unused_ballots"
                        value="{{ old('unused_ballots') }}"
                        min="0"
                        required
                    >
                </div>

                <div>
                    <label for="spoiled_ballots">Spoiled Ballots</label>
                    <input
                        type="number"
                        id="spoiled_ballots"
                        name="spoiled_ballots"
                        value="{{ old('spoiled_ballots') }}"
                        min="0"
                        required
                    >
                </div>

                <div>
                    <label for="rejected_votes">Rejected Votes</label>
                    <input
                        type="number"
                        id="rejected_votes"
                        name="rejected_votes"
                        value="{{ old('rejected_votes') }}"
                        min="0"
                        required
                    >
                </div>

                <div>
                    <label for="total_valid_votes">Total Valid Votes</label>
                    <input
                        type="number"
                        id="total_valid_votes"
                        name="total_valid_votes"
                        value="{{ old('total_valid_votes') }}"
                        min="0"
                        required
                    >
                </div>

                <fieldset>
                    <legend>Candidate Votes</legend>

                    <div>
                        <label for="candidate_id">Candidate ID</label>
                        <input
                            type="number"
                            id="candidate_id"
                            name="entries[0][candidate_id]"
                            value="{{ old('entries.0.candidate_id') }}"
                            required
                        >
                    </div>

                    <div>
                        <label for="vote_count">Vote Count</label>
                        <input
                            type="number"
                            id="vote_count"
                            name="entries[0][vote_count]"
                            value="{{ old('entries.0.vote_count') }}"
                            min="0"
                            required
                        >
                    </div>
                </fieldset>

                <button type="submit">Submit Result</button>
            </form>
        </section>

        <section>
            <h2>Verification</h2>

            <p>
                Run VeriVote NG's deterministic integrity checks against
                an existing recorded result.
            </p>

            <form
                method="POST"
                action="{{ route('results.verify', ['result' => 1]) }}"
            >
                @csrf

                <div>
                    <label for="verification_result_id">Result ID</label>
                    <input
                        type="number"
                        id="verification_result_id"
                        name="result_id"
                        value="1"
                        min="1"
                        required
                    >
                </div>

                <button type="submit">Run Verification</button>
            </form>
        </section>

        @if ($result && $result->verificationChecks->isNotEmpty())
            <section>
                <h2>Verification Results</h2>

                <p>
                    Verification results for Result #{{ $result->id }}.
                </p>

                @foreach ($result->verificationChecks as $check)
                    <article>
                        <h3>
                            {{ $check->rule_name }}
                            —
                            {{ $check->passed ? 'PASSED' : 'FAILED' }}
                        </h3>

                        <p>{{ $check->details }}</p>

                        <p>
                            Executed:
                            {{ $check->executed_at?->format('Y-m-d H:i:s') }}
                        </p>
                    </article>
                @endforeach
            </section>
        @endif
    </main>
</body>
</html>