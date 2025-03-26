<?php
// Database connection settings
$host = "localhost";  // Server name (default: localhost)
$user = "root";       // Default XAMPP username
$password = "";       // Default XAMPP has no password
$dbname = "participatory_budgeting";  // Change to your database name

// Create database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
