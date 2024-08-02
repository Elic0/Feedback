<?php
session_start();

if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
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
  <title>New Feedback</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../../../images/Favicon.png">
  <link rel="stylesheet" href="../../styles/employee.css">
  <link rel="stylesheet" href="../../styles/feedback.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body class="dark-theme">

<?php include '../../../header.php'; ?>

<main>
    <section class="new-feedback-section">
        <h1>New feedback</h1>
        <hr class="underline">
        <div id="feedbackList">
            <?php include 'outputNewData.php'; ?>
        </div>

        <!-- Close button -->
        <button id="closeButton" class="close-button" onclick="goBack()">
            X
        </button>
    </section>
</main>

<script>
function goBack() {
    window.location.href = '/employee.php';
}
</script>
<script src="../../javascript/employee.js"></script>
<script src="../../javascript/feedback.js"></script>
</body>
</html>
