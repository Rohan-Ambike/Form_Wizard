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
    <style>
        .editable {
            background-color: #f9f9f9;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Manage Users</h1>

        <a href="logout.php" class="btn btn-danger mb-3">Logout</a>

        <table class="table table-striped mt-4">
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
                fetch('update_user.php', {
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