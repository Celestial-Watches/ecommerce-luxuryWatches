async function fetchConversionRates() {
    const apiKey = 'e006d55d7df672b1786abd14';
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
            const priceNumber = parseFloat(priceInUSD.replace(/,/g, '').trim()); // Parse the number
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
                product.textContent = `${currencySymbol} ${formattedPrice}`;
            } else {
                console.error('Invalid price value:', priceNumber);
            }
        } 
    });
}

// Map country codes to currency codes
const countryCurrencyMap = {
    'US': 'usd',
    'IN': 'inr',
    'FR': 'eur',
    // Add more country to currency mappings as needed
};

// Define a secret key for encryption
const SECRET_KEY = 'languagecurrencysecure_@123'; // Change this to a secure key

// Function to encrypt and decrypt data
function encryptData(data) {
    return CryptoJS.AES.encrypt(JSON.stringify(data), SECRET_KEY).toString();
}

function decryptData(ciphertext) {
    const bytes = CryptoJS.AES.decrypt(ciphertext, SECRET_KEY);
    return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
}

// Function to fetch user currency based on location
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

// Fallback function to set currency to USD
function fallbackCurrency() {
    document.getElementById("currency").value = 'usd';
    updatePrices('usd'); // Update prices based on fallback currency
}

// Wait for the DOM to be fully loaded before running the script
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

// Handle currency selection change
document.getElementById("currency").addEventListener("change", (event) => {
    const selectedCurrency = event.target.value;
    const currencyData = { currency: selectedCurrency, timestamp: Date.now() };
    localStorage.setItem('selectedCurrencyData', encryptData(currencyData));
    updatePrices(selectedCurrency); // Update prices based on selected currency
});

// Google language translation below
function translateLanguage(lang) {
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

// Hide the Google Translate header
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

function selectCurrency(currency) {
    updateCurrency(currency);
    document.getElementById('selectedCurrency').textContent = currency.toUpperCase();
    setTimeout(() => {
        location.reload(); // Reload the page to close the navbar
    }, 100); // Delay to ensure the text update is visible before reload
}

function selectLanguage(language) {
    console.log(`Selected language: ${language}`); // Debugging log
    translateLanguage(language);
    const languageMap = { 'en': 'English', 'es': 'Español', 'fr': 'Français' };
    document.getElementById('selectedLanguage').textContent = languageMap[language];
    setTimeout(() => {
        location.reload(); // Reload the page to close the navbar
    }, 100); // Delay to ensure the text update is visible before reload
}
