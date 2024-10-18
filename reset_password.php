<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password and clear the reset token
        $query = "UPDATE users SET password = '$hashed_password', reset_token = NULL WHERE reset_token = '$token'";
        if ($conn->query($query) === TRUE) {
            $_SESSION['message'] = "Password updated successfully. You can now log in.";
            header("Location: index.php"); // Redirect to index
            exit();
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Passwords do not match.";
    }
}

// Check if token is valid
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT * FROM users WHERE reset_token = '$token'";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        echo "Invalid or expired token.";
        exit();
    }
} else {
    echo "No token provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h3>Reset Your Password</h3>
        <form method="post" action="reset_password.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</body>

</html>