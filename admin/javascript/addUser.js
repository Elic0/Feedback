function showPopup(message) {
    var popup = document.createElement("div");
    popup.className = "popup"; // Sets the class name for the popup element
    popup.innerHTML = message; // Inserts the message content into the popup
    document.body.appendChild(popup); // Appends the popup element to the body of the document
    popup.style.display = "block"; // Sets the display style of the popup to block, making it visible
    setTimeout(function() {
        popup.style.display = "none"; // Sets the display style of the popup to none after 2000 milliseconds, hiding it
    }, 2000); // Sets the timeout duration for the popup to 2000 milliseconds
}
