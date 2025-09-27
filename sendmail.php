<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $email    = trim($_POST['email'] ?? '');
    $name     = trim($_POST['name'] ?? '');
    $decision = strtolower(trim($_POST['decision'] ?? ''));

    if (!empty($email) && !empty($decision)) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'hsphere15@gmail.com'; // your Gmail
            $mail->Password   = 'glnloilroxesaqzs';    // your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('hsphere15@gmail.com', 'HR Department');
            $mail->addAddress($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Job Application Update - Healthsphere";

            if ($decision === 'hired') {
                $mail->Body = "Dear <b>$name</b>,<br><br>Congratulations! 🎉<br>You have been <b>hired</b> at Healthsphere.";
            } else {
                $mail->Body = "Dear <b>$name</b>,<br><br>Thank you for applying.<br>We regret to inform you that you were <b>not selected</b> at this time.";
            }

            $mail->send();

            echo "<div style='padding:15px; background:#d4edda; color:#155724; border:1px solid #c3e6cb;'>
                    ✅ Email sent to <b>$email</b> successfully!
                  </div>";
            echo "<br><a href='view_applications.php'>⬅ Back to Applications</a>";

        } catch (Exception $e) {
            echo "<div style='padding:15px; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb;'>
                    ❌ Email could not be sent. Error: {$mail->ErrorInfo}
                  </div>";
        }
    } else {
        echo "<div style='padding:15px; background:#fff3cd; color:#856404; border:1px solid #ffeeba;'>
                ⚠ Invalid request. Missing email or decision.
              </div>";
    }
} else {
    echo "<div style='padding:15px; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb;'>
            ❌ This page only accepts POST requests.
          </div>";
}
?>
