<?php
// Include the connection file
include 'Conn.php';

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Retrieve form data
$mail = $data['email'];
$allowContact = $data['contactCheckbox'] ? 1 : 0;
$topic = $data['subject'];
$feedback = $data['feedback'];

// Prepare and execute the SQL statement
$tsql = "INSERT INTO feedback (Mail, AllowContact, Topic, Feedback) VALUES (?, ?, ?, ?)";
$params = array($mail, $allowContact, $topic, $feedback);

$stmt = sqlsrv_query($conn, $tsql, $params);

if ($stmt === false) {
    // Print detailed error information if execution fails
    die(print_r(sqlsrv_errors(), true));
} else {
    // Send a response back to the client
    echo json_encode(array('success' => true));
}

// Free statement and close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
