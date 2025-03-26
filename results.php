<?php
include 'db.php';

$result = $conn->query("SELECT * FROM proposals ORDER BY votes DESC");
while ($row = $result->fetch_assoc()) {
    echo "<p>{$row['title']} - Votes: {$row['votes']}</p>";
}
?>
