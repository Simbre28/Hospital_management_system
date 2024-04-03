<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Get the user's role from the session
$user_role = $_SESSION["user_role"];
?>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <h2>Welcome, <?php echo $_SESSION["username"]; ?></h2>
    <?php if ($user_role == "admin") { ?>
        <div class="admin-section">
            <h3>Admin Functions</h3>
            <ul>
                <li><a href="patient_management.php">Manage Patients</a></li>
                <li><a href="ward_management.php">Manage Wards</a></li>
                <li><a href="doctor_management.php">Manage Doctors</a></li>
            </ul>
        </div>
    <?php } else { ?>
        <div class="staff-section">
            <h3>Staff Functions</h3>
            <ul>
                <li><a href="patient_list.php">View Patient List</a></li>
            </ul>
        </div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>