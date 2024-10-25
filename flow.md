<!-- 
    License: MIT License
    Author: Ruchit Doshi
    Date: 2024-10-18
-->


<!-- 
    index.php Functionality:
    This code handles secure session management, prevents session hijacking/fixation, and automatically logs out inactive users after a timeout of 30 minutes. It also manages session regeneration and checks for admin access.
-->

Session Management in PHP
Flow:
Configure session cookie parameters for security (lifetime, path, domain, etc.).
Start the session and regenerate the session ID to enhance security.
Check if the user navigated back from the OTP verification page; if so, destroy the session.
Determine if the user is an admin by checking session variables.
Set a session timeout period and check for session expiration.
If the session has expired, clear session data and redirect the user to the login page.
If the user is logged in, update the last activity timestamp.

<!-- 
    currency-language.js Functionality:
    The code handles fetching real-time conversion rates and dynamically updates product prices based on the user’s selected currency.
    It also allows users to choose their preferred language for the website via Google Translate and manages this information securely using encryption with localStorage.
-->

Currency Conversion with Fetch API
Flow:
Define a function to fetch conversion rates from an external API using the Fetch API.
Handle the API response: if successful, return the conversion rates; if not, log an error.
Define a function to update product prices based on the selected currency.
Convert prices by multiplying the original price by the conversion rate for the selected currency.
Format the converted price and update the displayed price for each product.
Use local storage to save the user’s selected currency and retrieve it on page load.
Fetch the user’s location to set a default currency based on the country.
Handle changes to the currency selection and update the displayed prices accordingly.

<!-- 
    navigation.js Functionality:
    This code is used to prevent form resubmission when a user refreshes the page after a form submission, especially after a POST request. It modifies the browser's history by replacing the current entry in the session history with the same URL. This way, if the user refreshes the page, the browser does not try to resubmit the form data.
-->

Replace Current History State
Flow:

Check for Browser Support:

Verify if the replaceState method is supported in the user's browser.
Replace Current History State:

If supported, execute the replaceState method:
Pass null as the first parameter (no new state object).
Pass null as the second parameter (title ignored by most browsers).
Pass window.location.href as the third parameter (current URL).
Effect:

The current history entry is replaced with the same URL, preventing the user from navigating back to the previous state.
This does not create a new history entry, ensuring a smoother user experience in scenarios where you want to manage navigation flow (e.g., after a form submission).

<!-- 
    otp-verify-function.js Functionality:
    The page loads, and the timer starts counting down using updateTimer.
    Every 30 seconds, fetchRemainingTime requests the remaining time from the server to ensure synchronization.
    If the OTP expires (remainingTime reaches 0), the user is informed, and the verify button is disabled.
-->

Timer for OTP Expiration
Flow:
Define a function to update the OTP timer display.
If the timer has expired, display a message and disable the verification button.
If active, calculate remaining minutes and seconds, format them, and update the display.
Define a function to fetch remaining time from the server using AJAX.
Send a request to the server and update the remaining time based on the server's response.
Set an interval to periodically fetch the remaining time every 30 seconds.
Start the timer countdown when the page loads.

<!-- 
    panel.js Functionality:
    When the logo is clicked, the user is redirected to the homepage.
    Clicking the sidebar toggle button will either open or close the sidebar. Text elements are shown or hidden based on the screen width. Submenus are toggled open or closed when clicked, and all other submenus are closed when one is opened. When the window is resized, the visibility of text elements inside the sidebar is adjusted dynamically for different screen sizes.
-->

Sidebar Toggle Functionality
Flow:
Add a click event listener to the logo to redirect the user to the homepage.
Define a function to toggle the sidebar open/close state.
Show or hide text elements based on screen size when toggling the sidebar.
Define a function to toggle the visibility of submenus when a button is clicked.
Close other submenus if one is opened and adjust sidebar visibility if on desktop.
Define a function to close all open submenus.
Add a resize event listener to handle text visibility changes when the window is resized.

<!-- 
    scroll-animation.js Functionality:
    This code enables a scroll animation effect for elements with the class animate-on-scroll. When at least 10% of an observed element comes into view, it triggers a CSS animation by adding the class visible. The observer stops tracking the element once the animation has started, ensuring the animation only plays once per scroll event.
-->

