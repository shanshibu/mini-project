<?php
include 'db_connection.php'; // Include the database connection file

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'];
$sql = "SELECT id, book_name, price, place, book_image, user_type FROM sellings WHERE book_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="icon" href="bmlogo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
        }
        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            background: #fff;
            padding: 20px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .card-title {
            color: #007bff;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            color: #555;
        }
        h2 {
            color: #007bff;
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-more {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 25px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .btn-more:hover {
            background-color: #0056b3;
            text-decoration: none;
        }
        /* Navbar styles */
        .navbar {
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar-brand img {
            height: 50px;
            margin-right: 15px;
        }
        .navbar-nav {
            flex-grow: 1;
            justify-content: center; /* Center menu items */
        }
        .nav-item {
            margin-left: 15px;
            margin-right: 15px;
        }
        .nav-link {
            font-size: 1.1rem;
            font-weight: bold;
            color: #007bff;
            text-transform: uppercase;
        }
        .navbar-brand img {
            width: 50px;
            height: 50px;
            border-radius: 50%; /* Make the logo circular */
        }
        .nav-link:hover {
            color: #0056b3;
        }
        .signout-btn {
            font-size: 1rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            text-align: center;
        }
        .signout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="bmlogo.png" alt="BMSmart Logo" style="width: 40px; height: 40px; border-radius: 50%;"> 
        <span class="ms-1" style="font-weight: bold; font-size: 1.5rem; color: black;">BooksMart</span>
    </a>
    <div class="navbar-collapse">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact_us.html">Contact Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sellings.php">Sell</a>
            </li>
        </ul>
        <a class="signout-btn" href="logout.php">Sign Out</a>
    </div>
</nav>


<div class="container">
    <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!is_null($row['book_image']) && !empty($row['book_image'])): ?>
                            <?php $imageData = base64_encode($row['book_image']); ?>
                            <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($imageData); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['book_name']); ?>">
                        <?php else: ?>
                            <img src="placeholder.jpg" class="card-img-top" alt="Placeholder Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['book_name']); ?></h5>
                            <p class="card-text">Place: <?php echo htmlspecialchars($row['place']); ?></p>
                            <p class="card-text">Price: ₹<?php echo htmlspecialchars($row['price']); ?></p>
                            <a href="proceed.php?id=<?php echo urlencode($row['id']); ?>&user_type=<?php echo urlencode($row['user_type']); ?>" class="btn-more">More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    No results found for "<?php echo htmlspecialchars($query); ?>".
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
