function submitFeedback() {
  const email = document.getElementById('email').value;
  const subject = document.getElementById('subject').value;
  const feedback = document.getElementById('feedback').value;
  const contactCheckbox = document.getElementById('contactCheckbox').checked ? 1 : 0;
  const acceptCheckbox = document.getElementById('acceptCheckbox').checked ? 1 : 0;

  // Check if all required fields are filled
  if (!email || !subject || !feedback || !acceptCheckbox) {
      console.error("Please fill in all required fields.");
      displayPopup("Please fill in all required fields.");
      return;
  }

  // Create an object with the form data
  const formData = {
      email: email,
      subject: subject,
      feedback: feedback,
      contactCheckbox: contactCheckbox,
      acceptCheckbox: acceptCheckbox
  };

  // Send the form data to the server using fetch API
  fetch('InsertData.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData),
  })
  .then(response => {
      if (!response.ok) {
          throw new Error('Network response was not ok');
      }
      return response.json();
  })
  .then(data => {
      console.log('Data sent successfully:', data);
      displayPopup("Feedback submitted successfully!");
      resetForm();
  })
  .catch(error => {
      console.error('Error sending data:', error);
      displayPopup("An error occurred while submitting feedback. Please try again later.");
  });
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
