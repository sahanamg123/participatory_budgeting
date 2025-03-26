<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['proposal_id'])) {
    $proposal_id = $_GET['proposal_id'];

    // Check if the user has already voted
    $check_vote = "SELECT * FROM votes WHERE user_id='$user_id' AND proposal_id='$proposal_id'";
    $result = $conn->query($check_vote);

    if ($result->num_rows == 0) {
        // Insert vote
        $insert_vote = "INSERT INTO votes (user_id, proposal_id) VALUES ('$user_id', '$proposal_id')";
        $conn->query($insert_vote);

        // Update vote count in proposals table
        $update_votes = "UPDATE proposals SET vote_count = vote_count + 1 WHERE id='$proposal_id'";
        $conn->query($update_votes);

        $message = "✅ Voting Successful!";
    } else {
        $message = "⚠️ You have already voted for this proposal.";
    }
} else {
    $message = "❌ Invalid Proposal ID.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vote Status</title>
    <style>
        body {
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            font-family: Arial, sans-serif;
            text-align: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        p {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
        }

        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .back-btn:hover {
            background: #217dbb;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🗳 Voting Status</h2>
        <p><?php echo $message; ?></p>
        <a href="vote_proposals.php" class="back-btn">⬅️ Back to Voting</a>
    </div>

</body>
</html>
