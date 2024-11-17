<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is an employee
if (!isset($_SESSION['employee_code']) || $_SESSION['role'] != 'employee') {
    echo "Unauthorized access!";
    exit();
}

$employee_code = $_SESSION['employee_code'];

// Insert new leave application if submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "From submitted!<br>";
    $subject = $_POST['leave_subject'];
    $dates = $_POST['leave_dates'];
    $message = $_POST['leave_message'];
    $type = $_POST['leave_type'];
    $status = 'Pending';
    
    $sql = "INSERT INTO `leave` (`Employee Code`, `Subject`, `Dates`, `Message`, `Leave Type`, `Status`)
            VALUES ('$employee_code', '$subject', '$dates', '$message', '$type', '$status')";
      if ($conn->query($sql) === TRUE) {
        // Redirect to the dashboard after successful form submission
        header("Location: employee_dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



// Retrieve leave history
$sql = "SELECT * FROM `leave` WHERE `Employee Code` = '$employee_code'";

$result = $conn->query($sql);

// Check if the request is AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    // Return leave history table rows only for AJAX requests
    ob_start(); // Start output buffering
    if ($result->num_rows > 0) {
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            $statusClass = '';
            switch($row['Status']) {
                case 'Pending':
                    $statusClass = 'pending';
                    break;
                case 'Approved':
                    $statusClass = 'approved';
                    break;
                case 'Rejected':
                    $statusClass = 'rejected';
                    break;
            }

            echo "<tr>
                    <td>{$i}</td>
                    <td>{$row['Subject']}</td>
                    <td>{$row['Dates']}</td>
                    <td>{$row['Message']}</td>
                    <td>{$row['Leave Type']}</td>
                    <td><button class='status-btn $statusClass'>{$row['Status']}</button></td>
                </tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='6'>No leaves applied yet.</td></tr>";
    }
    echo ob_get_clean(); // Output the buffered content
} else {
    // Standard HTML structure when not an AJAX request
    ?>
    <div id="apply-leave" style="width: 45%; float: left;">
        <h3>Apply for Leave</h3>
        <form id="leave-form" method="POST" action="leaves.php">
            <label for="leave-subject">Leave Subject</label>
            <input type="text" id="leave-subject" name="leave_subject" required><br>

            <label for="leave-dates">Leave Dates</label>
            <input type="text" id="leave-dates" name="leave_dates" required><br>

            <label for="leave-message">Leave Message</label>
            <textarea id="leave-message" name="leave_message" required></textarea><br>

            <label for="leave-type">Leave Type</label>
            <select id="leave-type" name="leave_type" required>
                <option value="Sick">Sick</option>
                <option value="Casual">Casual</option>
                <option value="Vacation">Vacation</option>
            </select><br>

            <button type="submit">Apply for Leave</button>
        </form>
    </div>

    <div id="leave-history" style="width: 45%; float: right;">
        <h3>My Leave History</h3>
        <table id="leave-history-table">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Subject</th>
                    <th>Dates</th>
                    <th>Message</th>
                    <th>Leave Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $statusClass = '';
                            switch($row['Status']) {
                                case 'Pending':
                                    $statusClass = 'pending';
                                    break;
                                case 'Approved':
                                    $statusClass = 'approved';
                                    break;
                                case 'Rejected':
                                    $statusClass = 'rejected';
                                    break;
                            }
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['Subject']; ?></td>
                            <td><?php echo $row['Dates']; ?></td>
                            <td><?php echo $row['Message']; ?></td>
                            <td><?php echo $row['Leave Type']; ?></td>
                            <td><button class="status-btn <?php echo $statusClass; ?>"><?php echo $row['Status']; ?></button></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No leaves applied yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>

