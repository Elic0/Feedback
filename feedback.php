<?php

?>
<style>
 .button-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
 }

 .feedback-link {
    position: fixed;
    bottom: 20px;
    right: 20px;
    opacity: 0.9;
    transition: opacity 0.3s ease;
 }

 .feedback-link img {
    width: 50px;
    height: auto;
    transform: rotate(270deg);
 }

 .feedback-link:hover {
    opacity: 1;
 }
</style>

<div class="button-container">
 <a href="index.html" class="feedback-link">
    <img src="../Images/Feedback-icon.png" alt="Feedback">
 </a>
</div>
