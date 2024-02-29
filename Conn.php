<html>
<head>
<title>Connecting to SQL Server in PHP</title>
</head>
<body>
<?php
$serverName = "(localdb)\\feedbackDB";
$connectionOptions = array(
    "Database" => "Feedback",
    "UID" => "", // Blank brugernavn
    "PWD" => "", // Blank adgangskode
);
// Attempt to establish the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
// Check if the connection was successful
if ($conn === false) {
    // Print detailed error information if connection fails
    die(print_r(sqlsrv_errors(), true));
} else {
    // Connection successful message
    echo "Connected successfully";
}
// Close the connection
sqlsrv_close($conn);
?>
</body>
</html>