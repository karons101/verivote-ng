{{--
    VeriVote NG Result Integrity Detail

    Presents the complete integrity record for a polling-unit result,
    including result data, cryptographic verification, deterministic checks,
    detected discrepancies, and the tamper-evident audit trail.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        VeriVote NG — Result #{{ $result->id }}
    </title>
</head>

<body>
    <main>
        <header>
            <p>
                <a href="{{ route('dashboard') }}">
                    Back to Dashboard
                </a>
            </p>

            <h1>
                Result #{{ $result->id }}
            </h1>

            <p>
                Polling-unit result integrity record.
            </p>
        </header>

        @if (session('success'))
            <section>
                <p>
                    <strong>
                        {{ session('success') }}
                    </strong>
                </p>
            </section>
        @endif

        <section>
            <h2>Verification Status</h2>

            <p>
                <strong>
                    {{ strtoupper($result->status) }}
                </strong>
            </p>

            @if ($result->status === 'verified')
                <p>
                    All deterministic verification checks passed.
                </p>
            @elseif ($result->status === 'flagged')
                <p>
                    One or more integrity checks failed and require human
                    investigation.
                </p>
            @else
                <p>
                    This result has not reached a final verification state.
                </p>
            @endif
        </section>

        <section>
            <h2>Polling Unit</h2>

            <p>
                <strong>Code:</strong>
                {{ $result->pollingUnit->pu_code }}
            </p>

            <p>
                <strong>Name:</strong>
                {{ $result->pollingUnit->name }}
            </p>

            <p>
                <strong>Location:</strong>
                {{ $result->pollingUnit->state }},
                {{ $result->pollingUnit->lga }},
                {{ $result->pollingUnit->ward }}
            </p>

            <p>
                <strong>Registered Voters:</strong>
                {{ $result->pollingUnit->registered_voters }}
            </p>
        </section>

        <section>
            <h2>Election</h2>

            <p>
                <strong>Code:</strong>
                {{ $result->election->code }}
            </p>

            <p>
                <strong>Title:</strong>
                {{ $result->election->title }}
            </p>

            <p>
                <strong>Type:</strong>
                {{ $result->election->election_type }}
            </p>

            <p>
                <strong>Voting Date:</strong>
                {{ $result->election->voting_date }}
            </p>
        </section>

        <section>
            <h2>Result Accounting</h2>

            <dl>
                <dt>Accredited Voters</dt>
                <dd>{{ $result->accredited_voters }}</dd>

                <dt>Ballots Issued</dt>
                <dd>{{ $result->ballots_issued }}</dd>

                <dt>Unused Ballots</dt>
                <dd>{{ $result->unused_ballots }}</dd>

                <dt>Spoiled Ballots</dt>
                <dd>{{ $result->spoiled_ballots }}</dd>

                <dt>Rejected Votes</dt>
                <dd>{{ $result->rejected_votes }}</dd>

                <dt>Total Valid Votes</dt>
                <dd>{{ $result->total_valid_votes }}</dd>
            </dl>
        </section>

        <section>
            <h2>Candidate Votes</h2>

            @if ($result->resultEntries->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th>Party</th>
                            <th>Candidate</th>
                            <th>Votes</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($result->resultEntries as $entry)
                            <tr>
                                <td>
                                    {{ $entry->candidate->party_code }}
                                </td>

                                <td>
                                    {{ $entry->candidate->candidate_name }}
                                </td>

                                <td>
                                    {{ $entry->vote_count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>
                    No candidate vote entries are recorded for this result.
                </p>
            @endif
        </section>

        <section>
            <h2>Cryptographic Integrity</h2>

            <p>
                <strong>Payload SHA-256:</strong>
                {{ $result->payload_hash ?? 'Not generated' }}
            </p>

            @if ($result->signature)
                <p>
                    <strong>Signer Role:</strong>
                    {{ $result->signature->signer_role }}
                </p>

                <p>
                    <strong>Public Key:</strong>
                    {{ $result->signature->public_key }}
                </p>

                <p>
                    <strong>Signature:</strong>
                    {{ $result->signature->signature }}
                </p>

                <p>
                    <strong>Signed At:</strong>
                    {{ $result->signature->signing_timestamp?->format('Y-m-d H:i:s') }}
                </p>
            @else
                <p>
                    No cryptographic signature is recorded for this result.
                </p>
            @endif
        </section>

        <section>
            <h2>Source Evidence</h2>

            @forelse ($result->evidence as $evidence)
                <article>
                    <p>
                        <strong>Type:</strong>
                        {{ $evidence->file_type }}
                    </p>

                    <p>
                        <strong>SHA-256:</strong>
                        {{ $evidence->file_hash }}
                    </p>

                    <p>
                        <strong>Stored Path:</strong>
                        {{ $evidence->storage_path }}
                    </p>

                    <p>
                        <strong>Captured At:</strong>
                        {{ $evidence->captured_at?->format('Y-m-d H:i:s') ?? 'Not recorded' }}
                    </p>
                </article>
            @empty
                <p>
                    No source evidence is attached to this result.
                </p>
            @endforelse
        </section>

        <section>
            <h2>Deterministic Verification</h2>

            @forelse ($result->verificationChecks as $check)
                <article>
                    <h3>
                        {{ $check->rule_name }}
                        —
                        {{ $check->passed ? 'PASSED' : 'FAILED' }}
                    </h3>

                    <p>
                        {{ $check->details }}
                    </p>

                    <p>
                        <strong>Executed:</strong>
                        {{ $check->executed_at?->format('Y-m-d H:i:s') }}
                    </p>
                </article>
            @empty
                <p>
                    No verification checks have been recorded.
                </p>
            @endforelse
        </section>

        <section>
            <h2>Discrepancies</h2>

            @forelse ($result->discrepancies as $discrepancy)
                <article>
                    <h3>
                        {{ $discrepancy->code }}
                        —
                        {{ $discrepancy->type }}
                    </h3>

                    <p>
                        <strong>Severity:</strong>
                        {{ $discrepancy->severity }}
                    </p>

                    <p>
                        <strong>Status:</strong>
                        {{ $discrepancy->status }}
                    </p>

                    <p>
                        {{ $discrepancy->description }}
                    </p>

                    <p>
                        <strong>Detected:</strong>
                        {{ $discrepancy->detected_at?->format('Y-m-d H:i:s') }}
                    </p>
                </article>
            @empty
                <p>
                    No discrepancies were detected for this result.
                </p>
            @endforelse
        </section>

        <section>
            <h2>Audit Trail</h2>

            @forelse ($result->auditLogs as $audit)
                <article>
                    <h3>
                        {{ $audit->action }}
                    </h3>

                    <p>
                        <strong>Entity:</strong>
                        {{ $audit->entity_name }} #{{ $audit->entity_id }}
                    </p>

                    <p>
                        <strong>Recorded:</strong>
                        {{ $audit->created_at?->format('Y-m-d H:i:s') }}
                    </p>

                    <p>
                        <strong>Previous Audit Hash:</strong>
                        {{ $audit->prev_audit_hash ?? 'Genesis record' }}
                    </p>

                    <p>
                        <strong>Current Audit Hash:</strong>
                        {{ $audit->current_hash }}
                    </p>
                </article>
            @empty
                <p>
                    No audit events are recorded for this result.
                </p>
            @endforelse
        </section>

        <section>
            <h2>Verification Action</h2>

            <form
                method="POST"
                action="{{ route('results.verify', ['result' => $result->id]) }}"
            >
                @csrf

                <button type="submit">
                    Run Verification Again
                </button>
            </form>
        </section>
    </main>
</body>
</html>