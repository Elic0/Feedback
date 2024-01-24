function submitFeedback() {
  const email = document.getElementById('email').value;
  const subject = document.getElementById('subject').value;
  const title = document.getElementById('title').value;
  const feedback = document.getElementById('feedback').value;

  if (subject === "") {
    displayPopup("Du skal vælge et emne.");
    return;
  }

  if (title === "") {
    displayPopup("Du skal indtaste en overskrift.");
    return;
  }

  if (feedback === "") {
    displayPopup("Du skal skrive en feedback-besked.");
    return;
  }

  
  displayPopup("Tak for din feedback!");
}

function displayPopup(message) {
  const popup = document.getElementById('popup');
  const popupMessage = document.getElementById('popupMessage');

  popupMessage.innerText = message;
  popup.style.display = 'block';
}

function closePopup() {
  const popup = document.getElementById('popup');
  popup.style.display = 'none';
}

function toggleTheme() {
  const body = document.body;
  const themeToggle = document.querySelector('.theme-toggle');

  body.classList.toggle('dark-theme');
  body.classList.toggle('light-theme');

  
  themeToggle.innerText = body.classList.contains('dark-theme') ? 'Light Mode' : 'Dark Mode';
}
