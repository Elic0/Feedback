let currentPopup = null; // Track the currently open popup

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('read-more')) {
        const feedbackRow = event.target.closest('tr');
        const feedbackId = feedbackRow.dataset.feedbackId;

        fetch(`readMore.php?id=${feedbackId}`)
            .then(response => response.text())
            .then(data => {
                // Close the current popup if one is open
                closePopup(currentPopup);
                // Show the new popup
                currentPopup = showPopup(data);
            })
            .catch(error => console.error('Error fetching feedback:', error));
    }
});

function showPopup(content) {
    var popup = document.createElement("div");
    popup.className = "popup";
    popup.innerHTML = "<p class='popup-message'>" + content + "</p><button onclick='closePopup()'>Close</button>";
    document.body.appendChild(popup);
    popup.style.display = "block";
    return popup;
}

function closePopup() {
    var popup = document.querySelector(".popup");
    if (popup) {
        popup.style.display = "none";
        document.body.removeChild(popup);
        currentPopup = null; // Opdater den aktuelle popup til null, da den er lukket
    }
}


function markAllFeedbackAsRead() {
    // Send AJAX request to mark all feedback as read
    fetch('markAllFeedbackAsRead.php', {
        method: 'POST'
    })
    .then(response => {
        if (response.ok) {
            console.log('All feedback marked as read successfully.');
        } else {
            console.error('Error marking all feedback as read.');
        }
    })
    .catch(error => console.error('Error marking all feedback as read:', error));
}

function goBack() {
    window.location.href = '/employee.php';
}
