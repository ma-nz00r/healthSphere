<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = "localhost";
$username = "root";
$password = "";
$dbname = "healthsphere";

$con = mysqli_connect($server, $username, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo '<h2>📋 List of Submitted Applications</h2>';
echo '<p><a href="connection.php">← Submit Another Application</a></p>';

$sql = "SELECT id, name, age, gender, address, number, qualification, specialization, experience, cv, email 
        FROM applications ORDER BY id ASC";
$result = mysqli_query($con, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellpadding='10'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Qualification</th>
                    <th>Specialization</th>
                    <th>Experience</th>
                    <th>CV</th>
                    <th>Action</th>
                </tr>";
       while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['age']}</td>
            <td>{$row['email']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['address']}</td>
            <td>{$row['number']}</td>
            <td>{$row['email']}</td>
            <td>{$row['qualification']}</td>
            <td>{$row['specialization']}</td>
            <td>{$row['experience']}</td>
            <td>{$row['cv']}</td>
           <td>
    <form method='POST' action='sendmail.php'>
        <input type='hidden' name='email' value='" . htmlspecialchars($row['email']) . "'>
        <input type='hidden' name='name' value='" . htmlspecialchars($row['name']) . "'>
        <button type='submit' name='decision' value='hired'>Hire</button>
        <button type='submit' name='decision' value='rejected'>Reject</button>
    </form>
</td>

          </tr>";
}

        echo "</table>";
    } else {
        echo "No applications found.";
    }
} else {
    echo "Error retrieving data: " . mysqli_error($con);
}

mysqli_close($con);
?>
