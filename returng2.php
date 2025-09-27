<?php
// Manual include for PHPMailer
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$server   = "localhost";
$username = "root";
$password = "";
$dbname   = "healthsphere";

$con = new mysqli($server, $username, $password, $dbname);
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = trim($_POST['fname'] ?? '');
    $lastname  = trim($_POST['lname'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $pass      = $_POST['pass'] ?? '';

    if (empty($firstname) || empty($lastname) || empty($email) || empty($pass)) {
        exit('❌ Please fill all required fields.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit('❌ Invalid email address.');
    }

    // Hash password
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Generate verification token
    $token = bin2hex(random_bytes(16));

    // Insert with prepared statement
    $stmt = $con->prepare("INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `verify_token`) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        exit("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("sssss", $firstname, $lastname, $email, $hashed_pass, $token);

    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);
        try {
            // Debug mode (remove or set to 0 in production)
            $mail->SMTPDebug = 2; 
            $mail->Debugoutput = 'html';

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'hsphere15@gmail.com';     // your Gmail
            $mail->Password   = 'glnloilroxesaqzs';        // your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or ENCRYPTION_SMTPS for SSL
            $mail->Port       = 587; // use 465 if SSL

            // Recipients
            $mail->setFrom('hsphere15@gmail.com', 'HealthSphere');
            $mail->addAddress($email, $firstname . ' ' . $lastname);

            // Email content
            $verifyLink = "http://localhost/newPROJECT/verify.php?token=$token";

            $mail->isHTML(true);
            $mail->Subject = 'Verify your HealthSphere account';
            $mail->Body    = "<p>Hello <b>{$firstname}</b>,</p>
                              <p>Thank you for registering at HealthSphere.</p>
                              <p>Please verify your account by clicking the link below:</p>
                              <p><a href='$verifyLink'>$verifyLink</a></p>
                              <p>If you did not register, please ignore this email.</p>";

            if ($mail->send()) {
                header("Location: newpatient.html");
                exit();
            } else {
                echo "❌ Email could not be sent.";
            }

        } catch (Exception $e) {
            echo 'Registration saved but email not sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo '❌ Insert failed: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo '❌ Invalid request method.';
}

$con->close();
