<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FormWizard";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
} else {
    // echo "<script>alert('Database connection successful!');</script>";
}
