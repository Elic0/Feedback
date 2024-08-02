<?php
//Detaljer som servernavn, brugernavn, kode og databasenavn
$servername = "192.168.116.50";
$username = "feedback";
$password = "password1";
$dbname = "feedbackDB";

// Opret forbindelse
$conn = new mysqli($servername, $username, $password, $dbname);

// Tjek forbindelse
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sæt tegnsættet til UTF-8
$conn->set_charset("utf8mb4");
?>
