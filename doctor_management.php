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

// Retrieve the list of doctors from the database
$doctors_result = $conn->query("SELECT * FROM doctors");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_doctor"])) {
        $doctor_name = $_POST["doctor_name"];
        $specialization = $_POST["specialization"];

        // Prepare and execute the SQL query to insert a new doctor
        $stmt = $conn->prepare("INSERT INTO doctors (name, specialization) VALUES (?, ?)");
        $stmt->bind_param("ss", $doctor_name, $specialization);
        $stmt->execute();
    } elseif (isset($_POST["edit_doctor"])) {
        $doctor_id = $_POST["doctor_id"];
        $doctor_name = $_POST["doctor_name"];
        $specialization = $_POST["specialization"];

        // Prepare and execute the SQL query to update the doctor
        $stmt = $conn->prepare("UPDATE doctors SET name = ?, specialization = ? WHERE id = ?");
        $stmt->bind_param("ssi", $doctor_name, $specialization, $doctor_id);
        $stmt->execute();
    } elseif (isset($_POST["delete_doctor"])) {
        $doctor_id = $_POST["doctor_id"];

        // Prepare and execute the SQL query to delete the doctor
        $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
    }

    header("Location: doctor_management.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Management</title>
</head>
<body>
    <h2>Doctor Management</h2>
    <form method="POST" action="doctor_management.php">
        <input type="text" name="doctor_name" placeholder="Doctor Name" required>
        <input type="text" name="specialization" placeholder="Specialization" required>
        <input type="submit" name="add_doctor" value="Add Doctor">
    </form>
    <table>
        <tr>
            <th>Doctor Name</th>
            <th>Specialization</th>
            <th>Actions</th>
        </tr>
        <?php while ($doctor = $doctors_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $doctor["name"]; ?></td>
                <td><?php echo $doctor["specialization"]; ?></td>
                <td>
                    <form method="POST" action="doctor_management.php">
                        <input type="hidden" name="doctor_id" value="<?php echo $doctor["id"]; ?>">
                        <input type="text" name="doctor_name" value="<?php echo $doctor["name"]; ?>" required>
                        <input type="text" name="specialization" value="<?php echo $doctor["specialization"]; ?>" required>
                        <input type="submit" name="edit_doctor" value="Edit">
                        <input type="submit" name="delete_doctor" value="Delete" onclick="return confirm('Are you sure you want to delete this doctor?')">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>