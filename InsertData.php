<?php
// Database connection
$servername = "192.168.116.50";
$username = "feedback";
$password = "password1"; 
$dbname = "feedbackDB"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$topic = $_POST['subject']; 
$email = $_POST['email'];
$message = $_POST['feedback'];
$allowContact = isset($_POST['contactCheckbox']) ? '1' : '0'; // 


// Insert data into database
$sql = "INSERT INTO feedbackInfo (topic, mail, feedback, allowContact) VALUES ('$topic', '$email', '$message', '$allowContact')";


if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
