{{--
    VeriVote NG Result Submission

    Provides the observer interface for recording polling-unit result data.
    Submitted data is validated by StoreResultRequest before processing.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriVote NG — Record Result</title>
</head>
<body>
    <main>
        <h1>Record Polling Unit Result</h1>

        <form method="POST" action="#">
            @csrf

            <div>
                <label for="election_id">Election ID</label>
                <input type="number" id="election_id" name="election_id" required>
            </div>

            <div>
                <label for="polling_unit_id">Polling Unit ID</label>
                <input type="number" id="polling_unit_id" name="polling_unit_id" required>
            </div>

            <div>
                <label for="accredited_voters">Accredited Voters</label>
                <input type="number" id="accredited_voters" name="accredited_voters" min="0" required>
            </div>

            <div>
                <label for="ballots_issued">Ballots Issued</label>
                <input type="number" id="ballots_issued" name="ballots_issued" min="0" required>
            </div>

            <div>
                <label for="unused_ballots">Unused Ballots</label>
                <input type="number" id="unused_ballots" name="unused_ballots" min="0" required>
            </div>

            <div>
                <label for="spoiled_ballots">Spoiled Ballots</label>
                <input type="number" id="spoiled_ballots" name="spoiled_ballots" min="0" required>
            </div>

            <div>
                <label for="rejected_votes">Rejected Votes</label>
                <input type="number" id="rejected_votes" name="rejected_votes" min="0" required>
            </div>

            <div>
                <label for="total_valid_votes">Total Valid Votes</label>
                <input type="number" id="total_valid_votes" name="total_valid_votes" min="0" required>
            </div>

            <button type="submit">Submit Result</button>
        </form>
    </main>
</body>
</html>