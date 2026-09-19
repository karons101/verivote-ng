{{-- Public Verification — read-only integrity record for a VeriVote NG result. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Public Verification | VeriVote NG</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
        :root {
            --bg: #eef2f7;
            --bg-accent: #e8f0f4;
            --surface: #ffffff;
            --surface-soft: #f6f8fb;
            --surface-muted: #f1f5f9;

            --border: #d9e1ea;
            --border-strong: #c8d3df;

            --text: #172033;
            --text-soft: #334155;
            --muted: #64748b;

            --primary: #0f766e;
            --primary-dark: #115e59;
            --primary-soft: #e8f5f3;

            --success: #16803c;
            --success-soft: #edf8f0;
            --success-border: #cfe9d7;

            --warning: #a16207;
            --warning-soft: #fff8e8;
            --warning-border: #f1dfae;

            --danger: #b42318;
            --danger-soft: #fff1f0;

            --shadow-sm: 0 4px 14px rgba(15, 23, 42, 0.045);
            --shadow-md: 0 12px 30px rgba(15, 23, 42, 0.065);

            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(
                    circle at 90% 0%,
                    rgba(15, 118, 110, 0.07),
                    transparent 25%
                ),
                linear-gradient(
                    180deg,
                    var(--bg-accent) 0%,
                    var(--bg) 260px
                );
            color: var(--text);
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        /* Header */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;

            border-bottom: 1px solid rgba(203, 213, 225, 0.85);

            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
        }

        .topbar-inner {
            min-height: 72px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 24px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 11px;

            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-mark {
            width: 37px;
            height: 37px;

            display: grid;
            place-items: center;

            border-radius: 11px;

            color: #ffffff;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    #149488
                );

            box-shadow:
                0 7px 17px rgba(15, 118, 110, 0.18);

            font-size: 17px;
            font-weight: 900;
        }

        .brand-copy {
            display: flex;
            flex-direction: column;

            line-height: 1.1;
        }

        .brand-name {
            font-size: 15px;
        }

        .brand-subtitle {
            margin-top: 4px;

            color: var(--muted);

            font-size: 10px;
            font-weight: 700;

            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .topbar-link {
            color: var(--muted);

            font-size: 13px;
            font-weight: 750;

            transition:
                color 0.2s ease,
                transform 0.2s ease;
        }

        .topbar-link:hover {
            color: var(--primary);
            transform: translateX(-2px);
        }

        /* Main */

        main {
            padding: 48px 0 70px;
        }

        .intro {
            margin-bottom: 24px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 7px 11px;

            border: 1px solid rgba(15, 118, 110, 0.14);
            border-radius: 999px;

            background: var(--primary-soft);
            color: var(--primary-dark);

            font-size: 10px;
            font-weight: 850;

            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .eyebrow-dot {
            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: currentColor;
        }

        h1 {
            margin: 15px 0 9px;

            color: var(--text);

            font-size: clamp(30px, 5vw, 45px);
            line-height: 1.05;

            letter-spacing: -0.045em;
        }

        .intro p {
            max-width: 730px;

            margin: 0;

            color: var(--muted);

            font-size: 15px;
            line-height: 1.7;
        }

        /* Status */

        .status-card {
            margin-bottom: 22px;
            padding: 26px;

            border: 1px solid var(--border);
            border-radius: 19px;

            background: rgba(255, 255, 255, 0.92);

            box-shadow: var(--shadow-md);
        }

        .status-card.verified {
            border-color: var(--success-border);

            background:
                linear-gradient(
                    135deg,
                    rgba(237, 248, 240, 0.9),
                    rgba(255, 255, 255, 0.98) 65%
                );
        }

        .status-card.flagged {
            border-color: var(--warning-border);

            background:
                linear-gradient(
                    135deg,
                    rgba(255, 248, 232, 0.92),
                    rgba(255, 255, 255, 0.98) 65%
                );
        }

        .status-layout {
            display: grid;
            grid-template-columns: 1fr auto;

            align-items: center;

            gap: 28px;
        }

        .status-label {
            color: var(--muted);

            font-size: 10px;
            font-weight: 850;

            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .status-title {
            margin: 7px 0 8px;

            font-size: clamp(28px, 5vw, 41px);
            line-height: 1;

            letter-spacing: -0.045em;
        }

        .verified .status-title {
            color: var(--success);
        }

        .flagged .status-title {
            color: var(--warning);
        }

        .status-description {
            max-width: 700px;

            margin: 0;

            color: var(--muted);

            font-size: 13px;
            line-height: 1.65;
        }

        .status-badge {
            min-width: 145px;

            padding: 15px 18px;

            border-radius: 13px;

            text-align: center;

            font-size: 11px;
            font-weight: 900;

            letter-spacing: 0.08em;
        }

        .verified .status-badge {
            border: 1px solid var(--success-border);

            background: var(--success-soft);
            color: var(--success);
        }

        .flagged .status-badge {
            border: 1px solid var(--warning-border);

            background: var(--warning-soft);
            color: var(--warning);
        }

        /* Content grid */

        .grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 18px;
        }

        .card {
            padding: 22px;

            border: 1px solid var(--border);
            border-radius: var(--radius);

            background: rgba(255, 255, 255, 0.96);

            box-shadow: var(--shadow-sm);
        }

        .card.full {
            grid-column: 1 / -1;
        }

        .card-heading {
            margin-bottom: 17px;
        }

        .card-heading h2 {
            margin: 0 0 4px;

            color: var(--text);

            font-size: 16px;
            font-weight: 800;

            letter-spacing: -0.02em;
        }

        .card-heading p {
            margin: 0;

            color: var(--muted);

            font-size: 12px;
            line-height: 1.55;
        }

        /* Details */

        .details {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 12px;
        }

        .detail {
            padding: 13px;

            border: 1px solid #e1e7ee;
            border-radius: 11px;

            background: var(--surface-soft);
        }

        .detail-label {
            display: block;

            margin-bottom: 5px;

            color: var(--muted);

            font-size: 9px;
            font-weight: 850;

            letter-spacing: 0.065em;
            text-transform: uppercase;
        }

        .detail-value {
            color: var(--text-soft);

            font-size: 13px;
            font-weight: 750;

            overflow-wrap: anywhere;
        }

        .hash {
            font-family:
                "SFMono-Regular",
                Consolas,
                "Liberation Mono",
                monospace;

            font-size: 10px;
            line-height: 1.6;

            word-break: break-all;
        }

        /* Verification checks */

        .check-list {
            display: grid;
            gap: 9px;
        }

        .check {
            display: flex;
            align-items: flex-start;

            gap: 11px;

            padding: 12px;

            border: 1px solid #e1e7ee;
            border-radius: 11px;

            background: var(--surface-soft);
        }

        .check-icon {
            width: 26px;
            height: 26px;

            flex: 0 0 26px;

            display: grid;
            place-items: center;

            border-radius: 50%;

            font-size: 12px;
            font-weight: 900;
        }

        .check.passed .check-icon {
            color: var(--success);
            background: var(--success-soft);
        }

        .check.failed .check-icon {
            color: var(--danger);
            background: var(--danger-soft);
        }

        .check-content strong {
            display: block;

            margin-bottom: 3px;

            color: var(--text);

            font-size: 12px;
        }

        .check-content span {
            color: var(--muted);

            font-size: 11px;
            line-height: 1.55;
        }

        /* Discrepancies */

        .discrepancy {
            padding: 16px;

            border: 1px solid var(--warning-border);
            border-radius: 12px;

            background: var(--warning-soft);
        }

        .discrepancy + .discrepancy {
            margin-top: 10px;
        }

        .discrepancy-top {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 12px;

            margin-bottom: 7px;
        }

        .discrepancy-code {
            color: var(--warning);

            font-size: 10px;
            font-weight: 900;

            letter-spacing: 0.07em;
        }

        .discrepancy-status {
            padding: 4px 7px;

            border: 1px solid var(--warning-border);
            border-radius: 999px;

            background: rgba(255, 255, 255, 0.75);
            color: var(--warning);

            font-size: 9px;
            font-weight: 900;

            text-transform: uppercase;
        }

        .discrepancy h3 {
            margin: 0 0 5px;

            color: #713f12;

            font-size: 13px;
        }

        .discrepancy p {
            margin: 0;

            color: #7c5a20;

            font-size: 11px;
            line-height: 1.6;
        }

        /* Evidence and audit */

        .evidence-list,
        .audit-list {
            display: grid;
            gap: 9px;
        }

        .evidence-item,
        .audit-item {
            padding: 13px;

            border: 1px solid #e1e7ee;
            border-radius: 11px;

            background: var(--surface-soft);
        }

        .evidence-item strong,
        .audit-item strong {
            display: block;

            margin-bottom: 4px;

            color: var(--text);

            font-size: 12px;
        }

        .evidence-item span,
        .audit-item span {
            color: var(--muted);

            font-size: 10px;
            line-height: 1.55;
        }

        /* Empty state */

        .empty {
            padding: 21px;

            border: 1px dashed var(--border-strong);
            border-radius: 11px;

            background: var(--surface-muted);
            color: var(--muted);

            font-size: 11px;
            text-align: center;
        }

        /* Actions */

        .action-bar {
            display: flex;
            flex-wrap: wrap;

            gap: 10px;

            margin-top: 21px;
        }

        .button {
            min-height: 42px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 15px;

            border: 1px solid var(--border-strong);
            border-radius: 10px;

            background: rgba(255, 255, 255, 0.95);
            color: var(--text-soft);

            font-size: 12px;
            font-weight: 800;

            transition:
                transform 0.18s ease,
                border-color 0.18s ease,
                box-shadow 0.18s ease,
                background 0.18s ease;
        }

        .button:hover {
            transform: translateY(-1px);

            border-color: #b8c5d2;

            background: #ffffff;

            box-shadow:
                0 6px 16px rgba(15, 23, 42, 0.07);
        }

        .button.primary {
            border-color: var(--primary);

            background: var(--primary);
            color: #ffffff;
        }

        .button.primary:hover {
            border-color: var(--primary-dark);
            background: var(--primary-dark);
        }

        /* Footer */

        footer {
            padding: 28px 0 45px;

            color: var(--muted);

            font-size: 10px;

            text-align: center;
        }

        /* Responsive */

        @media (max-width: 820px) {
            .status-layout,
            .grid {
                grid-template-columns: 1fr;
            }

            .card.full {
                grid-column: auto;
            }

            .status-badge {
                width: 100%;
            }
        }

        @media (max-width: 620px) {
            .shell {
                width: min(100% - 22px, 1180px);
            }

            main {
                padding-top: 34px;
            }

            .topbar-inner {
                min-height: 66px;
            }

            .topbar-link {
                display: none;
            }

            .status-card,
            .card {
                padding: 18px;
                border-radius: 14px;
            }

            .details {
                grid-template-columns: 1fr;
            }

            .action-bar {
                flex-direction: column;
            }

            .button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <div class="shell topbar-inner">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark">✓</span>

                <span class="brand-copy">
                    <span class="brand-name">VeriVote NG</span>
                    <span class="brand-subtitle">Public Verification</span>
                </span>
            </a>

            <a href="{{ route('home') }}" class="topbar-link">
                Back to VeriVote NG
            </a>
        </div>
    </header>

    <main>
        <div class="shell">

            <section class="intro">
                <span class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    Public Integrity Record
                </span>

                <h1>Verify a polling-unit result.</h1>

                <p>
                    This read-only record exposes the verification state,
                    evidence integrity, deterministic checks, and audit
                    information associated with the recorded result.
                </p>
            </section>

            @php
                $isVerified = $result->status === 'verified';
                $isFlagged = $result->status === 'flagged';
            @endphp

            <section class="status-card {{ $isVerified ? 'verified' : ($isFlagged ? 'flagged' : '') }}">
                <div class="status-layout">
                    <div>
                        <div class="status-label">
                            Result #{{ $result->id }}
                        </div>

                        <div class="status-title">
                            {{ strtoupper($result->status) }}
                        </div>

                        <p class="status-description">
                            @if ($isVerified)
                                All recorded verification checks currently pass
                                for this result.
                            @elseif ($isFlagged)
                                One or more integrity checks require human
                                investigation before this result can be treated
                                as internally consistent.
                            @else
                                This result has been recorded but does not yet
                                have a verified integrity state.
                            @endif
                        </p>
                    </div>

                    <div class="status-badge">
                        {{ $isVerified ? 'VERIFIED' : ($isFlagged ? 'FLAGGED' : 'PENDING') }}
                    </div>
                </div>
            </section>

            <div class="grid">

                <section class="card">
                    <div class="card-heading">
                        <h2>Election</h2>
                        <p>Election context for this recorded result.</p>
                    </div>

                    <div class="details">
                        <div class="detail">
                            <span class="detail-label">Election</span>
                            <span class="detail-value">
                                {{ $result->election?->title ?? '—' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Election Code</span>
                            <span class="detail-value">
                                {{ $result->election?->code ?? '—' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Election Type</span>
                            <span class="detail-value">
                                {{ $result->election?->election_type ?? '—' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Voting Date</span>
                            <span class="detail-value">
                                {{ $result->election?->voting_date?->format('d M Y') ?? '—' }}
                            </span>
                        </div>
                    </div>
                </section>

                <section class="card">
                    <div class="card-heading">
                        <h2>Polling Unit</h2>
                        <p>Location identity associated with the result.</p>
                    </div>

                    <div class="details">
                        <div class="detail">
                            <span class="detail-label">PU Code</span>
                            <span class="detail-value">
                                {{ $result->pollingUnit?->pu_code ?? '—' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Name</span>
                            <span class="detail-value">
                                {{ $result->pollingUnit?->name ?? '—' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">State</span>
                            <span class="detail-value">
                                {{ $result->pollingUnit?->state ?? '—' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">LGA / Ward</span>
                            <span class="detail-value">
                                {{ $result->pollingUnit?->lga ?? '—' }}
                                /
                                {{ $result->pollingUnit?->ward ?? '—' }}
                            </span>
                        </div>
                    </div>
                </section>

                <section class="card full">
                    <div class="card-heading">
                        <h2>Result Accounting</h2>
                        <p>
                            Recorded ballot and voter figures used by the
                            deterministic verification engine.
                        </p>
                    </div>

                    <div class="details">
                        <div class="detail">
                            <span class="detail-label">Accredited Voters</span>
                            <span class="detail-value">
                                {{ number_format($result->accredited_voters) }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Ballots Issued</span>
                            <span class="detail-value">
                                {{ number_format($result->ballots_issued) }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Valid Votes</span>
                            <span class="detail-value">
                                {{ number_format($result->total_valid_votes) }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Rejected Votes</span>
                            <span class="detail-value">
                                {{ number_format($result->rejected_votes) }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Unused Ballots</span>
                            <span class="detail-value">
                                {{ number_format($result->unused_ballots) }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Spoiled Ballots</span>
                            <span class="detail-value">
                                {{ number_format($result->spoiled_ballots) }}
                            </span>
                        </div>
                    </div>
                </section>

                <section class="card full">
                    <div class="card-heading">
                        <h2>Candidate Votes</h2>
                        <p>
                            Vote entries recorded against the result.
                        </p>
                    </div>

                    @if ($result->resultEntries->isNotEmpty())
                        <div class="details">
                            @foreach ($result->resultEntries as $entry)
                                <div class="detail">
                                    <span class="detail-label">
                                        {{ $entry->candidate?->party_code ?? 'Candidate' }}
                                    </span>

                                    <span class="detail-value">
                                        {{ $entry->candidate?->candidate_name ?? 'Unknown candidate' }}
                                        — {{ number_format($entry->vote_count) }} votes
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty">
                            No candidate vote entries are recorded for this result.
                        </div>
                    @endif
                </section>

                <section class="card">
                    <div class="card-heading">
                        <h2>Cryptographic Integrity</h2>
                        <p>
                            Evidence-bound cryptographic record.
                        </p>
                    </div>

                    <div class="details">
                        <div class="detail">
                            <span class="detail-label">Payload Hash</span>
                            <span class="detail-value hash">
                                {{ $result->payload_hash ?? 'Not available' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Signer Role</span>
                            <span class="detail-value">
                                {{ $result->signature?->signer_role ?? 'Not available' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Signing Timestamp</span>
                            <span class="detail-value">
                                {{ $result->signature?->signing_timestamp?->format('d M Y H:i:s') ?? 'Not available' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Signature</span>
                            <span class="detail-value hash">
                                {{ $result->signature?->signature ?? 'Not available' }}
                            </span>
                        </div>

                        <div class="detail">
                            <span class="detail-label">Public Key</span>
                            <span class="detail-value hash">
                                {{ $result->signature?->public_key ?? 'Not available' }}
                            </span>
                        </div>
                    </div>
                </section>

                <section class="card">
                    <div class="card-heading">
                        <h2>Evidence</h2>
                        <p>
                            Source evidence associated with the recorded result.
                        </p>
                    </div>

                    @if ($result->evidence->isNotEmpty())
                        <div class="evidence-list">
                            @foreach ($result->evidence as $evidence)
                                <div class="evidence-item">
                                    <strong>
                                        {{ strtoupper($evidence->file_type) }}
                                    </strong>

                                    <span>
                                        SHA-256:
                                        <span class="hash">
                                            {{ $evidence->file_hash }}
                                        </span>
                                    </span>

                                    @if ($evidence->captured_at)
                                        <br>

                                        <span>
                                            Captured:
                                            {{ $evidence->captured_at->format('d M Y H:i:s') }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty">
                            No source evidence is attached to this result.
                        </div>
                    @endif
                </section>

                <section class="card full">
                    <div class="card-heading">
                        <h2>Verification Checks</h2>
                        <p>
                            Deterministic rules executed against the recorded
                            result.
                        </p>
                    </div>

                    @if ($result->verificationChecks->isNotEmpty())
                        <div class="check-list">
                            @foreach ($result->verificationChecks as $check)
                                <div class="check {{ $check->passed ? 'passed' : 'failed' }}">
                                    <div class="check-icon">
                                        {{ $check->passed ? '✓' : '!' }}
                                    </div>

                                    <div class="check-content">
                                        <strong>
                                            {{ $check->rule_name }}
                                        </strong>

                                        <span>
                                            {{ $check->details ?? ($check->passed ? 'Check passed.' : 'Check failed.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty">
                            No verification checks have been recorded yet.
                        </div>
                    @endif
                </section>

                <section class="card full">
                    <div class="card-heading">
                        <h2>Discrepancies</h2>
                        <p>
                            Integrity failures or inconsistencies requiring
                            human investigation.
                        </p>
                    </div>

                    @if ($result->discrepancies->isNotEmpty())
                        @foreach ($result->discrepancies as $discrepancy)
                            <div class="discrepancy">
                                <div class="discrepancy-top">
                                    <span class="discrepancy-code">
                                        {{ $discrepancy->code }}
                                    </span>

                                    <span class="discrepancy-status">
                                        {{ $discrepancy->status }}
                                    </span>
                                </div>

                                <h3>
                                    {{ $discrepancy->type }}
                                </h3>

                                <p>
                                    {{ $discrepancy->description }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <div class="empty">
                            No discrepancies are currently recorded for this result.
                        </div>
                    @endif
                </section>

                <section class="card full">
                    <div class="card-heading">
                        <h2>Audit Trail</h2>
                        <p>
                            Recorded state transitions associated with this result.
                        </p>
                    </div>

                    @if ($result->auditLogs->isNotEmpty())
                        <div class="audit-list">
                            @foreach ($result->auditLogs as $audit)
                                <div class="audit-item">
                                    <strong>
                                        {{ strtoupper($audit->action) }}
                                    </strong>

                                    <span>
                                        {{ $audit->created_at?->format('d M Y H:i:s') ?? 'Unknown time' }}
                                    </span>

                                    <br>

                                    <span>
                                        Current audit hash:
                                        <span class="hash">
                                            {{ $audit->current_hash }}
                                        </span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty">
                            No audit events are currently available.
                        </div>
                    @endif
                </section>

            </div>

            <div class="action-bar">
                <a
                    href="{{ route('public.verify.report', $result) }}"
                    class="button primary"
                >
                    Verification Report
                </a>

                <a
                    href="{{ route('public.verify.qr', $result) }}"
                    class="button"
                >
                    QR Verification
                </a>

                <a
                    href="{{ route('results.show', $result) }}"
                    class="button"
                >
                    Result Details
                </a>

                <a
                    href="{{ route('home') }}"
                    class="button"
                >
                    VeriVote NG Home
                </a>
            </div>

        </div>
    </main>

    <footer>
        <div class="shell">
            VeriVote NG — cryptographically verifiable result integrity and
            discrepancy detection.
        </div>
    </footer>
</body>
</html>