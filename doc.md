admin.php -

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

db.php -

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

$db_selected = $conn->query("SHOW DATABASES LIKE '$dbname'");
if ($db_selected === false) {
    die("<script>alert('Error checking database existence: " . $conn->error . "');</script>");
}

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

index.php -

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

login.php -

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

            // Redirect to manageuser.php if the logged-in user is the master user
            if ($employee_email === 'master@admin.self') {
                header("Location: manageuser.php");
            } else {
                header("Location: admin.php");
            }
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


logout.php -

<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();


manageuser.php -

<?php
session_start();

// Check if the user is logged in and if their email matches the master email
if (!isset($_SESSION['employee_email']) || $_SESSION['employee_email'] !== 'master@admin.self') {
    header("Location: index.php"); // Redirect to the login page
    exit();
}

// Include the database connection
require 'db.php';

// Fetch users from the database, joining user_access
$query = "SELECT u.id, u.first_name, u.last_name, u.employee_email, 
                 ua.admin_access, ua.test_list, u.created_at 
          FROM users u
          LEFT JOIN user_access ua ON u.employee_email = ua.email_id";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="./styles/measurements.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/font.css">
    <style>
        .editable {
            background-color: #f9f9f9;
        }

        .hidden {
            display: none;
        }

        /* Ensure table cells wrap text and have a maximum width */
        td {
            max-width: 150px;
            /* Adjust this value as needed */
            overflow-wrap: normal;
            /* Allow text to wrap */
            word-wrap: break-word;
            /* Ensure compatibility */
            word-break: break-word;
            /* Prevent overflow */
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="text-center mb-0 playwrite fw-6">Manage Users</h1>
            <a href="logout.php" class="btn btn-danger mt-2 me-5 fira-sans-medium">Logout</a>
        </div>

        <div id="loading-spinner" class="text-center hidden">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <table class="table table-striped table-responsive fira-sans-regular">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Admin Access</th>
                    <th>Test List</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>
                                <span class='display'>" . htmlspecialchars($row['first_name']) . "</span>
                                <input type='text' value='" . htmlspecialchars($row['first_name']) . "' class='form-control editable hidden' />
                            </td>
                            <td>
                                <span class='display'>" . htmlspecialchars($row['last_name']) . "</span>
                                <input type='text' value='" . htmlspecialchars($row['last_name']) . "' class='form-control editable hidden' />
                            </td>
                            <td>
                                <span class='display'>" . htmlspecialchars($row['employee_email']) . "</span>
                                <input type='text' value='" . htmlspecialchars($row['employee_email']) . "' class='form-control editable hidden' />
                            </td>
                            <td>
                                <span class='display'>" . htmlspecialchars($row['admin_access']) . "</span>
                                <select class='form-select editable hidden'>
                                    <option value='yes' " . ($row['admin_access'] == 'yes' ? 'selected' : '') . ">Yes</option>
                                    <option value='no' " . ($row['admin_access'] == 'no' ? 'selected' : '') . ">No</option>
                                </select>
                            </td>
                            <td>
                                <span class='display'>" . htmlspecialchars($row['test_list']) . "</span>
                                <input type='text' value='" . htmlspecialchars($row['test_list']) . "' class='form-control editable hidden' />
                            </td>
                            <td>" . htmlspecialchars($row['created_at']) . "</td>
                            <td>
                                <button class='btn btn-primary edit-btn'>Edit</button>
                                <button class='btn btn-success save-btn hidden' disabled>Save</button>
                            </td>
                          </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const inputs = row.querySelectorAll('.editable');
                const displays = row.querySelectorAll('.display');
                const saveButton = row.querySelector('.save-btn');

                // Enable inputs and toggle button states
                inputs.forEach((input, index) => {
                    input.classList.remove('hidden');
                    input.removeAttribute('disabled');
                    displays[index].classList.add('hidden');
                });
                this.classList.add('hidden');
                saveButton.classList.remove('hidden');
                saveButton.removeAttribute('disabled');
            });
        });

        document.querySelectorAll('.save-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');
                const inputs = row.querySelectorAll('.editable');
                const displays = row.querySelectorAll('.display');
                const data = {
                    id: id,
                    first_name: inputs[0].value,
                    last_name: inputs[1].value,
                    email: inputs[2].value,
                    admin_access: inputs[3].value,
                    test_list: inputs[4].value
                };

                // Send the updated data to the server
                fetch('updateuser.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            // Update the displayed values and hide inputs
                            inputs.forEach((input, index) => {
                                input.classList.add('hidden');
                                displays[index].innerText = data[Object.keys(data)[index + 1]];
                                displays[index].classList.remove('hidden');
                            });
                            this.classList.add('hidden');
                            row.querySelector('.edit-btn').classList.remove('hidden');
                        } else {
                            alert(result.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>

</html>

<?php
$conn->close(); // Close the database connection
?>

signup.php -

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

                // Redirect to manageuser.php if the registered user is the master user
                if ($employee_email === 'master@admin.self') {
                    header('Location: manageuser.php');
                } else {
                    header('Location: admin.php'); // Redirect to a success page
                }
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


updateuser.php -

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
