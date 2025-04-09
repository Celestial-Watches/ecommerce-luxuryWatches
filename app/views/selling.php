<?php
session_start();
define('ALLOW_ACCESS', true);
include '../../PHP/components/navbar.php';
?>
<!DOCTYPE html>
<html>

<head>
  <title>Celestial Watches</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- IONICONS -->
  <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>


  <!-- Remix Icons / Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- JS -->
  <script src="/src/assets/js/navigation.js" async></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="/src/assets/css/deskView.css" />
  <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="/src/assets/css/google-header.css">

  <!-- FONTS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    .main-container {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
      flex-wrap: wrap;
    }

    .left-col {
      flex: 1 1 60%;
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      min-width: 300px;
    }

    .companyTitle {
      text-align: left;
      margin-bottom: 1.5rem;
      font-weight: 600;
      font-size: 1.2rem;
    }

    .options {
      display: flex;
      gap: 10px;
      margin-bottom: 2rem;
    }

    .option {
      flex: 1;
      min-height: 60px;
      border: 1px solid #000;
      cursor: pointer;
      transition: background-color 0.2s, color 0.2s;
      font-size: 16px;
      font-weight: 500;
      background-color: #fff;
      color: #000;
      text-align: center;
      padding: 10px;
    }

    .active {
      background-color: #000 !important;
      color: #fff !important;
    }

    .hidden {
      display: none;
    }

    .selection-Sellingcontainer {
      background-color: #fff;
      padding: 1rem;
      margin-bottom: 2rem;
    }

    .selection-Sellingcontainer p,
    .selection-Sellingcontainer h4 {
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .checkboxes {
      display: flex;
      gap: 10px;
    }

    .checkboxes .next-page {
      display: flex;
      align-items: center;
      width: fit-content;
      cursor: pointer;
      padding: 5px;
      transition: background-color 0.2s;
    }

    .checkboxes input[type="checkbox"] {
      margin-right: 0.5rem;
      accent-color: #000;
    }

    /* Right column: steps and contact info */
    .right-col {
      flex: 1 1 35%;
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      min-width: 250px;
    }

    .steps-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .steps-list {
      list-style: none;
      padding-left: 0;
      margin-bottom: 2rem;
    }

    .steps-list li {
      margin: 0.5rem 0;
      display: flex;
      align-items: center;
      font-size: 0.95rem;
    }

    .steps-list li i {
      color: #4CAF50;
      margin-right: 8px;
      font-size: 1.2rem;
    }

    .help-box p {
      margin: 0.5rem 0;
    }

    @media screen and (max-width: 420px) {
      option {
        font-size: 12px;
      }
    }

    @media screen and (max-width: 768px) {
      .main-container {
        flex-direction: column;
      }

      .left-col,
      .right-col {
        flex: 1 1 100%;
        margin: 0 0 20px 0;
      }
    }

    input[type="checkbox"] {
      width: auto;
    }
  </style>
</head>

<body>
  <div class="main-container">
    <!-- LEFT COLUMN -->
    <div class="left-col">
      <h3 class="companyTitle">First, do you want to sell or exchange your watch?</h3>
      <div class="options">
        <div class="option" id="exchangeOption">
          <div>
            <img alt="exchange-btn" data-src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/exchange-btn.png" class=" lazyloaded" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/exchange-btn.png">

            <img alt="exchange-btn-active" data-src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/exchange-btn-active.png" class="active lazyloaded" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/exchange-btn-active.png">
          </div>
          Exchange my watch
        </div>
        <div class="option" id="sellOption">
          <div>
            <img alt="sell-btn" data-src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/sell-btn.png" class=" lazyloaded" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/sell-btn.png">

            <img alt="sell-btn-active" data-src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/sell-btn-active.png" class="active lazyloaded" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/sell-btn-active.png">
          </div>
          Sell my watch
        </div>
      </div>

      <!-- Exchange my watch selection -->
      <div id="exchangeSelection" class="hidden selection-Sellingcontainer">
        <h4>Do you already have an account?</h4>
        <div class="checkboxes" style="flex-direction: column;">
          <div class="next-page" data-type="sing-in">
            <input type="checkbox" class="checkmark so_checkbox" id="exchange_have_account">
            <label for="exchange_have_account">Yes, I am a registered Celestial Watches customer</label>
          </div>
          <div class="next-page" data-type="register">
            <input type="checkbox" class="checkmark so_checkbox" id="exchange_no_account">
            <label for="exchange_no_account">No, I am new to Celestial Watches</label>
          </div>
        </div>
      </div>
      <!-- Sell my watch selection -->
      <div id="sellSelection" class="hidden selection-Sellingcontainer">
        <h4>Did you purchase your watch on Celestial Watches?</h4>
        <div class="checkboxes">
          <div class="next-page" data-type="sing-in">
            <input type="checkbox" class="checkmark so_checkbox" id="sell_have_account">
            <label for="sell_have_account">Yes, I did</label>
          </div>
          <div class="next-page" data-type="register">
            <input type="checkbox" class="checkmark so_checkbox" id="sell_no_account">
            <label for="sell_no_account">No, I didn't</label>
          </div>
        </div>
      </div>
    </div>
    <!-- RIGHT COLUMN -->
    <div class="right-col">
      <h4 class="steps-title">Four easy steps to sell &amp; exchange your watch</h4>
      <ul class="steps-list">
        <li><i class="ri-checkbox-circle-line"></i> Fill in the request form</li>
        <li><i class="ri-checkbox-circle-line"></i> Send us your watch</li>
        <li><i class="ri-checkbox-circle-line"></i> Watch inspection by our experts</li>
        <li><i class="ri-checkbox-circle-line"></i> Price proposal and payment to your account</li>
      </ul>
      <div class="help-box">
        <p><strong>Need help? Contact us.</strong></p>
        <p><i class="ri-phone-line"></i> +91 9909573480</p>
      </div>
    </div>
  </div>


  <?php include '../../PHP/components/footer.php'; ?>

  <script>
    let isLogIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

    const exchangeOption = document.getElementById('exchangeOption');
    const sellOption = document.getElementById('sellOption');
    const exchangeSelection = document.getElementById('exchangeSelection');
    const sellSelection = document.getElementById('sellSelection');

    document.querySelectorAll('.option img.active').forEach(img => img.classList.add('hidden'));

    exchangeOption.addEventListener('click', () => {
      if (isLogIn) {
        window.location.href = 'exchange_watch.php';
        return;
      }

      // Toggle active class
      sellOption.classList.remove('active');
      exchangeOption.classList.add('active');
      sellSelection.classList.add('hidden');
      exchangeSelection.classList.remove('hidden');

      // Toggle images
      toggleImages(exchangeOption);
      toggleImages(sellOption, false);
    });

    sellOption.addEventListener('click', () => {
      // Toggle active class
      exchangeOption.classList.remove('active');
      sellOption.classList.add('active');
      exchangeSelection.classList.add('hidden');
      sellSelection.classList.remove('hidden');

      // Toggle images
      toggleImages(sellOption);
      toggleImages(exchangeOption, false);
    });

    // Function to toggle images
    function toggleImages(option, showActive = true) {
      const lazyImg = option.querySelector('img.lazyloaded');
      const activeImg = option.querySelector('img.active');

      if (lazyImg && activeImg) {
        if (showActive) {
          lazyImg.classList.add('hidden');
          activeImg.classList.remove('hidden');
        } else {
          lazyImg.classList.remove('hidden');
          activeImg.classList.add('hidden');
        }
      }
    }

    // Handle Exchange selection clicks
    document.querySelectorAll('#exchangeSelection .next-page').forEach((element) => {
      element.addEventListener('click', () => {
        const type = element.getAttribute('data-type');
        if (!isLogIn) {
          if (type === 'sing-in') {
            window.location.href = '/app/controllers/login.php';
          } else {
            window.location.href = '/app/controllers/signin.php';
          }
        } else {
          window.location.href = 'exchange_watch.php';
        }
      });
    });

    // Handle Sell selection clicks
    document.querySelectorAll('#sellSelection .next-page').forEach((element) => {
      element.addEventListener('click', () => {
        if (!isLogIn) {
          window.location.href = '/app/controllers/login.php';
        } else {
          window.location.href = 'sell_watch.php';
        }
      });
    });
  </script>

  <!-- JS files -->
  <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
  <script src="/src/assets/js/index.js" async></script>
  <script src="/src/assets/js/currency-language.js" async></script>
  <script src="/src/assets/js/cookie-monitor.js" async></script>
  <script src="/src/assets/js/imagePreview.js" async></script>
</body>

</html>