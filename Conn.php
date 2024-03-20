<?php
$host = '192.168.116.50'; // Change this to your host
$dbname = 'feedbackDB'; // Change this to your database name
$username = 'feedback'; // Change this to your username
$password = 'password1'; // Change this to your password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
