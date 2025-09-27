<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "healthsphere");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo '<div style="padding:10px; background-color:#f2f2f2;">
    <a href="donationForm.php">← Submit New Donation</a>
</div>';

echo '<h2>📋 List of Donations</h2>';

$sql = "SELECT * FROM donations ORDER BY donor_id ASC";
$result = $conn->query($sql);

if ($result === false) {
    die("Query failed: " . htmlspecialchars($conn->error));
}

if ($result->num_rows > 0) {
    echo "<p>Rows found: " . $result->num_rows . "</p>";

    echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>
            <tr style='background-color: #f0f0f0;'>
                <th>ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Frequency</th>
                <th>Blood Group</th>
                <th>Availability Date</th>
                <th>Hospital</th>
                <th>Heart Condition</th>
                <th>Donor Name</th>
                <th>Email</th>
                <th>Country</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        $availability = $row['availability_date'] ?? $row['availability_date_blood'] ?? '-';
        $name = trim(($row['donor_first_name'] ?? '') . ' ' . ($row['donor_last_name'] ?? ''));

        echo "<tr>
            <td>" . htmlspecialchars($row['donor_id']) . "</td>
            <td>" . htmlspecialchars($row['donation_type'] ?? '-') . "</td>
            <td>" . htmlspecialchars($row['amount'] ?? '-') . "</td>
            <td>" . htmlspecialchars($row['frequency'] ?? '-') . "</td>
            <td>" . htmlspecialchars($row['blood_group'] ?? '-') . "</td>
            <td>" . htmlspecialchars($availability) . "</td>
            <td>" . htmlspecialchars($row['hospital'] ?? '-') . "</td>
            <td>" . htmlspecialchars($row['heart_condition'] ?? '-') . "</td>
            <td>" . htmlspecialchars($name) . "</td>
            <td>" . htmlspecialchars($row['donor_email'] ?? '-') . "</td>
            <td>" . htmlspecialchars($row['donor_country'] ?? '-') . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No donations found.</p>";
}

$conn->close();
?>
