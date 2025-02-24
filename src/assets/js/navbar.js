// ============================= NAVIGATION OPEN =============================

document.addEventListener('DOMContentLoaded', () => {

    const actionBtns = document.querySelectorAll('.has-menu-btn');
    const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
    const menuCloseBtns = document.querySelectorAll('.menu-close-btn'); 
    const accordionBtns = document.querySelectorAll('[data-accordion-btn]'); 
    let isMenuOpen = false; 

    actionBtns.forEach(btn => {
        btn.addEventListener('click', (event) => {
            console.log("Action button clicked!");
            event.stopPropagation();
            isMenuOpen = !isMenuOpen; 
            if (mobileNavigationMenu) {
                mobileNavigationMenu.classList.toggle('menu-visible', isMenuOpen);
                console.log('Menu button clicked, menu state:', isMenuOpen);
            }
        });
    });

    // Close the menu if a close button is clicked
    menuCloseBtns.forEach(btn => {
        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            isMenuOpen = false; 
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
        event.stopPropagation();
        if (isMenuOpen && !mobileNavigationMenu.contains(event.target)) {
            isMenuOpen = false;
            mobileNavigationMenu.classList.remove('menu-visible');
            console.log('Clicked outside, menu closed');
        }

        // ============================= NAVIGATION CLOSE =============================

    });

});

// Wait for the DOM to fully load
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const suggestions = document.getElementById('suggestions');
    const searchForm = document.getElementById('searchForm');
    let selectedIndex = -1; // Index of the selected suggestion

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value;

            if (query.length > 1) {
                fetch('http://localhost:3000/app/controllers/fetch-suggestion.php?search=' + encodeURIComponent(query))
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        suggestions.innerHTML = ''; // Clear previous suggestions
                        suggestions.style.display = 'none'; // Hide by default
                        selectedIndex = -1; // Reset selected index

                        if (data.length > 0) {
                            suggestions.style.display = 'block'; // Show suggestions
                            data.forEach((item, index) => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('suggestion-item');
                                suggestionItem.textContent = item.name;

                                suggestionItem.addEventListener('click', function() {
                                    searchInput.value = item.name;
                                    suggestions.style.display = 'none';
                                    searchForm.submit();
                                });

                                suggestionItem.addEventListener('mousedown', function() {
                                    searchInput.value = item.name;
                                    suggestions.style.display = 'none';
                                    searchForm.submit();
                                });

                                suggestions.appendChild(suggestionItem);

                                if (index < data.length - 1) {
                                    const hr = document.createElement('hr');
                                    suggestions.appendChild(hr);
                                }
                            });
                        } else {
                            // Show "No product found" if no suggestions
                            const noProductItem = document.createElement('div');
                            noProductItem.classList.add('no-product');
                            noProductItem.textContent = 'No product found';
                            suggestions.appendChild(noProductItem);
                            suggestions.style.display = 'block'; // Show the message
                        }
                    })
                    .catch(error => console.error('Error fetching suggestions:', error));
            } else {
                suggestions.style.display = 'none'; // Hide if query is short
            }
        });

        // Keydown event listener for arrow keys and enter
        searchInput.addEventListener('keydown', function(event) {
            const suggestionItems = suggestions.querySelectorAll('.suggestion-item');

            if (event.key === 'ArrowDown') {
                selectedIndex = (selectedIndex + 1) % suggestionItems.length; // Move down
                updateSuggestionSelection(suggestionItems);
                event.preventDefault(); // Prevent default scrolling
            } else if (event.key === 'ArrowUp') {
                selectedIndex = (selectedIndex - 1 + suggestionItems.length) % suggestionItems.length; // Move up
                updateSuggestionSelection(suggestionItems);
                event.preventDefault(); // Prevent default scrolling
            } else if (event.key === 'Enter') {
                if (selectedIndex >= 0 && selectedIndex < suggestionItems.length) {
                    suggestionItems[selectedIndex].click(); // Trigger click on selected item
                }
            }
        });

        // Function to update the selected suggestion style
        function updateSuggestionSelection(suggestionItems) {
            suggestionItems.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('selected'); // Add selected class for styling
                } else {
                    item.classList.remove('selected'); // Remove selected class
                }
            });
        }
    } else {
        console.error('Search input not found. Please check your HTML.');
    }
});


