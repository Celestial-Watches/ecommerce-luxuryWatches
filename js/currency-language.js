// Define the fixed conversion rate globally
const conversionRate = 0.85; // Example: 1 USD = 0.85 EUR

// Wait for the DOM to be fully loaded before running the script
document.addEventListener("DOMContentLoaded", function() {
    const currencyElement = document.getElementById("currency");

    // If the currency element exists on the page
    if (currencyElement) {
        const savedCurrencyData = JSON.parse(localStorage.getItem('selectedCurrencyData'));
        const currentTime = new Date().getTime();
        const currencyExpiryHours = 3 * 60 * 60 * 1000; // 3 hours in milliseconds

        if (savedCurrencyData && (currentTime - savedCurrencyData.timestamp) < currencyExpiryHours) {
            // If stored currency is still valid, use it
            currencyElement.value = savedCurrencyData.currency;
        } else {
            // Otherwise, default to USD and remove expired data
            localStorage.removeItem('selectedCurrencyData');
            currencyElement.value = 'usd';
        }

        updatePrices(); // Update prices based on saved currency
    } else {
        console.error('Currency element with id="currency" not found.');
    }
});

function updatePrices() {
    const selectedCurrency = document.getElementById("currency") ? document.getElementById("currency").value : 'usd';
    const productElements = document.querySelectorAll('.featured-price');

    productElements.forEach(product => {
        const priceInUSD = product.dataset.priceInUsd;

        if (priceInUSD) {
            const priceValue = parseFloat(priceInUSD.replace(/,/g, ''));

            if (!isNaN(priceValue)) {
                let convertedPrice;
                let currencySymbol;

                if (selectedCurrency === 'usd') {
                    convertedPrice = priceValue;
                    currencySymbol = '$';
                } else {
                    // Use the globally defined conversion rate
                    convertedPrice = Math.round((priceValue * conversionRate) * 100) / 100;
                    currencySymbol = '€';
                }

                // Check if the price text includes "FROM"
                if (product.textContent.includes("FROM")) {
                    // If the card contains "FROM", keep it and update the price
                    product.innerHTML = `FROM ${currencySymbol} ${convertedPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
                } else {
                    // Regular price for other cards
                    product.textContent = `${currencySymbol} ${convertedPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
                }
            }
        }
    });
}

// Add event listener for currency change
const currencyDropdown = document.getElementById("currency");
if (currencyDropdown) {
    currencyDropdown.addEventListener("change", function() {
        // Save the selected currency with a timestamp
        const selectedCurrency = currencyDropdown.value;
        const currencyData = {
            currency: selectedCurrency,
            timestamp: new Date().getTime()
        };
        localStorage.setItem('selectedCurrencyData', JSON.stringify(currencyData));

        updatePrices(); // Update prices whenever the currency is changed
    });
}
