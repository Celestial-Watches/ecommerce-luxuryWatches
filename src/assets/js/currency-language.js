(function () {
    const STORAGE_KEY_CURRENCY_DATA = 'selectedCurrencyData';
    const STORAGE_KEY_LANGUAGE = 'selectedLanguage';
    const STORAGE_KEY_LANGUAGE_TIMESTAMP = 'languageTimestamp';
    const currencySymbols = {
        usd: '$', eur: '€', inr: '₹', gbp: '£', jpy: '¥', aud: 'A$', cny: '¥',
    };

    const SECRET_KEY = 'languagecurrencysecure_@123';

    function encryptData(data) {
        return CryptoJS.AES.encrypt(JSON.stringify(data), SECRET_KEY).toString();
    }

    function decryptData(ciphertext) {
        const bytes = CryptoJS.AES.decrypt(ciphertext, SECRET_KEY);
        return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
    }

    function isLocalStorageExpired(key) {
        const savedData = localStorage.getItem(key);
        if (!savedData) return true; // No data, assume expired

        try {
            if (key === STORAGE_KEY_LANGUAGE_TIMESTAMP) {
                const savedTimestamp = parseInt(savedData, 10);
                if (isNaN(savedTimestamp)) throw new Error('Invalid timestamp');
                return Date.now() > savedTimestamp;
            } else {
                const { expiresAt } = decryptData(savedData);
                return Date.now() > expiresAt;
            }
        } catch (error) {
            console.error(`Error parsing localStorage data for ${key}:`, error);
            return true; 
        }
    }

    function toggleLoadingIndicator(show) {
        document.getElementById('loading').style.display = show ? 'block' : 'none';
    }

    function selectCurrency(currency) {
        document.querySelector(".current-currency").textContent = currency.toUpperCase();
        updatePrices(currency);
        const currencyElement = document.getElementById("currency");
        if (currencyElement) {
            currencyElement.value = currency;
        }

        const currencyButton = document.getElementById("selectedCurrency");
        if (currencyButton) {
            const currencySymbol = currencySymbols[currency.toLowerCase()] || '$'; 
            currencyButton.innerHTML = `${currency.toUpperCase()} ${currencySymbol}`;
        }

        const currencyData = { currency, timestamp: Date.now(), expiresAt: Date.now() + 3 * 60 * 60 * 1000 };
        localStorage.setItem(STORAGE_KEY_CURRENCY_DATA, encryptData(currencyData));
    }

    function setLanguageDisplay(lang) {
        const languageNames = {
            en: 'English', es: 'Spanish', fr: 'French', de: 'German',
            it: 'Italian', pt: 'Portuguese', 'zh-CN': 'Chinese (Simplified)',
            ja: 'Japanese', ru: 'Russian', ar: 'Arabic'
        };
        return languageNames[lang] || 'English';
    }

    function selectLanguage(lang) {
        document.getElementById("selectedLanguage").textContent = setLanguageDisplay(lang);
        document.querySelector(".current-lang").textContent = lang;
        translateLanguage(lang);
        updateLanguageFlag(lang);
    }

    function updateLanguageFlag(lang) {
        const flagImg = document.getElementById("languageFlag");
        if (flagImg) {
            const flagUrls = {
                en: 'https://www.watchesworld.com/wp-content/themes/ww2/assets/images/language-flags/en.png',
                es: 'https://www.watchesworld.com/wp-content/themes/ww2/assets/images/language-flags/es.png',
                fr: 'https://www.watchesworld.com/wp-content/themes/ww2/assets/images/language-flags/fr.png',
                de: 'https://www.watchesworld.com/wp-content/themes/ww2/assets/images/language-flags/de.png',
                it: 'https://www.watchesworld.com/wp-content/themes/ww2/assets/images/language-flags/it.png',
                pt: 'https://www.worldometers.info/img/flags/small/tn_po-flag.gif',
                'zh-CN': 'https://www.worldometers.info/img/flags/small/tn_ch-flag.gif',
                ja: 'https://www.worldometers.info/img/flags/small/tn_ja-flag.gif',
                ru: 'https://www.worldometers.info/img/flags/small/tn_rs-flag.gif',
                ar: 'https://www.worldometers.info/img/flags/small/tn_sa-flag.gif',
            };
            flagImg.src = flagUrls[lang] || flagUrls['en'];
            localStorage.setItem('selectedLanguageFlag', flagImg.src); 
        }
    }

    document.addEventListener("DOMContentLoaded", async function () {
        const currencyElement = document.getElementById("currency");
        if (currencyElement) {
            const savedCurrencyData = localStorage.getItem(STORAGE_KEY_CURRENCY_DATA);
            if (savedCurrencyData && !isLocalStorageExpired(STORAGE_KEY_CURRENCY_DATA)) {
                const decryptedData = decryptData(savedCurrencyData);
                selectCurrency(decryptedData.currency);
            }

            currencyElement.addEventListener("change", (event) => {
                selectCurrency(event.target.value);
            });
        }

        const savedLanguage = localStorage.getItem(STORAGE_KEY_LANGUAGE);
        if (savedLanguage && !isLocalStorageExpired(STORAGE_KEY_LANGUAGE_TIMESTAMP)) {
            selectLanguage(savedLanguage);
        }

        const flagImg = document.getElementById("languageFlag");
        const storedFlagUrl = localStorage.getItem('selectedLanguageFlag');
        if (storedFlagUrl && flagImg) {
            flagImg.src = storedFlagUrl;
        }
        fetchUser(currency);
    });

    window.selectCurrency = selectCurrency;
    window.selectLanguage = selectLanguage;
    window.translateLanguage = translateLanguage;

    const languageSelect = document.getElementById('customLanguageSelect');
    if (languageSelect) {
        languageSelect.addEventListener('change', function (event) {
            translateLanguage(event.target.value);
        });
    }

    async function fetchConversionRates(retries = 3, delay = 1000) {
        const RATES_STORAGE_KEY = 'conversionRates';
        const RATES_TIMESTAMP_KEY = 'ratesTimestamp';
        const CACHE_DURATION = 3 * 60 * 60 * 1000; // Cache for 3 hours

        const cachedRates = localStorage.getItem(RATES_STORAGE_KEY);
        const cachedTimestamp = localStorage.getItem(RATES_TIMESTAMP_KEY);
        if (cachedRates && cachedTimestamp && (Date.now() - cachedTimestamp < CACHE_DURATION)) {
            return JSON.parse(cachedRates); // Return cached rates
        }

        const apiKey = 'e65d4909b78f3650d3bcb0d8';
        const url = `https://v6.exchangerate-api.com/v6/${apiKey}/latest/USD`;

        for (let attempt = 0; attempt < retries; attempt++) {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                const conversionRates = data.conversion_rates;

                localStorage.setItem(RATES_STORAGE_KEY, JSON.stringify(conversionRates));
                localStorage.setItem(RATES_TIMESTAMP_KEY, Date.now());
                return conversionRates;
            } catch (error) {
                if (attempt === retries - 1) {
                    console.error('Error fetching conversion rates:', error);
                    return null; // Return null if all retries fail
                }
                await new Promise(resolve => setTimeout(resolve, delay)); // Retry delay
            }
        }
    }

    async function fetchUser(Currency) {
        const savedCurrency = localStorage.getItem(STORAGE_KEY_CURRENCY_DATA);
        if (savedCurrency && !isLocalStorageExpired(STORAGE_KEY_CURRENCY_DATA)) {
            const decryptedData = decryptData(savedCurrency);
            const userCurrency = decryptedData.currency;
            document.getElementById("currency").value = userCurrency;
            updatePrices(userCurrency);
            return;
        }

        try {
            const response = await fetch("https://ipinfo.io/json?token=b850294eaa5ee7");
            const data = await response.json();
            const userCurrency = countryCurrencyMap[data.country] || 'usd';
            document.getElementById("currency").value = userCurrency;

            const currencyData = { currency: userCurrency, timestamp: Date.now(), expiresAt: Date.now() + 3 * 60 * 60 * 1000 };
            localStorage.setItem(STORAGE_KEY_CURRENCY_DATA, encryptData(currencyData));

            updatePrices(userCurrency);
        } catch (error) {
            console.error('Error fetching location data:', error);
            fallbackCurrency();
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
            const priceInUSD = product.dataset.priceInUsd;
            if (priceInUSD) {
                let priceNumber = parseFloat(priceInUSD.replace(/,/g, '').trim());
                if (isNaN(priceNumber)) return;

                const currencySymbol = currencySymbols[selectedCurrency] || currencySymbols['usd'];
                const convertedPrice = priceNumber * (conversionRates[selectedCurrency.toUpperCase()] || 1);
                const formattedPrice = convertedPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                product.innerHTML = `<span class="currency-symbol">${currencySymbol}</span> <span class="formatted-price">${formattedPrice}</span>`;
            }
        });
    }

    const countryCurrencyMap = {
        'US': 'usd', 'IN': 'inr', 'FR': 'eur', 'GB': 'gbp', 'JP': 'jpy', 'AU': 'aud', 'CN': 'cny',
    };

    async function fetchUser(Currency) {
        const savedCurrency = localStorage.getItem(STORAGE_KEY_CURRENCY_DATA);
        if (savedCurrency && !isLocalStorageExpired(STORAGE_KEY_CURRENCY_DATA)) {
            const decryptedData = decryptData(savedCurrency);
            const userCurrency = decryptedData.currency;
            document.getElementById("currency").value = userCurrency;
            updatePrices(userCurrency);
        } else {
            try {
                const response = await fetch("https://ipinfo.io/json?token=b850294eaa5ee7");
                const data = await response.json();
                const userCurrency = countryCurrencyMap[data.country] || 'usd';
                document.getElementById("currency").value = userCurrency;

                const currencyData = { currency: userCurrency, timestamp: Date.now(), expiresAt: Date.now() + 3 * 60 * 60 * 1000 };
                localStorage.setItem(STORAGE_KEY_CURRENCY_DATA, encryptData(currencyData));

                updatePrices(userCurrency);
            } catch (error) {
                console.error('Error fetching location data:', error);
                fallbackCurrency();
            }
        }
    }

    function fallbackCurrency() {
        document.getElementById("currency").value = 'usd';
        updatePrices('usd');
    }

    function translateLanguage(lang) {
        setTimeout(() => {
            const googleTranslateDropdown = document.querySelector('.goog-te-combo');
            if (googleTranslateDropdown) {
                googleTranslateDropdown.value = lang || "en";
                googleTranslateDropdown.dispatchEvent(new Event('change'));
                localStorage.setItem(STORAGE_KEY_LANGUAGE, lang);
                localStorage.setItem(STORAGE_KEY_LANGUAGE_TIMESTAMP, Date.now());
            } else {
                console.error('Google Translate dropdown not found.');
            }
        }, 100);
    }

    window.onload = function () {
        const savedLanguage = localStorage.getItem(STORAGE_KEY_LANGUAGE);
        const savedTime = localStorage.getItem(STORAGE_KEY_LANGUAGE_TIMESTAMP);
        const currentTime = Date.now();

        if (savedLanguage && savedTime && (currentTime - savedTime < 3 * 60 * 60 * 1000)) {
            document.getElementById('customLanguageSelect').value = savedLanguage;
            const googleTranslateDropdown = document.querySelector('.goog-te-combo');
            if (googleTranslateDropdown) {
                googleTranslateDropdown.value = savedLanguage;
                googleTranslateDropdown.dispatchEvent(new Event('change'));
            }
        } else {
            localStorage.removeItem(STORAGE_KEY_LANGUAGE);
            localStorage.removeItem(STORAGE_KEY_LANGUAGE_TIMESTAMP);
            document.getElementById('customLanguageSelect').value = 'en'; // Default to English
        }
    };

    function hideGoogleTranslateHeader() {
        const translateHeader = document.querySelector('.goog-te-banner-frame');
        if (translateHeader) {
            translateHeader.style.display = 'none';
        }
    }
})();