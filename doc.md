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
            // Insert into user_access after successful user registration
            $accessQuery = "INSERT INTO user_access (email_id, admin_access, test_list) VALUES (?, 'no', '[]')";
            $stmtAccess = $conn->prepare($accessQuery);
            $stmtAccess->bind_param("s", $employee_email);

            if ($stmtAccess->execute()) {
                $_SESSION['employee_email'] = $employee_email;
                header('Location: hello.php'); // Redirect to a success page
                exit();
            } else {
                echo "Error inserting into user_access: " . $stmtAccess->error;
            }
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


db.php

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

// Check if the database exists
$db_selected = $conn->query("SHOW DATABASES LIKE '$dbname'");

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


create_database.sql

-- Create the database
CREATE DATABASE
IF NOT EXISTS formwizard;

-- Use the created database
USE formwizard;

-- Create the users table
CREATE TABLE
IF NOT EXISTS users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR
(50) NOT NULL,
    last_name VARCHAR
(50) NOT NULL,
    employee_email VARCHAR
(100) UNIQUE NOT NULL,
    password VARCHAR
(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the user_access table
CREATE TABLE
IF NOT EXISTS user_access
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email_id VARCHAR
(100) UNIQUE NOT NULL,
    admin_access ENUM
('yes', 'no') DEFAULT 'no',
    test_list JSON DEFAULT '[]',
    FOREIGN KEY
(email_id) REFERENCES users
(employee_email) ON
DELETE CASCADE
);


