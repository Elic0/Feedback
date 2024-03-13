// Håndter omdirigering til index.html
function redirectIndex() {
  window.location.href = 'index.html';

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
    body.style.backgroundImage = "url('../Images/Dark-Mode.jpg')";
  } else {
    icon.className = 'gg-moon'; 
    body.style.backgroundImage = "url('../Images/Light-Mode.jpg')";
  }
}


// Håndter klikbegivenheden for "0" under "Nye feedback"
document.getElementById('newFeedbackCount').addEventListener('click', function() {
  // Omdiriger til feedback.html
  window.location.href = 'feedback.html';
});

// Håndter klikbegivenheden for "32" under "Alle feedback"
document.getElementById('allFeedbackCount').addEventListener('click', function() {
  // Omdiriger til feedback.html
  window.location.href = 'all-Feedback.html';
});


// Håndter klikbegivenheden for "7" under "Alle Projekter"
document.getElementById('allProjectsCount').addEventListener('click', function() {
  // Omdiriger til feedback.html
  window.location.href = 'all-projects.html';
});

