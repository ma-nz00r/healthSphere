<?php
include("dbconnect.php");

$query = "SELECT * FROM appointments";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Records</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .container {
            max-width: 1100px;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Booked Appointments</h1>
    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Date</th>
                <th>Time</th>
                <th>Speciality</th>
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['patientName']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['time']}</td>
                    <td>{$row['speciality']}</td>
                    <td>{$row['doctor']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No appointments found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$con->close();
?>
