const toggleButton = document.getElementById('toggle-btn');
const sidebar = document.getElementById('sidebar');
const redirectHome = document.querySelector('.logo');

// Redirect to home when logo is clicked
redirectHome.addEventListener('click', function() {
    window.location.href = '../../../index.php'; 
});

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

function closeAllSubMenus() {
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
        ul.classList.remove('show');
        ul.previousElementSibling.classList.remove('rotate');
    });
}

// Listen to resize events to handle text visibility correctly when window size changes
window.addEventListener('resize', () => {
    const noTextElements = sidebar.querySelectorAll('.noShow');

    if (window.innerWidth > 800) {
        if (!sidebar.classList.contains('close')) {
            noTextElements.forEach(el => el.style.display = 'inline'); // Show text on desktop if sidebar is open
        }
    } else {
        // Always hide text on mobile screens
        noTextElements.forEach(el => el.style.display = 'none');
    }
});
