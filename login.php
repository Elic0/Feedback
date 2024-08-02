<?php
session_start();

include 'connection.php';

// Get username and password from the login form
$username = $_POST['username'];
$passcode = $_POST['passcode'];

// Sanitize inputs to prevent SQL injection
$username = mysqli_real_escape_string($conn, $username);
$passcode = mysqli_real_escape_string($conn, $passcode);

// Query to check if the user exists and get their role
$query = "SELECT * FROM login WHERE username='$username' AND passcode='$passcode'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // Valid login
    $user = mysqli_fetch_assoc($result);
    
    // Set session variables
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $user['role'];

    // Redirect to employee.php
    header("Location: /employee.php");
    exit();
} else {
    // Invalid login
    header("Location: index.php?error=incorrect");
    exit();
}

mysqli_close($conn);
?>
