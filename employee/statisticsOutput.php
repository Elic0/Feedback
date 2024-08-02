<?php
session_start(); // Start the session if it hasn't been started already

include '/var/www/feedback/connection.php';

// Initialize variables
$username = $_SESSION['username'];
$totalFeedbackCount = 0;
$totalProjectsCount = 0;
$newFeedbackCount = 0;

try {
    // Fetch total count of feedback entries for the user's projects
    $sqlTotalFeedback = "SELECT COUNT(*) AS total_feedback_count 
                         FROM feedbackInfo AS f
                         WHERE (f.feedbackStatus = 1 OR f.feedbackStatus = 2) AND
                         EXISTS (
                             SELECT 1 FROM userProjects AS up 
                             WHERE f.fromWebsite LIKE CONCAT('%', up.fromWebsite, '%') 
                             AND up.username = ?
                         )";
    $stmtTotalFeedback = $conn->prepare($sqlTotalFeedback);
    $stmtTotalFeedback->bind_param("s", $username);
    $stmtTotalFeedback->execute();
    $resultTotalFeedback = $stmtTotalFeedback->get_result();

    // Fetch total count of projects
    $sqlTotalProjects = "SELECT COUNT(DISTINCT projectName) AS total_projects_count 
                         FROM userProjects";
    $stmtTotalProjects = $conn->prepare($sqlTotalProjects);
    $stmtTotalProjects->bind_param("s", $username);
    $stmtTotalProjects->execute();
    $resultTotalProjects = $stmtTotalProjects->get_result();

    // Fetch new feedback count for the user's projects
    $sqlNewFeedback = "SELECT COUNT(*) AS new_feedback_count 
                       FROM feedbackInfo AS f
                       WHERE f.feedbackStatus = 0
                       AND EXISTS (
                           SELECT 1 FROM userProjects AS up 
                           WHERE f.fromWebsite LIKE CONCAT('%', up.fromWebsite, '%') 
                           AND up.username = ?
                       )";
    $stmtNewFeedback = $conn->prepare($sqlNewFeedback);
    $stmtNewFeedback->bind_param("s", $username);
    $stmtNewFeedback->execute();
    $resultNewFeedback = $stmtNewFeedback->get_result();

    // Fetch counts
    if ($rowTotalFeedback = $resultTotalFeedback->fetch_assoc()) {
        $totalFeedbackCount = $rowTotalFeedback["total_feedback_count"];
    }
    if ($rowTotalProjects = $resultTotalProjects->fetch_assoc()) {
        $totalProjectsCount = $rowTotalProjects["total_projects_count"];
    }
    if ($rowNewFeedback = $resultNewFeedback->fetch_assoc()) {
        $newFeedbackCount = $rowNewFeedback["new_feedback_count"];
    }

    // Close prepared statements
    $stmtTotalFeedback->close();
    $stmtTotalProjects->close();
    $stmtNewFeedback->close();
} catch (Exception $e) {
    // Handle exceptions
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn->close();

// Return counts as a JSON object
echo json_encode([
    "totalFeedbackCount" => $totalFeedbackCount,
    "totalProjectsCount" => $totalProjectsCount,
    "newFeedbackCount" => $newFeedbackCount
]);
?>
