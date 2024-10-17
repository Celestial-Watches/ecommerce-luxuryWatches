// Function to update the timer display
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

// Function to fetch the remaining time from the server using AJAX
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

// Fetch the remaining time every 30 seconds (adjust as needed)
setInterval(fetchRemainingTime, 30000); // Fetch updated time from the server

// Start the timer when the page loads
window.onload = updateTimer;