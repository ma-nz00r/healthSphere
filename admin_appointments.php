<?php
// Start session and enable error reporting for debugging
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is an admin
$_SESSION['user_role'] = 'admin';
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: appointment.php"); 
    exit();
}

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthsphere";

$con = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch appointments
$sql = "SELECT * FROM appointments ORDER BY date, time";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Appointments Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1 { color: #333; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th { background-color: #f2f2f2; }
        .message { color: green; margin-top: 15px; }
        .btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .accept { background-color: #4CAF50; color: white; }
        .reject { background-color: #f44336; color: white; }
    </style>
</head>
<body>

<h1>Admin Appointments Dashboard</h1>

<?php
if (isset($_GET['message'])) {
    echo '<p class="message">' . htmlspecialchars($_GET['message']) . '</p>';
}
?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Date</th>
            <th>Time</th>
            <th>Speciality</th>
            <th>Doctor</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['patientName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['speciality']) . "</td>";
                echo "<td>" . htmlspecialchars($row['doctor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status'] ?? 'Pending') . "</td>";
                echo "<td>
                        <form method='POST' action='update_appointment.php' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <input type='hidden' name='email' value='" . $row['email'] . "'>
                            <input type='hidden' name='patientName' value='" . $row['patientName'] . "'>
                            <input type='hidden' name='date' value='" . $row['date'] . "'>
                            <input type='hidden' name='time' value='" . $row['time'] . "'>
                            <button class='btn accept' name='action' value='accept'>Accept</button>
                            <button class='btn reject' name='action' value='reject'>Reject</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No appointments found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>