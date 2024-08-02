<?php
session_start();

if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: ../../../index.php");
    exit();
}

$userRole = $_SESSION['role'];

$project = filter_input(INPUT_GET, 'project', FILTER_SANITIZE_STRING);

include '../../connection.php';

$sql = "SELECT DISTINCT f.* FROM feedbackInfo AS f JOIN userProjects AS up ON f.fromWebsite LIKE CONCAT(up.fromWebsite, '%') WHERE up.projectName = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $project);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - <?php echo htmlspecialchars($project); ?> Feedback</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../../images/Favicon.png">
    <link rel="stylesheet" href="../styles/employee.css">
    <link rel="stylesheet" href="../styles/feedback.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <style>
        .back-button-container {
            position: absolute;
            top: 155px;
            right: 120px;
        }
		
		.close-button {
			top: 155px;
			right: 55px;
		}
	
		.btn-1,
		.btn-1 *,
		.btn-1 :after,
		.btn-1 :before,
		.btn-1:after,
		.btn-1:before {
		  border: 0 solid;
		  box-sizing: border-box;
		}
		.btn-1 {
		  -webkit-tap-highlight-color: transparent;
		  -webkit-appearance: button;
		  background-color: #2C3240;
		  background-image: none;
		  color: #fff;
		  cursor: pointer;
		  font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
		    Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif,
		    Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
		  font-size: 100%;
		  font-weight: 650;
		  line-height: 1.5;
		  margin: 0;
		  -webkit-mask-image: -webkit-radial-gradient(#000, #fff);
		  padding: 0.1rem 0.7rem;
		  border-radius: 99rem;
		  border-width: 2px;
		  text-transform: uppercase;
		}
		.btn-1:disabled {
		  cursor: default;
		}
		.btn-1:-moz-focusring {
		  outline: auto;
		}
		.btn-1:hover {
		  color: #999;
		}
    </style>
</head>
<body class="dark-theme">

<?php include '../../header.php'; ?>

<main>
    <button id="closeButton" class="close-button" onclick="goBack()">
        X
    </button>
    <section class="project-feedback-section">
        <h1>Feedback - <?php echo htmlspecialchars($project); ?></h1>
        <?php 
        if ($result->num_rows > 0) {
            echo "<div class='container'>";
            echo "<table>";
            echo "<tr><th>Topic</th><th>Feedback</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $topic = htmlspecialchars(substr($row["topic"], 0, 50)); // Limit to first 50 characters
                $feedback = htmlspecialchars(substr($row["feedback"], 0, 50)); // Limit to first 50 characters
                echo "<tr data-feedback-id='" . htmlspecialchars($row["ID"]) . "'>"; // Add data-feedback-id attribute here
                echo "<td>$topic</td>";
                echo "<td>";
                echo "<div class='feedback-content' data-full-feedback='" . htmlspecialchars($row["feedback"]) . "'>$feedback";
                echo "<button class='read-more btn-1'>Read More</button>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>"; // Close container
        } else {
            echo "<p>No feedback available for this project.</p>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
        ?>
    </section>
</main>

<script src="../javascript/employee.js"></script>
<script src="../javascript/read-more.js"></script>

</body>
</html>
