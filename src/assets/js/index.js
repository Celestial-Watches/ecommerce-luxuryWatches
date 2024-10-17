"use strict";

document.addEventListener('DOMContentLoaded', () => {
    // ============================= NAVIGATION OPEN =============================

    const actionBtns = document.querySelectorAll('.has-menu-btn'); // This is for the side menu for navigation
    const menuCloseBtns = document.querySelectorAll('.menu-close-btn'); // This is for side menu closing button
    const accordionBtns = document.querySelectorAll('[data-accordion-btn]'); // This is for side menu dropdown button

    actionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
            if (mobileNavigationMenu) {
                mobileNavigationMenu.classList.toggle('active');
            }
        });
    });

    menuCloseBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
            if (mobileNavigationMenu) {
                mobileNavigationMenu.classList.remove('active');
            }
        });
    });

    accordionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const submenu = btn.nextElementSibling;
            if (submenu) {
                submenu.classList.toggle('active');
            }
            btn.classList.toggle('active');
        });
    });

    // ============================= NAVIGATION CLOSE =============================

    console.log(actionBtns);
    console.log(menuCloseBtns);
    console.log(accordionBtns);

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

                // Set local storage to remember subscription
                setLocalStorageWithExpiry('subscribed', 'true', 3); // Set subscription state with 3 hours expiry

                // Close the modal
                modalCloseFunc(); // Call the modal close function
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
});

// ============================= PASSWORD SHOW/HIDE START =============================


document.querySelectorAll('.password-toggle-icon i').forEach(function(toggleIcon) {
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

// ============================= SWIPER START =============================




// ============================= SWIPER END =============================