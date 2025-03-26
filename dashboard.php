<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized Access! Please <a href='login.php'>Login</a> first.");
}

include 'db.php';
$user_id = $_SESSION['user_id'];

// ✅ Check if the user is an admin
$sql = "SELECT role FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$is_admin = ($user['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <style>
        body {
            background: url('https://example.com/your-image.jpg') no-repeat center center fixed;
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

        h2, h3 {
            color: #FFD700; /* Gold color */
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
        }

        a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            background: #3498db; /* Blue color */
            border-radius: 5px;
            transition: 0.3s;
        }

        a:hover {
            background: #2980b9;
        }

        .logout {
            background: #e74c3c;
        }

        .logout:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>! 🎉</h2>
        <h3>Participatory Budgeting System</h3>

        <ul>
            <li><a href="submit_proposal.php">📜 Submit a Proposal</a></li>
            <li><a href="view_proposals.php">📋 View My Proposals</a></li>
            <li><a href="vote_proposals.php">🗳️ Vote on Proposals</a></li>
            <?php if ($is_admin): ?>
                <li><a href="admin_dashboard.php" style="background: #f39c12;">🔹 Admin Dashboard (View Votes)</a></li>
            <?php endif; ?>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>
    </div>

</body>
</html>