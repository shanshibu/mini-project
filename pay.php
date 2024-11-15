<?php
include 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Include PHPMailer's autoloader

// Retrieve data from the form submission
$customerName = $_POST['customer_name'];
$phoneNumber = $_POST['phone_number'];
$email = $_POST['email'];
$paymentMethod = $_POST['payment_method'];
$bookName = $_POST['book_name'];
$price = $_POST['price'];
$transactionType = $_POST['rent_or_sell'];
$noOfDays = isset($_POST['no_of_days']) ? $_POST['no_of_days'] : null;

// Insert payment data into `payment` table
$sql = "INSERT INTO payment (customer_name, phone_number, email, transaction_type, book_name, price, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $customerName, $phoneNumber, $email, $transactionType, $bookName, $price, $paymentMethod);

if ($stmt->execute()) {
    // Send a thank you email to the customer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use the Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'booksmartbm123@gmail.com';  // Your email address
        $mail->Password = 'dpwd jxgg sjwb qten';  // Your email password or App password (for Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('booksmartbm123@gmail.com', 'BooksMart');
        $mail->addAddress($email, $customerName);  // Customer's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Your Purchase at BooksMart';

// Conditional message based on rent or purchase
if ($noOfDays > 0) {
    $mail->Body = "Dear $customerName,<br><br>Thank you for renting the book '$bookName' for ₹$price.<br>Your payment method: $paymentMethod.<br><br>We kindly ask that you return the book within $noOfDays days.<br><br>We appreciate your business and hope to see you again soon!<br><br>Best regards,<br>BooksMart Team";
} else {
    $mail->Body = "Dear $customerName,<br><br>Thank you for purchasing the book '$bookName' for ₹$price.<br>Your payment method: $paymentMethod.<br><br>We appreciate your business and hope to see you again soon!<br><br>Best regards,<br>BooksMart Team";
}

        

        // Send the email
        $mail->send();
        // Redirect or display success message
        header("Location: payment.php?success=true");
        exit();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
