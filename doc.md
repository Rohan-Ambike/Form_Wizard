index.php
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
                <img class="img-fluid rounded-circle" src="./images/FormWizard.jpeg" alt="Logo" width="170">
                <h2 class="playwrite fm-9 fw-5 ps-4 ps-md-5">
                    Form Wizard
                </h2>
            </div>
        </div>

        <div class="row justify-content-center mt-2 d-none" id="signup-section">
            <div class="col-md-8 col-lg-6 col-xl-5 shadow p-5">
                <h3 class="text-start mb-4 fira-sans-medium">Sign Up</h3>
                <form method="post" action="signup.php">
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
                        <!-- <button type="button" class="btn btn-warning" onclick="showSection('forgot-section')">Reset Password</button> -->
                        <button type="submit" class="btn btn-success">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row justify-content-center mt-2 align-items-center">
            <div class="col-md-8 col-lg-6 col-xl-5 shadow p-5 d-none" id="login-section">
                <h3 class="text-start mb-4 fira-sans-medium">Login</h3>
                <form method="post" action="login.php" onsubmit="return loginAlert()">
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
                        <!-- <button type="button" class="btn btn-warning" onclick="showSection('forgot-section')">Reset Password</button> -->
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row justify-content-center mt-2 d-none" id="forgot-section">
            <div class="col-md-8 col-lg-6 col-xl-5 shadow p-5">
                <h3 class="text-start mb-4 fira-sans-medium">Forgot Password</h3>
                <form method="post" action="forgot_password.php">
                    <div class="mb-3">
                        <label for="forgotEmployeeID" class="form-label">Employee Email</label>
                        <input type="text" class="form-control" id="forgotEmployeeID" name="employee_email" required>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" onclick="showSection('login-section')">Login</button>
                        <button type="submit" class="btn btn-warning">Reset Password</button>
                        <button type="button" class="btn btn-success" onclick="showSection('signup-section')">Sign Up</button>
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
                document.getElementById('forgot-section').classList.add('d-none');

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

db.php

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FormWizard";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
} else {
    // echo "<script>alert('Database connection successful!');</script>";
}


signup.php

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


login.php

<?php
session_start();
require 'db.php'; // Make sure db.php connects to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_email = $_POST['employee_email'];
    $password = $_POST['password'];

    // Check if input fields are not empty
    if (!empty($employee_email) && !empty($password)) {
        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT * FROM users WHERE employee_email = ?");
        $stmt->bind_param("s", $employee_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
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
    } else {
        $_SESSION['login_error'] = "All fields are required.";
    }

    // Redirect back to index.php with an alert
    header('Location: index.php');
    exit();
}

hello.php

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

logout.php

<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
