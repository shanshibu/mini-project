<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit;
}
?>
<?php
// Include the database connection file
include 'db_connection.php';

// Get the id and user_type from the URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
$user_type = isset($_GET['user_type']) ? $_GET['user_type'] : '';

if ($id != '' && $user_type != '') {
    // Fetch all details for the selected book based on both id and user_type
    $sql = "SELECT * FROM sellings WHERE user_type = ? AND id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_type, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "No book found with that id and user type.";
        exit;
    }
} else {
    echo "Invalid id or user type.";
    exit;
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="icon" href="bmlogo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://img.freepik.com/premium-photo/stack-books-stationery-background-school-board_147376-4875.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .nav-link {
            color: white !important;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
        }
        .table {
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            vertical-align: middle;
        }
        .book-details img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
        }
        .book-details img:hover {
            opacity: 0.8;
        }
        .enlarged-img {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
            z-index: 1000;
            border: 2px solid #007bff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .btn-primary, .btn-success {
            font-size: 18px;
            padding: 10px 20px;
            width: 150px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="home.php">
            <img src="bmlogo.png" alt="Logo" class="logo">
            <span class="ms-2" style="color: white; font-size: 1.5rem;">BooksMart</span>
        </a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav">
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
            </div>
            <a class="btn btn-danger" href="logout.php">Sign Out</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center mb-4">Book Details</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Book Name</strong></td>
                    <td><?php echo $book['book_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Price</strong></td>
                    <td>₹<?php echo $book['price']; ?></td>
                </tr>
                <tr>
                    <td><strong>Place</strong></td>
                    <td><?php echo $book['place']; ?></td>
                </tr>
                <tr>
                    <td><strong>Available for</strong></td>
                    <td><?php echo $book['rent_or_sell'] ? ucfirst($book['rent_or_sell']) : 'N/A'; ?></td>
                </tr>
                <?php if ($book['rent_or_sell'] == 'rent') { ?>
                <tr>
                    <td><strong>Number of Days for Rent</strong></td>
                    <td><?php echo $book['no_of_days']; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td><strong>Owner Name</strong></td>
                    <td><?php echo $book['owner_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Phone Number</strong></td>
                    <td><?php echo $book['phone_number']; ?></td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td><?php echo $book['email']; ?></td>
                </tr>
                <tr>
                    <td><strong>Images (Front-side & Back-side)</strong></td>
                    <td class="book-details">
                        <!-- Book Front Image -->
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($book['book_image']); ?>" alt="Book Front Image" id="bookImage">
                        <!-- Book Back Image -->
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($book['book_back_image']); ?>" alt="Book Back Image" id="bookBackImage" style="margin-left: 20px;">
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-4">
    <a href="payment.php?id=<?php echo urlencode($id); ?>" class="btn btn-success">Proceed</a>
</div>



    </div>

    <!-- Enlarged Image Modal -->
    <div class="overlay" id="overlay"></div>
    <img id="enlargedImage" class="enlarged-img" src="data:image/jpeg;base64,<?php echo base64_encode($book['book_image']); ?>" alt="Enlarged Book Image">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle image enlargement
            const enlargeImage = function(imageElement, imageUrl) {
                const enlargedImage = document.getElementById('enlargedImage');
                const overlay = document.getElementById('overlay');

                if (enlargedImage && overlay) {
                    imageElement.addEventListener('click', function() {
                        enlargedImage.src = imageUrl;
                        enlargedImage.style.display = 'block';
                        overlay.style.display = 'block';
                    });
                }
            };

            // Image elements
            const bookImage = document.getElementById('bookImage');
            const bookBackImage = document.getElementById('bookBackImage');

            if (bookImage) {
                enlargeImage(bookImage, 'data:image/jpeg;base64,<?php echo base64_encode($book['book_image']); ?>');
            }
            if (bookBackImage) {
                enlargeImage(bookBackImage, 'data:image/jpeg;base64,<?php echo base64_encode($book['book_back_image']); ?>');
            }

            // Hide enlarged image on overlay click
            const overlay = document.getElementById('overlay');
            const enlargedImage = document.getElementById('enlargedImage');

            if (overlay && enlargedImage) {
                overlay.addEventListener('click', function() {
                    enlargedImage.style.display = 'none';
                    overlay.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>
