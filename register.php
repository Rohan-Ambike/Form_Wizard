<?php
// Include database connection
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Determine role
    $role = (strpos($email, 'admin') !== false) ? 'admin' : 'user';

    // Insert into database
    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='index.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Form Wizard</title>
</head>
<body>
    <h1>Register</h1>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
