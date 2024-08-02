<?php
session_start();

$servername = "192.168.116.50";
$username = "feedback";
$password = "password1"; 
$dbname = "feedbackDB"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['id'])) {
    $feedbackId = $_GET['id'];
    
    $sql = "SELECT * FROM feedbackInfo WHERE ID = $feedbackId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $topic = $row["topic"];
        $feedback = $row["feedback"];
        $mail = $row["mail"];
        $userOS = $row["userOS"];
        $fromWebsite = $row["fromWebsite"];
        $allowContact = $row["allowContact"]; 
        
        echo "<div class='feedback-details'>";
        echo "<h2 style='font-weight: bold;'>Topic: $topic</h2>";
        echo "<p><b>Feedback:</b><br>$feedback</p>";
        echo "<p><b>Mail:</b> $mail</p>";
        echo "<div class='popup-header'>";
        echo "<p class='popup-label'><b>From page:</b></p>";
        echo "<p class='popup-value'>$fromWebsite</p>";
        echo "</div>";
        echo "<p><b>User OS:</b> $userOS</p>";
        echo "<p><b>Allows Contact:</b> " . ($allowContact == 1 ? "Yes" : "No") . "</p>"; 
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
