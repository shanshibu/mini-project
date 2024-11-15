<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <link rel="icon" href="bmlogo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            background-color: #f8f9fa;
        }

        /* Navbar styling */
        .navbar-custom {
            background-color: #007bff;
            border-radius: 0 0 20px 20px;
        }

        .navbar-custom .navbar-brand {
            margin-left: 1rem;
        }

        .navbar-custom .navbar-nav .nav-link {
            color: white;
            margin: 0 1rem;
        }

        .navbar-custom .navbar-nav .nav-link:hover {
            color: #e0e0e0;
        }

        .navbar-custom .btn-signout {
            margin-right: 1rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Page styling */
        .container {
            margin-top: 20px;
        }

        .heading {
            text-align: center;
            margin: 30px 0;
            font-size: 2rem;
            color: #343a40;
        }

        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .book-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .book-card img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .card-body {
            text-align: center;
        }

        .more-btn {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
        }

        .more-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="home.php">
            <img src="bmlogo.png" alt="Logo" class="logo">
            <span class="ms-2" style="color: white; font-size: 1.5rem;">BooksMart</span>
        </a>
            
            <!-- Navbar Toggler for mobile view -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar items -->
            <div class="collapse navbar-collapse" id="navbarNav">
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
                                <form action="logout.php" method="post">
            <button class="btn btn-outline-danger" type="submit">Sign Out</button>
        </form>

            </div>
        </div>
    </nav>

<!-- Search section -->
<div class="container filter-container">
    <form method="GET">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <input type="text" name="searchPlace" class="form-control" placeholder="Search by Place" value="<?php echo isset($_GET['searchPlace']) ? $_GET['searchPlace'] : ''; ?>">
            </div>
            <div class="col-md-4">
                <input type="text" name="searchBook" class="form-control" placeholder="Search by Book Name" value="<?php echo isset($_GET['searchBook']) ? $_GET['searchBook'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
</div>


    <!-- Heading -->
    <div class="heading">Available Books</div>

    <!-- Book Cards -->
    <div class="container">
        <div class="row">
<?php
// Include the database connection file
include 'db_connection.php';

// Search logic
$searchPlace = isset($_GET['searchPlace']) ? $_GET['searchPlace'] : '';
$searchBook = isset($_GET['searchBook']) ? $_GET['searchBook'] : '';

// Base SQL query
$sql = "SELECT id, book_name, price, place, book_image, user_type FROM sellings WHERE user_type = 'professional'";

// Append conditions based on the search inputs
if ($searchPlace) {
    $sql .= " AND place LIKE '%$searchPlace%'";
}
if ($searchBook) {
    $sql .= " AND book_name LIKE '%$searchBook%'";
}


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
echo '
<div class="col-md-4">
    <div class="card book-card">
<img src="' . (!is_null($row['book_image']) ? 'data:image/jpeg;base64,' . base64_encode($row['book_image']) : 'placeholder.jpg') . '" alt="Book Image">

        <div class="card-body">
            <h5 class="card-title">' . $row['book_name'] . '</h5>
            <p class="card-text">Price: ₹' . $row['price'] . '</p>
            <p class="card-text">Place: ' . $row['place'] . '</p>
            <!-- More button links to proceed.php with book_name -->
            <a href="proceed.php?id=' . urlencode($row['id']) . '&user_type=' . urlencode($row['user_type']) . '" class="btn more-btn">More</a>
        </div>
    </div>
</div>
';

    }
} else {
    echo '<p>No books available for the selected filters.</p>';
}

$conn->close();
?>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
