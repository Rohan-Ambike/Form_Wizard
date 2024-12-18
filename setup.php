<?php
$servername = "localhost";
$username = "root";
$password = "";

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load SQL script
$sql_file = file_get_contents('./sql/form_wizard.sql');

// Execute SQL script
if ($conn->multi_query($sql_file)) {
    echo "Database and tables created successfully!";
} else {
    echo "Error creating database and tables: " . $conn->error;
}

// Close the connection
$conn->close();
?>
