"use strict";


// ============================= NAVIGATION OPEN =============================

document.addEventListener('DOMContentLoaded', () => {

    const actionBtns = document.querySelectorAll('.has-menu-btn');
    const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
    const menuCloseBtns = document.querySelectorAll('.menu-close-btn'); // Declare menu close buttons
    const accordionBtns = document.querySelectorAll('[data-accordion-btn]'); // Declare accordion buttons

    let isMenuOpen = false; // Flag to track menu state

    // Toggle the menu on button click
    actionBtns.forEach(btn => {
        btn.addEventListener('click', (event) => {
            console.log("Action button clicked!");
            event.stopPropagation();
            isMenuOpen = !isMenuOpen; // Toggle the flag
            if (mobileNavigationMenu) {
                mobileNavigationMenu.classList.toggle('menu-visible', isMenuOpen); // Add or remove 'menu-visible'
                console.log('Menu button clicked, menu state:', isMenuOpen);
            }
        });
    });

    // Close the menu if a close button is clicked
    menuCloseBtns.forEach(btn => {
        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            isMenuOpen = false; // Update flag
            if (mobileNavigationMenu) {
                mobileNavigationMenu.classList.remove('menu-visible');
            }
        });
    });

    // Toggle accordion dropdowns inside the menu
    accordionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const submenu = btn.nextElementSibling;
            if (submenu) {
                submenu.classList.toggle('active');
            }
            btn.classList.toggle('active');
        });
    });



    // Close the menu if clicking outside the menu area
    document.addEventListener('click', (event) => {
        if (isMenuOpen && !mobileNavigationMenu.contains(event.target)) {
            isMenuOpen = false;
            mobileNavigationMenu.classList.remove('menu-visible');
            console.log('Clicked outside, menu closed');
        }

        // ============================= NAVIGATION CLOSE =============================
        console.log('Action Buttons:', actionBtns);
        console.log('Accordion Buttons:', accordionBtns);

    });

});


    // ============================= MODAL OPEN =============================

    // modal variables
    const modal = document.querySelector('[data-modal]');
    const modalCloseBtn = document.querySelector('[data-modal-close]');
    const modalCloseOverlay = document.querySelector('[data-modal-overlay]');
    const subscribeForm = document.querySelector('.newsletter form');

    // Function to set a local storage item with an expiration time
    function setLocalStorageWithExpiry(key, value, hours) {
        const now = new Date();
        const expiryTime = now.getTime() + hours * 60 * 60 * 1000;
        const item = {
            value: value,
            expiry: expiryTime
        };
        localStorage.setItem(key, JSON.stringify(item));
    }

    // Function to get a local storage item with an expiration time
    function getLocalStorageWithExpiry(key) {
        const itemStr = localStorage.getItem(key);
        if (!itemStr) {
            return null;
        }
        const item = JSON.parse(itemStr);
        const now = new Date();
        if (now.getTime() > item.expiry) {
            localStorage.removeItem(key);
            return null;
        }
        return item.value;
    }

    // Modal function to close the modal
    const modalCloseFunc = function () {
        if (modal) {
            modal.classList.add('closed');
            setLocalStorageWithExpiry('modalClosed', 'true', 3); // Set modal closed state with 3 hours expiry
        }
    };

    // Check if the user has closed the modal
    if (modal) {
        if (getLocalStorageWithExpiry('modalClosed') === 'true') {
            modal.classList.add('closed'); // Hide modal if closed
        } else {
            modal.classList.remove('closed'); // Show modal if not closed
        }

        // Handle form submission
        if (subscribeForm) {
            subscribeForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent form from submitting normally

                const email = document.getElementById('subscribe-email').value; // Get the email value
                const feedbackMessage = document.getElementById('feedback-message'); // Assuming there's a div for feedback
                feedbackMessage.textContent = ''; // Clear previous messages

                if (!email) {
                    feedbackMessage.textContent = 'Please enter your email.';
                    feedbackMessage.style.color = 'red';
                    return;
                }

                const formData = new FormData();
                formData.append('email', email);

                // Show loading indicator
                feedbackMessage.textContent = 'Processing...';
                feedbackMessage.style.color = 'black'; // Reset color for loading state

                // Disable the submit button to prevent multiple submissions
                const submitButton = subscribeForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                fetch('../../../app/controllers/subscribe.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        // Check if the response is ok (status in the range 200-299)
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        feedbackMessage.style.color = data.status === 'success' ? 'green' : 'red';
                        feedbackMessage.textContent = data.message;

                        if (data.status === 'success') {
                            // Set local storage to remember subscription
                            setLocalStorageWithExpiry('subscribed', 'true', 3); // Set subscription state with 3 hours expiry
                            subscribeForm.reset(); // Clear the input field
                            modalCloseFunc(); // Close the modal
                            location.reload();

                            const thankYouMessage = document.createElement('div');
                            thankYouMessage.textContent = 'Thank you for subscribing!';
                            document.body.appendChild(thankYouMessage);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        feedbackMessage.textContent = 'An unexpected error occurred. Please try again later.';
                        feedbackMessage.style.color = 'red';
                    })
                    .finally(() => {
                        // Re-enable the submit button after processing is done
                        submitButton.disabled = false;
                    });
            });
        }


        // Modal event listeners
        if (modalCloseOverlay) {
            modalCloseOverlay.addEventListener('click', modalCloseFunc);
        }
        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', modalCloseFunc);
        }

        // Reset modal state if the user visits the website again
        if (localStorage.getItem('modalClosed') === 'false') {
            localStorage.removeItem('modalClosed');
        }
    }

    // ============================= MODAL CLOSE =============================

    console.log(modal);
    console.log(modalCloseBtn);
    console.log(modalCloseOverlay);
    console.log(subscribeForm);
    console.log(modalCloseFunc);


// ============================= PASSWORD SHOW/HIDE START =============================


document.querySelectorAll('.password-toggle-icon i').forEach(function (toggleIcon) {
    toggleIcon.addEventListener('click', function () {
        const passwordField = this.closest('.input-group').querySelector('.password-field');

        if (passwordField.type === "password") {
            passwordField.type = "text";
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
            this.parentNode.title = 'Hide Password';
        } else {
            passwordField.type = "password";
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
            this.parentNode.title = 'Show Password';
        }
    });
});

// ============================= PASSWORD SHOW/HIDE OVER =============================
