<?php
session_start();
require 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit();
}

// Check if the request is a POST request and if the 'status' field is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    // Retrieve the new status from the POST data
    $status = intval($_POST['status']);

    // Get the logged-in user's username from the session
    $username = $_SESSION['username'];

    // Update the notification column in the database for the given user
    $sql = "UPDATE login SET notification = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $status, $username);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}

$conn->close();
?>
