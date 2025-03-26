<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized Access! Please <a href='login.php'>login</a> first.");
}

include 'db.php';
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM proposals WHERE user_id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Proposals</title>
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
        }

        .container {
            width: 50%;
            margin: 100px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #FFD700;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 18px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            text-decoration: none;
            background: #3498db;
            color: white;
            border-radius: 5px;
        }

        a:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>My Submitted Proposals</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li><strong>" . htmlspecialchars($row['title']) . "</strong>: " . htmlspecialchars($row['description']) . "</li>";
                }
            } else {
                echo "<li>No proposals submitted yet.</li>";
            }
            ?>
        </ul>
        <a href="dashboard.php">🔙 Go Back</a>
    </div>

</body>
</html>
