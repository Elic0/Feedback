<?php
// Include the connection file
include 'Conn.php';

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $mail = $_POST['email'];
    $allowContact = isset($_POST['contactCheckbox']) ? 1 : 0; // Convert checkbox value to BIT
    $topic = $_POST['subject'];
    $feedback = $_POST['feedback'];
    $images = null; // Replace null with the actual binary image data if applicable

    // Prepare and execute the stored procedure
    $tsql = "{CALL InsertFeedback (?, ?, ?, ?, ?)}";
    $params = array(
        array($mail, SQLSRV_PARAM_IN),
        array($allowContact, SQLSRV_PARAM_IN),
        array($topic, SQLSRV_PARAM_IN),
        array($feedback, SQLSRV_PARAM_IN),
        array($images, SQLSRV_PARAM_IN)
    );

    // Execute the stored procedure
    $stmt = sqlsrv_query($conn, $tsql, $params);

    // Check if the execution was successful
    if ($stmt === false) {
        // Print detailed error information if execution fails
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Feedback inserted successfully!";
    }

    // Free statement and close connection
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>
