<?php
session_start();
include 'db.php'; // Include database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";

// Handle Proposal Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id']; // Get logged-in user ID

    // ✅ Check if the same title + description already exists
    $check_sql = "SELECT * FROM proposals WHERE user_id = '$user_id' AND title = '$title' AND description = '$description'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $message = "⚠️ You have already submitted this proposal!";
    } else {
        // Insert new proposal
        $sql = "INSERT INTO proposals (user_id, title, description, votes) VALUES ('$user_id', '$title', '$description', 0)";
        if ($conn->query($sql) === TRUE) {
            $message = "✅ Proposal submitted successfully!";
        } else {
            $message = "❌ Error submitting proposal: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Submit Proposal</title>
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
        }

        .container {
            width: 40%;
            margin: 100px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #FFD700;
        }

        label {
            font-size: 18px;
        }

        input, textarea {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 85%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background: #2980b9;
        }

        .message {
            color: #FFD700;
        }

        .back-button {
            width: 85%;
            padding: 10px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
        }

        .back-button:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Submit a Proposal</h2>
        
        <p class="message"><?php echo $message; ?></p>

        <form method="POST">
            <label for="title">Title:</label><br>
            <input type="text" name="title" required><br><br>

            <label for="description">Description:</label><br>
            <textarea name="description" required></textarea><br><br>

            <button type="submit">Submit Proposal</button>
        </form>

        <!-- 🔹 Go Back Button -->
        <br>
        <a href="dashboard.php" class="back-button">⬅️ Go Back</a>
    </div>

</body>
</html>
