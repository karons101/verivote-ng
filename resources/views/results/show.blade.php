{{-- 
    VeriVote NG Result Integrity Detail

    Presents the complete integrity record for a polling-unit result,
    including verification status, result accounting, cryptographic evidence,
    deterministic checks, discrepancies, and the tamper-evident audit trail.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VeriVote NG — Result #{{ $result->id }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
        :root {
            --bg: #07111f;
            --panel: #0d1b2e;
            --panel-soft: rgba(255, 255, 255, 0.025);
            --border: rgba(148, 163, 184, 0.16);
            --text: #f8fafc;
            --muted: #9fb0c3;
            --accent: #38bdf8;
            --accent-soft: rgba(56, 189, 248, 0.1);
            --success: #34d399;
            --success-soft: rgba(52, 211, 153, 0.1);
            --warning: #fbbf24;
            --warning-soft: rgba(251, 191, 36, 0.1);
            --danger: #fb7185;
            --danger-soft: rgba(251, 113, 133, 0.1);
            --max: 1180px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family:
                Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(
                    circle at 15% 0%,
                    rgba(56, 189, 248, 0.1),
                    transparent 28%
                ),
                var(--bg);
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(var(--max), calc(100% - 40px));
            margin: 0 auto;
        }

        .topbar {
            border-bottom: 1px solid var(--border);
            background: rgba(7, 17, 31, 0.9);
            backdrop-filter: blur(16px);
        }

        .topbar-inner {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-mark {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: #03111d;
            font-weight: 900;
        }

        .brand-name span {
            color: var(--accent);
        }

        .back-link {
            color: var(--muted);
            font-size: 0.84rem;
        }

        .back-link:hover {
            color: var(--text);
        }

        main {
            padding: 55px 0 100px;
        }

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 25px;
        }

        .kicker {
            color: var(--accent);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        h1 {
            margin-top: 7px;
            font-size: clamp(2.3rem, 5vw, 3.8rem);
            line-height: 1;
            letter-spacing: -0.06em;
        }

        .subtitle {
            margin-top: 12px;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 900;
            letter-spacing: 0.04em;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .status-verified {
            color: #a7f3d0;
            background: var(--success-soft);
        }

        .status-verified .status-dot {
            background: var(--success);
            box-shadow: 0 0 10px rgba(52, 211, 153, 0.65);
        }

        .status-flagged {
            color: #fde68a;
            background: var(--warning-soft);
        }

        .status-flagged .status-dot {
            background: var(--warning);
            box-shadow: 0 0 10px rgba(251, 191, 36, 0.55);
        }

        .status-other {
            color: #bae6fd;
            background: var(--accent-soft);
        }

        .status-other .status-dot {
            background: var(--accent);
        }

        .alert {
            margin-bottom: 20px;
            padding: 15px 17px;
            border: 1px solid rgba(52, 211, 153, 0.2);
            border-radius: 11px;
            background: var(--success-soft);
            color: #a7f3d0;
            font-size: 0.84rem;
        }

        .hero-status {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .status-card,
        .card {
            border: 1px solid var(--border);
            border-radius: 16px;
            background:
                linear-gradient(
                    145deg,
                    rgba(16, 35, 58, 0.9),
                    rgba(9, 24, 40, 0.92)
                );
        }

        .status-card {
            padding: 24px;
        }

        .status-card h2 {
            margin-top: 14px;
            font-size: 1.2rem;
            letter-spacing: -0.03em;
        }

        .status-card p {
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.83rem;
        }

        .result-id-card {
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .result-id-label {
            color: var(--muted);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .result-id {
            margin-top: 3px;
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 1.65rem;
            font-weight: 800;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .card-header {
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            font-size: 1rem;
            letter-spacing: -0.025em;
        }

        .card-header p {
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.76rem;
        }

        .card-body {
            padding: 22px;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .detail {
            padding: 12px;
            border-radius: 10px;
            background: var(--panel-soft);
        }

        .detail-label {
            color: var(--muted);
            font-size: 0.67rem;
        }

        .detail-value {
            margin-top: 3px;
            overflow-wrap: anywhere;
            font-size: 0.81rem;
            font-weight: 700;
        }

        .accounting {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .accounting-item {
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--panel-soft);
        }

        .accounting-item span {
            display: block;
            color: var(--muted);
            font-size: 0.66rem;
        }

        .accounting-item strong {
            display: block;
            margin-top: 3px;
            font-size: 1.08rem;
        }

        .votes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .votes-table th,
        .votes-table td {
            padding: 11px 8px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            font-size: 0.78rem;
        }

        .votes-table th {
            color: var(--muted);
            font-size: 0.67rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .votes-table td:last-child,
        .votes-table th:last-child {
            text-align: right;
        }

        .votes-table tr:last-child td {
            border-bottom: 0;
        }

        .hash {
            padding: 13px;
            border-radius: 9px;
            background: rgba(0, 0, 0, 0.18);
            overflow-wrap: anywhere;
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.67rem;
            color: #bae6fd;
        }

        .crypto-item + .crypto-item {
            margin-top: 16px;
        }

        .crypto-label {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 0.68rem;
            font-weight: 700;
        }

        .evidence-item,
        .check-item,
        .discrepancy-item,
        .audit-item {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: var(--panel-soft);
        }

        .evidence-item + .evidence-item,
        .check-item + .check-item,
        .discrepancy-item + .discrepancy-item,
        .audit-item + .audit-item {
            margin-top: 10px;
        }

        .item-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
        }

        .item-header h3 {
            font-size: 0.82rem;
        }

        .item-meta {
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.72rem;
        }

        .check-status {
            padding: 4px 7px;
            border-radius: 999px;
            font-size: 0.62rem;
            font-weight: 900;
        }

        .check-passed {
            color: #a7f3d0;
            background: var(--success-soft);
        }

        .check-failed {
            color: #fecdd3;
            background: var(--danger-soft);
        }

        .item-description {
            margin-top: 8px;
            color: #cbd5e1;
            font-size: 0.76rem;
        }

        .discrepancy-item {
            border-color: rgba(251, 191, 36, 0.2);
        }

        .discrepancy-code {
            color: #fde68a;
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.7rem;
            font-weight: 800;
        }

        .audit-hash {
            margin-top: 8px;
            padding: 10px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.16);
            overflow-wrap: anywhere;
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.64rem;
            color: #bae6fd;
        }

        .empty {
            padding: 25px 10px;
            color: var(--muted);
            text-align: center;
            font-size: 0.78rem;
        }

        .action-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 16px;
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--panel-soft);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 15px;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: #dbeafe;
            background: rgba(255, 255, 255, 0.025);
            font-size: 0.76rem;
            font-weight: 800;
            cursor: pointer;
        }

        .button:hover {
            border-color: rgba(56, 189, 248, 0.35);
        }

        .button-primary {
            border-color: var(--accent);
            background: var(--accent);
            color: #03111d;
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 26px 0;
            color: var(--muted);
            font-size: 0.74rem;
        }

        @media (max-width: 850px) {
            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .hero-status,
            .grid {
                grid-template-columns: 1fr;
            }

            .accounting {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .container {
                width: min(var(--max), calc(100% - 28px));
            }

            main {
                padding: 45px 0 70px;
            }

            .details,
            .accounting {
                grid-template-columns: 1fr;
            }

            .card-header,
            .card-body,
            .status-card,
            .result-id-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

    <header class="topbar">
        <div class="container topbar-inner">

            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark">V</span>
                <span class="brand-name">Veri<span>Vote</span> NG</span>
            </a>

            <a href="{{ route('dashboard') }}" class="back-link">
                ← Back to dashboard
            </a>

        </div>
    </header>

    <main>
        <div class="container">

            <header class="page-header">

                <div>
                    <div class="kicker">Integrity record</div>

                    <h1>Result #{{ $result->id }}</h1>

                    <p class="subtitle">
                        Polling-unit result integrity record and verification history.
                    </p>
                </div>

                @if ($result->status === 'verified')

                    <span class="status status-verified">
                        <span class="status-dot"></span>
                        VERIFIED
                    </span>

                @elseif ($result->status === 'flagged')

                    <span class="status status-flagged">
                        <span class="status-dot"></span>
                        FLAGGED
                    </span>

                @else

                    <span class="status status-other">
                        <span class="status-dot"></span>
                        {{ strtoupper($result->status) }}
                    </span>

                @endif

            </header>

            @if (session('success'))
                <div class="alert">
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <section class="hero-status">

                <article class="status-card">

                    @if ($result->status === 'verified')

                        <span class="status status-verified">
                            <span class="status-dot"></span>
                            VERIFICATION PASSED
                        </span>

                        <h2>Integrity checks passed</h2>

                        <p>
                            All recorded verification checks passed for this
                            result. The record can be inspected through the
                            public verification workflow.
                        </p>

                    @elseif ($result->status === 'flagged')

                        <span class="status status-flagged">
                            <span class="status-dot"></span>
                            REVIEW REQUIRED
                        </span>

                        <h2>One or more integrity checks failed</h2>

                        <p>
                            A detected inconsistency requires human investigation.
                            The flagged state does not by itself establish fraud
                            or intent.
                        </p>

                    @else

                        <span class="status status-other">
                            <span class="status-dot"></span>
                            NOT FINAL
                        </span>

                        <h2>Verification is not yet final</h2>

                        <p>
                            This result has not reached a final verification state.
                        </p>

                    @endif

                </article>

                <article class="result-id-card">

                    <div class="result-id-label">
                        Recorded result
                    </div>

                    <div class="result-id">
                        #{{ $result->id }}
                    </div>

                </article>

            </section>

            <div class="grid">

                {{-- Polling Unit --}}
                <section class="card">

                    <div class="card-header">
                        <h2>Polling Unit</h2>
                        <p>Location and registration context.</p>
                    </div>

                    <div class="card-body">

                        <div class="details">

                            <div class="detail">
                                <div class="detail-label">Code</div>
                                <div class="detail-value">
                                    {{ $result->pollingUnit->pu_code }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Name</div>
                                <div class="detail-value">
                                    {{ $result->pollingUnit->name }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">State</div>
                                <div class="detail-value">
                                    {{ $result->pollingUnit->state }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">LGA</div>
                                <div class="detail-value">
                                    {{ $result->pollingUnit->lga }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Ward</div>
                                <div class="detail-value">
                                    {{ $result->pollingUnit->ward }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Registered voters</div>
                                <div class="detail-value">
                                    {{ $result->pollingUnit->registered_voters }}
                                </div>
                            </div>

                        </div>

                    </div>
                </section>

                {{-- Election --}}
                <section class="card">

                    <div class="card-header">
                        <h2>Election</h2>
                        <p>Election context associated with this result.</p>
                    </div>

                    <div class="card-body">

                        <div class="details">

                            <div class="detail">
                                <div class="detail-label">Code</div>
                                <div class="detail-value">
                                    {{ $result->election->code }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Type</div>
                                <div class="detail-value">
                                    {{ $result->election->election_type }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Title</div>
                                <div class="detail-value">
                                    {{ $result->election->title }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Voting date</div>
                                <div class="detail-value">
                                    {{ $result->election->voting_date }}
                                </div>
                            </div>

                        </div>

                    </div>
                </section>

            </div>

            {{-- Result accounting --}}
            <section class="card" style="margin-top: 16px;">

                <div class="card-header">
                    <h2>Result Accounting</h2>
                    <p>Ballot and vote totals recorded for this result.</p>
                </div>

                <div class="card-body">

                    <div class="accounting">

                        <div class="accounting-item">
                            <span>Accredited voters</span>
                            <strong>{{ $result->accredited_voters }}</strong>
                        </div>

                        <div class="accounting-item">
                            <span>Ballots issued</span>
                            <strong>{{ $result->ballots_issued }}</strong>
                        </div>

                        <div class="accounting-item">
                            <span>Unused ballots</span>
                            <strong>{{ $result->unused_ballots }}</strong>
                        </div>

                        <div class="accounting-item">
                            <span>Spoiled ballots</span>
                            <strong>{{ $result->spoiled_ballots }}</strong>
                        </div>

                        <div class="accounting-item">
                            <span>Rejected votes</span>
                            <strong>{{ $result->rejected_votes }}</strong>
                        </div>

                        <div class="accounting-item">
                            <span>Total valid votes</span>
                            <strong>{{ $result->total_valid_votes }}</strong>
                        </div>

                    </div>

                </div>
            </section>

            {{-- Candidate votes --}}
            <section class="card" style="margin-top: 16px;">

                <div class="card-header">
                    <h2>Candidate Votes</h2>
                    <p>Candidate-level vote entries recorded for the result.</p>
                </div>

                <div class="card-body">

                    @if ($result->resultEntries->isNotEmpty())

                        <table class="votes-table">
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
                                            <strong>{{ $entry->vote_count }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @else

                        <div class="empty">
                            No candidate vote entries are recorded for this result.
                        </div>

                    @endif

                </div>
            </section>

            {{-- Cryptographic integrity --}}
            <section class="card" style="margin-top: 16px;">

                <div class="card-header">
                    <h2>Cryptographic Integrity</h2>
                    <p>Hashes and signature material associated with the result.</p>
                </div>

                <div class="card-body">

                    <div class="crypto-item">
                        <div class="crypto-label">
                            Payload SHA-256
                        </div>

                        <div class="hash">
                            {{ $result->payload_hash ?? 'Not generated' }}
                        </div>
                    </div>

                    @if ($result->signature)

                        <div class="details" style="margin-top: 18px;">

                            <div class="detail">
                                <div class="detail-label">Signer role</div>
                                <div class="detail-value">
                                    {{ $result->signature->signer_role }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Signed at</div>
                                <div class="detail-value">
                                    {{ $result->signature->signing_timestamp?->format('Y-m-d H:i:s') }}
                                </div>
                            </div>

                        </div>

                        <div class="crypto-item" style="margin-top: 18px;">
                            <div class="crypto-label">
                                Public key
                            </div>

                            <div class="hash">
                                {{ $result->signature->public_key }}
                            </div>
                        </div>

                        <div class="crypto-item">
                            <div class="crypto-label">
                                Ed25519 signature
                            </div>

                            <div class="hash">
                                {{ $result->signature->signature }}
                            </div>
                        </div>

                    @else

                        <div class="empty">
                            No cryptographic signature is recorded for this result.
                        </div>

                    @endif

                </div>
            </section>

            <div class="grid">

                {{-- Source evidence --}}
                <section class="card">

                    <div class="card-header">
                        <h2>Source Evidence</h2>
                        <p>Evidence associated with this recorded result.</p>
                    </div>

                    <div class="card-body">

                        @forelse ($result->evidence as $evidence)

                            <article class="evidence-item">

                                <div class="item-header">
                                    <h3>
                                        {{ $evidence->file_type }}
                                    </h3>
                                </div>

                                <div class="item-meta">
                                    Captured:
                                    {{ $evidence->captured_at?->format('Y-m-d H:i:s') ?? 'Not recorded' }}
                                </div>

                                <div class="crypto-label" style="margin-top: 13px;">
                                    Evidence SHA-256
                                </div>

                                <div class="hash">
                                    {{ $evidence->file_hash }}
                                </div>

                                <div class="item-meta">
                                    Stored path:
                                    {{ $evidence->storage_path }}
                                </div>

                            </article>

                        @empty

                            <div class="empty">
                                No source evidence is attached to this result.
                            </div>

                        @endforelse

                    </div>
                </section>

                {{-- Deterministic verification --}}
                <section class="card">

                    <div class="card-header">
                        <h2>Deterministic Verification</h2>
                        <p>Explicit integrity rules evaluated against this result.</p>
                    </div>

                    <div class="card-body">

                        @forelse ($result->verificationChecks as $check)

                            <article class="check-item">

                                <div class="item-header">

                                    <h3>
                                        {{ $check->rule_name }}
                                    </h3>

                                    <span
                                        class="check-status {{ $check->passed ? 'check-passed' : 'check-failed' }}"
                                    >
                                        {{ $check->passed ? 'PASSED' : 'FAILED' }}
                                    </span>

                                </div>

                                <p class="item-description">
                                    {{ $check->details }}
                                </p>

                                <div class="item-meta">
                                    Executed:
                                    {{ $check->executed_at?->format('Y-m-d H:i:s') }}
                                </div>

                            </article>

                        @empty

                            <div class="empty">
                                No verification checks have been recorded.
                            </div>

                        @endforelse

                    </div>
                </section>

            </div>

            {{-- Discrepancies --}}
            <section class="card" style="margin-top: 16px;">

                <div class="card-header">
                    <h2>Discrepancies</h2>
                    <p>
                        Integrity inconsistencies detected during verification.
                    </p>
                </div>

                <div class="card-body">

                    @forelse ($result->discrepancies as $discrepancy)

                        <article class="discrepancy-item">

                            <div class="item-header">

                                <div>
                                    <div class="discrepancy-code">
                                        {{ $discrepancy->code }}
                                    </div>

                                    <h3 style="margin-top: 4px;">
                                        {{ $discrepancy->type }}
                                    </h3>
                                </div>

                                <span class="check-status check-failed">
                                    {{ strtoupper($discrepancy->status) }}
                                </span>

                            </div>

                            <p class="item-description">
                                {{ $discrepancy->description }}
                            </p>

                            <div class="item-meta">
                                Severity:
                                {{ $discrepancy->severity }}
                                ·
                                Detected:
                                {{ $discrepancy->detected_at?->format('Y-m-d H:i:s') }}
                            </div>

                        </article>

                    @empty

                        <div class="empty">
                            No discrepancies were detected for this result.
                        </div>

                    @endforelse

                </div>
            </section>

            {{-- Audit trail --}}
            <section class="card" style="margin-top: 16px;">

                <div class="card-header">
                    <h2>Audit Trail</h2>
                    <p>
                        Hash-linked verification events recorded for this result.
                    </p>
                </div>

                <div class="card-body">

                    @forelse ($result->auditLogs as $audit)

                        <article class="audit-item">

                            <div class="item-header">

                                <h3>
                                    {{ $audit->action }}
                                </h3>

                                <span class="item-meta">
                                    {{ $audit->created_at?->format('Y-m-d H:i:s') }}
                                </span>

                            </div>

                            <div class="item-meta">
                                Entity:
                                {{ $audit->entity_name }} #{{ $audit->entity_id }}
                            </div>

                            <div class="crypto-label" style="margin-top: 13px;">
                                Previous audit hash
                            </div>

                            <div class="audit-hash">
                                {{ $audit->prev_audit_hash ?? 'Genesis record' }}
                            </div>

                            <div class="crypto-label" style="margin-top: 12px;">
                                Current audit hash
                            </div>

                            <div class="audit-hash">
                                {{ $audit->current_hash }}
                            </div>

                        </article>

                    @empty

                        <div class="empty">
                            No audit events are recorded for this result.
                        </div>

                    @endforelse

                </div>
            </section>

            {{-- Verification actions --}}
            <section class="action-bar">

                <form
                    method="POST"
                    action="{{ route('results.verify', ['result' => $result->id]) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Run Verification Again
                    </button>
                </form>

                <a
                    href="{{ route('public.verify', ['result' => $result->id]) }}"
                    class="button"
                >
                    Public Verification
                </a>

                <a
                    href="{{ route('public.verify.report', ['result' => $result->id]) }}"
                    class="button"
                >
                    Verification Report
                </a>

                <a
                    href="{{ route('public.verify.qr', ['result' => $result->id]) }}"
                    class="button"
                >
                    QR Verification
                </a>

            </section>

        </div>
    </main>

    <footer>
        <div class="container">
            VeriVote NG · Result Integrity Record · Evidence Verification MVP
        </div>
    </footer>

</body>
</html>