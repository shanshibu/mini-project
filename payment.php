<?php
include 'db_connection.php';

// Retrieve id from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the book details from the database
$sql = "SELECT book_name, price, rent_or_sell, qr_code_image, no_of_days, id, user_type FROM sellings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($bookName, $price, $rentOrSell, $qrCodeBlob, $noOfDays, $bookId, $userType);
$stmt->fetch();
$stmt->close();
$conn->close();


// Convert the QR code binary data to base64 if it's not null
$qrCodeImage = $qrCodeBlob ? base64_encode($qrCodeBlob) : null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('payment.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
            max-width: 600px;
        }
        .btn-primary {
            width: 100%;
            font-size: 18px;
            font-weight: bold;
        }
        /* Styles for enlarged QR code */
        .qr-code-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
            padding-top: 60px;
        }
        .qr-code-modal img {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 shadow-sm w-100">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="home.php">  <img src="bmlogo.png" alt="Logo" class="rounded-circle" style="width: 40px; height: 40px; margin-right: 10px;">
            <span class="fw-bold" style="font-size: 24px;">BooksMart</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end">
            <a class="btn btn-outline-danger px-4" href="logout.php">Sign Out</a>
        </div>
    </div>
</nav>


<div class="container">
    <h2 class="text-center mb-4">Payment Details</h2>
    <form id="paymentForm" action="pay.php" method="post">
    <input type="hidden" name="book_name" value="<?php echo htmlspecialchars($bookName); ?>">
    <input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>">
    <input type="hidden" name="rent_or_sell" value="<?php echo htmlspecialchars($rentOrSell); ?>">
    <input type="hidden" name="no_of_days" value="<?php echo htmlspecialchars($noOfDays); ?>">
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Payment Method</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="Cash" required onclick="toggleQrCode(false)">
                <label class="form-check-label" for="cash">Cash</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" id="qr_code" value="QR Code" required onclick="toggleQrCode(true)">
                <label class="form-check-label" for="qr_code">QR Code</label>
            </div>
        </div>
        <div class="text-center mt-4">
            <?php if ($qrCodeImage): ?>
                <img src="data:image/png;base64,<?php echo $qrCodeImage; ?>" alt="QR Code" style="width: 150px; height: 150px; cursor: pointer; display: none;" id="qrCodeImage" onclick="openQrCodeModal()"/>
            <?php else: ?>
                <p>No QR code available for this item.</p>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>

<!-- QR Code Modal -->
<div id="qrCodeModal" class="qr-code-modal" onclick="closeQrCodeModal()">
    <div class="modal-content">
        <span onclick="closeQrCodeModal()" style="cursor: pointer; float: right;">&times;</span>
        <?php if ($qrCodeImage): ?>
            <img src="data:image/png;base64,<?php echo $qrCodeImage; ?>" alt="QR Code" style="max-width: 50%; max-height: 50%;"/>
        <?php endif; ?>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Payment details stored successfully.</p>
            </div>
            <div class="modal-footer">
                <!-- Pass the book id when redirecting to proceed.php -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetForm(); setTimeout(function(){ window.location.href='proceed.php?id=<?php echo $id; ?>'; }, 300);">Close</button>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleQrCode(show) {
        const qrCodeImage = document.getElementById('qrCodeImage');
        qrCodeImage.style.display = show ? 'block' : 'none';
    }

    function openQrCodeModal() {
        document.getElementById('qrCodeModal').style.display = "block";
    }

    function closeQrCodeModal() {
        document.getElementById('qrCodeModal').style.display = "none";
    }

    function resetForm() {
        document.getElementById('paymentForm').reset(); // Reset the form fields
        toggleQrCode(false); // Hide the QR code image if it was shown
    }

    // Check if the success parameter is present in the URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    }
</script>
</body>
</html>
