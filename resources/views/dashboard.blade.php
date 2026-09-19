{{-- 
    VeriVote NG Observer Dashboard

    Presents available elections and provides the observer entry point for
    recording results and reviewing the verification workflow.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VeriVote NG — Observer Dashboard</title>
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
                    transparent 30%
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
            font-size: 0.86rem;
        }

        .back-link:hover {
            color: var(--text);
        }

        main {
            padding: 70px 0 100px;
        }

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 40px;
        }

        .kicker {
            color: var(--accent);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        h1 {
            margin-top: 8px;
            font-size: clamp(2.2rem, 5vw, 3.7rem);
            line-height: 1.05;
            letter-spacing: -0.055em;
        }

        .intro {
            max-width: 650px;
            margin-top: 14px;
            color: var(--muted);
        }

        .primary-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 18px;
            border-radius: 10px;
            background: var(--accent);
            color: #03111d;
            font-size: 0.88rem;
            font-weight: 800;
            white-space: nowrap;
            transition: transform 0.2s ease;
        }

        .primary-action:hover {
            transform: translateY(-1px);
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        h2 {
            font-size: 1.2rem;
            letter-spacing: -0.025em;
        }

        .section-count {
            color: var(--muted);
            font-size: 0.78rem;
        }

        .elections {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .election-card {
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background:
                linear-gradient(
                    145deg,
                    rgba(16, 35, 58, 0.9),
                    rgba(9, 24, 40, 0.9)
                );
            transition:
                border-color 0.2s ease,
                transform 0.2s ease;
        }

        .election-card:hover {
            border-color: rgba(56, 189, 248, 0.35);
            transform: translateY(-2px);
        }

        .election-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
        }

        .election-code {
            color: var(--accent);
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            border-radius: 999px;
            color: #a7f3d0;
            background: rgba(52, 211, 153, 0.1);
            font-size: 0.67rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--success);
        }

        .election-card h3 {
            margin-top: 14px;
            font-size: 1.18rem;
            line-height: 1.3;
            letter-spacing: -0.025em;
        }

        .metadata {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 22px;
        }

        .metadata-item {
            padding: 11px;
            border-radius: 9px;
            background: var(--panel-soft);
        }

        .metadata-label {
            color: var(--muted);
            font-size: 0.67rem;
        }

        .metadata-value {
            margin-top: 2px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .empty-state {
            padding: 50px 25px;
            border: 1px dashed var(--border);
            border-radius: 16px;
            background: var(--panel-soft);
            text-align: center;
        }

        .empty-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto;
            display: grid;
            place-items: center;
            border-radius: 12px;
            color: #bae6fd;
            background: var(--accent-soft);
            font-weight: 900;
        }

        .empty-state h3 {
            margin-top: 16px;
            font-size: 1rem;
        }

        .empty-state p {
            max-width: 460px;
            margin: 7px auto 0;
            color: var(--muted);
            font-size: 0.86rem;
        }

        .workflow {
            margin-top: 55px;
            padding: 26px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--panel-soft);
        }

        .workflow-header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
        }

        .workflow-header p {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .workflow-steps {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 22px;
        }

        .workflow-step {
            padding: 14px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.025);
        }

        .workflow-number {
            color: var(--accent);
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.68rem;
            font-weight: 800;
        }

        .workflow-step span:last-child {
            display: block;
            margin-top: 6px;
            color: #cbd5e1;
            font-size: 0.76rem;
            font-weight: 700;
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 26px 0;
            color: var(--muted);
            font-size: 0.75rem;
        }

        @media (max-width: 850px) {
            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .elections {
                grid-template-columns: 1fr;
            }

            .workflow-steps {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .container {
                width: min(var(--max), calc(100% - 28px));
            }

            main {
                padding: 50px 0 70px;
            }

            .metadata {
                grid-template-columns: 1fr;
            }

            .workflow-steps {
                grid-template-columns: 1fr;
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

            <a href="{{ route('home') }}" class="back-link">
                ← Back to home
            </a>

        </div>
    </header>

    <main>
        <div class="container">

            <header class="page-header">
                <div>
                    <div class="kicker">Observer workspace</div>

                    <h1>Election Dashboard</h1>

                    <p class="intro">
                        Review available elections and move into the result
                        capture and verification workflow.
                    </p>
                </div>

                <a
                    href="{{ route('results.create') }}"
                    class="primary-action"
                >
                    + Record Result
                </a>
            </header>

            <section aria-labelledby="elections-heading">

                <div class="section-heading">
                    <h2 id="elections-heading">Available Elections</h2>

                    <span class="section-count">
                        {{ $elections->count() }}
                        {{ $elections->count() === 1 ? 'election' : 'elections' }}
                    </span>
                </div>

                @forelse ($elections as $election)

                    <div class="elections">

                        <article class="election-card">

                            <div class="election-top">

                                <span class="election-code">
                                    {{ $election->code }}
                                </span>

                                <span class="status">
                                    <span class="status-dot"></span>
                                    {{ $election->status }}
                                </span>

                            </div>

                            <h3>
                                {{ $election->title }}
                            </h3>

                            <div class="metadata">

                                <div class="metadata-item">
                                    <div class="metadata-label">
                                        Type
                                    </div>

                                    <div class="metadata-value">
                                        {{ $election->election_type }}
                                    </div>
                                </div>

                                <div class="metadata-item">
                                    <div class="metadata-label">
                                        Voting date
                                    </div>

                                    <div class="metadata-value">
                                        {{ $election->voting_date }}
                                    </div>
                                </div>

                                <div class="metadata-item">
                                    <div class="metadata-label">
                                        Status
                                    </div>

                                    <div class="metadata-value">
                                        {{ $election->status }}
                                    </div>
                                </div>

                            </div>

                        </article>

                    </div>

                @empty

                    <div class="empty-state">

                        <div class="empty-icon">!</div>

                        <h3>No elections available</h3>

                        <p>
                            There are currently no active elections available
                            for the observer workflow.
                        </p>

                    </div>

                @endforelse

            </section>

            <section class="workflow" aria-labelledby="workflow-heading">

                <div class="workflow-header">

                    <div>
                        <h2 id="workflow-heading">
                            Verification Workflow
                        </h2>

                        <p>
                            The observer workflow follows the evidence through
                            verification and public auditability.
                        </p>
                    </div>

                </div>

                <div class="workflow-steps">

                    <div class="workflow-step">
                        <span class="workflow-number">01</span>
                        <span>Record result</span>
                    </div>

                    <div class="workflow-step">
                        <span class="workflow-number">02</span>
                        <span>Attach evidence</span>
                    </div>

                    <div class="workflow-step">
                        <span class="workflow-number">03</span>
                        <span>Verify integrity</span>
                    </div>

                    <div class="workflow-step">
                        <span class="workflow-number">04</span>
                        <span>Review discrepancies</span>
                    </div>

                    <div class="workflow-step">
                        <span class="workflow-number">05</span>
                        <span>Publish verification</span>
                    </div>

                </div>

            </section>

        </div>
    </main>

    <footer>
        <div class="container">
            VeriVote NG · Observer Dashboard · Evidence verification MVP
        </div>
    </footer>

</body>
</html>