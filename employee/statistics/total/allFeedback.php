<?php
session_start();

if (!isset($_SESSION['username'])) {
   
    header("Location: ../../../index.php");
    exit();
}

$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Feedback</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../../../images/Favicon.png">
  <link rel="stylesheet" href="../../styles/employee.css">
  <link rel="stylesheet" href="../../styles/feedback.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
  <style>
    .back-button-container {
      position: absolute;
      top: 155px;
      right: 120px;
    }
  </style>
</head>
<body class="dark-theme">

<?php include '../../../header.php'; ?>

  <main>
    <section class="new-feedback-section">
      <h1>All feedback</h1>
      <hr class="underline">
      <div id="feedbackList">
        <?php include 'outputData.php'; ?>
      </div>
      <button id="closeButton" class="close-button" onclick="goBack()">
        X
      </button>
    </section>
  </main>


  <script src="../../javascript/employee.js"></script>
    <script src="../../javascript/read-more.js"></script>
	
</body>
</html>