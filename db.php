<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formwizard"; // Name of the database

// Create a connection without specifying the database
$conn = new mysqli($servername, $username, $password);

// Check the connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

$db_selected = $conn->query("SHOW DATABASES LIKE '$dbname'");
if ($db_selected === false) {
    die("<script>alert('Error checking database existence: " . $conn->error . "');</script>");
}

if ($db_selected->num_rows == 0) {
    // If the database doesn't exist, execute the SQL file to create it
    $sql_file = file_get_contents('./sql/create_database.sql');

    if (mysqli_multi_query($conn, $sql_file)) {
        echo "<script>alert('Database created successfully!');</script>";
        // Wait for all queries to complete
        while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
        }
    } else {
        die("<script>alert('Error creating database: " . $conn->error . "');</script>");
    }
}

// Close and reconnect to the newly created database
$conn->close();
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection to the newly created database
if ($conn->connect_error) {
    die("<script>alert('Connection to database failed: " . $conn->connect_error . "');</script>");
}

// Now you can use $conn to interact with the database
