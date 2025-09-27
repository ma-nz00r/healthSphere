<?php
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

    // Insert with prepared statement
    $stmt = $con->prepare("INSERT INTO `application_register` (`fname`, `lname`, `email`, `pass`) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        exit("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_pass);

    if ($stmt->execute()) {
        // ✅ Redirect after success (no email)
        header("Location: doctorPanel.html");
        exit();
    } else {
        echo '❌ Insert failed: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo '❌ Invalid request method.';
}

$con->close();
?>
