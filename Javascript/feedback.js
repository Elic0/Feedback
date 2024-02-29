// Tilføj feedback dynamisk til feedbackList
function displayFeedback(feedback) {
    const feedbackList = document.getElementById('feedbackList');
    feedbackList.innerHTML = ''; // Tømmer indholdet for at undgå duplikater
  
    if (feedback.length > 0) {
      feedback.forEach((item, index) => {
        const feedbackItem = document.createElement('div');
        feedbackItem.classList.add('feedback-item');
        feedbackItem.innerHTML = `<p>${index + 1}. ${item}</p>`;
        feedbackList.appendChild(feedbackItem);
      });
    } else {
      feedbackList.innerHTML = "<p>Ikke noget nyt feedback tilgængeligt</p>";
    }
  }
  
  // Simuleret feedback data
  const feedbackData = []; // Tilføj feedback til dette array efter behov
  
  // Kald funktionen for at vise feedback ved start
  displayFeedback(feedbackData);
  
  // Eksempel på tilføjelse af feedback til listen (kan gøres dynamisk baseret på brugerens handlinger)
  // feedbackData.push("Ny feedback 1");
  // displayFeedback(feedbackData);
  