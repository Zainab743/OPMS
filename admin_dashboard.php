<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['employee_code']) || $_SESSION['role'] != 'admin') {
    // Redirect to login if not logged in or not an admin
    header("Location: login_form.html");
    exit();
}

// Fetch employee details for the Employee Section
$sql = "SELECT `Employee Code`, `Name`, `Email`, `Contact`, `DOB`, `Joining`, `Blood`, `Employee Type` FROM `details`";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching employee data: " . $conn->error);
}

// Fetch salary slip details for the Salary Slips section
$salary_sql = "SELECT s.`Salary Month`, s.`Employee Code`, s.`Earnings`, s.`Deductions`, s.`Net Salary`, d.`Name`
               FROM `salary` s
               JOIN `details` d ON s.`Employee Code` = d.`Employee Code`";
$salary_result = $conn->query($salary_sql);
if (!$salary_result) {
    die("Error fetching salary slips: " . $conn->error);
}

// Fetch leave management data for the Leave Management section
$leave_sql = "SELECT `Employee Code`, `Subject`, `Dates`, `Message`, `Leave Type`, `Status` FROM `leave`";
$leave_result = $conn->query($leave_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminstyle.css">
    <!-- Include Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 20%;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
        }
        .sidebar h3 {
            margin: 0;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            background-color: #34495e;
            border-radius: 5px;
        }
        .sidebar ul li a:hover {
            background-color: #1abc9c;
        }
        .main-content {
            width: 80%;
            padding: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #34495e;
            color: white;
            padding: 10px 20px;
        }
        .top-bar .status {
            display: flex;
            align-items: center;
        }
        .top-bar .status .dot {
            width: 10px;
            height: 10px;
            background-color: green;
            border-radius: 50%;
            margin-right: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #34495e;
            color: white;
        }
        .hidden {
            display: none;
        }
        /* Styling for logout button */
        .logout-btn {
        padding: 10px 20px;
        background-color: #e74c3c; /* Red color */
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
       }

       .logout-btn:hover {
       background-color: #c0392b; /* Darker red on hover */
       }

    </style>
</head>
<body>

<div class="dashboard">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin Dashboard</h3>
        <ul>
            <li><a href="#" data-section="employee-section">Employee Section</a></li>
            <li><a href="#" data-section="salary-slips">Salary Slips</a></li>
            <li><a href="#" data-section="leave-management">Leave Management</a></li>
            <li><a href="#pay-heads">Pay Heads</a></li>
            <li><a href="#attendance">Attendance</a></li>
        </ul>
        <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Log Out</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h3>Admin Dashboard</h3>
            <div class="status">
                <div class="dot"></div>
                <span>Online</span>
            </div>
               

        </div>

        <!-- Default Content: Employee Section -->
        <div id="employee-section">
            <h2>Employee Section</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>DOB</th>
                        <th>Joining</th>
                        <th>Blood</th>
                        <th>Employee Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['Employee Code']); ?></td>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                <td><?php echo htmlspecialchars($row['Contact']); ?></td>
                                <td><?php echo htmlspecialchars($row['DOB']); ?></td>
                                <td><?php echo htmlspecialchars($row['Joining']); ?></td>
                                <td><?php echo htmlspecialchars($row['Blood']); ?></td>
                                <td><?php echo htmlspecialchars($row['Employee Type']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No employee data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Salary Slips Content -->
        <div id="salary-slips" class="hidden">
            <h2>Salary Slips</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Month</th>
                        <th>Earnings</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($salary_result->num_rows > 0): ?>
                        <?php while ($row = $salary_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['Employee Code']); ?></td>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($row['Salary Month']); ?></td>
                                <td><?php echo htmlspecialchars($row['Earnings']); ?></td>
                                <td><?php echo htmlspecialchars($row['Deductions']); ?></td>
                                <td><?php echo htmlspecialchars($row['Net Salary']); ?></td>
                                <td>
                                    <a href="#" class="material-icons">download</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No salary slips available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Leave Management Section -->
        <div id="leave-management" class="hidden">
            <h2>Leave Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Employee Code</th>
                        <th>Subject</th>
                        <th>Dates</th>
                        <th>Message</th>
                        <th>Leave Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($leave_result->num_rows > 0): ?>
                        <?php $sl_no = 1; ?>
                        <?php while ($row = $leave_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $sl_no++; ?></td>
                                <td><?php echo htmlspecialchars($row['Employee Code']); ?></td>
                                <td><?php echo htmlspecialchars($row['Subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['Dates']); ?></td>
                                <td><?php echo htmlspecialchars($row['Message']); ?></td>
                                <td><?php echo htmlspecialchars($row['Leave Type']); ?></td>
                                <td><?php echo htmlspecialchars($row['Status']); ?></td>
                                <td>
                                    <a href="#" class="material-icons" style="color: green;" onclick="updateLeaveStatus(<?php echo $row['Employee Code']; ?>, 'approved')">check_circle</a>
                                    <a href="#" class="material-icons" style="color: red;" onclick="updateLeaveStatus(<?php echo $row['Employee Code']; ?>, 'rejected')">cancel</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No leave records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript for toggling sections -->
<script>
    const sectionLinks = document.querySelectorAll('.sidebar ul li a');
    const sections = document.querySelectorAll('.main-content > div');

    sectionLinks.forEach(link => {
        link.addEventListener('click', () => {
            const targetSection = document.getElementById(link.dataset.section);

            sections.forEach(section => section.classList.add('hidden'));
            targetSection.classList.remove('hidden');
        });
    });

    // Update Leave Status using AJAX
    function updateLeaveStatus(employeeCode, status) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_leave_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Leave status updated to " + status);
                location.reload(); // Reload the page to reflect changes
            }
        };
        xhr.send("employee_code=" + employeeCode + "&status=" + status);
    }
</script>
<script src="leavescript.js"></script>
</body>
</html>
