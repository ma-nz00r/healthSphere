<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donations List</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>Donation Records</h2>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "donations";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM donations ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Details</th>
                <th>Donor Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>City</th>
                <th>Country</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        $details = "";
        switch ($row['donation_type']) {
            case 'money':
                $details = "Amount: " . $row['amount'] . ", Frequency: " . $row['frequency'];
                break;
            case 'eye':
            case 'liver':
                $details = "Availability Date: " . $row['availability_date'];
                break;
            case 'kidney':
                $details = "Hospital: " . $row['hospital'];
                break;
            case 'heart':
                $details = "Condition: " . $row['heart_condition'];
                break;
            case 'blood':
                $details = "Blood Group: " . $row['blood_group'];
                break;
        }

        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['donation_type']}</td>
                <td>$details</td>
                <td>{$row['donor_first_name']} {$row['donor_last_name']}</td>
                <td>{$row['donor_email']}</td>
                <td>{$row['donor_address']}</td>
                <td>{$row['donor_city']}</td>
                <td>{$row['donor_country']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No donations found.</p>";
}

$conn->close();
?>

</body>
</html>
