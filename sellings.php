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
    <title>Book Upload Page</title>
    <link rel="icon" href="bmlogo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>


        /* Custom styles */
        body {
            background-image: url('sellings.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed; /* Ensures the background stays in place while scrolling */
            background-position: center;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.8); /* White with 80% opacity */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 40px auto;
            max-width: 700px;
        }

        .form-heading {
            text-align: center;
            font-size: 1.75rem;
            margin-bottom: 20px;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .hidden {
            display: none;
        }

        /* Menubar styles */
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
        .success-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
        }

        .success-popup h4 {
            margin: 0;
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

    <div class="container">
        <div class="form-container">
            <div class="form-heading">Book Upload Form</div>
            <form id="bookUploadForm" action="upload.php" method="post" enctype="multipart/form-data">
                <!-- Name of the Book -->
                <div class="row mb-3">
                    <label for="bookName" class="col-sm-4 col-form-label">Name of the Book</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="bookName" name="bookName" placeholder="Enter book name" required>
                    </div>
                </div>

                <!-- Price -->
                <div class="row mb-3">
                    <label for="price" class="col-sm-4 col-form-label">Price</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
                    </div>
                </div>

                <!-- Owner Name -->
                <div class="row mb-3">
                    <label for="ownerName" class="col-sm-4 col-form-label">Owner Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="ownerName" name="ownerName" placeholder="Enter owner name" required>
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="row mb-3">
                    <label for="phoneNumber" class="col-sm-4 col-form-label">Phone Number</label>
                    <div class="col-sm-8">
                        <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number" required>
                    </div>
                </div>

                <!-- Place -->
                <div class="row mb-3">
                    <label for="place" class="col-sm-4 col-form-label">Place</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="place" name="place" placeholder="Enter place" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="row mb-3">
                    <label for="email" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                </div>

                <!-- Pincode -->
                <div class="row mb-3">
                    <label for="pincode" class="col-sm-4 col-form-label">Pincode</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter pincode" required>
                    </div>
                </div>

                <!-- User Type -->
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">User Type</label>
                    <div class="col-sm-8">
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="userType" id="student" value="student">
                                <label class="form-check-label" for="student">Student</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="userType" id="professional" value="professional">
                                <label class="form-check-label" for="professional">Professional</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="userType" id="casuals" value="casuals">
                                <label class="form-check-label" for="casuals">Casuals</label>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Book Image -->
                <div class="row mb-3">
                    <label for="bookImage" class="col-sm-4 col-form-label">Upload Book Image</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="bookImage" name="bookImage" accept="image/*">
                    </div>
                </div>

                <!-- Book Back Image -->
                <div class="row mb-3">
                    <label for="bookBackImage" class="col-sm-4 col-form-label">Upload Book Back Image</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="bookBackImage" name="bookBackImage" accept="image/*">
                    </div>
                </div>

                <!-- QR Code Upload -->
                <div class="row mb-3 " id="qrCodeUploadContainer">
                    <label for="qrCodeUpload" class="col-sm-4 col-form-label">Upload QR Code</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="qrCodeUpload" name="qrCodeUpload" accept="image/*">
                    </div>
                </div>

                <!-- Type Dropdown and Rent Field -->
                <div class="row mb-3">
                    <label for="typeDropdown" class="col-sm-4 col-form-label">Type</label>
                    <div class="col-sm-8">
                        <select class="form-select" id="typeDropdown" name="typeDropdown">
                            <option value="sell">Sell</option>
                            <option value="rent">Rent</option>
                        </select>
                    </div>
                </div>

                <!-- Rent Days Field -->
                <div class="row mb-3 hidden" id="rentDaysContainer">
                    <label for="rentDays" class="col-sm-4 col-form-label">Rent Days</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="rentDays" name="rentDays" placeholder="Enter number of days">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn submit-btn">Submit</button>
                </div>
            </form>
        </div>
        <div id="successPopup" class="success-popup">
        <h4>Successfully uploaded!</h4>
    </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

document.addEventListener('DOMContentLoaded', function () {
    const userTypeRadios = document.querySelectorAll('input[name="userType"]');
    const categoryContainer = document.getElementById('categoryContainer');
    const qrCodeUploadContainer = document.getElementById('qrCodeUploadContainer');
    const rentDaysContainer = document.getElementById('rentDaysContainer');
    const typeDropdown = document.getElementById('typeDropdown');
    const paymentMethodRadios = document.querySelectorAll('input[name="paymentMethod"]');
    const successPopup = document.getElementById('successPopup');

    // Toggle Rent Days based on Type Dropdown selection
    typeDropdown.addEventListener('change', function () {
        rentDaysContainer.classList.toggle('hidden', this.value !== 'rent');
    });

    // Set default visibility for Rent Days
    rentDaysContainer.classList.toggle('hidden', typeDropdown.value !== 'rent');

    // Show success popup if redirected with success=1
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        successPopup.style.display = 'block';
        setTimeout(() => {
            successPopup.style.display = 'none';
        }, 3000);
    }

    // Additional logic can be added here, e.g., handling payment method changes
});


    </script>

</body>
</html>
