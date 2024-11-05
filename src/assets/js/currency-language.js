// Constructs the API URL for fetching exchange rates using an API key.
// Sends an asynchronous request to get the latest conversion rates with USD as the base currency.
// Returns the conversion rates if successful.
// Handles errors in case the API request fails, and returns null.

async function fetchConversionRates() {
    const apiKey = '223277458a876e4b0d9bffc4';
    const url = `https://v6.exchangerate-api.com/v6/${apiKey}/latest/USD`;
    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();
        return data.conversion_rates; // Return the conversion rates
    } catch (error) {
        console.error('Error fetching conversion rates:', error);
        return null; // Return null in case of error
    }
}

// Fetches conversion rates by calling fetchConversionRates.
// Retrieves all product elements with the class featured-price.
// Iterates over each product and:
// Extracts the price in USD from a data-price-in-usd attribute.
// Converts the price based on the selected currency (USD, EUR, INR).
// Updates the product price with the converted value and correct currency symbol.
// Logs errors if price values are invalid.

async function updatePrices(selectedCurrency) {
    const conversionRates = await fetchConversionRates();
    if (!conversionRates) {
        console.error("Unable to fetch conversion rates.");
        return;
    }

    const productElements = document.querySelectorAll('.featured-price');

    productElements.forEach(product => {
        const priceInUSD = product.dataset.priceInUsd; // Get the value directly
        if (priceInUSD) { // Check if priceInUSD is defined
            const originalPrice = priceInUSD.trim(); // Store the original price string
            let priceNumber = null;
            let isFromPrice = false;

            // Check if the price string starts with "FROM"
            if (originalPrice.startsWith("FROM")) {
                isFromPrice = true; // Mark that this is a "FROM" price
                // Match the number after "FROM"
                const match = originalPrice.match(/FROM\s*([0-9,]+(?:\.[0-9]{1,2})?)/i);
                if (match) {
                    priceNumber = parseFloat(match[1].replace(/,/g, '').trim()); // Convert to float
                }
            } else {
                // For prices without "FROM", extract the numeric part directly
                priceNumber = parseFloat(originalPrice.replace(/,/g, '').trim());
            }

            if (!isNaN(priceNumber)) {
                let convertedPrice;
                let currencySymbol;

                switch (selectedCurrency) {
                    case 'usd':
                        convertedPrice = priceNumber; // No conversion needed for USD
                        currencySymbol = '$';
                        break;
                    case 'eur':
                        convertedPrice = Math.round(priceNumber * conversionRates.EUR * 100) / 100;
                        currencySymbol = '€';
                        break;
                    case 'inr':
                        convertedPrice = Math.round(priceNumber * conversionRates.INR * 100) / 100;
                        currencySymbol = '₹';
                        break;
                    default:
                        convertedPrice = priceNumber; // Fallback to USD if currency is unknown
                        currencySymbol = '$';
                        break;
                }

                const formattedPrice = convertedPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                // Construct the final price display
                let finalPriceDisplay;
                if (isFromPrice) {
                    // If the price was a "FROM" price, include that in the display
                    finalPriceDisplay = `FROM <span class="currency-symbol">${currencySymbol}</span> <span class="formatted-price">${formattedPrice}</span>`;
                } else {
                    // Normal display without "FROM"
                    finalPriceDisplay = `<span class="currency-symbol">${currencySymbol}</span> <span class="formatted-price">${formattedPrice}</span>`;
                }
                
                product.innerHTML = finalPriceDisplay; // Update the product price display
            } else {
                console.error('Invalid price value:', priceNumber);
            }
        }
    });
}


// Maps country codes (e.g., 'US', 'IN', 'FR') to their respective currency codes ('usd', 'inr', 'eur').
const countryCurrencyMap = {
    'US': 'usd',
    'IN': 'inr',
    'FR': 'eur',
    // Add more country to currency mappings as needed
};

// Define a secret key for encryption
const SECRET_KEY = 'languagecurrencysecure_@123'; // Change this to a secure key

// Encrypts data (like currency information) using CryptoJS.AES.
function encryptData(data) {
    return CryptoJS.AES.encrypt(JSON.stringify(data), SECRET_KEY).toString();
}

// Decrypts the encrypted data back into its original format.

function decryptData(ciphertext) {
    const bytes = CryptoJS.AES.decrypt(ciphertext, SECRET_KEY);
    return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
}

// Tries to retrieve saved currency data from localStorage.
// If saved currency data is found:
// Decrypts and retrieves the currency.
// Updates prices based on the saved currency.
// If no saved data is found or expired:
// Fetches user location using the ipinfo.io API.
// Uses country information to determine the user’s currency.
// Saves the detected currency in localStorage (with encryption).
// Updates prices accordingly.


async function fetchUserCurrency() {
    const savedCurrency = localStorage.getItem('selectedCurrencyData');
    
    if (savedCurrency) {
        const decryptedData = decryptData(savedCurrency);
        const userCurrency = decryptedData.currency;
        document.getElementById("currency").value = userCurrency;
        updatePrices(userCurrency); // Update prices based on saved currency
    } else {
        try {
            const response = await fetch("https://ipinfo.io/json?token=b850294eaa5ee7");
            const data = await response.json();
            const userCurrency = countryCurrencyMap[data.country] || 'usd'; // Default to 'usd'

            document.getElementById("currency").value = userCurrency;
            const currencyData = { currency: userCurrency, timestamp: Date.now() };
            localStorage.setItem('selectedCurrencyData', encryptData(currencyData));

            updatePrices(userCurrency); // Update prices based on detected currency
        } catch (error) {
            console.error('Error fetching location data:', error);
            fallbackCurrency(); // Fallback to USD on error
        }
    }
}

