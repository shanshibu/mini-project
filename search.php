<?php
// Include the database connection
include('db_connection.php'); // Make sure this path is correct

// Get the search query
if (isset($_POST['query'])) {
    $query = $conn->real_escape_string($_POST['query']);
    
    // Query to search the book_name column
    $sql = "SELECT book_name FROM donation
            WHERE book_name LIKE '%$query%' 
            LIMIT 5"; // Limit results to 5

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display results
        while ($row = $result->fetch_assoc()) {
            echo '<a href="#" class="list-group-item list-group-item-action">' . htmlspecialchars($row['book_name']) . '</a>';
        }
    } else {
        // No results found
        echo '<div class="list-group-item">Not found or available</div>';
    }
}

$conn->close();
?>