Scroll Animation on Element Visibility
Flow:
Wait for the DOM to fully load using the DOMContentLoaded event.
Select all elements with the class animate-on-scroll.
Create an IntersectionObserver to monitor visibility changes of the selected elements:
Define a callback function (onIntersection) to handle elements that intersect with the viewport.
In the callback, check if the element is visible (isIntersecting).
If it is visible, add the class visible to trigger the animation.
Stop observing the element to avoid repeating the animation.
Set the threshold for the observer to 10%, meaning the element needs to be at least 10% visible to trigger the animation.
For each element that should animate, start observing it with the IntersectionObserver.

<!-- 
    featured-product.php Functionality:
    The main functionality of the script is to calculate and return the remaining time before an OTP (One-Time Password) expires. This would be particularly useful in scenarios where an OTP is sent to a user (e.g., for authentication) and must be validated within a specific time frame. 
-->

Session Handling:

The script starts by calling session_start(), which initiates or resumes the user session. This allows access to any session variables stored for the user, such as the OTP expiry time.
Retrieve OTP Expiry:

The script checks if the session variable $_SESSION['otp_expiry'] exists.
If the variable exists, it assigns the value of $_SESSION['otp_expiry'] to the variable $otp_expiry_time.
If it doesn't exist, the value of $otp_expiry_time is set to 0, indicating no OTP expiration has been set.
Calculate Remaining Time:

The script retrieves the current timestamp using the time() function, which returns the number of seconds since the Unix Epoch (January 1, 1970).
It then calculates the remaining time by subtracting the current timestamp from the OTP expiry time.
The max() function ensures that the remaining time is never negative (if the expiry time has passed, it returns 0).
Return Remaining Time:

The remaining time is then echoed as plain text, ready to be used by the frontend. This output can be processed, such as for displaying a countdown timer.

<!-- 
    forgot-password.php Functionality:
    The PHP script facilitates the process of generating and sending a One-Time Password (OTP) for users who wish to reset their passwords. Initially, it starts a session and sets the timezone to ensure accurate time calculations for OTP expiry. Upon receiving a POST request with an email address, the script validates the email format and checks its existence in the database. If the email is valid and associated with an account, a random 6-digit OTP is generated and stored in the session along with its expiry time, which is set for 10 minutes in the future. The script then configures PHPMailer to send the OTP to the user's email address, crafting an informative email that includes the OTP and instructions for use. If the email is sent successfully, the user is redirected to an OTP verification page. Throughout the process, error handling ensures that any issues, such as invalid email formats or failed email deliveries, are reported back to the user. 
-->

Start Session:

The script begins by initiating a session to store user-specific data, enabling state management throughout the user's interaction.

Set Timezone:

The timezone is set to 'Asia/Kolkata', ensuring that any date or time operations within the script are accurate according to the specified region.

Check Request Method:

The script checks if the incoming request method is POST and if an email address has been submitted. This is essential for determining whether to proceed with OTP generation.

Email Validation:

It validates the provided email address:
Checks if the email field is empty and adds an error message if it is.
Verifies the email format using filter_var. If the format is invalid, an error message is recorded.

Database Query to Check Email Existence:

A prepared statement is created to securely query the database for the existence of the provided email in the users table.
The script executes the query and retrieves the result.
Generate OTP:

If the email is found in the database (indicating that the user has an account), a random 6-digit OTP is generated.
Store OTP and Expiry in Session:

The generated OTP and its expiry time (set to 10 minutes from the current time) are stored in the session. The user's email is also saved for later use.
Setup PHPMailer:

PHPMailer is configured with SMTP settings to prepare for sending the OTP email. This includes defining the SMTP host, authentication details, and the encryption method.
Create Email Content:

The email content is composed, which includes:
The subject line and body of the email, incorporating the OTP and instructions for the user.
A plain text alternative body for email clients that do not support HTML.
Send OTP Email:

The script attempts to send the composed email using PHPMailer. If the email is sent successfully, it proceeds to the next step.
Error Handling:

If the email fails to send, an error message is added to the errors array. Additionally, if no account is found with the given email, another error message is recorded.
Redirect After Successful OTP Request:

Upon successful sending of the OTP email, the script resets any relevant session variables and redirects the user to the OTP verification page (verify_repass_otp.php), allowing them to proceed with the password reset process.