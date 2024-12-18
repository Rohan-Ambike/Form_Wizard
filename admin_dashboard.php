<?php
// Include database connection and session
include 'includes/db.php';
session_start();

// Restrict access to admin only
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied!'); window.location.href='index.php';</script>";
    exit;
}

// Fetch all users for assigning tests
$sql_users = "SELECT id, email FROM users WHERE role = 'user'";
$result_users = $conn->query($sql_users);

// Handle test creation and assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_name = $_POST['test_name'];
    $questions = json_encode($_POST['questions']); // Encode questions into JSON format
    $assigned_to = $_POST['assigned_to']; // Array of user IDs

    // Insert test into database
    $sql_test = "INSERT INTO tests (test_name, questions) VALUES (?, ?)";
    $stmt_test = $conn->prepare($sql_test);
    $stmt_test->bind_param("ss", $test_name, $questions);
    $stmt_test->execute();
    $test_id = $stmt_test->insert_id;

    // Assign test to selected users
    foreach ($assigned_to as $user_id) {
        $sql_assign = "INSERT INTO test_results (test_id, user_id) VALUES (?, ?)";
        $stmt_assign = $conn->prepare($sql_assign);
        $stmt_assign->bind_param("ii", $test_id, $user_id);
        $stmt_assign->execute();
    }

    echo "<script>alert('Test created and assigned successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Form Wizard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>Create and Assign Test</h2>

    <form method="POST" action="">
        <label for="test_name">Test Name:</label>
        <input type="text" name="test_name" id="test_name" required><br><br>

        <h3>Questions:</h3>
        <div id="questions">
            <div class="question">
                <input type="text" name="questions[0][question]" placeholder="Enter question" required>
                <input type="text" name="questions[0][options][]" placeholder="Option 1" required>
                <input type="text" name="questions[0][options][]" placeholder="Option 2" required>
                <input type="text" name="questions[0][options][]" placeholder="Option 3" required>
                <input type="text" name="questions[0][options][]" placeholder="Option 4" required>
                <label>Correct Answer:</label>
                <input type="text" name="questions[0][correct]" required>
            </div>
        </div>
        <button type="button" onclick="addQuestion()">Add Question</button><br><br>

        <h3>Assign Test To:</h3>
        <?php while ($user = $result_users->fetch_assoc()) { ?>
            <label>
                <input type="checkbox" name="assigned_to[]" value="<?= $user['id']; ?>">
                <?= $user['email']; ?>
            </label><br>
        <?php } ?>

        <br>
        <button type="submit">Create and Assign Test</button>
    </form>

    <script>
        let questionCount = 1;

        function addQuestion() {
            const questionsDiv = document.getElementById('questions');
            const newQuestion = `
                <div class="question">
                    <input type="text" name="questions[${questionCount}][question]" placeholder="Enter question" required>
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 1" required>
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 2" required>
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 3" required>
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 4" required>
                    <label>Correct Answer:</label>
                    <input type="text" name="questions[${questionCount}][correct]" required>
                </div>`;
            questionsDiv.insertAdjacentHTML('beforeend', newQuestion);
            questionCount++;
        }
    </script>
</body>
</html>
