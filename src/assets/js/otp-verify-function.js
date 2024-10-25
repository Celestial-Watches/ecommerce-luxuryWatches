// updateTimer Function:
// Purpose: Updates the OTP countdown timer displayed on the page.
// Steps:
// If the remaining time (remainingTime) is zero or less:
// Display "OTP has expired."
// Disable the "Verify" button.
// Stop the timer update.
// Otherwise:
// Calculate minutes and seconds from the remaining time.
// Display the remaining time in the format mm:ss.
// Decrement the remaining time by 1 second.
// Set a timeout to call updateTimer again after 1 second to keep updating the timer.

function updateTimer() {
    if (remainingTime <= 0) {
        document.getElementById('timer').innerHTML = "OTP has expired.";
        document.getElementById('verify-button').disabled = true; // Disable verify button
        return;
    }

    const minutes = Math.floor(remainingTime / 60);
    const seconds = remainingTime % 60;
    document.getElementById('timer').innerHTML = `Expires in: ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    remainingTime--; // Decrement remaining time on the client-side

    // Continue updating the timer every second
    setTimeout(updateTimer, 1000);
}

// fetchRemainingTime Function:
// Purpose: Fetches the remaining OTP expiration time from the server using AJAX.
// Steps:
// Create a new XMLHttpRequest.
// Open a GET request to the PHP file that will return the remaining time.
// When the server responds with status 200 (OK):
// Update remainingTime with the time received from the server.
// Send the AJAX request to the server.

function fetchRemainingTime() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../../PHP/components/fetch_remaining_time.php', true); // Your PHP file that returns remaining time
    xhr.onload = function () {
        if (this.status === 200) {
            remainingTime = parseInt(this.responseText); // Update remainingTime with the server response
        }
    };
    xhr.send();
}

// Fetching Time Periodically:
// Purpose: Keep the client's remaining time in sync with the server.
// Steps:
// Use setInterval to fetch the remaining time from the server every 30 seconds.
// Call the fetchRemainingTime function periodically to ensure the countdown is accurate.

setInterval(fetchRemainingTime, 30000); // Fetch updated time from the server

// Start Timer on Page Load:
// Purpose: Begin the OTP timer countdown when the page loads.
// Steps:
// Use the window.onload event to trigger the updateTimer function as soon as the page finishes loading.

window.onload = updateTimer;