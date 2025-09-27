<?php
// Replace with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    $productName = $_POST['productName'];
    $itemNo = $_POST['itemNo'];
    $problem = $_POST['problem'];
    $manufacturer = $_POST['manufacturer'];
    $category = $_POST['category'];
    $storeBox = $_POST['storeBox'];
    $price = (float) $_POST['price'];
    $quantity = (int) $_POST['quantity'];
    $expiryDate = $_POST['expiryDate'];

    $sql = "INSERT INTO inventory (productName, itemNo, problem, manufacturer, category, storeBox, price, quantity, expiryDate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssids", $productName, $itemNo, $problem, $manufacturer, $category, $storeBox, $price, $quantity, $expiryDate);

    if ($stmt->execute()) {
        echo "New item added successfully";
        // header("Location: inventory.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
