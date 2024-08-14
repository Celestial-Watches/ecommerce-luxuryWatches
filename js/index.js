"use strict";

// ============================= NAVIGATION OPEN =============================


const actionBtns = document.querySelectorAll('.has-menu-btn'); // This is for the side menu for navigation
const menuCloseBtns = document.querySelectorAll('.menu-close-btn'); // This is for side menu closing button
const accordionBtns = document.querySelectorAll('[data-accordion-btn]'); // This is for side menu dropdown button

actionBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
    mobileNavigationMenu.classList.toggle('active');
  });
});

menuCloseBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
      mobileNavigationMenu.classList.remove('active');
    });
  });

accordionBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const submenu = btn.nextElementSibling;
    submenu.classList.toggle('active');
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

// Check if the user is already subscribed
if (localStorage.getItem('subscribed') === 'true') {
    modal.classList.add('closed'); // Hide modal if subscribed
} else {
    modal.classList.remove('closed'); // Show modal if not subscribed
}

// Modal function to close the modal
const modalCloseFunc = function () {
    modal.classList.add('closed');
}

// Handle form submission
subscribeForm.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form from submitting normally

    // Set local storage to remember subscription
    localStorage.setItem('subscribed', 'true');

    // Close the modal
    modal.classList.add('closed');
});

// Modal event listeners
modalCloseOverlay.addEventListener('click', modalCloseFunc);
modalCloseBtn.addEventListener('click', modalCloseFunc);

if (localStorage.getItem('subscribed') === 'false'){
  localStorage.removeItem('subscribed');
}



// ============================= MODAL CLOSE =============================

console.log(modal);
console.log(modalCloseBtn);
console.log(modalCloseOverlay);
console.log(subscribeForm);
console.log(modalCloseFunc);