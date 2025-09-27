<?php
// Place use statements for PHPMailer at the top of the file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli("localhost", "root", "", "healthsphere");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize form data
$pid              = $_POST['pid'] ?? '';
$patient_name     = $_POST['patient_name'] ?? '';
$email            = $_POST['email'] ?? '';
$invoice_number   = $_POST['invoice_number'] ?? '';
$consultation_fee = floatval($_POST['consultation_fee'] ?? 0);
$lab_fee          = floatval($_POST['lab_fee'] ?? 0);
$medicine_fee     = floatval($_POST['medicine_fee'] ?? 0);
$total_amount     = $consultation_fee + $lab_fee + $medicine_fee;

// Insert invoice into the database with all fields
$stmt = $conn->prepare("INSERT INTO invoices (pid, patient_name, email, invoice_number, consultation_fee, lab_fee, medicine_fee, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("isssdddd", $pid, $patient_name, $email, $invoice_number, $consultation_fee, $lab_fee, $medicine_fee, $total_amount);

if ($stmt->execute()) {
    $new_id = $conn->insert_id;

    // Email sending code starts here
    // Make sure these paths are correct for your project
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hsphere15@gmail.com'; // Your SMTP username
        $mail->Password   = 'glnloilroxesaqzs'; // Your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('hsphere15@gmail.com', 'Health Sphere');
        $mail->addAddress($email, $patient_name);

     // In save_invoice.php, within the email body...

// Build the payment link using the unique invoice number
$payment_link = "http://localhost/newPROJECT/pay.php?invoice_number=" . urlencode($invoice_number);

// Content
$mail->isHTML(true); // Set to true to allow HTML
$mail->Subject = 'Your Invoice from Health Sphere';
$mail->Body    = "Dear " . htmlspecialchars($patient_name) . ",<br><br>"
               . "Your invoice has been generated successfully.<br><br>"
               . "<b>Invoice Details:</b><br>"
               . "Invoice Number: " . htmlspecialchars($invoice_number) . "<br>"
               . "Consultation Fee: $" . number_format($consultation_fee, 2) . "<br>"
               . "Lab Test Fee: $" . number_format($lab_fee, 2) . "<br>"
               . "Medicine Fee: $" . number_format($medicine_fee, 2) . "<br>"
               . "<b>Total Amount Due: $" . number_format($total_amount, 2) . "</b><br><br>"
               . "To view and pay your invoice, please click the link below:<br>"
               . "<a href='" . htmlspecialchars($payment_link) . "'>Pay Your Invoice Now</a><br><br>"
               . "Thank you!";
        $mail->send();
        
        echo "<script>alert('Invoice generated successfully and sent to patient via email.'); window.location='view_invoice.html';</script>";

    } catch (Exception $e) {
        echo "<script>alert('Invoice generated, but failed to send email. Mailer Error: {$mail->ErrorInfo}'); window.location='view_invoice.html';</script>";
    }

} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
exit;
?>