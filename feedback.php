<?php
phpinfo();
?>
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
</style>

<div class="feedback-container">
 <a href="index.html" class="feedback-link">
    <img src="../Images/Feedback-icon.png" alt="Feedback">
 </a>
</div>