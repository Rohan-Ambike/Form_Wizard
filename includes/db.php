<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "form_wizard";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// To reuse this connection, include 'db.php' in other scripts
?>
