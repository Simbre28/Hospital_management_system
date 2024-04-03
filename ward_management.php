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

// Retrieve the list of wards from the database
$wards_result = $conn->query("SELECT * FROM wards");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_ward"])) {
        $ward_name = $_POST["ward_name"];

        // Prepare and execute the SQL query to insert a new ward
        $stmt = $conn->prepare("INSERT INTO wards (name) VALUES (?)");
        $stmt->bind_param("s", $ward_name);
        $stmt->execute();
    } elseif (isset($_POST["edit_ward"])) {
        $ward_id = $_POST["ward_id"];
        $ward_name = $_POST["ward_name"];

        // Prepare and execute the SQL query to update the ward
        $stmt = $conn->prepare("UPDATE wards SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $ward_name, $ward_id);
        $stmt->execute();
    } elseif (isset($_POST["delete_ward"])) {
        $ward_id = $_POST["ward_id"];

        // Prepare and execute the SQL query to delete the ward
        $stmt = $conn->prepare("DELETE FROM wards WHERE id = ?");
        $stmt->bind_param("i", $ward_id);
        $stmt->execute();
    }

    header("Location: ward_management.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ward Management</title>
</head>
<body>
    <h2>Ward Management</h2>
    <form method="POST" action="ward_management.php">
        <input type="text" name="ward_name" required>
        <input type="submit" name="add_ward" value="Add Ward">
    </form>
    <table>
        <tr>
            <th>Ward Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($ward = $wards_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $ward["name"]; ?></td>
                <td>
                    <form method="POST" action="ward_management.php">
                        <input type="hidden" name="ward_id" value="<?php echo $ward["id"]; ?>">
                        <input type="text" name="ward_name" value="<?php echo $ward["name"]; ?>" required>
                        <input type="submit" name="edit_ward" value="Edit">
                        <input type="submit" name="delete_ward" value="Delete" onclick="return confirm('Are you sure you want to delete this ward?')">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>