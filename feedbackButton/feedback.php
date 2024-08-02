<?php
include 'grab.php';
?>

<!DOCTYPE html>
<html>
<head>
    <style>
    .feedback-link {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      position: fixed;
      bottom: 350px;
      right: -97px;
      z-index: 999;
    }

    .feedback-link img {
        width: 200px; 
        height: auto; 
        transform: rotate(270deg); 
    }

    .feedback-link:hover {
        opacity: 1;
    }
	
    @media (max-width: 600px) {
        .feedback-link {
            bottom: 350px; /* Adjust bottom position for better visibility */
            right: -90px; /* Adjust right position for better placement */
        }
        
        .feedback-link img {
            width: 165px; /* Decrease width further for smaller size on mobile */
        }
    }
    </style>
</head>
<body>

<div class="feedback-container">
    <a href="#" class="feedback-link" onclick="handleFeedbackClick()">
        <img src="../../../feedbackButton/images/Blue-White.png" alt="Feedback">
    </a>
</div>

<script>
function handleFeedbackClick() {
    var fromWebsite = encodeURIComponent(window.location.href);
    var userOS = encodeURIComponent("<?php echo $userOS; ?>");

    // Midlertidige console.log-udskrifter for fejlfinding
    console.log("fromWebsite: " + fromWebsite);
    console.log("userOS: " + userOS);

    var feedbackURL = "https://feedback.socdata.dk?fromWebsite=" + fromWebsite + "&userOS=" + userOS;
    window.location.href = feedbackURL;
}
</script>

</body>
</html>
