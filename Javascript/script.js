document.addEventListener('DOMContentLoaded', function () {
  const feedbackForm = document.getElementById('feedbackForm'); // Brug formens id
  feedbackForm.addEventListener('submit', handleSubmit);

  // Add event listener to change text color based on checkbox status
  const acceptCheckbox = document.getElementById('acceptCheckbox');
  acceptCheckbox.addEventListener('change', toggleAcceptCheckboxColor);

  // Add event listener to terms label
  const termsLabel = document.getElementById('termsLabel');
  termsLabel.addEventListener('click', showTermsPopup);
});

function handleSubmit(event) {
  event.preventDefault(); // Forhindrer standardformularindsendelse

  const form = event.target;
  const formData = new FormData(form);

  fetch('insertData.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (response.ok) {
      return response.json(); // Parse JSON-svar
    } else {
      throw new Error('Error');
    }
  })
  .then(data => {
    if (data.success) {
      displayPopup("We appreciate your feedback!");
      resetForm();
    } else {
      throw new Error(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    displayPopup("Error. Try again.");
  });
}

// Reset form function
function resetForm() {
  const form = document.getElementById('feedbackForm');
  form.reset(); // Nulstil alle inputfelter

  // Nulstil kontakt afkrydsningsfelt
  const contactCheckbox = document.getElementById('contactCheckbox');
  if (contactCheckbox) {
    contactCheckbox.checked = false;
  }
}

// Display popup function
function displayPopup(content, autoClose = true) {
  const popup = document.getElementById('popup');
  const popupMessage = document.getElementById('popupMessage');

  popupMessage.innerHTML = content;
  popup.style.display = 'block';

  if (autoClose) {
    // Luk popup automatisk efter 2 sekunder
    setTimeout(function () {
      closePopup();
    }, 2000);
  }
}

// Close popup function
function closePopup() {
  const popup = document.getElementById('popup');
  popup.style.display = 'none';
}

// Dark & Light mode 
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

// Determine whether email checkbox should appear or not
function toggleContactCheckbox() {
  const emailInput = document.getElementById('email');
  const contactCheckboxContainer = document.getElementById('contactCheckboxContainer');

  if (emailInput.value.trim() !== '') {
    contactCheckboxContainer.style.display = 'flex';
  } else {
    contactCheckboxContainer.style.display = 'none';
  }
}

// Focus on login input field
function focusLoginInput() {
  document.getElementById('username').focus();
}

// Login popup
function showLoginPopup() {
  const loginPopup = document.getElementById('loginPopup');
  loginPopup.style.display = 'block';
}

function hideLoginPopup() {
  const loginPopup = document.getElementById('loginPopup');
  loginPopup.style.display = 'none';
}

function showLoginError() {
  const loginPopup = document.getElementById('loginError');
  loginPopup.style.display = 'block';
}

if (window.location.search.includes('error=incorrect')) {
  showLoginPopup();  
  showLoginError();
}

document.getElementById('loginButton').addEventListener('click', showLoginPopup);
document.getElementById('loginPopup').querySelector('button').addEventListener('click', hideLoginPopup);

window.addEventListener('DOMContentLoaded', function () {
  // Function to move checkbox only in mobile view
  function moveCheckbox() {
    const acceptCheckbox = document.getElementById('acceptCheckbox');
    const acceptCheckboxContainer = document.getElementById('acceptCheckboxContainer');

    if (window.innerWidth <= 600) { // Check if screen width is less than or equal to 600px (typically mobile view)
      acceptCheckboxContainer.insertBefore(acceptCheckbox, acceptCheckboxContainer.firstChild); // Flyt afkrydsningsfeltet til begyndelsen af dets beholder
    } else {
      acceptCheckboxContainer.appendChild(acceptCheckbox); // Flyt afkrydsningsfeltet tilbage til sin oprindelige position
    }
  }

  // Call moveCheckbox function on page load and when window size changes
  moveCheckbox();
  window.addEventListener('resize', moveCheckbox);
});

function showTermsPopup() {
  const termsText = `
    <h2>Terms of Service</h2>
    <p>By accepting our terms, you confirm that we may store and utilize the provided information to assist in enhancing website performance. Consequently, it is recommended not to disclose any personal details, such as passwords, etc.</p>
  `;
  displayPopup(termsText, false); 
}