// Cart system starts

document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.getElementById('cart-btn');
    const wishlistBtn = document.getElementById('wish-btn');
    const cartDrawer = document.getElementById('cart-drawer');
    const wishlistDrawer = document.getElementById('wishlist-drawer');
    const cartCloseBtn = document.getElementById('cart-close-btn');
    const wishlistCloseBtn = document.getElementById('wishlist-close-btn');

    // Function to show and hide drawers and buttons
    function toggleDrawer(drawer, otherDrawer, button) {
        drawer.style.right = '0';
        button.style.display = 'none'; // Hide the current button
        otherDrawer.style.right = '-400px'; // Close the other drawer if open
    }

    function closeDrawer(drawer, button) {
        drawer.style.right = '-400px'; // Hide the drawer
        button.style.display = 'block'; // Show the button again
    }

    // Open the cart drawer when cart button is clicked
    cartBtn.addEventListener('click', () => {
        toggleDrawer(cartDrawer, wishlistDrawer, cartBtn);
    });

    // Close the cart drawer when close button is clicked
    cartCloseBtn.addEventListener('click', () => {
        closeDrawer(cartDrawer, cartBtn);
    });

    // Open the wishlist drawer when wishlist button is clicked
    wishlistBtn.addEventListener('click', () => {
        toggleDrawer(wishlistDrawer, cartDrawer, wishlistBtn);
    });

    // Close the wishlist drawer when close button is clicked
    wishlistCloseBtn.addEventListener('click', () => {
        closeDrawer(wishlistDrawer, wishlistBtn);
    });
});

const langCurrBox = document.getElementById('langCurrBox');
const langCurrTrigger = document.querySelector('.lang-curr');
const dropdownArrow = langCurrTrigger.querySelector('.arrow');

// Open/Close Dropdown on Click
langCurrTrigger.addEventListener('click', (event) => {
    event.stopPropagation(); // Prevent event from bubbling up
    langCurrBox.style.display = langCurrBox.style.display === 'block' ? 'none' : 'block';
    dropdownArrow.classList.toggle('rotate'); // Toggle rotation class
});

// Close Dropdown on Outside Click
document.addEventListener('click', () => {
    langCurrBox.style.display = 'none';
    dropdownArrow.classList.remove('rotate'); // Reset rotation
});

// Prevent Dropdown from Closing When Clicking Inside It
langCurrBox.addEventListener('click', (event) => {
    event.stopPropagation();
});

// Attach listeners for currency and language selection
const currencySelect = document.getElementById("currency");
currencySelect.addEventListener("change", (event) => {
    const selectedCurrency = event.target.value;
    selectCurrency(selectedCurrency); // Ensure this function is defined in your JS
});

const languageSelect = document.getElementById('customLanguageSelect');
languageSelect.addEventListener('change', function(event) {
    selectLanguage(event.target.value); // Ensure this function is defined in your JS
});


// Handle login
document.querySelectorAll('.styled-login[href*="login"]').forEach(btn => {
    btn.addEventListener('click', () => {
        // Migrate from sessionStorage to user-specific localStorage
        const cart = getStorage(CART_BASE_KEY);
        const wishlist = getStorage(WISHLIST_BASE_KEY);

        if (!userId) {
            sessionStorage.removeItem(CART_BASE_KEY);
            sessionStorage.removeItem(WISHLIST_BASE_KEY);
        }
    });
});

// Handle logout
document.querySelectorAll('[href*="logout"]').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        // Clear user-specific storage
        if (userId) {
            localStorage.removeItem(`${CART_BASE_KEY}_${userId}`);
            localStorage.removeItem(`${WISHLIST_BASE_KEY}_${userId}`);
        }
        // Clear guest storage
        sessionStorage.removeItem(CART_BASE_KEY);
        sessionStorage.removeItem(WISHLIST_BASE_KEY);
        // Redirect to logout page
        window.location.href = btn.href;
    });
});

// Function to toggle visibility of buttons based on login status
function toggleAddToButtons(isLoggedIn) {
    const addToCartBtns = document.querySelectorAll('.add-to-cart');
    const addToWishlistBtns = document.querySelectorAll('.add-to-wishlist');

    addToCartBtns.forEach(btn => {
        btn.style.display = isLoggedIn ? 'inline-block' : 'none';
    });

    addToWishlistBtns.forEach(btn => {
        btn.style.display = isLoggedIn ? 'inline-block' : 'none';
    });
}


