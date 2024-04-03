<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "hospital_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the list of patients from the database
$patients_result = $conn->query("SELECT p.*, w.name AS ward_name, d.name AS doctor_name FROM patients p INNER JOIN wards w ON p.ward_id = w.id INNER JOIN doctors d ON p.doctor_id = d.id");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient List</title>
</head>
<body>
    <h2>Patient List</h2>
    <?php if ($_SESSION["user_role"] == "admin") { ?>
        <a href="patient_form.php">Add Patient</a>
    <?php } ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Contact Info</th>
            <th>Patient Type</th>
            <th>Ward</th>
            <th>Doctor</th>
            <?php if ($_SESSION["user_role"] == "admin") { ?>
                <th>Actions</th>
            <?php } ?>
        </tr>
        <?php while ($patient = $patients_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $patient["name"]; ?></td>
                <td><?php echo $patient["contact_info"]; ?></td>
                <td><?php echo $patient["in_out_patient"]; ?></td>
                <td><?php echo $patient["ward_name"]; ?></td>
                <td><?php echo $patient["doctor_name"]; ?></td>
                <?php if ($_SESSION["user_role"] == "admin") { ?>
                    <td>
                        <a href="patient_edit.php?id=<?php echo $patient["id"]; ?>">Edit</a>
                        <a href="patient_delete.php?id=<?php echo $patient["id"]; ?>">Delete</a>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
</body>
</html>