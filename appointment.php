<?php
// Show all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthsphere";

// Connect
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check request method and if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['newAppointment'])) {

    // Get and sanitize inputs
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : null;
    $patientName = trim($_POST['patientName']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $speciality = $_POST['speciality'];
    $doctor = $_POST['doctor'];

    // Basic required field check
    if (!$patientName || !$phone || !$email || !$date || !$time || !$speciality || !$doctor) {
        die("Please fill in all required fields.");
    }

    // Insert into DB with default status 'pending'
    if ($id !== null) {
        $sql = "INSERT INTO appointments (id, patientName, phone, email, date, time, speciality, doctor, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isssssss", $id, $patientName, $phone, $email, $date, $time, $speciality, $doctor);
    } else {
        $sql = "INSERT INTO appointments (patientName, phone, email, date, time, speciality, doctor, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssss", $patientName, $phone, $email, $date, $time, $speciality, $doctor);
    }

    // Execute
    if ($stmt->execute()) {
        echo "✅ Your appointment request has been submitted. We will inform you once it is reviewed.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "❌ No data received or form not submitted properly.";
}

$con->close();
?>