<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['employee_email'])) {
    // Redirect to login page if session is not set
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container text-center">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['employee_email']); ?>!</h1>
        <p class="mt-4">You are successfully logged in to the Form Wizard system.</p>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>
</body>

</html>