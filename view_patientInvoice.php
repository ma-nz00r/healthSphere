<?php
$conn = null;
if (isset($_POST['email'])) {
    $conn = new mysqli("localhost", "root", "", "healthsphere");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM invoices WHERE email = ? ORDER BY id ASC");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View My Invoice</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <h1>View Your Invoice</h1>
    <form action="view_patient_invoice.php" method="POST">
        <label>Enter your Email:</label>
        <input type="email" name="email" required>
        <button type="submit">View Invoice</button>
    </form>

    <?php if (isset($result) && $result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Patient Name</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($row['total_amount'], 2)); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif (isset($result)): ?>
        <p>No invoices found for this email.</p>
    <?php endif; ?>
</body>
</html>
<?php if ($conn) $conn->close(); ?>