// Cart and Wishlist Management
const CART_KEY = 'cart_items';
const WISHLIST_KEY = 'wishlist_items';

const cartDrawer = document.getElementById('cart-drawer');
const wishlistDrawer = document.getElementById('wishlist-drawer');

// Storage Functions
const getStorage = (baseKey) => {
    const key = userId ? `${baseKey}_${userId}` : baseKey;
    const storage = userId ? localStorage : sessionStorage;
    return JSON.parse(storage.getItem(key)) || [];
};

const setStorage = (baseKey, items) => {
    const key = userId ? `${baseKey}_${userId}` : baseKey;
    const storage = userId ? localStorage : sessionStorage;
    storage.setItem(key, JSON.stringify(items));
};

// Update all instances where CART_KEY and WISHLIST_KEY are used
const CART_BASE_KEY = 'cart';
const WISHLIST_BASE_KEY = 'wishlist';

// Notification System
function showNotification(message, isError = false) {
    const notification = document.createElement('div');
    notification.className = `notification ${isError ? 'error' : ''}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Update Counters
function updateCounters() {
    document.querySelectorAll('.global-cart-counter').forEach(el => {
        el.textContent = getStorage(CART_KEY).length;
    });

    document.querySelectorAll('.global-wish-counter').forEach(el => {
        el.textContent = getStorage(WISHLIST_KEY).length;
    });
}

// Add to Cart/Wishlist Functions
function addToCart(product) {
    if (!isLoggedIn) {
        showNotification('Please login to add items to cart', true);
        setTimeout(() => window.location.href = '../../app/controllers/login.php', 5000);
        return;
    }

    if (!product.numericPrice) {
        showNotification('This product is available on request only', true);
        return;
    }

    const cart = getStorage(CART_KEY);
    const existing = cart.find(item => item.id === product.id);

    if (!existing) {
        cart.push({
            ...product,
            quantity: 1,
            addedAt: new Date().toISOString(),
            currency: getCurrentCurrency()
        });
        setStorage(CART_KEY, cart);
        updateCounters();
        showNotification('Item added to cart');
    }
    setStorage(CART_BASE_KEY, cart);
}

function getCurrentCurrency() {
    return localStorage.getItem('currency') || 'usd';
}

function addToWishlist(product) {
    if (!isLoggedIn) {
        showNotification('Please login to add items to wishlist', true);
        setTimeout(() => window.location.href = '../../app/controllers/login.php', 5000);
        return;
    }

    const wishlist = getStorage(WISHLIST_KEY);
    if (!wishlist.find(item => item.id === product.id)) {
        wishlist.push({
            ...product,
            numericPrice: product.numericPrice !== null ? product.numericPrice : null,
            isOnRequest: product.numericPrice === null
        });
        setStorage(WISHLIST_KEY, wishlist);
        updateCounters();
        showNotification('Item added to wishlist');
    }
    setStorage(WISHLIST_BASE_KEY, wishlist);
}

function convertAndFormatPrice(usdPrice) {
    if (usdPrice === null || isNaN(usdPrice)) {
        return 'ON REQUEST';
    }

    const currency = getCurrentCurrency();
    const rates = JSON.parse(localStorage.getItem('conversionRates')) || {
        USD: 1
    };
    const rate = rates[currency.toUpperCase()] || 1;

    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency.toUpperCase(),
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(usdPrice * rate);
}

// Drawer Content Rendering
function renderDrawerContent(key, drawer) {
    const items = getStorage(key);
    const content = drawer.querySelector('.cart-drawer-content');

    // Clear existing content
    while (content.children.length > 2) content.removeChild(content.lastChild);

    if (items.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'empty-message';
        empty.textContent = 'Your ' + (key === CART_KEY ? 'cart' : 'wishlist') + ' is empty';
        content.appendChild(empty);
        return;
    }

    items.forEach(item => {
        const isCart = key === CART_KEY;
        const numericValue = isCart ? item.numericPrice * (item.quantity || 1) : item.numericPrice;
        const displayPrice = convertAndFormatPrice(numericValue);

        const itemEl = document.createElement('div');
        itemEl.className = 'drawer-item';
        itemEl.dataset.itemId = item.id;


        itemEl.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="preview-image" data-preview="${item.image}">                <div class="item-info">
            <h3>${item.brand} ${item.name}</h3>
            <p>Ref: ${item.ref_code}</p>
            ${isCart ? `<div class="quantity-controls">
                <button class="qty-btn" data-action="decrease">-</button>
                <span>${item.quantity}</span>
                <button class="qty-btn" data-action="increase">+</button>
            </div>` : ''}
            <p class="price">
                ${isCart ? `${convertAndFormatPrice(item.numericPrice)} × ${item.quantity} = ` : ''}
                ${displayPrice}
            </p>
        </div>
        <button class="remove-btn">&times;</button>
    `;

        itemEl.querySelector('.price').textContent = displayPrice;

        itemEl.querySelector('.remove-btn').addEventListener('click', () => {
            const updated = getStorage(key).filter(i => i.id !== item.id);
            setStorage(key, updated);
            renderDrawerContent(key, drawer);
            updateCounters();
        });

        if (key === CART_KEY) {
            const qtyControls = itemEl.querySelector('.quantity-controls');
            qtyControls.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    const action = e.target.dataset.action;
                    const updated = getStorage(CART_KEY).map(i => {
                        if (i.id === item.id) {
                            i.quantity = action === 'increase' ? i.quantity + 1 : Math.max(1, i.quantity - 1);
                        }
                        return i;
                    });
                    setStorage(CART_KEY, updated);
                    renderDrawerContent(key, drawer);
                    updateCounters();
                }
            });
        }

        content.appendChild(itemEl);
    });

    if (key === CART_KEY) {
        const checkoutBtn = document.createElement('button');
        checkoutBtn.className = 'checkout-btn';
        checkoutBtn.textContent = 'Proceed to Checkout';
        checkoutBtn.addEventListener('click', () => {
            if (!isLoggedIn) {
                showNotification('Please login to proceed to checkout', true);
                setTimeout(() => window.location.href = '../../app/controllers/login.php', 5000);
                return;
            }
            window.location.href = '/app/views/checkout.php';
        });
        content.appendChild(checkoutBtn);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCounters();

    if (userId) {
        const cart = getStorage(CART_BASE_KEY);
        const wishlist = getStorage(WISHLIST_BASE_KEY);
    }

    // Get references to the drawers
    const cartDrawer = document.getElementById('cart-drawer');
    const wishlistDrawer = document.getElementById('wishlist-drawer');

    // Handle both desktop and mobile cart buttons
    document.querySelectorAll('#cart-btn, #mobile-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            cartDrawer.style.right = '0';
            renderDrawerContent(CART_KEY, cartDrawer);
        });
    });

    // Handle both desktop and mobile wishlist buttons
    document.querySelectorAll('#wish-btn, #mobile-wish-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            wishlistDrawer.style.right = '0';
            renderDrawerContent(WISHLIST_KEY, wishlistDrawer);
        });
    });

    // Close handlers
    document.getElementById('cart-close-btn').addEventListener('click', () => {
        cartDrawer.style.right = '-400px';
    });

    document.getElementById('wishlist-close-btn').addEventListener('click', () => {
        wishlistDrawer.style.right = '-400px';
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // Handle quantity changes through event delegation
    document.querySelector('.cart-drawer-content').addEventListener('click', function(e) {
        const button = e.target.closest('[data-action]');
        if (!button) return;

        const action = button.dataset.action;
        const itemElement = button.closest('.drawer-item');
        const itemId = itemElement.dataset.itemId;

        const cart = getStorage(CART_KEY);
        const itemIndex = cart.findIndex(item => item.id == itemId);

        if (itemIndex === -1) return;

        // Update quantity
        if (action === 'increase') {
            cart[itemIndex].quantity++;
        } else if (action === 'decrease') {
            cart[itemIndex].quantity = Math.max(1, cart[itemIndex].quantity - 1);
        }

        setStorage(CART_KEY, cart);
        renderDrawerContent(CART_KEY, cartDrawer);
        updateCounters();
    });
});

document.addEventListener('currencyChanged', () => {
    renderDrawerContent(CART_KEY, cartDrawer);
    renderDrawerContent(WISHLIST_KEY, wishlistDrawer);
});
