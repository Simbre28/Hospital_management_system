<!DOCTYPE html>
<html>
<head>
    <title>Hospital Management System</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <h1>Hospital Management System</h1>
        <nav>
            <?php if (isset($_SESSION["user_id"])) { ?>
                <a href="dashboard.php">Dashboard</a>
                <?php if ($_SESSION["user_role"] == "admin") { ?>
                    <a href="patient_management.php">Patient Management</a>
                    <a href="ward_management.php">Ward Management</a>
                    <a href="doctor_management.php">Doctor Management</a>
                <?php } ?>
                <a href="logout.php">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </nav>
    </header>