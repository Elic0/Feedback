<?php
// Include your database connection file
include 'Conn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hent data fra formularen
    $subject = $_POST['subject'];
    $feedback = $_POST['feedback'];
    $email = $_POST['email'];
    $allowContact = isset($_POST['contactCheckbox']) ? 1 : 0; // Konverterer checkbox til 1 eller 0

    // Process the image if required
    $image = null; // Replace this with your image processing logic

    // Call the stored procedure
    $sql = "EXEC InsertFeedback @Mail=?, @AllowContact=?, @Topic=?, @FromWebsite=?, @Feedback=?, @Images=?";
    $params = array($email, $contactCheckbox, $subject, $_SERVER['HTTP_REFERER'], $feedback, $image);
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    
    if (sqlsrv_execute($stmt)) {
        // Success
        echo "Feedback submitted successfully.";
    } else {
        // Error
        echo "Error submitting feedback.";
    }

    // Close the connection
    sqlsrv_close($conn);
} else {
    echo "Form not submitted.";
}
?>
