<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized Access! Please <a href='login.php'>login</a> first.");
}

include 'db.php';
$user_id = $_SESSION['user_id'];

// Fetch proposals submitted by others
$sql = "SELECT proposals.*, 
        (SELECT COUNT(*) FROM votes WHERE votes.proposal_id = proposals.id) AS vote_count
        FROM proposals 
        WHERE proposals.user_id != '$user_id'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vote on Proposals</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #ecf0f1;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        strong {
            color: #2980b9;
        }

        .vote-btn {
            padding: 10px 15px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        .vote-btn:hover {
            background: #219150;
        }

        .voted {
            color: #27ae60;
            font-weight: bold;
        }

        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #e74c3c;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .back-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🗳 Vote on Proposals</h2>

        <?php
        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><div><strong>" . htmlspecialchars($row['title']) . "</strong><br>" . htmlspecialchars($row['description']);
                echo "<br>🔹 Votes: " . $row['vote_count'] . "</div>";

                $proposal_id = $row['id']; // Ensure proposal ID is set
                $vote_check = "SELECT * FROM votes WHERE user_id='$user_id' AND proposal_id='$proposal_id'";
                $vote_result = $conn->query($vote_check);

                if ($vote_result->num_rows == 0) {
                    echo "<a href='vote.php?proposal_id=$proposal_id' class='vote-btn'>✅ Vote</a>";
                } else {
                    echo "<span class='voted'>✔️ Voted</span>";
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>❌ No proposals available for voting.</p>";
        }
        ?>

        <a href="dashboard.php" class="back-btn">⬅️ Go Back</a>
    </div>

</body>
</html>