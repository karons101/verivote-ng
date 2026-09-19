{{-- 
    VeriVote NG Landing Page

    Presents the product purpose, current MVP capabilities, verification
    workflow, demo records, system boundaries, and clearly labelled future
    extensions from the public application entry point.
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VeriVote NG — Evidence You Can Verify</title>

    <meta
        name="description"
        content="VeriVote NG is an independent evidence-verification layer for polling-unit election results."
    >

    <style>
        :root {
            --bg: #07111f;
            --bg-soft: #0d1b2e;
            --panel: rgba(15, 31, 51, 0.82);
            --panel-solid: #10233a;
            --border: rgba(148, 163, 184, 0.16);
            --text: #f8fafc;
            --muted: #9fb0c3;
            --accent: #38bdf8;
            --accent-soft: rgba(56, 189, 248, 0.12);
            --success: #34d399;
            --warning: #fbbf24;
            --danger: #fb7185;
            --max: 1180px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            font-family:
                Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(
                    circle at 15% 10%,
                    rgba(56, 189, 248, 0.12),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 85% 20%,
                    rgba(16, 185, 129, 0.08),
                    transparent 25%
                ),
                var(--bg);
            color: var(--text);
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

        .nav {
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 1px solid var(--border);
            background: rgba(7, 17, 31, 0.82);
            backdrop-filter: blur(18px);
        }

        .nav-inner {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: #03111d;
            font-weight: 900;
            box-shadow: 0 8px 30px rgba(14, 165, 233, 0.25);
        }

        .brand-name {
            font-size: 1.05rem;
        }

        .brand-name span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .nav-links a:hover {
            color: var(--text);
        }

        .nav-cta {
            padding: 10px 16px;
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 10px;
            color: var(--text);
            background: var(--accent-soft);
        }

        .hero {
            padding: 100px 0 90px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 64px;
            align-items: center;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 11px;
            border: 1px solid rgba(56, 189, 248, 0.24);
            border-radius: 999px;
            color: #bae6fd;
            background: var(--accent-soft);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .eyebrow-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 12px rgba(52, 211, 153, 0.8);
        }

        h1 {
            margin-top: 24px;
            max-width: 760px;
            font-size: clamp(3rem, 7vw, 5.8rem);
            line-height: 0.98;
            letter-spacing: -0.065em;
        }

        .gradient-text {
            background: linear-gradient(100deg, #f8fafc 20%, #7dd3fc 70%, #38bdf8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-copy {
            max-width: 680px;
            margin-top: 26px;
            color: var(--muted);
            font-size: 1.12rem;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 34px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 18px;
            border-radius: 10px;
            border: 1px solid var(--border);
            font-weight: 700;
            font-size: 0.92rem;
            transition: 0.2s ease;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .button-primary {
            background: #38bdf8;
            border-color: #38bdf8;
            color: #03111d;
        }

        .button-secondary {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text);
        }

        .hero-card {
            position: relative;
            padding: 26px;
            border: 1px solid var(--border);
            border-radius: 22px;
            background:
                linear-gradient(
                    145deg,
                    rgba(20, 42, 68, 0.95),
                    rgba(9, 24, 40, 0.92)
                );
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .hero-card::before {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: 22px;
            pointer-events: none;
            background: linear-gradient(
                135deg,
                rgba(56, 189, 248, 0.25),
                transparent 45%
            );
            z-index: -1;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .status-label {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 9px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .status-verified {
            color: #a7f3d0;
            background: rgba(52, 211, 153, 0.12);
        }

        .status-flagged {
            color: #fde68a;
            background: rgba(251, 191, 36, 0.12);
        }

        .result-number {
            margin-top: 24px;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .result-title {
            margin-top: 5px;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .hash-box {
            margin-top: 22px;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.16);
        }

        .hash-label {
            color: var(--muted);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .hash {
            margin-top: 7px;
            overflow-wrap: anywhere;
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.72rem;
            color: #bae6fd;
        }

        .security-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 18px;
        }

        .security-item {
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: #cbd5e1;
            background: rgba(255, 255, 255, 0.025);
            font-size: 0.72rem;
            text-align: center;
        }

        section {
            padding: 88px 0;
        }

        .section-heading {
            max-width: 720px;
            margin-bottom: 42px;
        }

        .section-kicker {
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        h2 {
            margin-top: 9px;
            font-size: clamp(2rem, 4vw, 3.3rem);
            line-height: 1.08;
            letter-spacing: -0.05em;
        }

        .section-description {
            margin-top: 15px;
            color: var(--muted);
            font-size: 1rem;
        }

        .principle {
            margin: 28px 0 48px;
            padding: 24px;
            border-left: 3px solid var(--accent);
            border-radius: 0 14px 14px 0;
            background: rgba(56, 189, 248, 0.055);
            color: #dbeafe;
            font-size: 1.08rem;
        }

        .principle strong {
            color: #fff;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        .step,
        .feature,
        .future-card,
        .demo-card,
        .boundary-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--panel);
        }

        .step {
            padding: 22px;
        }

        .step-number {
            color: var(--accent);
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .step h3 {
            margin-top: 20px;
            font-size: 1rem;
        }

        .step p {
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.84rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .feature {
            padding: 25px;
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            color: #bae6fd;
            background: var(--accent-soft);
            font-weight: 900;
        }

        .feature h3 {
            margin-top: 18px;
            font-size: 1.05rem;
        }

        .feature p {
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .demo-section {
            background:
                linear-gradient(
                    180deg,
                    rgba(13, 27, 46, 0.2),
                    rgba(13, 27, 46, 0.7)
                );
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .demo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .demo-card {
            padding: 26px;
        }

        .demo-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .demo-card h3 {
            margin-top: 7px;
            font-size: 1.3rem;
        }

        .demo-card p {
            margin-top: 16px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .demo-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 22px;
        }

        .meta-item {
            padding: 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
        }

        .meta-label {
            color: var(--muted);
            font-size: 0.7rem;
        }

        .meta-value {
            margin-top: 2px;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .demo-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 22px;
        }

        .small-button {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: #dbeafe;
            background: rgba(255, 255, 255, 0.025);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .boundaries {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .boundary-card {
            padding: 26px;
        }

        .boundary-card h3 {
            font-size: 1.05rem;
        }

        .boundary-card ul {
            margin-top: 16px;
            padding-left: 19px;
            color: var(--muted);
        }

        .boundary-card li + li {
            margin-top: 9px;
        }

        .future-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .future-card {
            position: relative;
            padding: 24px;
            opacity: 0.92;
        }

        .coming-soon {
            display: inline-flex;
            padding: 5px 8px;
            border-radius: 999px;
            color: #fde68a;
            background: rgba(251, 191, 36, 0.1);
            font-size: 0.67rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .future-card h3 {
            margin-top: 17px;
            font-size: 1rem;
        }

        .future-card p {
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.84rem;
        }

        .cta {
            padding: 75px 0 100px;
        }

        .cta-box {
            padding: 48px;
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 22px;
            background:
                radial-gradient(
                    circle at 80% 20%,
                    rgba(56, 189, 248, 0.1),
                    transparent 30%
                ),
                rgba(15, 31, 51, 0.8);
            text-align: center;
        }

        .cta-box p {
            max-width: 650px;
            margin: 15px auto 0;
            color: var(--muted);
        }

        .cta-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 26px;
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 30px 0;
            color: var(--muted);
            font-size: 0.78rem;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .footer-brand {
            color: #dbeafe;
            font-weight: 800;
        }

        @media (max-width: 950px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .features,
            .future-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            .container {
                width: min(var(--max), calc(100% - 28px));
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 70px 0 65px;
            }

            h1 {
                font-size: 3.1rem;
            }

            .security-strip,
            .steps,
            .features,
            .demo-grid,
            .boundaries,
            .future-grid {
                grid-template-columns: 1fr;
            }

            section {
                padding: 65px 0;
            }

            .cta-box {
                padding: 32px 22px;
            }

            .footer-inner {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <header class="nav">
        <div class="container nav-inner">
            <a href="{{ route('home') }}" class="brand" aria-label="VeriVote NG home">
                <span class="brand-mark">V</span>
                <span class="brand-name">Veri<span>Vote</span> NG</span>
            </a>

            <nav class="nav-links" aria-label="Primary navigation">
                <a href="#how-it-works">How it works</a>
                <a href="#capabilities">Capabilities</a>
                <a href="#demo">Demo</a>
                <a href="#future">Future</a>
                <a href="{{ route('dashboard') }}" class="nav-cta">Open Dashboard</a>
            </nav>
        </div>
    </header>

    <main>

        {{-- Hero --}}
        <section class="hero">
            <div class="container hero-grid">

                <div>
                    <div class="eyebrow">
                        <span class="eyebrow-dot"></span>
                        MVP / Demo Build
                    </div>

                    <h1>
                        Evidence you can
                        <span class="gradient-text">verify.</span>
                    </h1>

                    <p class="hero-copy">
                        VeriVote NG is an independent evidence-verification layer
                        for polling-unit election results. It binds source evidence
                        to recorded vote data, verifies deterministic integrity rules,
                        and surfaces discrepancies for human review.
                    </p>

                    <div class="hero-actions">
                        <a
                            href="{{ route('dashboard') }}"
                            class="button button-primary"
                        >
                            Open Observer Dashboard
                        </a>

                        <a
                            href="{{ route('public.verify', ['result' => 4]) }}"
                            class="button button-secondary"
                        >
                            Verify Demo Result
                        </a>
                    </div>
                </div>

                <div class="hero-card">
                    <div class="status-row">
                        <span class="status-label">Integrity verification</span>

                        <span class="status status-verified">
                            ● VERIFIED
                        </span>
                    </div>

                    <div class="result-number">
                        Demo Result #4
                    </div>

                    <div class="result-title">
                        Polling Unit Result Record
                    </div>

                    <div class="hash-box">
                        <div class="hash-label">Payload SHA-256</div>

                        <div class="hash">
                            56a60f...2703f
                        </div>
                    </div>

                    <div class="security-strip">
                        <div class="security-item">SHA-256</div>
                        <div class="security-item">Ed25519</div>
                        <div class="security-item">C1–C5</div>
                        <div class="security-item">Audit Chain</div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Core principle --}}
        <section>
            <div class="container">

                <div class="section-heading">
                    <div class="section-kicker">Verification model</div>

                    <h2>
                        Separate evidence, verification,
                        interpretation, and judgment.
                    </h2>

                    <p class="section-description">
                        VeriVote NG is designed around explicit technical boundaries
                        so that cryptographic verification does not become a claim
                        about intent or outcome.
                    </p>
                </div>

                <div class="principle">
                    <strong>AI interprets.</strong>
                    Cryptography protects.
                    <strong>Deterministic rules verify.</strong>
                    Humans decide.
                </div>

                <div id="how-it-works" class="steps">

                    <article class="step">
                        <div class="step-number">01 / CAPTURE</div>
                        <h3>Record evidence</h3>
                        <p>
                            Associate polling-unit result data with its source
                            evidence.
                        </p>
                    </article>

                    <article class="step">
                        <div class="step-number">02 / BIND</div>
                        <h3>Hash & sign</h3>
                        <p>
                            Generate deterministic hashes and bind evidence to
                            the recorded result cryptographically.
                        </p>
                    </article>

                    <article class="step">
                        <div class="step-number">03 / VERIFY</div>
                        <h3>Run checks</h3>
                        <p>
                            Execute cryptographic and deterministic mathematical
                            integrity checks.
                        </p>
                    </article>

                    <article class="step">
                        <div class="step-number">04 / DETECT</div>
                        <h3>Surface discrepancies</h3>
                        <p>
                            Identify integrity failures and inconsistencies that
                            require human investigation.
                        </p>
                    </article>

                    <article class="step">
                        <div class="step-number">05 / PUBLISH</div>
                        <h3>Verify publicly</h3>
                        <p>
                            Expose a read-only verification record, report, and
                            QR entry point.
                        </p>
                    </article>

                </div>
            </div>
        </section>

        {{-- Capabilities --}}
        <section id="capabilities">
            <div class="container">

                <div class="section-heading">
                    <div class="section-kicker">Current MVP</div>

                    <h2>
                        Built around verifiable engineering primitives.
                    </h2>

                    <p class="section-description">
                        The current implementation focuses on a small,
                        demonstrable verification workflow rather than claiming
                        functionality that has not been built.
                    </p>
                </div>

                <div class="features">

                    <article class="feature">
                        <div class="feature-icon">01</div>
                        <h3>Evidence capture</h3>
                        <p>
                            Store source result evidence and associate it with
                            a specific polling-unit result record.
                        </p>
                    </article>

                    <article class="feature">
                        <div class="feature-icon">02</div>
                        <h3>Cryptographic binding</h3>
                        <p>
                            Use SHA-256 hashes and Ed25519 signatures to bind
                            result data and source evidence.
                        </p>
                    </article>

                    <article class="feature">
                        <div class="feature-icon">03</div>
                        <h3>Deterministic verification</h3>
                        <p>
                            Evaluate explicit integrity rules covering voter,
                            ballot, and candidate vote accounting.
                        </p>
                    </article>

                    <article class="feature">
                        <div class="feature-icon">04</div>
                        <h3>Discrepancy detection</h3>
                        <p>
                            Classify detected integrity failures so they can be
                            surfaced for human review.
                        </p>
                    </article>

                    <article class="feature">
                        <div class="feature-icon">05</div>
                        <h3>Audit trail</h3>
                        <p>
                            Record verification events using a tamper-evident
                            hash-linked audit structure.
                        </p>
                    </article>

                    <article class="feature">
                        <div class="feature-icon">06</div>
                        <h3>Public verification</h3>
                        <p>
                            Provide public verification pages, verification
                            reports, and QR-based access to recorded results.
                        </p>
                    </article>

                </div>
            </div>
        </section>

        {{-- Demo --}}
        <section id="demo" class="demo-section">
            <div class="container">

                <div class="section-heading">
                    <div class="section-kicker">Live application demo</div>

                    <h2>
                        See both a verified result and a detected discrepancy.
                    </h2>

                    <p class="section-description">
                        These records belong to the VeriVote NG demo dataset.
                        They are intentionally constructed to demonstrate the
                        verification workflow.
                    </p>
                </div>

                <div class="demo-grid">

                    {{-- Verified demo --}}
                    <article class="demo-card">
                        <div class="demo-card-top">
                            <div>
                                <div class="status-label">Result #4</div>
                                <h3>Verified result</h3>
                            </div>

                            <span class="status status-verified">
                                ● VERIFIED
                            </span>
                        </div>

                        <p>
                            The cryptographic and deterministic verification
                            checks pass for this demo result.
                        </p>

                        <div class="demo-meta">
                            <div class="meta-item">
                                <div class="meta-label">Candidate A</div>
                                <div class="meta-value">50 votes</div>
                            </div>

                            <div class="meta-item">
                                <div class="meta-label">Candidate B</div>
                                <div class="meta-value">25 votes</div>
                            </div>

                            <div class="meta-item">
                                <div class="meta-label">Candidate C</div>
                                <div class="meta-value">10 votes</div>
                            </div>

                            <div class="meta-item">
                                <div class="meta-label">Valid votes</div>
                                <div class="meta-value">85</div>
                            </div>
                        </div>

                        <div class="demo-actions">
                            <a
                                href="{{ route('public.verify', ['result' => 4]) }}"
                                class="small-button"
                            >
                                Public Verification
                            </a>

                            <a
                                href="{{ route('public.verify.report', ['result' => 4]) }}"
                                class="small-button"
                            >
                                Verification Report
                            </a>

                            <a
                                href="{{ route('public.verify.qr', ['result' => 4]) }}"
                                class="small-button"
                            >
                                QR
                            </a>
                        </div>
                    </article>

                    {{-- Flagged demo --}}
                    <article class="demo-card">
                        <div class="demo-card-top">
                            <div>
                                <div class="status-label">Result #5</div>
                                <h3>Discrepancy detected</h3>
                            </div>

                            <span class="status status-flagged">
                                ● FLAGGED
                            </span>
                        </div>

                        <p>
                            The candidate vote entries do not reconcile with the
                            recorded total valid votes, triggering rule C5.
                        </p>

                        <div class="demo-meta">
                            <div class="meta-item">
                                <div class="meta-label">Candidate A</div>
                                <div class="meta-value">50 votes</div>
                            </div>

                            <div class="meta-item">
                                <div class="meta-label">Candidate B</div>
                                <div class="meta-value">25 votes</div>
                            </div>

                            <div class="meta-item">
                                <div class="meta-label">Candidate C</div>
                                <div class="meta-value">9 votes</div>
                            </div>

                            <div class="meta-item">
                                <div class="meta-label">Recorded valid votes</div>
                                <div class="meta-value">85</div>
                            </div>
                        </div>

                        <div class="demo-actions">
                            <a
                                href="{{ route('public.verify', ['result' => 5]) }}"
                                class="small-button"
                            >
                                Inspect Discrepancy
                            </a>

                            <a
                                href="{{ route('public.verify.report', ['result' => 5]) }}"
                                class="small-button"
                            >
                                Verification Report
                            </a>

                            <a
                                href="{{ route('public.verify.qr', ['result' => 5]) }}"
                                class="small-button"
                            >
                                QR
                            </a>
                        </div>
                    </article>

                </div>
            </div>
        </section>

        {{-- Boundaries --}}
        <section>
            <div class="container">

                <div class="section-heading">
                    <div class="section-kicker">System boundaries</div>

                    <h2>
                        What VeriVote NG does — and does not — claim.
                    </h2>

                    <p class="section-description">
                        Clear boundaries are part of the product design.
                    </p>
                </div>

                <div class="boundaries">

                    <article class="boundary-card">
                        <h3>What it does</h3>

                        <ul>
                            <li>
                                Creates cryptographically verifiable records
                                of recorded polling-unit results.
                            </li>

                            <li>
                                Checks mathematical consistency using explicit
                                deterministic rules.
                            </li>

                            <li>
                                Detects changes and inconsistencies in recorded
                                evidence.
                            </li>

                            <li>
                                Preserves an auditable verification history.
                            </li>

                            <li>
                                Provides public read-only verification surfaces.
                            </li>
                        </ul>
                    </article>

                    <article class="boundary-card">
                        <h3>What it does not do</h3>

                        <ul>
                            <li>
                                It does not determine who won an election.
                            </li>

                            <li>
                                It does not by itself establish electoral
                                fraud or intent.
                            </li>

                            <li>
                                It does not replace INEC or IReV.
                            </li>

                            <li>
                                It does not treat an AI model as the arbiter
                                of electoral truth.
                            </li>

                            <li>
                                A detected discrepancy requires human
                                investigation and contextual review.
                            </li>
                        </ul>
                    </article>

                </div>
            </div>
        </section>

        {{-- Future extensions --}}
        <section id="future">
            <div class="container">

                <div class="section-heading">
                    <div class="section-kicker">Product roadmap</div>

                    <h2>
                        Beyond the current MVP.
                    </h2>

                    <p class="section-description">
                        The following capabilities are planned extensions.
                        They are intentionally labelled as future work and are
                        not represented as currently implemented functionality.
                    </p>
                </div>

                <div class="future-grid">

                    <article class="future-card">
                        <span class="coming-soon">Coming Soon</span>

                        <h3>Offline-first synchronization</h3>

                        <p>
                            Capture and verify evidence in unreliable-connectivity
                            environments, then synchronize when connectivity returns.
                        </p>
                    </article>

                    <article class="future-card">
                        <span class="coming-soon">Coming Soon</span>

                        <h3>Distributed evidence exchange</h3>

                        <p>
                            Explore local and peer-to-peer evidence exchange for
                            environments where conventional connectivity is limited.
                        </p>
                    </article>

                    <article class="future-card">
                        <span class="coming-soon">Coming Soon</span>

                        <h3>Cross-source comparison</h3>

                        <p>
                            Compare independently recorded evidence with later
                            published or transmitted representations and surface
                            inconsistencies for review.
                        </p>
                    </article>

                    <article class="future-card">
                        <span class="coming-soon">Coming Soon</span>

                        <h3>Expanded AI assistance</h3>

                        <p>
                            Explain verification failures in natural language
                            while keeping deterministic verification outside the
                            AI decision boundary.
                        </p>
                    </article>

                    <article class="future-card">
                        <span class="coming-soon">Coming Soon</span>

                        <h3>Incident & safety reporting</h3>

                        <p>
                            Provide structured reporting for incidents such as
                            intimidation, interference, violence, or destruction.
                        </p>
                    </article>

                    <article class="future-card">
                        <span class="coming-soon">Coming Soon</span>

                        <h3>Broader audit infrastructure</h3>

                        <p>
                            Extend tamper-evident audit mechanisms across more
                            electoral entities and verification workflows.
                        </p>
                    </article>

                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="cta">
            <div class="container">

                <div class="cta-box">
                    <div class="section-kicker">Explore the MVP</div>

                    <h2>
                        Inspect the evidence trail yourself.
                    </h2>

                    <p>
                        Move from recorded result to cryptographic verification,
                        deterministic checks, discrepancy detection, audit history,
                        and public verification.
                    </p>

                    <div class="cta-actions">
                        <a
                            href="{{ route('dashboard') }}"
                            class="button button-primary"
                        >
                            Open Observer Dashboard
                        </a>

                        <a
                            href="{{ route('results.create') }}"
                            class="button button-secondary"
                        >
                            Record Demo Result
                        </a>

                        <a
                            href="{{ route('public.verify', ['result' => 4]) }}"
                            class="button button-secondary"
                        >
                            Public Verification
                        </a>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <footer>
        <div class="container footer-inner">
            <div>
                <span class="footer-brand">VeriVote NG</span>
                <span> — Evidence verification for polling-unit results.</span>
            </div>

            <div>
                MVP / Demo Build · Transparency & Accountability
            </div>
        </div>
    </footer>

</body>
</html>