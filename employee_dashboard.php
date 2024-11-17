<?php
session_start();

// Check if the user is logged in and is an employee
if (!isset($_SESSION['employee_code']) || $_SESSION['role'] != 'employee') {
    header("Location: login_form.html");
    exit();
}

// Get the employee details from the database
include('db_connection.php');
$employee_code = $_SESSION['employee_code'];
$sql = "SELECT * FROM users WHERE `Employee Code` = '$employee_code' LIMIT 1";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Link to your JavaScript for AJAX functionality -->
</head>
<body>

<div class="container">
    <!-- Sidebar navigation -->
    <div class="sidebar">
        <!-- Profile section at the top of sidebar -->
        <div class="profile-header">
            <h2><?php echo $user['Name']; ?></h2>
            <p class="status">
                <span class="status-dot"></span> Online
            </p>
            

        </div>

        <ul>
            <li><a href="#" onclick="loadSection('salary_slip.php', 'Salary Slip')">Salary Slip</a></li>
            <li><a href="#" onclick="loadSection('leaves.php', 'Leaves')">Leaves</a></li>
            <li><a href="change_password.php">Change Password</a></li>
        </ul>
        <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Log Out</button>
        </form>
    </div>
    
    <!-- Main content area -->
    <div class="main-content">
        <!-- Breadcrumb navigation at the top right -->
        <div class="breadcrumb">
            <p><a href="home.php">Home</a> > <span id="current-page">Profile</span></p>
        </div>

        <h1 id="page-title">Welcome, <?php echo $user['Name']; ?></h1>
        <h2 id="sub-title">Your Profile</h2>

        <!-- Profile Details -->
        <div id="profile-details">
            <p><strong>Employee Code:</strong> <?php echo $user['Employee Code']; ?></p>
            <p><strong>Name:</strong> <?php echo $user['Name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['Email']; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $user['Phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['Address']; ?></p>
            <p><strong>Department:</strong> <?php echo $user['Department']; ?></p>
        </div>

        <!-- Placeholder for dynamic content like Salary Slip and Leaves -->
        <div id="dynamic-content" style="display: none;"></div>
    </div>
</div>

<script>
// Function to load content dynamically into the main content area
function loadSection(url, title) {
    // Update title and breadcrumb
    document.getElementById('page-title').innerText = title;
    document.getElementById('current-page').innerText = title;

    // Hide profile details and sub-title, show dynamic content container
    document.getElementById('profile-details').style.display = 'none';
    document.getElementById('sub-title').style.display = 'none';
    document.getElementById('dynamic-content').style.display = 'block';

    // Fetch the content from the specified URL and load it into #dynamic-content
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('dynamic-content').innerHTML = html;
        })
        .catch(error => console.error('Error loading content:', error));
}
</script>

</body>
</html>