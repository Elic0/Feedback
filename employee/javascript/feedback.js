let currentPopup = null; // Track the currently open popup

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('read-more')) {
        const feedbackId = event.target.dataset.feedbackId;

        fetch(`readMore.php?id=${feedbackId}`)
            .then(response => response.text())
            .then(data => {
                if (currentPopup) {
                    closePopup(currentPopup);
                }
                currentPopup = showPopup(data);
            })
            .catch(error => console.error('Error fetching feedback:', error));
    } else if (!event.target.closest('.popup')) {
        closePopup(currentPopup);
    }
});

function showPopup(content) {
    // Close any existing popup
    closePopup();

    var popup = document.createElement("div");
    popup.className = "popup open"; // Add the 'open' class initially to ensure it's displayed
    popup.innerHTML = "<p class='popup-message'>" + content + "</p><button class='popup-close'>Close</button>";
    document.body.appendChild(popup);

    // Close popup when clicking close button
    popup.querySelector('.popup-close').addEventListener('click', function() {
        closePopup(popup);
    });

    // Set the currentPopup to the newly created popup
    currentPopup = popup;
    return popup;
}

function closePopup(popup) {
    if (popup && popup.classList.contains('open')) {
        popup.classList.remove('open');
        setTimeout(() => {
            document.body.removeChild(popup);
        }, 300); // Delay removal to allow for transition
        currentPopup = null;
    }
}

function markAsRead(feedbackId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateFeedbackStatus.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Update UI based on response
                if (xhr.responseText.trim() === 'success') {
                    // Update UI here if status updated successfully
                    alert('Feedback marked as read successfully');
                    // You might want to refresh the feedback list after marking as read
                    window.location.reload();
                } else {
                    // Handle error
                    alert('Error marking feedback as read');
                }
            } else {
                // Handle HTTP error
                console.error('Error marking feedback as read. HTTP status:', xhr.status);
            }
        }
    };
    xhr.send('feedback_id=' + feedbackId);
}

function goBack() {
    window.location.href = '/employee.php';
}
