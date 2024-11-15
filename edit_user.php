<?php
// Database connection
$conn = new mysqli("127.0.0.1:3307", "root", "", "project");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get updated user data from AJAX request
if (isset($_POST['id']) && isset($_POST['username']) && isset($_POST['email'])) {
    $id = intval($_POST['id']);
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update query
    $sql = "UPDATE login SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $id);

    if ($stmt->execute()) {
        echo "User updated successfully.";
    } else {
        echo "Error updating user: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
