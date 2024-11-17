<?php
// Include database connection
include 'db_connection.php';

// Initialize the result set to avoid errors
$sql = "SELECT 
            s.`Employee Code` AS id, 
            d.Name, 
            s.`Salary Month` AS month, 
            s.Earnings, 
            s.Deductions, 
            s.`Net Salary`
        FROM salary s
        JOIN details d 
        ON s.`Employee Code` = d.`Employee Code`";

$result = $conn ? $conn->query($sql) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Font Awesome CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Google Material Icons -->
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
    </style>
</head>

<body>
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
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['Name']) ?></td>
                        <td><?= htmlspecialchars($row['month']) ?></td>
                        <td><?= htmlspecialchars($row['Earnings']) ?></td>
                        <td><?= htmlspecialchars($row['Deductions']) ?></td>
                        <td><?= htmlspecialchars($row['Net Salary']) ?></td>
                        <!-- Material Icon for download action -->
                        <td><a href="#"><i class="material-icons" style="font-size: 16px;">download</i></a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No salary slips found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
