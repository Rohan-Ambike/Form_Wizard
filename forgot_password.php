<?php
// require 'db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $employee_email = $conn->real_escape_string($_POST['employee_email']);

//     // Check if the email exists
//     $query = "SELECT * FROM users WHERE employee_email = '$employee_email'";
//     $result = $conn->query($query);

//     if ($result->num_rows > 0) {
//         $user = $result->fetch_assoc();

//         // Generate a unique token
//         $reset_token = bin2hex(random_bytes(16));
//         $updateQuery = "UPDATE users SET reset_token = '$reset_token' WHERE employee_email = '$employee_email'";
//         if ($conn->query($updateQuery) === TRUE) {
//             // Prepare the reset link
//             $reset_link = "http://yourdomain.com/reset_password.php?token=$reset_token";

//             // Send email (ensure mail() function is set up properly in your server)
//             $subject = "Password Reset Request";
//             $message = "Click the following link to reset your password: $reset_link";
//             $headers = "From: no-reply@yourdomain.com\r\n";

//             mail($employee_email, $subject, $message, $headers);

//             echo "Password reset link has been sent to your email.";
//         } else {
//             echo "Error updating reset token: " . $conn->error;
//         }
//     } else {
//         echo "No user found with that email.";
//     }
// }
// $conn->close();
