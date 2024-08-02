<?php

include '../../../connection.php';

// Check if feedback_id is set in the request
if (isset($_POST['feedback_id'])) {
    $feedbackId = $_POST['data-feedback_id'];
    
    // Prepare and bind the SQL statement with parameterized query to avoid SQL injection
    $sql = "UPDATE feedbackInfo SET feedbackStatus = 1 WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedbackId); // "i" indicates integer type
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    // Close statement
    $stmt->close();
} else {
    echo "Feedback ID is not set";
}

// Close connection
$conn->close();
?>
