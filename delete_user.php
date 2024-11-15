<?php
// Database connection
$conn = new mysqli("127.0.0.1:3307", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user ID is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get user ID from URL

    // Delete query
    $sql = "DELETE FROM login WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "User deleted successfully.";
        header("Location: dashboard.php"); // Redirect to user management page
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
