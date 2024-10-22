<?php
session_start();

// Check if the user is logged in and has appropriate access
if (!isset($_SESSION['employee_email']) || $_SESSION['employee_email'] !== 'master@admin.self') {
    header("Location: index.php");
    exit();
}

// Include the database connection
require 'db.php';

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Prepare and bind for updating user details
$stmtUser = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, employee_email = ? WHERE id = ?");
$stmtUser->bind_param("sssi", $data['first_name'], $data['last_name'], $data['email'], $data['id']);

// Execute the statement for user details
$userUpdateSuccess = $stmtUser->execute();

// Prepare and bind for updating user access
$stmtAccess = $conn->prepare("UPDATE user_access SET admin_access = ?, test_list = ? WHERE email_id = (SELECT employee_email FROM users WHERE id = ?)");
$stmtAccess->bind_param("ssi", $data['admin_access'], $data['test_list'], $data['id']);

// Execute the statement for user access
$accessUpdateSuccess = $stmtAccess->execute();

// Check if both updates were successful
if ($userUpdateSuccess && $accessUpdateSuccess) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user or user access.']);
}

// Close the statements and connection
$stmtUser->close();
$stmtAccess->close();
$conn->close();
