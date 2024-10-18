<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $employee_email = $_POST['employee_email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE employee_email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $employee_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['redirected'] = true;
        header('Location: index.php'); // Redirect to the index page
        exit();
    } else {
        // Insert the new user using a prepared statement
        $query = "INSERT INTO users (first_name, last_name, employee_email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $first_name, $last_name, $employee_email, $password);

        if ($stmt->execute()) {
            $_SESSION['employee_email'] = $employee_email;
            header('Location: hello.php'); // Redirect to a success page
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}
$conn->close();
