

<?php
header('Content-Type: application/json');

include 'db_connection.php';

$query = $_GET['query'];
$sql = "SELECT book_name FROM sellings WHERE book_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$bookNames = array();
while ($row = $result->fetch_assoc()) {
    $bookNames[] = $row['book_name'];
}

echo json_encode($bookNames);

$stmt->close();
$conn->close();
?>

