{{-- 
    VeriVote NG Observer Dashboard

    Presents election data retrieved by DashboardController and provides
    the observer entry point for reviewing results, evidence, and verification status.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriVote NG — Observer Dashboard</title>
</head>
<body>
    <main>
        <h1>VeriVote NG Observer Dashboard</h1>

        <p>Review election results, evidence, and verification status.</p>

        <section>
            <h2>Elections</h2>

            @forelse ($elections as $election)
                <article>
                    <h3>{{ $election->title }}</h3>
                    <p>Code: {{ $election->code }}</p>
                    <p>Type: {{ $election->election_type }}</p>
                    <p>Voting Date: {{ $election->voting_date }}</p>
                    <p>Status: {{ $election->status }}</p>
                </article>
            @empty
                <p>No elections are currently available.</p>
            @endforelse
        </section>
    </main>
</body>
</html>