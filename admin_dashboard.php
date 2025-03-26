<?php
session_start();
include 'db.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<h2 style='color: red; text-align: center;'>Unauthorized Access! Please <a href='login.php' style='color: yellow;'>Login</a> first.</h2>");
}

// ✅ Fetch user role
$user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// ✅ Allow only admin access
if ($user['role'] !== 'admin') {
    die("<h2 style='color: red; text-align: center;'>❌ Access Denied! You are not authorized to view this page.</h2>");
}

// ✅ Fetch all proposals with vote count
$sql = "SELECT proposals.id, proposals.title, proposals.description, users.name AS submitted_by, 
        (SELECT COUNT(*) FROM votes WHERE votes.proposal_id = proposals.id) AS total_votes
        FROM proposals 
        JOIN users ON proposals.user_id = users.id
        ORDER BY total_votes DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Vote Review</title>
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.3);
        }

        h2 {
            color: #f1c40f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: black;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .buttons {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin: 5px;
            transition: 0.3s;
        }

        .btn-dashboard {
            background: #27ae60;
            color: white;
        }

        .btn-logout {
            background: #e74c3c;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🔹 Admin Dashboard - Proposal Votes 🔹</h2>
        <h3>Welcome, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?></h3>

        <table>
            <tr>
                <th>Proposal Title</th>
                <th>Description</th>
                <th>Submitted By</th>
                <th>Total Votes</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['title']) . "</td>
                            <td>" . htmlspecialchars($row['description']) . "</td>
                            <td>" . htmlspecialchars($row['submitted_by']) . "</td>
                            <td>" . $row['total_votes'] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No proposals found.</td></tr>";
            }
            ?>
        </table>

        <div class="buttons">
            <a href="dashboard.php" class="btn btn-dashboard">🏠 Go to User Dashboard</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
    </div>

</body>
</html>
