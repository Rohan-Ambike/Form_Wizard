<?php
session_start();
require 'db.php'; // Ensure db.php connects to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_email = $_POST['employee_email'];
    $password_entered = $_POST['password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM users WHERE employee_email = ?");
    $stmt->bind_param("s", $employee_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashed_password_from_db = $user['password'];

        // Verify password
        if (password_verify($password_entered, $hashed_password_from_db)) {
            $_SESSION['employee_email'] = $employee_email;
            header("Location: hello.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid password. Please try again.";
        }
    } else {
        $_SESSION['login_error'] = "User not found. Please check your email.";
    }

    $stmt->close();
    header('Location: index.php');
    exit();
}
