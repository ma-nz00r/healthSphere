<?php
session_start();

// Fixed admin credentials
$admin_username = "hsphere15@gmail.com";
$admin_password = "HealthSphere786"; // Change this to a strong password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: adminDashboard.html"); // redirect to admin dashboard
        exit();
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='adminDashboard.html';</script>";
        exit();
    }
}
?>
