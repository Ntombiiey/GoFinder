<?php
$host = 'localhost';  // Database host (usually localhost)
$dbname = 'go_finder'; // Database name
$username = 'root';   // Database username
$password = '';       // Database password (set your password here)

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
