// Håndter omdirigering til index.html
function redirectIndex() {
    window.location.href = '../../../logout.php';

}

function logout() {
    window.location.href = '../../../../logout.php';
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
    body.style.backgroundImage = "url('/../../images/Dark-Mode.jpg')";
  } else {
    icon.className = 'gg-moon'; 
    body.style.backgroundImage = "url('/../../images/Light-Mode.jpg')";
  }
}


// Håndter klikbegivenheden for under "Nye feedback"
document.getElementById('newFeedbackCount').addEventListener('click', function() {
  // Omdiriger til feedback.html
  window.location.href = '../employee/statistics/new/newFeedback.php';
});

// Håndter klikbegivenheden for "Alle feedback"
document.getElementById('totalFeedbackCount').addEventListener('click', function() {
  // Omdiriger til feedback.html
  window.location.href = '../employee/statistics/total/allFeedback.php';
});


// Håndter klikbegivenheden for "Alle Projekter"
document.getElementById('allProjectsCount').addEventListener('click', function() {
  // Omdiriger til feedback.html
  window.location.href = '../employee/statistics/projects/totalProjects.php';
});


function closeFeedback() {
    var feedbackSection = document.querySelector('.new-feedback-section');
    feedbackSection.style.display = 'none';
}


function openAdminPanel() {
   
        window.location.href = "https://feedback.socdata.dk/admin/admin.php";
}