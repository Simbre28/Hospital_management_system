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

$patient_id = $_GET["id"];

// Retrieve the patient record from the database
$patient_result = $conn->query("SELECT * FROM patients WHERE id = $patient_id");
$patient = $patient_result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $contact_info = $_POST["contact_info"];
    $in_out_patient = $_POST["in_out_patient"];
    $ward_id = $_POST["ward_id"];
    $doctor_id = $_POST["doctor_id"];

    // Prepare and execute the SQL query to update the patient record
    $stmt = $conn->prepare("UPDATE patients SET name = ?, contact_info = ?, in_out_patient = ?, ward_id = ?, doctor_id = ? WHERE id = ?");
    $stmt->bind_param("sssiii", $name, $contact_info, $in_out_patient, $ward_id, $doctor_id, $patient_id);
    $stmt->execute();

    header("Location: patient_list.php");
    exit();
}

// Retrieve the list of wards and doctors from the database
$wards_result = $conn->query("SELECT * FROM wards");
$doctors_result = $conn->query("SELECT * FROM doctors");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
</head>
<body>
    <h2>Edit Patient</h2>
    <form method="POST" action="patient_edit.php?id=<?php echo $patient_id; ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $patient["name"]; ?>" required><br>
        <label>Contact Info:</label>
        <input type="text" name="contact_info" value="<?php echo $patient["contact_info"]; ?>" required><br>
        <label>Patient Type:</label>
        <select name="in_out_patient" required>
            <option value="in" <?php if ($patient["in_out_patient"] == "in") echo "selected"; ?>>In-patient</option>
            <option value="out" <?php if ($patient["in_out_patient"] == "out") echo "selected"; ?>>Out-patient</option>
        </select><br>
        <label>Ward:</label>
        <select name="ward_id" required>
            <?php while ($ward = $wards_result->fetch_assoc()) { ?>
                <option value="<?php echo $ward["id"]; ?>" <?php if ($patient["ward_id"] == $ward["id"]) echo "selected"; ?>><?php echo $ward["name"]; ?></option>
            <?php } ?>
        </select><br>
        <label>Doctor:</label>
        <select name="doctor_id" required>
            <?php while ($doctor = $doctors_result->fetch_assoc()) { ?>
                <option value="<?php echo $doctor["id"]; ?>" <?php if ($patient["doctor_id"] == $doctor["id"]) echo "selected"; ?>><?php echo $doctor["name"]; ?></option>
            <?php } ?>
        </select><br>
        <input type="submit" value="Update Patient">
    </form>
</body>
</html>