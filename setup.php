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

// Execute SQL script to create database and tables
if ($conn->multi_query($sql_file)) {
    echo "Database and tables created successfully!<br>";
} else {
    echo "Error creating database and tables: " . $conn->error . "<br>";
}

// Add the 'test_name' column if it doesn't exist in the 'tests' table
$alter_sql = "ALTER TABLE tests
              ADD COLUMN IF NOT EXISTS test_name VARCHAR(255) NOT NULL;";

if ($conn->query($alter_sql) === TRUE) {
    echo "test_name column added successfully!<br>";
} else {
    echo "Error adding column: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>
