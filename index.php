<?php
require 'db.php'; // Include the database connection check

session_start();

$showLoginSection = false; // Initialize a variable to control section display

if (isset($_SESSION['redirected']) && $_SESSION['redirected'] === true) {
    echo '<script type="text/javascript">';
    echo 'alert("This email is already registered!");';
    echo '</script>';
    unset($_SESSION['redirected']);
}

if (isset($_SESSION['login_error'])) {
    $showLoginSection = true; // Set flag to show login section
    echo '<script type="text/javascript">';
    echo 'alert("' . addslashes($_SESSION['login_error']) . '");';
    echo '</script>';
    unset($_SESSION['login_error']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Wizard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="./styles/measurements.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/font.css">
    <link rel="icon" href="./images/favicon.png" sizes="192x192" />
</head>

<body class="" style="min-height: 100vh;">
    <div class="container pt-2 pb-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 col-xl-5 p-4 d-flex align-items-center justify-content-center">
                <img class="img-fluid rounded-circle" src="./images/formwizard.jpeg" alt="Logo" width="170">
                <h2 class="playwrite fm-9 fw-5 ps-4 ps-md-5">
                    Form Wizard
                </h2>
            </div>
        </div>

        <div class="row justify-content-center mt-2 d-none" id="signup-section">
            <div class="col-md-8 col-lg-6 col-xl-5 shadow p-5">
                <h3 class="text-start mb-4 fira-sans-medium">Sign Up</h3>
                <form class="mb-0" method="post" action="signup.php">
                    <div class="mb-3">
                        <label for="signupFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="signupFirstName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="signupLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="signupLastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="signupEmployeeID" class="form-label">Employee Email</label>
                        <input type="text" class="form-control" id="signupEmployeeID" name="employee_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="signupPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="signupPassword" name="password" required>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" onclick="showSection('login-section')">Login</button>
                        <button type="submit" class="btn btn-success">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row justify-content-center mt-2 align-items-center">
            <div class="col-md-8 col-lg-6 col-xl-5 shadow p-5 d-none" id="login-section">
                <h3 class="text-start mb-4 fira-sans-medium">Login</h3>
                <form class="mb-0" method="post" action="login.php" onsubmit="return loginAlert()">
                    <div class="mb-3">
                        <label for="loginEmployeeEmail" class="form-label">Employee Email</label>
                        <input type="text" class="form-control" id="loginEmployeeEmail" name="employee_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-success" onclick="showSection('signup-section')">Sign Up</button>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Function to show specific sections
            function showSection(sectionId) {
                // Hide all sections first
                document.getElementById('login-section').classList.add('d-none');
                document.getElementById('signup-section').classList.add('d-none');

                // Show the selected section
                document.getElementById(sectionId).classList.remove('d-none');
            }

            // Automatically show the appropriate section on page load
            window.onload = () => {
                showSection('signup-section'); // Default section

                // Show login section if there was a login error
                <?php if ($showLoginSection): ?>
                    showSection('login-section');
                <?php endif; ?>
            };
        </script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
    </div>
</body>

</html>