<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Mercantec - Feedback</title>
  <link rel="icon" type="image/png" sizes="32x32" href="images/Favicon.png">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="dark-theme">

<?php include 'header.php'; ?>
<?php include 'connection.php'; ?>

<main>
  <div class="container">
    <form id="feedbackForm" method="post">
      <label for="subject">Category</label>
      <select id="subject" name="subject" required>
        <option value="">Choose a category</option>
        <option value="Error">Error</option>
        <option value="Content">Content</option>
        <option value="Design">Design</option>
        <option value="Performance">Performance</option>
        <option value="Security">Security</option>
        <option value="Other">Other</option>
      </select>

      <label for="feedback">Feedback message</label>
      <textarea id="feedback" name="feedback" placeholder="Write your message (What happened)" rows="8" required></textarea>

      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" placeholder="Input your mail (optional)" oninput="toggleContactCheckbox()">

      <div id="contactCheckboxContainer" style="display: none;">
        <label for="contactCheckbox">May we contact you?</label>
        <input type="checkbox" id="contactCheckbox" name="contactCheckbox">
      </div>

      <?php
      if (isset($_GET['fromWebsite'])) {
          echo "<input type='hidden' name='fromWebsite' value='" . htmlspecialchars($_GET['fromWebsite']) . "'>";
      } else {
          echo "<label for='fromWebsite'>From Website</label>";
          echo "<select id='fromWebsite' name='fromWebsite'>";
          echo "<option value=''>Choose a website</option>";
          $sql = "SELECT projectName, fromWebsite FROM projects";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  echo "<option value='{$row['fromWebsite']}'>{$row['projectName']}</option>";
              }
          }
          echo "</select>";
      }
      ?>

      <label id="termsLabel" for="acceptCheckbox">
        <input type="checkbox" id="acceptCheckbox" name="acceptCheckbox" required>
        <h3 class="terms-of-service" onclick="showTermsPopup()">I accept the terms of service</h3>
      </label>
      
      <div class="button-container-feedback">
        <button type="button" onclick="submitFeedback()">Submit Feedback</button>
      </div>
    </form>
  </div>
</main>

<div id="popup" class="popup">
  <span class="popup-message" id="popupMessage"></span>
  <button onclick="closePopup()">OK</button>
</div>

<!-- Login Popup -->
<div id="loginPopup" class="login-box">
  <button class="close-button" onclick="hideLoginPopup()">&times;</button>
  <form id="loginForm" action="login.php" method="post">
    <label for="username">Email</label>
    <input type="text" id="username" name="username" required>

    <label for="passcode">Password</label>
    <input type="password" id="passcode" name="passcode" required>

    <input type="submit" id="button-login" value="Login">
    <p id="loginError" style="color: red; display: none; text-align: center;">Incorrect username or password <br><br> Contact SOC Helpdesk <br> in case you need password reset</p>
  </form>
</div>

<footer>
  <p>H.C.Andersensvej 9, 8800 - Tlf. +45 89 50 34 25 - soc-feedback@edu.mercantec.dk</p>
  <p>&copy; 2024 Mercantec</p>
</footer>

<script src="Javascript/script.js" charset="UTF-8"></script>
<script>
function submitFeedback() {
    var formData = $('#feedbackForm').serialize();
    $.ajax({
        type: 'POST',
        url: 'insertData.php',
        data: formData,
        success: function(response) {
            alert('Feedback submitted successfully!');
            $('#feedbackForm')[0].reset();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('An error occurred while submitting feedback. Please try again later.');
        }
    });
}
</script>

</body>
</html>
