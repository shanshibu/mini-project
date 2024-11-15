<?php
include 'db_connection.php';

// Retrieve book name from query string
$bookName = isset($_GET['name']) ? $conn->real_escape_string($_GET['name']) : '';

// Query to get book details
$sql = "SELECT book_name, price, place, owner_name, phone_number, email, payment_method, rent_or_sell FROM sellings WHERE book_name = '$bookName'";
$result = $conn->query($sql);

// Fetch result and return as JSON
if ($result->num_rows > 0) {
    $bookDetails = $result->fetch_assoc();
    echo json_encode($bookDetails);
} else {
    echo json_encode([]);
}

$conn->close();
?>
