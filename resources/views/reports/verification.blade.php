{{--
    VeriVote NG Verification Report

    Presents a concise, read-only integrity report for a recorded polling-unit
    result, including result identity, verification status, cryptographic
    integrity, deterministic checks, discrepancies, and audit references.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        VeriVote NG — Verification Report #{{ $result->id }}
    </title>
</head>

<body>
    <main>
        <header>
            <p>
                <a href="{{ route('home') }}">
                    VeriVote NG
                </a>
            </p>

            <h1>
                Verification Report
            </h1>

            <p>
                Read-only integrity report for Result #{{ $result->id }}.
            </p>
        </header>

        <section>
            <h2>Verification Status</h2>

            <p>
                <strong>
                    {{ strtoupper($result->status) }}
                </strong>
            </p>

            @if ($result->status === 'verified')
                <p>
                    All recorded deterministic verification checks passed.
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
            <h2>Result Identity</h2>

            <dl>
                <dt>Result ID</dt>
                <dd>{{ $result->id }}</dd>

                <dt>Election Code</dt>
                <dd>{{ $result->election->code }}</dd>

                <dt>Election Title</dt>
                <dd>{{ $result->election->title }}</dd>

                <dt>Election Type</dt>
                <dd>{{ $result->election->election_type }}</dd>

                <dt>Voting Date</dt>
                <dd>{{ $result->election->voting_date }}</dd>

                <dt>Polling Unit Code</dt>
                <dd>{{ $result->pollingUnit->pu_code }}</dd>

                <dt>Polling Unit Name</dt>
                <dd>{{ $result->pollingUnit->name }}</dd>
            </dl>
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

            @forelse ($result->resultEntries as $entry)
                <article>
                    <p>
                        <strong>
                            {{ $entry->candidate->party_code }}
                        </strong>
                        —
                        {{ $entry->candidate->candidate_name }}
                    </p>

                    <p>
                        Votes: {{ $entry->vote_count }}
                    </p>
                </article>
            @empty
                <p>
                    No candidate vote entries are recorded.
                </p>
            @endforelse
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
                    No cryptographic signature is recorded.
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
                </article>
            @empty
                <p>
                    No source evidence is attached.
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
                </article>
            @empty
                <p>
                    No discrepancies were detected for this result.
                </p>
            @endforelse
        </section>

        <section>
            <h2>Audit References</h2>

            @forelse ($result->auditLogs as $audit)
                <article>
                    <h3>
                        {{ $audit->action }}
                    </h3>

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

        <footer>
            <p>
                VeriVote NG provides evidence verification and discrepancy
                detection. A flagged result identifies an integrity discrepancy
                requiring human investigation; it does not by itself establish
                the cause of that discrepancy.
            </p>
        </footer>
    </main>
</body>
</html>