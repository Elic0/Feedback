<?php
session_start();

// Include database connection
include '../../../connection.php';

if (isset($_GET['id'])) {
    $feedbackId = $conn->real_escape_string($_GET['id']);
    
    $sql = "SELECT * FROM feedbackInfo WHERE ID = $feedbackId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $topic = htmlspecialchars($row["topic"], ENT_QUOTES, 'UTF-8');
        $feedback = htmlspecialchars($row["feedback"], ENT_QUOTES, 'UTF-8');
        $mail = htmlspecialchars($row["mail"], ENT_QUOTES, 'UTF-8');
        $userOS = htmlspecialchars($row["userOS"], ENT_QUOTES, 'UTF-8');
        $fromWebsite = htmlspecialchars($row["fromWebsite"], ENT_QUOTES, 'UTF-8');
        $allowContact = $row["allowContact"] == 1 ? "Yes" : "No";
        $userBrowser = htmlspecialchars($row["userBrowser"], ENT_QUOTES, 'UTF-8');
        $time = htmlspecialchars($row["time"], ENT_QUOTES, 'UTF-8');
        
        echo "<div class='feedback-details'>";
        echo "<h2 style='font-weight: bold;'>Topic: $topic</h2>";
        echo "<p><b>Feedback:</b><br>$feedback</p>";
        echo "<p><b>Mail:</b> $mail</p>";
        echo "<div class='popup-header'>";
        echo "<p class='popup-label'><b>From page:</b></p>";
        echo "<p class='popup-value'>$fromWebsite</p>";
        echo "</div>";
        echo "<p><b>User OS:</b> $userOS</p>";
        echo "<p><b>User browser:</b> $userBrowser</p>";
        echo "<p><b>Allows Contact:</b> $allowContact</p>"; 
        echo "<p><b>Time sent in:</b> $time</p>";
        echo "</div>";
    } else {
        echo "No feedback found for the given ID";
    }
} else {
    echo "Feedback ID not provided";
}

$conn->close();
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var popups = document.querySelectorAll('.popup');
        var isOpen = false;

        popups.forEach(function(popup) {
            popup.addEventListener('click', function() {
                if (!isOpen) {
                    this.style.display = 'block';
                    isOpen = true;
                } else {
                    popups.forEach(function(popup) {
                        popup.style.display = 'none';
                    });
                    this.style.display = 'block';
                }
            });
        });
    });
</script>
