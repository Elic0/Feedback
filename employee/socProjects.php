<?php
include '../connection.php';

// SQL query to fetch project data from the database
$sql = "SELECT * FROM projects";
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='raleway' style='line-height: 0.7;'>" . $row["projectName"] . "</div><br>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
