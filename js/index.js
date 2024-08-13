const actionBtns = document.querySelectorAll('.action-btn');
const menuCloseBtns = document.querySelectorAll('.menu-close-btn');
const accordionBtns = document.querySelectorAll('[data-accordion-btn]');

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

