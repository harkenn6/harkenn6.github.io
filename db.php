<?php
// db
$conn = new mysqli('localhost', 'root', '', 'newbies_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
