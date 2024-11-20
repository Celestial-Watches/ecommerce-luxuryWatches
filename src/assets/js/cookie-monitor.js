document.addEventListener("DOMContentLoaded", function() {
    const cookieCard = document.querySelector(".cookie-card");
    const cookieSettingsModal = document.getElementById("cookie-settings-modal");
    const managePreferencesButton = document.getElementById("manage-preferences");
    const acceptCookiesButton = document.getElementById("accept-cookies");
    const closeSettingsButton = document.getElementById("close-settings");
    const saveCookiesButton = document.getElementById("save-cookies");
    
    // Show the cookie consent banner if the user has not accepted cookies yet
    if (!localStorage.getItem("cookie-consent")) {
        cookieCard.style.display = "block";
    }
    
    // Accept cookies and hide the banner
    acceptCookiesButton.addEventListener("click", function() {
        localStorage.setItem("cookie-consent", "true");
        cookieCard.style.display = "none";
    });

    // Open the cookie preferences modal
    managePreferencesButton.addEventListener("click", function() {
        cookieSettingsModal.style.display = "block";
    });

    // Close the settings modal
    closeSettingsButton.addEventListener("click", function() {
        cookieSettingsModal.style.display = "none";
    });

    // Save cookie preferences
    saveCookiesButton.addEventListener("click", function() {
        const analyticsCookies = document.getElementById("cookie-analytics").checked;
        const marketingCookies = document.getElementById("cookie-marketing").checked;
        
        // Save preferences to localStorage
        localStorage.setItem("cookie-analytics", analyticsCookies);
        localStorage.setItem("cookie-marketing", marketingCookies);
        
        cookieSettingsModal.style.display = "none";
    });
});
