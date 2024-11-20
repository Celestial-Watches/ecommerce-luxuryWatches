"use strict";

// ============================= Utility Functions =============================
/**
 * Sets an item in localStorage with an expiration time.
 */
function setLocalStorageWithExpiry(key, value, hours) {
    const now = new Date();
    const expiryTime = now.getTime() + hours * 60 * 60 * 1000;
    const item = { value, expiry: expiryTime };
    localStorage.setItem(key, JSON.stringify(item));
}

/**
 * Gets an item from localStorage, ensuring it's not expired.
 */
function getLocalStorageWithExpiry(key) {
    const itemStr = localStorage.getItem(key);
    if (!itemStr) return null;

    const item = JSON.parse(itemStr);
    if (new Date().getTime() > item.expiry) {
        localStorage.removeItem(key);
        return null;
    }

    return item.value;
}

// ============================= Modal Logic =============================
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.querySelector("[data-modal]");
    const modalCloseBtn = document.querySelector("[data-modal-close]");
    const modalCloseOverlay = document.querySelector("[data-modal-overlay]");
    const subscribeForm = document.querySelector(".newsletter form");

    // Function to open or close modal with animation
    const toggleModal = (isOpen) => {
        if (modal) {
            modal.classList.toggle("closed", !isOpen);
            modal.setAttribute("aria-hidden", !isOpen);
        }
    };

    // Check if modal should be shown
    const isModalClosed = getLocalStorageWithExpiry("modalClosed") === "true";
    if (!isModalClosed && modal) {
        toggleModal(true);
    } else if (modal) {
        toggleModal(false);
    }

    // Close modal and set a timer to avoid re-showing it
    const closeModal = () => {
        toggleModal(false);
        setLocalStorageWithExpiry("modalClosed", "true", 3); // Expires in 3 hours
    };

    // Add event listeners for modal close actions
    if (modalCloseBtn) modalCloseBtn.addEventListener("click", closeModal);
    if (modalCloseOverlay) modalCloseOverlay.addEventListener("click", closeModal);

    // ============================= Subscription Logic =============================
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function (event) {
            event.preventDefault();
        
            const email = document.getElementById('subscribe-email').value;
            const feedbackMessage = document.getElementById('feedback-message');
            feedbackMessage.textContent = ''; // Clear previous messages
        
            if (!email) {
                feedbackMessage.textContent = 'Please enter your email.';
                feedbackMessage.style.color = 'red';
                return;
            }
        
            const formData = new FormData();
            formData.append('email', email);
        
            feedbackMessage.textContent = 'Processing...';
            feedbackMessage.style.color = 'black';
        
            const submitButton = subscribeForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
        
            fetch('../../../app/controllers/subscribe.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json()) // Expect JSON
                .then(data => {
                    feedbackMessage.style.color = data.status === 'success' ? 'green' : 'red';
                    feedbackMessage.textContent = data.message;
        
                    if (data.status === 'success') {
                        subscribeForm.reset();
                        modalCloseFunc();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedbackMessage.textContent = 'An unexpected error occurred. Please try again later.';
                    feedbackMessage.style.color = 'red';
                })
                .finally(() => {
                    submitButton.disabled = false;
                });
        });   
    }
});

// ============================= Password Toggle =============================
document.body.addEventListener("click", (event) => {
    if (event.target.matches(".password-toggle-icon i")) {
        const toggleIcon = event.target;
        const passwordField = toggleIcon.closest(".input-group").querySelector(".password-field");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
            toggleIcon.parentNode.title = "Hide Password";
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
            toggleIcon.parentNode.title = "Show Password";
        }
    }
});

// ============================= Accessibility Enhancements =============================
/**
 * Ensures modal and other interactive elements are accessible.
 */
document.querySelectorAll("[data-modal]").forEach((modal) => {
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-hidden", "true");
    modal.setAttribute("aria-labelledby", "modalTitle");
});
