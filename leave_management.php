<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['employee_code']) || $_SESSION['role'] != 'admin') {
    header("Location: login_form.html");
    exit();
}

// Handle leave approval or rejection via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'] === 'approve' ? 'Approved' : 'Rejected';

    $update_sql = "UPDATE `leave` SET `Status` = ? WHERE `ID` = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $action, $id);

    $response = [];
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Leave status updated successfully!';
        $response['id'] = $id;
        $response['new_status'] = $action;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update leave status!';
    }
    $stmt->close();
    echo json_encode($response);
    exit();
}

// Fetch all leave applications
$leave_sql = "SELECT `ID`, `Employee Code`, `Subject`, `Dates`, `Message`, `Leave Type`, `Status` FROM `leave`";
$leave_result = $conn->query($leave_sql);
if (!$leave_result) {
    die("Error fetching leave data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Google Material Icons -->
    <style>
        body {
            font-family: Arial, sans-serif;
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
        .actions .material-icons {
            cursor: pointer;
            margin: 0 5px;
            font-size: 20px;
        }
        .actions .material-icons.approve {
            color: green;
        }
        .actions .material-icons.reject {
            color: red;
        }
    </style>
</head>
<body>
    <div id="navigation">
        <button id="leave-management-btn">Leave Management</button>
    </div>

    <div class="main-content">
        <h2>Leave Management</h2>
        <table>
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Dates</th>
                    <th>Message</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="leave-table-body">
                <?php if ($leave_result->num_rows > 0): ?>
                    <?php $sl_no = 1; ?>
                    <?php while ($row = $leave_result->fetch_assoc()): ?>
                        <tr data-id="<?php echo $row['ID']; ?>">
                            <td><?php echo $sl_no++; ?></td>
                            <td><?php echo htmlspecialchars($row['Employee Code']); ?></td>
                            <td><?php echo htmlspecialchars($row['Subject']); ?></td>
                            <td><?php echo htmlspecialchars($row['Dates']); ?></td>
                            <td><?php echo htmlspecialchars($row['Message']); ?></td>
                            <td><?php echo htmlspecialchars($row['Leave Type']); ?></td>
                            <td class="status"><?php echo htmlspecialchars($row['Status']); ?></td>
                            <td class="actions">
                                <?php if ($row['Status'] == 'Pending'): ?>
                                    <i class="material-icons approve" data-action="approve">check</i>
                                    <i class="material-icons reject" data-action="reject">close</i>
                                <?php else: ?>
                                    <span>N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No leave applications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="leave_management.js"></script> <!-- Link to your JS file -->
</body>
</html>
