<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "hospital_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the list of wards and doctors from the database
$wards_result = $conn->query("SELECT * FROM wards");
$doctors_result = $conn->query("SELECT * FROM doctors");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $contact_info = $_POST["contact_info"];
    $in_out_patient = $_POST["in_out_patient"];
    $ward_id = $_POST["ward_id"];
    $doctor_id = $_POST["doctor_id"];

    // Prepare and execute the SQL query to insert a new patient record
    $stmt = $conn->prepare("INSERT INTO patients (name, contact_info, in_out_patient, ward_id, doctor_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $name, $contact_info, $in_out_patient, $ward_id, $doctor_id);
    $stmt->execute();

    header("Location: patient_list.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
</head>
<body>
    <h2>Add Patient</h2>
    <form method="POST" action="patient_form.php">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Contact Info:</label>
        <input type="text" name="contact_info" required><br>
        <label>Patient Type:</label>
        <select name="in_out_patient" required>
            <option value="in">In-patient</option>
            <option value="out">Out-patient</option>
        </select><br>
        <label>Ward:</label>
        <select name="ward_id" required>
            <?php while ($ward = $wards_result->fetch_assoc()) { ?>
                <option value="<?php echo $ward["id"]; ?>"><?php echo $ward["name"]; ?></option>
            <?php } ?>
        </select><br>
        <label>Doctor:</label>
        <select name="doctor_id" required>
            <?php while ($doctor = $doctors_result->fetch_assoc()) { ?>
                <option value="<?php echo $doctor["id"]; ?>"><?php echo $doctor["name"]; ?></option>
            <?php } ?>
        </select><br>
        <input type="submit" value="Add Patient">
    </form>
</body>
</html>