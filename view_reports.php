<?php
// Show all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthsphere";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// SQL to fetch all reports from the patient_reports table
$sql = "SELECT * FROM patient_reports ORDER BY upload_date DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin View - All Reports</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; color: #555; }
        a.download-link { text-decoration: none; color: #007bff; }
        a.download-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h1>All Patient Reports (Admin View)</h1>

    <?php
 $sql = "SELECT * FROM patient_reports ORDER BY id Asc";
$result = $conn->query($sql);
if ($result->num_rows > 0) {   echo "<table>";
        echo "<thead><tr><th>ID</th><th>Patient Name</th><th>Email</th><th>Report Name</th><th>Upload Date</th></tr></thead>";
        echo "<tbody>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['report_name']) . "</td>";
            // Create a link to download the file
            echo "<td><a class='download-link' href='download_report.php?file=" . urlencode($row['file_path']) . "'>Download</a></td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
}
 else {
        echo "<p>No reports found.</p>";
    }

    $conn->close();
    ?>

</body>
</html>