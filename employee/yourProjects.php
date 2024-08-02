<?php
session_start();

include '/var/www/feedback/connection.php';

$username = $_SESSION['username'];

// Prepare the SQL statement with a placeholder for username
$sql = "SELECT projectName FROM userProjects WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind the username parameter
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<form action='employee/projects/projectData.php' method='GET'>";
            echo "<button type='submit' name='project' value='" . htmlspecialchars($row['projectName']) . "'>" . htmlspecialchars($row["projectName"]) . "</button>";
            echo "</form>";
        }
    } else {
        echo "0 results";
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle statement preparation error
    echo "Error preparing statement: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
