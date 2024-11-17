<?php
session_start();
include('db_connection.php');

// Check if the request is made by an admin
if (!isset($_SESSION['employee_code']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

// Ensure both parameters are set
if (isset($_POST['employee_code']) && isset($_POST['status'])) {
    $employee_code = $_POST['employee_code'];
    $status = $_POST['status'];

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE `leave` SET `Status` = ? WHERE `Employee Code` = ?");
    $stmt->bind_param("si", $status, $employee_code);

    if ($stmt->execute()) {
        echo "Leave status updated successfully.";
    } else {
        echo "Error updating leave status: " . $conn->error;
    }

    $stmt->close();
}
?>
