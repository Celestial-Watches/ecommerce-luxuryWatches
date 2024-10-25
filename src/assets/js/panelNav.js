const toggleButton = document.getElementById('toggle-btn');
const sidebar = document.getElementById('sidebar');
const redirectHome = document.querySelector('.logo');

// Redirect on Logo Click:
// Objective: Redirect the user to the homepage when the logo is clicked.
// Steps:
// Add an event listener to the logo element.
// When clicked, redirect the user to '../../../index.php'.

redirectHome.addEventListener('click', function() {
    window.location.href = '../../../index.php'; 
});

// toggleSidebar Function:
// Objective: Open or close the sidebar and handle the visibility of text inside it based on screen size.
// Steps:
// Select elements with the noShow class, which are meant to have toggleable text.
// Toggle the close class on the sidebar to open/close it.
// Toggle the rotate class on the toggle button to rotate it when clicked.
// If the window width is greater than 800px (desktop):
// If the sidebar is closed, hide the text elements (noShow).
// If the sidebar is open, show the text elements (noShow).
// If the window width is less than or equal to 800px (mobile):
// Always hide the text elements for smaller screens.

function toggleSidebar() {
    const noTextElements = sidebar.querySelectorAll('.noShow');

    // Toggle the sidebar open/close state
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');

    // Handle text visibility based on screen size
    if (window.innerWidth > 800) {
        if (sidebar.classList.contains('close')) {
            noTextElements.forEach(el => el.style.display = 'none'); // Hide text when sidebar is closed
        } else {
            noTextElements.forEach(el => el.style.display = 'inline'); // Show text when sidebar is open
        }
    } else {
        // For mobile: Ensure text is always hidden on small screens (< 800px)
        noTextElements.forEach(el => el.style.display = 'none');
    }
}

// toggleSubMenu Function:
// Objective: Open or close a submenu and close other submenus if any are open.
// Steps:
// Identify the submenu that follows the button.
// If the submenu is not already shown:
// Close all other submenus using closeAllSubMenus.
// On desktop screens, reset the sidebar to the open state and show the text elements.
// Toggle the visibility (show class) of the selected submenu.
// Rotate the submenu button icon.

function toggleSubMenu(button) {
    const submenu = button.nextElementSibling;

    // If the submenu is not currently shown, close all other submenus
    if (!submenu.classList.contains('show')) {
        closeAllSubMenus();

        // For desktop: Revert sidebar state when a submenu is clicked
        if (window.innerWidth > 800) {
            sidebar.classList.remove('close');
            toggleButton.classList.remove('rotate');
            sidebar.querySelectorAll('.noShow').forEach(el => el.style.display = 'inline'); // Show text
        }
    }

    // Toggle submenu visibility
    submenu.classList.toggle('show');
    button.classList.toggle('rotate');
}

// closeAllSubMenus Function:
// Objective: Close all open submenus.
// Steps:
// Find all elements with the show class and remove the class.
// Reset the submenu toggle buttons (remove rotate class).

function closeAllSubMenus() {
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
        ul.classList.remove('show');
        ul.previousElementSibling.classList.remove('rotate');
    });
}

// resize Event Listener:
// Objective: Adjust the visibility of the text when the window size changes.
// Steps:
// Add an event listener for window resizing.
// If the window width is greater than 800px and the sidebar is open, display the text elements.
// If the window width is less than or equal to 800px, hide the text elements.

// resize Event Listener
window.addEventListener('resize', () => {
    const noTextElements = sidebar.querySelectorAll('.noShow');

    if (window.innerWidth > 800) {
        if (!sidebar.classList.contains('close')) {
            noTextElements.forEach(el => el.style.display = 'inline'); // Show text on desktop if sidebar is open
        } else {
            noTextElements.forEach(el => el.style.display = 'none'); // Hide text when sidebar is closed
        }
    } else {
        // Always hide text on mobile screens
        noTextElements.forEach(el => el.style.display = 'none');
    }
});
