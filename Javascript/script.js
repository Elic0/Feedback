// Submit formen for feedback (Hvad den sender fra hjemmesiden til en mail eller database)
function submitFeedback() {
  const email = document.getElementById('email').value;
  const subject = document.getElementById('subject').value;
  const feedback = document.getElementById('feedback').value;
  const form = document.getElementById('feedbackForm');

  // Pop-up (Hvis nogle felter ikke er udfyldt)
  if (subject === "") {
    displayPopup("Du skal vælge en kategori.");
    return;
  }

  if (feedback === "") {
    displayPopup("Du skal skrive en feedback-besked.");
    return;
  }

  if (email && document.getElementById('contactCheckbox').checked) {
    // Email logikken skal ind her 
  }

  displayPopup("Tak for din feedback!");

  // Nulstil formular
  resetForm();
}

// Nulstil formular
function resetForm() {
  const form = document.getElementById('feedbackForm');
  form.reset(); // Nulstil alle inputfelter

  // Nulstil kontaktcheckbox
  const contactCheckbox = document.getElementById('contactCheckbox');
  if (contactCheckbox) {
    contactCheckbox.checked = false;
  }
}

// Viser popup funktionen
function displayPopup(message) {
  const popup = document.getElementById('popup');
  const popupMessage = document.getElementById('popupMessage');

  popupMessage.textContent = message;
  popup.style.display = 'block';

  // Lukker automatisk popup efter 3 sekunder
  setTimeout(function () {
    closePopup();
  }, 3000);
}

// Luk popup funktion
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




// Bestemmer om email-checkbox kommer op eller ej
function toggleContactCheckbox() {
  const emailInput = document.getElementById('email');
  const contactCheckboxContainer = document.getElementById('contactCheckboxContainer');

  if (emailInput.value.trim() !== '') {
    contactCheckboxContainer.style.display = 'flex';
  } else {
    contactCheckboxContainer.style.display = 'none';
  }
}

// Event for formen bliver sendt 
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('feedbackForm');
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    submitFeedback();
  });
});

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

// Submit login form
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

// Fokus på login inputfelt
function focusLoginInput() {
  document.getElementById('username').focus();
}

// Viser popup funktionen
function displayPopup(message) {
  const popup = document.getElementById('popup');
  const popupMessage = document.getElementById('popupMessage');

  popupMessage.textContent = message;
  popup.style.display = 'block';
  popup.style.zIndex = '1000'; // Sæt z-index for at sikre, at popupen vises foran login-popupen

  // Lukker automatisk popup efter 3 sekunder
  setTimeout(function () {
    closePopup();
  }, 3000);
}

// Luk popup funktion
function closePopup() {
  const popup = document.getElementById('popup');
  popup.style.display = 'none';
}

// Popup for login
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

// Event for formen bliver sendt 
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('feedbackForm');
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    submitFeedback();
  });
});

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

//Redirecter fra normal side til medarbejder side
function redirectMedarbejder() {
  window.location.href = 'medarbejder.html';
}

