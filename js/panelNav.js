const toggleButton = document.getElementById('toggle-btn')
const sidebar = document.getElementById('sidebar')
const redirectHome = document.querySelector('.logo')

redirectHome.addEventListener('click', function() {
    // Redirect to another page
    window.location.href = 'index.php'; // Change to your desired URL
});


function toggleSidebar() {
    const noTextElements = sidebar.querySelectorAll('.noShow');

    // Toggle sidebar open/close
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');

    // If sidebar is closed, hide text; if open, show text
    if (sidebar.classList.contains('close')) {
        noTextElements.forEach(el => el.style.display = 'none'); // Hide text
    } else {
        noTextElements.forEach(el => el.style.display = 'block'); // Show text
    }
}

function toggleSubMenu(button) {
    const submenu = button.nextElementSibling;

    // If the submenu is not currently shown, close all submenus
    if (!submenu.classList.contains('show')) {
        closeAllSubMenus();
        // Revert sidebar to show text when an icon is clicked
        sidebar.classList.remove('close');
        toggleButton.classList.remove('rotate');
        sidebar.querySelectorAll('.noShow').forEach(el => el.style.display = 'block'); // Show text
    }

    // Toggle the submenu visibility
    submenu.classList.toggle('show');
    button.classList.toggle('rotate');
}

function closeAllSubMenus() {
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
        ul.classList.remove('show');
        ul.previousElementSibling.classList.remove('rotate');
    });
}