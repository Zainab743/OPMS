<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
// Include the database connection
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $employee_code = $_POST['employee_code'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $password = $_POST['password'];
    $role = "employee"; // Fixed role to 'employee'

    // Check if the employee code or email already exists
    $sql_check = "SELECT * FROM users WHERE `Employee Code`='$employee_code' OR `Email`='$email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Employee code or email already exists
        echo "Employee code or email is already registered. Please try again with different details.";
    } else {
        // Insert the new user into the 'users' table
        $sql_users = "INSERT INTO users (`Name`, `Email`, `Address`, `Employee Code`, `Phone`, `Role`, `Department`, `Password`) 
                      VALUES ('$name', '$email', '$address', '$employee_code', '$phone', '$role', '$department', '$password')";

        if ($conn->query($sql_users) === TRUE) {
            // Insert the new user into the 'details' table
            $sql_details = "INSERT INTO details (`Employee Code`, `Name`, `Email`, `Contact`) 
                            VALUES ('$employee_code', '$name', '$email', '$phone')";

            if ($conn->query($sql_details) === TRUE) {
                // Start a session for the newly registered user
                session_start();
                $_SESSION['employee_code'] = $employee_code;
                $_SESSION['role'] = 'employee';

                // Redirect to the employee dashboard
                header("Location: employee_dashboard.php");
                exit(); // Ensure no further code is executed
            } else {
                echo "Error inserting into 'details': " . $conn->error;
            }
        } else {
            echo "Error inserting into 'users': " . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>
