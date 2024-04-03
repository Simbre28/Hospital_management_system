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

// Prepare and execute the SQL query to delete the patient record
$stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();

$conn->close();

header("Location: patient_list.php");
exit();
?>