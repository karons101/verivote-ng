{{-- 
    VeriVote NG Result Submission and Verification

    Provides the observer interface for recording polling-unit result data,
    submitting source evidence, and reviewing deterministic verification outcomes.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VeriVote NG — Record Result</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
        :root {
            --bg: #07111f;
            --panel: #0d1b2e;
            --panel-soft: rgba(255, 255, 255, 0.025);
            --border: rgba(148, 163, 184, 0.16);
            --border-focus: rgba(56, 189, 248, 0.55);
            --text: #f8fafc;
            --muted: #9fb0c3;
            --accent: #38bdf8;
            --accent-soft: rgba(56, 189, 248, 0.1);
            --success: #34d399;
            --success-soft: rgba(52, 211, 153, 0.1);
            --warning: #fbbf24;
            --danger: #fb7185;
            --danger-soft: rgba(251, 113, 133, 0.1);
            --max: 1100px;
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
            padding: 65px 0 100px;
        }

        .page-header {
            max-width: 760px;
            margin-bottom: 34px;
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
            margin-top: 14px;
            color: var(--muted);
            font-size: 0.98rem;
        }

        .alert {
            margin-bottom: 20px;
            padding: 16px 18px;
            border-radius: 12px;
            font-size: 0.86rem;
        }

        .alert-success {
            border: 1px solid rgba(52, 211, 153, 0.2);
            background: var(--success-soft);
            color: #a7f3d0;
        }

        .alert-error {
            border: 1px solid rgba(251, 113, 133, 0.22);
            background: var(--danger-soft);
            color: #fecdd3;
        }

        .alert h2 {
            font-size: 0.9rem;
            margin-bottom: 7px;
        }

        .alert ul {
            padding-left: 18px;
        }

        .alert li + li {
            margin-top: 4px;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 18px;
            background:
                linear-gradient(
                    145deg,
                    rgba(16, 35, 58, 0.9),
                    rgba(9, 24, 40, 0.92)
                );
            overflow: hidden;
        }

        .card-header {
            padding: 24px 26px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            font-size: 1.18rem;
            letter-spacing: -0.025em;
        }

        .card-header p {
            margin-top: 5px;
            color: var(--muted);
            font-size: 0.83rem;
        }

        .card-body {
            padding: 26px;
        }

        .form-section + .form-section {
            margin-top: 34px;
            padding-top: 34px;
            border-top: 1px solid var(--border);
        }

        .section-title {
            margin-bottom: 18px;
        }

        .section-title h3 {
            font-size: 0.96rem;
        }

        .section-title p {
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.77rem;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field {
            min-width: 0;
        }

        .field-full {
            grid-column: 1 / -1;
        }

        label,
        legend {
            display: block;
            margin-bottom: 7px;
            color: #dbeafe;
            font-size: 0.76rem;
            font-weight: 700;
        }

        input[type="number"],
        input[type="file"] {
            width: 100%;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 9px;
            outline: none;
            color: var(--text);
            background: rgba(0, 0, 0, 0.16);
            font: inherit;
            font-size: 0.84rem;
        }

        input[type="number"]:focus,
        input[type="file"]:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.08);
        }

        input[type="file"] {
            padding-top: 9px;
        }

        .field-help {
            margin-top: 6px;
            color: var(--muted);
            font-size: 0.72rem;
        }

        fieldset {
            border: 0;
        }

        .candidate-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .candidate-row {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--panel-soft);
        }

        .candidate-label {
            margin-bottom: 12px;
            color: var(--accent);
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .candidate-fields {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            gap: 10px;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
        }

        .button {
            min-height: 45px;
            padding: 0 18px;
            border: 1px solid var(--border);
            border-radius: 9px;
            font-size: 0.83rem;
            font-weight: 800;
            cursor: pointer;
        }

        .button-primary {
            border-color: var(--accent);
            background: var(--accent);
            color: #03111d;
        }

        .verification-card {
            margin-top: 22px;
        }

        .verification-card .card-header {
            background: rgba(56, 189, 248, 0.025);
        }

        .verification-form {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .verification-form .field {
            width: min(300px, 100%);
        }

        .checks {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 22px;
        }

        .check {
            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: var(--panel-soft);
        }

        .check-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .check h4 {
            font-size: 0.82rem;
        }

        .check-status {
            padding: 4px 7px;
            border-radius: 999px;
            font-size: 0.64rem;
            font-weight: 800;
        }

        .passed {
            color: #a7f3d0;
            background: rgba(52, 211, 153, 0.1);
        }

        .failed {
            color: #fecdd3;
            background: rgba(251, 113, 133, 0.1);
        }

        .check p {
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.75rem;
        }

        .check-time {
            margin-top: 7px;
            color: #64748b;
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 0.66rem;
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 26px 0;
            color: var(--muted);
            font-size: 0.75rem;
        }

        @media (max-width: 760px) {
            .container {
                width: min(var(--max), calc(100% - 28px));
            }

            main {
                padding: 50px 0 70px;
            }

            .fields,
            .candidate-grid,
            .checks {
                grid-template-columns: 1fr;
            }

            .verification-form {
                align-items: stretch;
                flex-direction: column;
            }

            .verification-form .field {
                width: 100%;
            }

            .form-actions {
                justify-content: stretch;
            }

            .form-actions .button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .candidate-fields {
                grid-template-columns: 1fr;
            }

            .card-header,
            .card-body {
                padding: 20px;
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
                <div class="kicker">Observer workflow</div>

                <h1>Record Polling Unit Result</h1>

                <p class="intro">
                    Record result data, attach its source evidence, and submit
                    the record for VeriVote NG integrity verification.
                </p>
            </header>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <h2>Submission could not be completed</h2>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="card">

                <div class="card-header">
                    <h2>Submit Result</h2>

                    <p>
                        Enter the polling-unit accounting figures and
                        candidate vote entries exactly as recorded.
                    </p>
                </div>

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('results.store') }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        {{-- Result identity and accounting --}}

                        <div class="form-section">

                            <div class="section-title">
                                <h3>Result information</h3>

                                <p>
                                    Identify the election and polling unit,
                                    then provide the ballot accounting figures.
                                </p>
                            </div>

                            <div class="fields">

                                <div class="field">
                                    <label for="election_id">
                                        Election ID
                                    </label>

                                    <input
                                        type="number"
                                        id="election_id"
                                        name="election_id"
                                        value="{{ old('election_id', 1) }}"
                                        min="1"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="polling_unit_id">
                                        Polling Unit ID
                                    </label>

                                    <input
                                        type="number"
                                        id="polling_unit_id"
                                        name="polling_unit_id"
                                        value="{{ old('polling_unit_id', 1) }}"
                                        min="1"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="accredited_voters">
                                        Accredited Voters
                                    </label>

                                    <input
                                        type="number"
                                        id="accredited_voters"
                                        name="accredited_voters"
                                        value="{{ old('accredited_voters', 100) }}"
                                        min="0"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="ballots_issued">
                                        Ballots Issued
                                    </label>

                                    <input
                                        type="number"
                                        id="ballots_issued"
                                        name="ballots_issued"
                                        value="{{ old('ballots_issued', 100) }}"
                                        min="0"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="unused_ballots">
                                        Unused Ballots
                                    </label>

                                    <input
                                        type="number"
                                        id="unused_ballots"
                                        name="unused_ballots"
                                        value="{{ old('unused_ballots', 10) }}"
                                        min="0"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="spoiled_ballots">
                                        Spoiled Ballots
                                    </label>

                                    <input
                                        type="number"
                                        id="spoiled_ballots"
                                        name="spoiled_ballots"
                                        value="{{ old('spoiled_ballots', 0) }}"
                                        min="0"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="rejected_votes">
                                        Rejected Votes
                                    </label>

                                    <input
                                        type="number"
                                        id="rejected_votes"
                                        name="rejected_votes"
                                        value="{{ old('rejected_votes', 5) }}"
                                        min="0"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="total_valid_votes">
                                        Total Valid Votes
                                    </label>

                                    <input
                                        type="number"
                                        id="total_valid_votes"
                                        name="total_valid_votes"
                                        value="{{ old('total_valid_votes', 85) }}"
                                        min="0"
                                        required
                                    >
                                </div>

                            </div>
                        </div>

                        {{-- Candidate vote entries --}}

                        <div class="form-section">

                            <div class="section-title">
                                <h3>Candidate votes</h3>

                                <p>
                                    Enter each candidate identifier and the
                                    corresponding recorded vote count.
                                </p>
                            </div>

                            <fieldset>
                                <legend>Candidate vote entries</legend>

                                <div class="candidate-grid">

                                    <div class="candidate-row">
                                        <div class="candidate-label">
                                            Candidate 01
                                        </div>

                                        <div class="candidate-fields">

                                            <div class="field">
                                                <label for="candidate_id_1">
                                                    Candidate ID
                                                </label>

                                                <input
                                                    type="number"
                                                    id="candidate_id_1"
                                                    name="entries[0][candidate_id]"
                                                    value="{{ old('entries.0.candidate_id', 1) }}"
                                                    min="1"
                                                    required
                                                >
                                            </div>

                                            <div class="field">
                                                <label for="vote_count_1">
                                                    Vote Count
                                                </label>

                                                <input
                                                    type="number"
                                                    id="vote_count_1"
                                                    name="entries[0][vote_count]"
                                                    value="{{ old('entries.0.vote_count', 50) }}"
                                                    min="0"
                                                    required
                                                >
                                            </div>

                                        </div>
                                    </div>

                                    <div class="candidate-row">
                                        <div class="candidate-label">
                                            Candidate 02
                                        </div>

                                        <div class="candidate-fields">

                                            <div class="field">
                                                <label for="candidate_id_2">
                                                    Candidate ID
                                                </label>

                                                <input
                                                    type="number"
                                                    id="candidate_id_2"
                                                    name="entries[1][candidate_id]"
                                                    value="{{ old('entries.1.candidate_id', 2) }}"
                                                    min="1"
                                                    required
                                                >
                                            </div>

                                            <div class="field">
                                                <label for="vote_count_2">
                                                    Vote Count
                                                </label>

                                                <input
                                                    type="number"
                                                    id="vote_count_2"
                                                    name="entries[1][vote_count]"
                                                    value="{{ old('entries.1.vote_count', 25) }}"
                                                    min="0"
                                                    required
                                                >
                                            </div>

                                        </div>
                                    </div>

                                    <div class="candidate-row">
                                        <div class="candidate-label">
                                            Candidate 03
                                        </div>

                                        <div class="candidate-fields">

                                            <div class="field">
                                                <label for="candidate_id_3">
                                                    Candidate ID
                                                </label>

                                                <input
                                                    type="number"
                                                    id="candidate_id_3"
                                                    name="entries[2][candidate_id]"
                                                    value="{{ old('entries.2.candidate_id', 3) }}"
                                                    min="1"
                                                    required
                                                >
                                            </div>

                                            <div class="field">
                                                <label for="vote_count_3">
                                                    Vote Count
                                                </label>

                                                <input
                                                    type="number"
                                                    id="vote_count_3"
                                                    name="entries[2][vote_count]"
                                                    value="{{ old('entries.2.vote_count', 10) }}"
                                                    min="0"
                                                    required
                                                >
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                        </div>

                        {{-- Source evidence --}}

                        <div class="form-section">

                            <div class="section-title">
                                <h3>Source evidence</h3>

                                <p>
                                    Attach the evidence associated with this
                                    polling-unit result.
                                </p>
                            </div>

                            <div class="field">
                                <label for="evidence">
                                    Evidence file
                                </label>

                                <input
                                    type="file"
                                    id="evidence"
                                    name="evidence"
                                    required
                                >

                                <p class="field-help">
                                    Maximum file size: 10 MB.
                                </p>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button
                                type="submit"
                                class="button button-primary"
                            >
                                Submit Result for Verification
                            </button>
                        </div>

                    </form>

                </div>
            </section>

            {{-- Existing result verification --}}

            <section class="card verification-card">

                <div class="card-header">
                    <h2>Verify Existing Result</h2>

                    <p>
                        Run VeriVote NG's deterministic integrity checks
                        against an existing recorded result.
                    </p>
                </div>

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('results.verify', ['result' => 1]) }}"
                        class="verification-form"
                    >
                        @csrf

                        <div class="field">
                            <label for="verification_result_id">
                                Result ID
                            </label>

                            <input
                                type="number"
                                id="verification_result_id"
                                name="result_id"
                                value="1"
                                min="1"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="button button-primary"
                        >
                            Run Verification
                        </button>

                    </form>

                    @if ($result && $result->verificationChecks->isNotEmpty())

                        <div class="checks">

                            @foreach ($result->verificationChecks as $check)

                                <article class="check">

                                    <div class="check-title">

                                        <h4>
                                            {{ $check->rule_name }}
                                        </h4>

                                        <span
                                            class="check-status {{ $check->passed ? 'passed' : 'failed' }}"
                                        >
                                            {{ $check->passed ? 'PASSED' : 'FAILED' }}
                                        </span>

                                    </div>

                                    <p>
                                        {{ $check->details }}
                                    </p>

                                    <div class="check-time">
                                        Executed:
                                        {{ $check->executed_at?->format('Y-m-d H:i:s') }}
                                    </div>

                                </article>

                            @endforeach

                        </div>

                    @endif

                </div>
            </section>

        </div>
    </main>

    <footer>
        <div class="container">
            VeriVote NG · Result Capture · Evidence Verification MVP
        </div>
    </footer>

</body>
</html>