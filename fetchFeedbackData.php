<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'connection.php';

$username = $_SESSION['username'];

// Query to get feedback counts by website for the user's projects
$sql = "
    SELECT f.fromWebsite, COUNT(*) as feedbackCount
    FROM feedbackInfo f
    JOIN userProjects up ON f.fromWebsite LIKE CONCAT(up.fromWebsite, '%')
    WHERE up.username = ?
    GROUP BY f.fromWebsite
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$colorIndex = 0;
$colors = [
    '#FF6384',  // Red
    '#36A2EB',  // Blue
    '#55632C',  // Dark Green
    '#4BC0C0',  // Aqua
    '#9966FF',  // Purple
    '#FF9F40',  // Orange
    '#FFD700',  // Gold
    '#7CFC00',  // Lawn Green
    '#FF4500',  // Orange Red
    '#8A2BE2',  // Blue Violet
    '#32CD32',  // Lime Green
    '#FF6347',  // Tomato
    '#00FFFF',  // Cyan / Aqua
    '#8B008B',  // Dark Magenta
    '#FFD700',  // Gold
    '#1E90FF',  // Dodger Blue
    '#FF00FF',  // Magenta / Fuchsia
    '#FF69B4',  // Hot Pink
    '#2E8B57',  // Sea Green
    '#DC143C',  // Crimson
    '#00FF7F',  // Spring Green
    '#FFA500',  // Orange
    '#8B4513',  // Saddle Brown
    '#20B2AA',  // Light Sea Green
    '#FFC0CB',  // Pink
    '#808080'   // Gray
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $website = $row['fromWebsite'];
        $data[] = [
            'fromWebsite' => $website,
            'feedbackCount' => intval($row['feedbackCount']),
            'color' => $colors[$colorIndex % count($colors)] // Assign colors in a loop
        ];
        $colorIndex++;
    }
}

$stmt->close();
$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
