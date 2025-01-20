"use strict";
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
    let lastFocusedElement;

    // Function to open or close modal with animation
    const toggleModal = (isOpen) => {
        if (modal) {
            modal.classList.toggle("closed", !isOpen);
            modal.setAttribute("aria-hidden", !isOpen);
            if (isOpen) {
                lastFocusedElement = document.activeElement;
                modal.setAttribute("aria-modal", "true");
                const firstFocusableElement = modal.querySelector('a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])');
                if (firstFocusableElement) {
                    firstFocusableElement.focus();
                }
            } else {
                modal.removeAttribute("aria-modal");
                if (lastFocusedElement) {
                    lastFocusedElement.focus();
                }
            }
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
        setLocalStorageWithExpiry("modalClosed", "true", 3);
        document.querySelector('.subscribe-button').focus();
    };

    // Add event listeners for modal close actions
    if (modalCloseBtn) modalCloseBtn.addEventListener("click", closeModal);
    if (modalCloseOverlay) modalCloseOverlay.addEventListener("click", closeModal);

    // Close modal on Escape key press
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && !modal.classList.contains("closed")) {
            closeModal();
        }
    });

    // Trap focus within the modal
    modal.addEventListener("keydown", (event) => {
        if (event.key === "Tab") {
            const focusableElements = modal.querySelectorAll('a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (event.shiftKey) {
                if (document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }
        }
    });

    // ============================= Subscription Logic =============================
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const email = document.getElementById('subscribe-email').value;
            const feedbackMessage = document.getElementById('feedback-message');
            feedbackMessage.textContent = ''; // Clear previous messages

            // Email validation
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                feedbackMessage.textContent = 'Please enter a valid email.';
                feedbackMessage.style.color = 'red';
                return;
            }

            const formData = new FormData();
            formData.append('email', email);

            feedbackMessage.textContent = 'Processing...';
            feedbackMessage.style.color = 'black';

            const submitButton = subscribeForm.querySelector('button[type="submit"]');
            submitButton.disabled = true; // Disable button at the start

            const BASE_URL = '../../../app/controllers/subscribe.php'; // Define a base URL

            fetch(BASE_URL, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errData => {
                            throw new Error(errData.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    feedbackMessage.style.color = data.status === 'success' ? 'green' : 'red';
                    feedbackMessage.textContent = data.message;

                    if (data.status === 'success') {
                        subscribeForm.reset();
                        setTimeout(() => {
                            feedbackMessage.textContent = '';
                            closeModal();
                        }, 2000); // Close modal after 2 seconds
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedbackMessage.textContent = 'An unexpected error occurred. Please try again later.';
                    feedbackMessage.style.color = 'red';
                })
                .finally(() => {
                    submitButton.disabled = false; // Ensure button is re-enabled
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
    modal.setAttribute("aria-describedby", "modalDescription");
});
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
        setLocalStorageWithExpiry("modalClosed", "true", 3);
        modalCloseBtn.removeEventListener("click", closeModal);
        modalCloseOverlay.removeEventListener("click", closeModal);
        document.querySelector('.subscribe-button').focus();
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

            // Email validation
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                feedbackMessage.textContent = 'Please enter a valid email.';
                feedbackMessage.style.color = 'red';
                return;
            }

            const formData = new FormData();
            formData.append('email', email);

            feedbackMessage.textContent = 'Processing...';
            feedbackMessage.style.color = 'black';

            const submitButton = subscribeForm.querySelector('button[type="submit"]');
            submitButton.disabled = true; // Disable button at the start

            const BASE_URL = '../../../app/controllers/subscribe.php'; // Define a base URL

            fetch(BASE_URL, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errData => {
                            throw new Error(errData.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    feedbackMessage.style.color = data.status === 'success' ? 'green' : 'red';
                    feedbackMessage.textContent = data.message;

                    if (data.status === 'success') {
                        subscribeForm.reset();
                        feedbackMessage.textContent = '';
                        setTimeout(closeModal, 2000); // Close modal after 2 seconds
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedbackMessage.textContent = 'An unexpected error occurred. Please try again later.';
                    feedbackMessage.style.color = 'red';
                })
                .finally(() => {
                    submitButton.disabled = false; // Ensure button is re-enabled
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