// Sets the currency to USD if no user currency is detected or if an error occurs.
// Updates product prices based on USD.

function fallbackCurrency() {
    document.getElementById("currency").value = 'usd';
    updatePrices('usd'); // Update prices based on fallback currency
}

// Wait for the DOM to be fully loaded before running the script
// Runs when the DOM is fully loaded.
// Checks for saved currency data in localStorage.
// If valid saved data is found:
// Updates the currency and product prices.
// If no valid data is found, fetches the user’s currency using fetchUserCurrency.
// Listens for changes in the currency dropdown to update prices in real-time.

document.addEventListener("DOMContentLoaded", async function () {
    const currencyElement = document.getElementById("currency");
    const savedCurrencyData = localStorage.getItem('selectedCurrencyData');
    const currentTime = Date.now();
    const currencyExpiryHours = 3 * 60 * 60 * 1000; // 3 hours in milliseconds

    if (savedCurrencyData) {
        const decryptedData = decryptData(savedCurrencyData);
        if (decryptedData && (currentTime - decryptedData.timestamp) < currencyExpiryHours) {
            currencyElement.value = decryptedData.currency;
            updatePrices(decryptedData.currency); // Update prices based on saved currency
        } else {
            fetchUserCurrency(); // Fetch user's currency if data is invalid or expired
        }
    } else {
        fetchUserCurrency(); // Fetch user's currency if no saved data
    }
});

// Listens for user selection changes in the currency dropdown.
// Updates and saves the selected currency in localStorage.
// Calls updatePrices to adjust prices based on the selected currency.

document.getElementById("currency").addEventListener("change", (event) => {
    const selectedCurrency = event.target.value;
    const currencyData = { currency: selectedCurrency, timestamp: Date.now() };
    localStorage.setItem('selectedCurrencyData', encryptData(currencyData));
    updatePrices(selectedCurrency); // Update prices based on selected currency
});

// Handles language translation using Google Translate.
// Saves the selected language in localStorage with a timestamp.
// Checks if the language data is still valid (less than 3 hours old).
// Translates the page to the selected language.
// Provides language change functionality for the user.

function translateLanguage(lang) {
    setTimeout(() => { // Wait a moment to ensure the element is available
        const googleTranslateDropdown = document.querySelector('.goog-te-combo');
        if (googleTranslateDropdown) {
            googleTranslateDropdown.value = lang || "en"; // Default to English
            googleTranslateDropdown.dispatchEvent(new Event('change'));

            // Store the selected language in localStorage
            localStorage.setItem('selectedLanguage', lang);
            localStorage.setItem('languageTimestamp', Date.now());
        } else {
            console.error('Google Translate dropdown not found.');
        }
    }, 100); // Adjust the timeout as needed
}


window.onload = function () {
    const savedLanguage = localStorage.getItem('selectedLanguage');
    const savedTime = localStorage.getItem('languageTimestamp');
    const currentTime = Date.now();

    if (savedLanguage && savedTime && (currentTime - savedTime < 3 * 60 * 60 * 1000)) {
        document.getElementById('customLanguageSelect').value = savedLanguage;
        const googleTranslateDropdown = document.querySelector('.goog-te-combo');
        if (googleTranslateDropdown) {
            googleTranslateDropdown.value = savedLanguage;
            googleTranslateDropdown.dispatchEvent(new Event('change'));
        }
    } else {
        localStorage.removeItem('selectedLanguage');
        localStorage.removeItem('languageTimestamp');
        document.getElementById('customLanguageSelect').value = 'en'; // Default to English
    }
};

// Hides the Google Translate banner using a MutationObserver.
// Continuously monitors the DOM to ensure that the translation header is hidden.

function hideGoogleTranslateHeader() {
    const translateHeader = document.querySelector('.skiptranslate');
    if (translateHeader) {
        translateHeader.style.display = 'none';
    }
}

// Use MutationObserver to monitor changes in the DOM
const observer = new MutationObserver(() => {
    hideGoogleTranslateHeader();
});

observer.observe(document.body, { childList: true, subtree: true });

// Function to update currency based on selection
function updateCurrency(selectedCurrency) {
    const currencyData = { currency: selectedCurrency, timestamp: Date.now() };
    localStorage.setItem('selectedCurrencyData', encryptData(currencyData));
    updatePrices(selectedCurrency); // Update prices based on selected currency
}

// Adds event listeners to the currency and language selection elements.
// Updates the prices and language translation based on user interactions.
// Reloads the page with a slight delay after selection to update the UI.

// Ensure the event listeners are set up for both desktop and mobile
document.addEventListener("DOMContentLoaded", function () {
    const currencyElement = document.getElementById("currency");
    if (currencyElement) {
        currencyElement.addEventListener("change", (event) => {
            updateCurrency(event.target.value);
        });
    }
    // Initialize currency and language settings
    fetchUserCurrency();
    const savedLanguage = localStorage.getItem('selectedLanguage');
    if (savedLanguage) {
        translateLanguage(savedLanguage);
    }
});

// Handles the selection of currency or language.
// Updates the displayed currency or language on the page.
// Reloads the page to apply the changes.

function selectCurrency(currency) {
    updateCurrency(currency);
    document.getElementById('selectedCurrency').textContent = currency.toUpperCase();
    setTimeout(() => {
        location.reload(); // Reload the page to close the navbar
    }, 100); // Delay to ensure the text update is visible before reload
}

// Touch event support
const currencyLinks = document.querySelectorAll('.submenu-category a');
currencyLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default anchor click behavior
        selectCurrency(this.getAttribute('onclick').match(/'([^']+)'/)[1]); // Extract currency from onclick
    });
});

