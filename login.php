<?php
// Start the session
session_start();

// Include database connection
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);

    // Check if admin credentials are entered
    if ($inputUsername === 'admin' && $inputPassword === 'admin') {
        // Set admin session and redirect to dashboard
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin'; // Set role to identify the user as admin
        echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
        exit;
    }

    // Prepare and execute SQL statement for regular users
    $stmt = $conn->prepare("SELECT password FROM login WHERE username = ?");
    if ($stmt === false) {
        die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
    }

    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($storedPassword);
        $stmt->fetch();

        // Verify password
        if ($inputPassword === $storedPassword) {
            // Start user session and redirect to home
            $_SESSION['username'] = $inputUsername;
            echo json_encode(['success' => true, 'redirect' => 'home.php']);
        } else {
            // Invalid password
            echo json_encode(['error' => 'Invalid username or password.']);
        }
    } else {
        // Username does not exist
        echo json_encode(['error' => 'Invalid username or password.']);
    }

    $stmt->close();
}

$conn->close();
?>
