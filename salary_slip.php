<?php
include('db_connection.php');  // Include the database connection file

session_start(); // Start session to get the employee code
$employee_code = $_SESSION['employee_code'];  // Fetch employee code from session

// SQL query to fetch salary details for the logged-in employee
$sql = "SELECT `Salary Month`, `Earnings`, `Deductions`, `Net Salary`
        FROM `salary`
        WHERE `Employee Code` = ?";

if (!empty($employee_code)) {
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $employee_code);  // Bind employee code parameter
        $stmt->execute();
        $stmt->bind_result($salary_month, $earnings, $deductions, $net_salary);

        // Display the salary details in a table
        echo "<table class='table'>";
        echo "<thead><tr><th>Salary Month</th><th>Earnings</th><th>Deductions</th><th>Net Salary</th></tr></thead>";
        echo "<tbody>";

        while ($stmt->fetch()) {
            echo "<tr><td>$salary_month</td><td>$earnings</td><td>$deductions</td><td>$net_salary</td></tr>";
        }

        echo "</tbody></table>";

        $stmt->close();
    } else {
        echo "Error in preparing the SQL query: " . $conn->error;
    }
} else {
    echo "Employee code is missing.";
}

$conn->close();
?>
