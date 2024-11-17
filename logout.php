<?php
// logout.php

// Start the session
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to the login page (login_form.html)
header("Location: login_form.html");
exit();
?>
