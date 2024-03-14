function submitFeedback() {
  const email = document.getElementById('email').value;
  const subject = document.getElementById('subject').value;
  const feedback = document.getElementById('feedback').value;
  const contactCheckbox = document.getElementById('contactCheckbox').checked ? 1 : 0;
  const acceptCheckbox = document.getElementById('acceptCheckbox').checked ? 1 : 0;

  // Log the values of the form fields including category, contactCheckbox, and acceptCheckbox
  console.log("Email:", email);
  console.log("Subject:", subject);
  console.log("Feedback:", feedback);
  console.log("Contact Checkbox:", contactCheckbox);
  console.log("Accept Checkbox:", acceptCheckbox);

  // Email logikken skal ind her 

  displayPopup("Tak for din feedback!");

  // Nulstil formular
  resetForm();
}


 
 // Function to reset form
 function resetForm() {
  const form = document.getElementById('feedbackForm');
  form.reset(); // Reset all input fields
 
  // Reset contact checkbox
  const contactCheckbox = document.getElementById('contactCheckbox');
  if (contactCheckbox) {
     contactCheckbox.checked = false;
  }
 }
 
 // Function to display popup
 function displayPopup(message) {
  const popup = document.getElementById('popup');
  const popupMessage = document.getElementById('popupMessage');
 
  popupMessage.textContent = message;
  popup.style.display = 'block';
 
  // Automatically close popup after 3 seconds
  setTimeout(function () {
     closePopup();
  }, 3000);
 }
 
 // Function to close popup
 function closePopup() {
  const popup = document.getElementById('popup');
  popup.style.display = 'none';
 }
 
 // Function to toggle theme
 function toggleTheme() {
  const body = document.body;
  const themeToggle = document.getElementById('themeToggle');
  const icon = themeToggle.querySelector('i');
 
  body.classList.toggle('dark-theme');
  body.classList.toggle('light-theme');
 
  if (body.classList.contains('dark-theme')) {
     icon.className = 'gg-sun'; 
  } else {
     icon.className = 'gg-moon'; 
  }
 }
 
 // Function to toggle contact checkbox visibility
 function toggleContactCheckbox() {
  const emailInput = document.getElementById('email');
  const contactCheckboxContainer = document.getElementById('contactCheckboxContainer');
 
  if (emailInput.value.trim() !== '') {
     contactCheckboxContainer.style.display = 'flex';
  } else {
     contactCheckboxContainer.style.display = 'none';
  }
 }
 
 // Event for form submission
 document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('feedbackForm');
  form.addEventListener('submit', function (event) {
     event.preventDefault();
     submitFeedback();
  });
 });
 
 // Function to submit login form
 function submitLoginForm() {
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
 
  // Check username and password
  if (username === "admin" && password === "MercAdmin123") {
     // Redirect to medarbejder.html if credentials are correct
     window.location.href = 'medarbejder.html';
  } else {
     // Display error message if credentials are incorrect
     displayPopup("Forkert brugernavn eller adgangskode.");
     focusLoginInput();
  }
 }
 
 // Function to focus on login input field
 function focusLoginInput() {
  document.getElementById('username').focus();
 }
 
 // Function to show login popup
 function showLoginPopup() {
  const loginPopup = document.getElementById('loginPopup');
  loginPopup.style.display = 'block';
 }
 
 function hideLoginPopup() {
  const loginPopup = document.getElementById('loginPopup');
  loginPopup.style.display = 'none';
 }
 
 document.getElementById('loginButton').addEventListener('click', showLoginPopup);
 document.getElementById('loginPopup').querySelector('button').addEventListener('click', hideLoginPopup);
 document.getElementById('imageInput').addEventListener('change', handleImageInput);
 
 function handleImageInput() {
  const imageInput = document.getElementById('imageInput');
  const attachedImageContainer = document.getElementById('attachedImageContainer');
 
  const selectedImage = imageInput.files[0];
 
  if (selectedImage) {
     const imageUrl = URL.createObjectURL(selectedImage);
     
     console.log("Attached Image URL:", imageUrl);
  }
 }
 
 // Function to redirect from feedback page to employee page
 function redirectMedarbejder() {
  window.location.href = 'medarbejder.html';
 }
 