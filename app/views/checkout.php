<?php
session_start();
define('ALLOW_ACCESS', true);
if (!isset($_SESSION['user_id'])) {
  header('Location: /index.php');
  exit;
}
define('CART_KEY', 'cart_items');

include '../config/conn.php';

$userId = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$query = "SELECT username, email, phone FROM users WHERE id = '$userId'";
$result = mysqli_query($conn, $query);
$userData = mysqli_fetch_assoc($result);

$defaultAddress = null;
$addressQuery = "SELECT * FROM address WHERE user_id = '$userId' LIMIT 1";
$addressResult = mysqli_query($conn, $addressQuery);
if ($addressResult && mysqli_num_rows($addressResult) > 0) {
  $defaultAddress = mysqli_fetch_assoc($addressResult);
}

// Check if net banking OTP verification was successful
$netbankVerified = isset($_SESSION['netbank_otp_verified']) && $_SESSION['netbank_otp_verified'] === true;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Luxury Watches | Checkout</title>
  <!-- IONICONS -->
  <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js" integrity="sha512-a+SUDuwNzXDvz4XrIcXHuCf089/iJAoN4lmrXJg18XnduKK6YlDHNRalv4yd1N40OKI80tFidF+rqTFKGPoWFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- JS Libraries -->
  <script src="/src/assets/js/navigation.js" async></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="/src/assets/css/deskView.css" />
  <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="/src/assets/css/google-header.css">
  <!-- FONTS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #1a1a1a;
      --accent-color: #ff6666;
      --light-bg: #f8f8f8;
      --tooltip-bg: #333;
      --tooltip-color: #fff;
    }

    #empty-cart {
      width: 100%;
      min-height: 60vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      padding: 20px;
    }

    .saved-address-container {
      margin-bottom: 1.5rem;
    }

    .saved-address-btn {
      background: none;
      border: 2px solid var(--accent-color);
      color: var(--accent-color);
      padding: 0.8rem 1.5rem;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .saved-address-btn:hover {
      background-color: var(--accent-color);
      color: white;
    }

    .saved-address-btn i {
      font-size: 1.1rem;
    }

    /* Container with Left Nav, Center Form & Right Summary */
    .checkout-wrapper {
      display: grid;
      grid-template-columns: 250px 1fr 300px;
      gap: 2rem;
      max-width: 1400px;
      margin: 2rem auto;
      padding: 2rem;
    }

    /* Left Navigation Panel */
    .checkout-nav {
      background: var(--light-bg);
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 20px;
    }

    .checkout-nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .checkout-nav li {
      padding: 1rem;
      margin-bottom: 1rem;
      border-left: 4px solid transparent;
      cursor: pointer;
      position: relative;
      transition: background 0.3s, border-color 0.3s;
    }

    .checkout-nav li:hover {
      background: #eee;
    }

    .checkout-nav li.active {
      border-color: var(--accent-color);
      background: #f0f0f0;
    }

    .checkout-nav li .tooltip {
      position: absolute;
      left: 105%;
      top: 50%;
      transform: translateY(-50%);
      background: var(--tooltip-bg);
      color: var(--tooltip-color);
      padding: 0.5rem;
      border-radius: 4px;
      font-size: 0.8rem;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s;
      z-index: 10;
    }

    .checkout-nav li:hover .tooltip {
      opacity: 1;
      visibility: visible;
    }

    /* Center Multi–Step Form */
    .checkout-content {
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .step {
      display: none;
      animation: fadeIn 0.5s ease-in-out;
    }

    .step.active {
      display: block;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .form-group {
      margin-bottom: 1.5rem;
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 0.8rem;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 1rem;
    }

    .form-group input[type='checkbox'] {
      width: auto;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
    }

    .form-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 2rem;
    }

    .btn {
      background: var(--accent-color);
      color: white;
      padding: 1rem 2rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      transition: opacity 0.3s;
    }

    .btn:hover {
      opacity: 0.9;
    }

    /* Right Order Summary Panel */
    .order-summary {
      background: var(--light-bg);
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 20px;
    }

    .order-summary h2 {
      margin-top: 0;
    }

    .checkout-item {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      border-bottom: 1px solid #eee;
      padding-bottom: 1rem;
    }

    .checkout-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 4px;
    }

    .items-details h3 {
      margin: 0;
      font-size: 16px;
    }

    .items-details p {
      margin: 0.2rem 0;
      font-size: 14px;
      color: #777;
    }

    .total-summary {
      margin-top: 1.5rem;
      font-size: 16px;
    }

    .total-summary div {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }

    @media (max-width: 1024px) {
      .checkout-wrapper {
        grid-template-columns: 1fr;
      }

      .checkout-nav,
      .order-summary {
        position: relative;
        top: 0;
      }
    }
  </style>
</head>

<body>
  <?php include '../../PHP/components/navbar.php'; ?>

  <!-- Loading Spinner -->
  <div id="loading-spinner" style="display: none;">
    <div class="spinner"></div>
  </div>

  <style>
    #loading-spinner {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .spinner {
      border: 8px solid #f3f3f3;
      border-top: 8px solid #007bff;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  <div class="checkout-container">
    <!-- This container will show the empty cart message if no items exist -->
    <div id="empty-cart" style="display: none;">
      <h1>No Products in Your Cart</h1>
      <p>Your cart is empty. Please <a href="productLanding.php">go to the shop page</a> to add products.</p>
    </div>
    <div class="checkout-wrapper" id="checkout-wrapper">
      <!-- Left Navigation Panel -->
      <nav class="checkout-nav">
        <ul>
          <li data-step="1" class="active">
            1. Shipping Information
            <span class="tooltip">Enter your name, address &amp; contact details.</span>
          </li>
          <li data-step="2">
            2. Billing &amp; Payment
            <span class="tooltip">Provide billing info &amp; choose payment method.</span>
          </li>
          <li data-step="3">
            3. Order Review &amp; Discounts
            <span class="tooltip">Apply promo codes &amp; add order notes.</span>
          </li>
          <li data-step="4">
            4. Confirmation &amp; Completion
            <span class="tooltip">Final confirmation &amp; tracking details.</span>
          </li>
        </ul>
      </nav>
      <!-- Center Multi–Step Form -->
      <div class="checkout-content">
        <?php if ($netbankVerified): ?>
          <div class="alert alert-success">
            Net Banking OTP verified successfully. You can now apply coupons or write notes.
          </div>
        <?php endif; ?>
        <!-- Note the novalidate attribute -->
        <form id="multiStepForm" novalidate>
          <!-- Step 1: Shipping Information -->
          <div class="step active" id="step-1">
            <h2 style="margin-bottom: 10px;">Shipping Information</h2>
            <div class="form-grid">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input style="text-transform: uppercase;" type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($userData['username']) ?>" required>
              </div>
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input style="text-transform: uppercase;" type="text" id="last_name" name="last_name" required>
              </div>
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="+1234567890" value="<?= htmlspecialchars($userData['phone']) ?>" required>
            </div>
            <div class="form-group">
              <label for="company">Company Name (optional)</label>
              <input type="text" id="company" name="company" placeholder="Your company name">
            </div>
            <?php if ($defaultAddress): ?>
              <div class="saved-address-container">
                <button type="button" class="saved-address-btn" id="useSavedAddress">
                  <i class="fas fa-map-marker-alt"></i>
                  Use Saved Address
                </button>
              </div>
            <?php endif; ?>
            <!-- Dynamic Location Fields: Country, State, City -->
            <div class="form-group">
              <label for="country">Country</label>
              <!-- Hard-coded options (make sure these match your saved data) -->
              <select id="country" name="country" required>
                <option value="">Select Country</option>
                <option value="United States" data-code="US">United States</option>
                <option value="United Kingdom" data-code="GB">United Kingdom</option>
                <option value="Switzerland" data-code="CH">Switzerland</option>
                <option value="India" data-code="IN">India</option>
              </select>
            </div>
            <div class="form-group">
              <label for="state">State</label>
              <select id="state" name="state" required disabled>
                <option value="">Select State</option>
              </select>
            </div>
            <div class="form-group">
              <label for="city">City</label>
              <select id="city" name="city" required disabled>
                <option value="">Select City</option>
              </select>
            </div>
            <div class="form-group">
              <label for="address">Street Address</label>
              <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
              <label for="zip">ZIP Code</label>
              <input type="text" id="zip" name="zip" required>
            </div>
            <div class="form-group" style="flex-direction: row; align-items: center !important; gap: 0.5rem;">
              <input type="checkbox" id="save_address" name="save_address">
              <label for="save_address" style="margin-bottom: 0;">Save this address for future purchases</label>
            </div>
            <div class="form-actions">
              <div></div>
              <button type="button" class="btn" id="next-1">Next</button>
            </div>
          </div>
          <!-- Step 2: Billing & Payment -->
          <div class="step" id="step-2">
            <h2 style="margin-bottom: 10px;">Billing &amp; Payment</h2>
            <div class="form-group" style="flex-direction: row; align-items: center !important; gap: 0.5rem;">
              <input type="checkbox" id="same_as_shipping" name="same_as_shipping" checked>
              <label for="same_as_shipping" style="margin-bottom: 0;">Billing address same as shipping</label>
            </div>
            <div id="billing-section" style="display: none;">
              <div class="form-group">
                <label for="billing_address">Billing Street Address</label>
                <input type="text" id="billing_address" name="billing_address">
              </div>
              <div class="form-grid">
                <div class="form-group">
                  <label for="billing_city">Billing City</label>
                  <input type="text" id="billing_city" name="billing_city">
                </div>
                <div class="form-group">
                  <label for="billing_zip">Billing ZIP Code</label>
                  <input type="text" id="billing_zip" name="billing_zip">
                </div>
              </div>
              <div class="form-group">
                <label for="billing_country">Billing Country</label>
                <select id="billing_country" name="billing_country">
                  <option value="">Select Country</option>
                  <option value="United States">United States</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="India">India</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="payment_method">Payment Method</label>
              <!-- Only Credit/Debit Card is available now -->
              <select id="payment_method" name="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="credit_card">Credit/Debit Card</option>
                <option value="net_banking">Net Banking</option>
              </select>
            </div>


            <!-- Credit/Debit Card Fields -->
            <div id="credit_card_fields" style="display: none;">
              <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" placeholder="4242 4242 4242 4242">
              </div>
              <div class="form-grid">
                <div class="form-group">
                  <label for="exp_date">Expiration Date</label>
                  <input type="month" id="exp_date" name="exp_date">
                </div>
                <div class="form-group">
                  <label for="cvc">CVC</label>
                  <input type="text" maxlength="3" id="cvc" name="cvc" placeholder="123">
                </div>
              </div>
            </div>

            <!-- Net Banking Fields -->

            <div id="net_banking_fields" style="display: none;">
              <input type="hidden" id="netbank_otp_verified" name="netbank_otp_verified" value="<?= $netbankVerified ? '1' : '0' ?>">
              <div class="form-group">
                <label for="net_bank">Select Bank</label>
                <select id="net_bank" name="net_bank" required>
                  <option value="">Select Bank</option>
                  <option value="HDFC Bank - Mumbai">HDFC Bank - Mumbai</option>
                  <option value="ICICI Bank - Delhi">ICICI Bank - Delhi</option>
                  <option value="SBI Bank - Bangalore">SBI Bank - Bangalore</option>
                  <option value="Axis Bank - Kolkata">Axis Bank - Kolkata</option>
                </select>
              </div>
              <div class="form-group">
                <label for="net_user">User ID</label>
                <input type="text" id="net_user" name="net_user" placeholder="Enter your net banking user ID" required>
              </div>
              <div class="form-group">
                <label for="net_pass">Password</label>
                <input type="password" id="net_pass" name="net_pass" placeholder="Enter your net banking password" required>
              </div>
              <div class="form-group">
                <label for="net_email">Registered Email</label>
                <input type="email" id="net_email" name="net_email" placeholder="your-email@example.com" required>
              </div>
              <div class="form-actions">
                <button type="button" class="btn" id="send_netbank_otp">Proceed to OTP</button>
              </div>
              <!-- OTP Section for Net Banking -->
              <div id="net_banking_otp_section" style="display: none; margin-top: 10px;">
                <div class="form-group">
                  <label for="net_otp">Enter OTP</label>
                  <input type="text" id="net_otp" name="net_otp" placeholder="Enter OTP" required>
                </div>
                <div class="form-actions">
                  <button type="button" class="btn" id="verify_netbank_otp">Verify & Confirm Payment</button>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" class="btn" id="back-2">Back</button>
              <button type="button" class="btn" id="next-2">Next</button>
            </div>
          </div>
          <!-- Step 3: Order Review & Discounts -->
          <div class="step" id="step-3">
            <h2 style="margin-bottom: 10px;">Order Review &amp; Discounts</h2>

            <div class="form-group">
              <label for="promo_code">Promo Code / Gift Card</label>
              <div style="display: flex; gap: 1rem;">
                <input type="text" id="promo_code" name="promo_code" placeholder="Enter promo code">
                <button type="button" class="btn" id="applyCoupon">Apply</button>
              </div>
            </div>

            <!-- New Offers Field -->
            <div class="form-group view-offers" style="margin-top: 1rem;">
              <label for="offersList">View Offers</label>
              <ul id="offersList" style="list-style: none; padding: 0; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; margin-top: 0.5rem;"></ul>
            </div>

            <div class="form-group">
              <label for="order_notes">Order Notes (optional)</label>
              <textarea id="order_notes" name="order_notes" rows="4" placeholder="Any special instructions for your order"></textarea>
            </div>
            <div class="form-actions">
              <button type="button" class="btn" id="back-3">Back</button>
              <button type="button" class="btn" id="next-3">Next</button>
            </div>
          </div>

          <!-- Step 4: Confirmation & Completion -->
          <div class="step" id="step-4">
            <h2 style="margin-bottom: 10px;">Confirmation &amp; Completion</h2>
            <p>Please review your final order details. Estimated delivery time and tracking options will be provided once the order is confirmed.</p>
            <div class="form-actions">
              <button type="button" class="btn" id="back-4">Back</button>
              <button type="submit" class="btn">Complete Purchase</button>
            </div>
          </div>
        </form>
      </div>
      <!-- Right Order Summary Panel -->
      <aside class="order-summary">
        <h2 style="margin-bottom: 10px;">Your Order</h2>
        <div id="checkoutItems"></div>
        <div class="total-summary">
          <div style="display: flex; justify-content: space-between;">
            <span>Subtotal:</span>
            <span id="checkoutSubtotal"></span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span>Discount:</span>
            <span id="checkoutDiscount">-0</span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span>Shipping:</span>
            <span id="checkoutShipping"></span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span>Tax:</span>
            <span id="checkoutTax"></span>
          </div>
          <hr>
          <div style="display: flex; justify-content: space-between; font-weight: bold;">
            <span>Total:</span>
            <span id="checkoutTotal"></span>
          </div>
        </div>
      </aside>
    </div>
  </div>


  <?php include '../../PHP/components/footer.php'; ?>

  <script src="/src/assets/js/currency-language.js"></script>
  <script>
    function fetchOffersByPaymentMethod(paymentMethod) {
      const url = '/app/models/get_offers.php?paymentMethod=' + encodeURIComponent(paymentMethod);
      fetch(url)
        .then(response => response.json())
        .then(data => {
          const offersList = document.getElementById('offersList');
          offersList.innerHTML = '';

          if (data && Array.isArray(data) && data.length > 0) {
            data.forEach(offer => {
              const li = document.createElement('li');
              li.style.padding = '0.5rem';
              li.style.borderBottom = '1px solid #ddd';
              li.textContent = `${offer.discount_name} - ${offer.discount_percentage}% off`;
              offersList.appendChild(li);
            });
          } else {
            offersList.innerHTML = '<li style="padding: 0.5rem;">No offers available.</li>';
          }
        })
        .catch(error => {
          console.error('Error fetching offers:', error);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const paymentSelect = document.getElementById('payment_method');
      if (paymentSelect) {
        fetchOffersByPaymentMethod(paymentSelect.value);
        paymentSelect.addEventListener('change', function() {
          fetchOffersByPaymentMethod(this.value);
        });
      }
    });


    document.addEventListener('DOMContentLoaded', () => {

      /**********************
       * Global Variables for Tax & Shipping
       **********************/
      let currentTaxRate = 0;
      let currentShippingCost = 0;
      let isUsingSavedAddress = false;
      window.savedAddress = <?php echo json_encode($defaultAddress); ?>;

      /**********************
       * Helper Functions
       **********************/

      // Convert USD to current currency without formatting
      function convertUsdToCurrent(usdAmount) {
        return usdAmount * getCurrentRate();
      }

      // Recalculate and update all totals and displayed prices
      function updateTotal() {
        const cartItems = getStorage('<?= CART_KEY ?>') || [];
        const subtotalUSD = cartItems.reduce((acc, item) => acc + (item.numericPrice * item.quantity), 0);
        const discountUSD = parseFloat(document.getElementById('checkoutDiscount').dataset.usd || 0);
        const shippingUSD = currentShippingCost;
        const taxUSD = subtotalUSD * currentTaxRate;
        const totalConverted = subtotalUSD - discountUSD + shippingUSD + taxUSD;

        // Update displayed values with formatted prices
        document.getElementById('checkoutSubtotal').textContent = convertAndFormatPrice(subtotalUSD);
        document.getElementById('checkoutDiscount').textContent = `-${convertAndFormatPrice(discountUSD)}`;
        document.getElementById('checkoutShipping').textContent = convertAndFormatPrice(shippingUSD);
        document.getElementById('checkoutTax').textContent = convertAndFormatPrice(taxUSD);
        document.getElementById('checkoutTotal').textContent = convertAndFormatPrice(totalConverted);
      }



      // Enhanced address handling with async/await
      async function applySavedAddress() {
        if (!savedAddress) return;

        isUsingSavedAddress = true;
        const countrySelect = document.getElementById('country');
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');

        // Set country and wait for states to load
        countrySelect.value = savedAddress.country;
        await new Promise(resolve => {
          countrySelect.dispatchEvent(new Event('change'));
          setTimeout(resolve, 1000); // Allow time for API response
        });

        // Set state and wait for cities to load
        if (savedAddress.state) {
          stateSelect.value = savedAddress.state;
          await new Promise(resolve => {
            stateSelect.dispatchEvent(new Event('change'));
            setTimeout(resolve, 1000); // Allow time for API response
          });
        }

        // Set city
        if (savedAddress.city) {
          citySelect.value = savedAddress.city;
        }

        // Set remaining fields
        document.getElementById('address').value = savedAddress.address1 +
          (savedAddress.address2 ? ', ' + savedAddress.address2 : '');
        document.getElementById('zip').value = savedAddress.zip;

        // Temporarily disable zip code lookup
        setTimeout(() => {
          isUsingSavedAddress = false;
        }, 3000);
      }


      /**********************
       * Save/Load Form Data (with Summary)
       **********************/
      function saveFormData() {
        const formData = {};
        document.querySelectorAll('#multiStepForm input, #multiStepForm select, #multiStepForm textarea').forEach(el => {
          formData[el.id] = (el.type === 'checkbox') ? el.checked : el.value;
        });
        const activeStep = document.querySelector('.step.active');
        if (activeStep) {
          formData.currentStep = activeStep.id.split('-')[1];
        }
        // Save current summary values
        formData.discount = parseFloat(document.getElementById('checkoutDiscount').textContent.replace(/[^0-9.-]+/g, "")) || 0;
        formData.shipping = parseFloat(document.getElementById('checkoutShipping').textContent.replace(/[^0-9.-]+/g, "")) || 0;
        formData.tax = parseFloat(document.getElementById('checkoutTax').textContent.replace(/[^0-9.-]+/g, "")) || 0;
        localStorage.setItem('checkoutFormData', JSON.stringify(formData));
      }

      function loadFormData() {
        const storedData = localStorage.getItem('checkoutFormData');
        if (storedData) {
          try {
            const formData = JSON.parse(storedData);
            document.querySelectorAll('#multiStepForm input, #multiStepForm select, #multiStepForm textarea')
              .forEach(el => {
                if (el.type === 'checkbox') {
                  el.checked = (formData[el.id] !== undefined) ? formData[el.id] : false;
                } else {
                  el.value = (formData[el.id] !== undefined) ? formData[el.id] : '';
                }
              });

            // Restore active step if saved
            if (formData.currentStep) {
              showStep(formData.currentStep);
            }

            if (formData.discount !== undefined) {
              document.getElementById('checkoutDiscount').textContent = '-' + convertAndFormatPrice(formData.discount);
            }
            // Instead of restoring shipping/tax values directly, check if a country is selected.
            if (document.getElementById('country').value) {
              // Recalculate shipping/tax based on country selection.
              const selectedOption = document.getElementById('country').options[document.getElementById('country').selectedIndex];
              const countryCode = selectedOption.getAttribute('data-code');
              if (countryCode) {
                calculateShippingAndTax(countryCode);
              } else {
                currentShippingCost = 0;
                currentTaxRate = 0;
                document.getElementById('checkoutShipping').textContent = convertAndFormatPrice(0);
                document.getElementById('checkoutTax').textContent = convertAndFormatPrice(0);
              }
            } else {
              // No country selected, force shipping and tax to 0.
              currentShippingCost = 0;
              currentTaxRate = 0;
              document.getElementById('checkoutShipping').textContent = convertAndFormatPrice(0);
              document.getElementById('checkoutTax').textContent = convertAndFormatPrice(0);
            }
            updateTotal();

          } catch (e) {
            console.error("Error loading form data", e);
            localStorage.removeItem('checkoutFormData');
          }
        }
      }

      /**********************
       * Luhn Algorithm & Expiry Check
       **********************/
      function luhnCheck(cardNumber) {
        let sum = 0,
          shouldDouble = false;
        for (let i = cardNumber.length - 1; i >= 0; i--) {
          let digit = parseInt(cardNumber.charAt(i), 10);
          if (shouldDouble) {
            digit *= 2;
            if (digit > 9) digit -= 9;
          }
          sum += digit;
          shouldDouble = !shouldDouble;
        }
        return sum % 10 === 0;
      }

      function isValidExpiry(expDate) {
        if (!expDate) return false;
        let [year, month] = expDate.split('-').map(Number);
        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = now.getMonth() + 1;
        if (year < currentYear) return false;
        if (year === currentYear && month < currentMonth) return false;
        return true;
      }

      /**********************
       * Form Navigation
       **********************/
      let maxStepAllowed = 1;

      function validateStep(stepNumber) {
        const step = document.getElementById('step-' + stepNumber);
        // Only validate elements that are visible (and not disabled)
        const requiredElements = Array.from(step.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)'));
        let valid = true;
        requiredElements.forEach(input => {
          // Check if the field is visible
          if (input.offsetParent !== null && !input.checkValidity()) {
            input.reportValidity();
            valid = false;
          }
        });
        return valid;
      }

      function showStep(stepNumber) {
        document.querySelectorAll('.step').forEach(step => step.classList.remove('active'));
        document.getElementById('step-' + stepNumber).classList.add('active');
        document.querySelectorAll('.checkout-nav li').forEach(item => {
          item.classList.toggle('active', item.getAttribute('data-step') === stepNumber.toString());
        });
        saveFormData();
      }
      document.querySelectorAll('.checkout-nav li').forEach(item => {
        item.addEventListener('click', () => {
          const step = parseInt(item.getAttribute('data-step'));
          if (step <= maxStepAllowed) {
            showStep(step);
          } else {
            showNotification('Please complete previous steps first.');
          }
        });
      });
      document.getElementById('next-1').addEventListener('click', () => {
        if (!validateStep(1)) return;
        maxStepAllowed = Math.max(maxStepAllowed, 2);
        showStep(2);
      });
      document.getElementById('back-2').addEventListener('click', () => showStep(1));
      document.getElementById('next-2').addEventListener('click', () => {
        if (!validateStep(2)) return;

        const paymentMethod = document.getElementById('payment_method').value;
        if (!paymentMethod) {
          showNotification('Please select a payment method.', true);
          return;
        }

        // Validate credit card fields if selected
        if (paymentMethod === 'credit_card') {
          const cardNumber = document.getElementById('card_number').value.replace(/\s+/g, '');
          const expDate = document.getElementById('exp_date').value;
          const cvc = document.getElementById('cvc').value.trim();
          if (cardNumber.length !== 16 || !luhnCheck(cardNumber)) {
            showNotification('Invalid credit card number.', true);
            return;
          }
          if (!isValidExpiry(expDate)) {
            showNotification('Credit card is expired or expiry date is invalid.', true);
            return;
          }
          if (!/^\d{3}$/.test(cvc)) {
            showNotification('CVC must be 3 digits.', true);
            return;
          }
        }

        // Validate net banking fields if selected
        if (paymentMethod === 'net_banking') {
          const netUser = document.getElementById('net_user').value.trim();
          const netPass = document.getElementById('net_pass').value.trim();
          const netEmail = document.getElementById('net_email').value.trim();
          if (!netUser || !netPass || !netEmail) {
            showNotification("Please fill in all net banking fields.", true);
            return;
          }
        }

        // Proceed to step 3
        maxStepAllowed = Math.max(maxStepAllowed, 3);
        showStep(3);
      });
      document.getElementById('back-3').addEventListener('click', () => showStep(2));
      document.getElementById('next-3').addEventListener('click', () => {
        if (!validateStep(3)) return;
        maxStepAllowed = Math.max(maxStepAllowed, 4);
        showStep(4);
      });
      document.getElementById('back-4').addEventListener('click', () => showStep(3));

      /**********************
       * Toggle Fields
       **********************/
      const sameAsShipping = document.getElementById('same_as_shipping');
      const billingSection = document.getElementById('billing-section');
      sameAsShipping.addEventListener('change', () => {
        billingSection.style.display = sameAsShipping.checked ? 'none' : 'block';
        saveFormData();
      });
      const paymentMethod = document.getElementById('payment_method');
      // Toggle payment fields based on selection
      document.getElementById('payment_method').addEventListener('change', function() {
        const method = this.value;
        const creditCardFields = document.getElementById('credit_card_fields');
        const netBankingFields = document.getElementById('net_banking_fields');

        if (method === 'credit_card') {
          creditCardFields.style.display = 'block';
          netBankingFields.style.display = 'none';
          // Disable net banking required fields
          netBankingFields.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = true;
          });
          // Ensure credit card fields are enabled
          creditCardFields.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = false;
          });
        } else if (method === 'net_banking') {
          creditCardFields.style.display = 'none';
          netBankingFields.style.display = 'block';
          // Disable credit card required fields
          creditCardFields.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = true;
          });
          // Ensure net banking fields are enabled
          netBankingFields.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = false;
          });
        } else {
          creditCardFields.style.display = 'none';
          netBankingFields.style.display = 'none';
        }
        saveFormData();
      });


      // When "Proceed to OTP" is clicked for net banking
      document.getElementById('send_netbank_otp').addEventListener('click', function() {
        // Validate net banking fields (simple example)
        const bank = document.getElementById('net_bank').value;
        const netUser = document.getElementById('net_user').value.trim();
        const netPass = document.getElementById('net_pass').value.trim();
        const netEmail = document.getElementById('net_email').value.trim();
        if (!bank || !netUser || !netPass || !netEmail) {
          showNotification("Please fill in all net banking fields.");
          return;
        }

        // Show loading or processing message
        document.getElementById('net_banking_otp_section').style.display = 'none';

        // Send net banking details to PHP script to simulate sending OTP
        $.ajax({
          url: "/app/controllers/send_netbank_otp.php",
          type: "POST",
          data: {
            net_bank: bank,
            net_user: netUser,
            net_pass: netPass,
            net_email: netEmail
          },
          success: function(response) {
            if (response.trim() === "OTP_SENT") {
              document.getElementById('net_banking_otp_section').style.display = 'block';
              showNotification("An OTP has been sent to your registered email.");
            } else {
              showNotification("Error: " + response);
            }
          },
          error: function() {
            showNotification("An error occurred while sending OTP.");
          }
        });
      });

      // When "Verify & Confirm Payment" is clicked for net banking
      document.getElementById('verify_netbank_otp').addEventListener('click', function() {
        const netOtp = document.getElementById('net_otp').value.trim();
        if (!netOtp) {
          showNotification("Please enter the OTP.");
          return;
        }
        $.ajax({
          url: "/app/controllers/verify_netbank_otp.php",
          type: "POST",
          data: {
            net_otp: netOtp
          },
          success: function(response) {
            if (response.trim() === "OTP_VERIFIED") {
              showNotification("OTP verified successfully!");
              document.getElementById('netbank_otp_verified').value = '1';
              document.getElementById('net_banking_otp_section').style.display = 'none';
              // Enable proceeding to next step
              maxStepAllowed = Math.max(maxStepAllowed, 3);
            } else {
              showNotification("Invalid OTP. Please try again.");
            }
          },
          error: function() {
            showNotification("An error occurred while verifying OTP.");
          }
        });
      });

      /**********************
       * Dynamic Location API Integration
       **********************/
      const countrySelect = document.getElementById('country');
      const stateSelect = document.getElementById('state');
      const citySelect = document.getElementById('city');
      const zipInput = document.getElementById('zip');

      countrySelect.addEventListener('change', () => {
        const countryName = countrySelect.value;
        stateSelect.innerHTML = '<option value="">Select State</option>';
        stateSelect.disabled = true;
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;
        if (!countryName) return;
        fetch('https://countriesnow.space/api/v0.1/countries/states', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              country: countryName
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.error) {
              showNotification('States not found for ' + countryName, true);
            } else {
              data.data.states.forEach(state => {
                const option = document.createElement('option');
                option.value = state.name;
                option.textContent = state.name;
                stateSelect.appendChild(option);
              });
              stateSelect.disabled = false;
            }
          })
          .catch(err => {
            console.error(err);
            showNotification('Error fetching states.', true);
          });
      });

      stateSelect.addEventListener('change', () => {
        const countryName = countrySelect.value;
        const stateName = stateSelect.value;
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;
        if (!stateName) return;
        fetch('https://countriesnow.space/api/v0.1/countries/state/cities', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              country: countryName,
              state: stateName
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.error) {
              showNotification('Cities not found for ' + stateName, true);
            } else {
              data.data.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
              });
              citySelect.disabled = false;
            }
          })
          .catch(err => {
            console.error(err);
            showNotification('Error fetching cities.', true);
          });
      });



      /**********************
       * Cart & Order Summary Logic
       **********************/
      // Retrieve cart items using a literal key for consistency
      const cartItemss = getStorage('<?= CART_KEY ?>') || [];
      console.log('Cart Items:', cartItemss);
      const checkoutWrapper = document.getElementById('checkout-wrapper');
      const emptyCartMessage = document.getElementById('empty-cart');
      if (cartItemss.length === 0) {
        if (checkoutWrapper) checkoutWrapper.style.display = 'none';
        if (emptyCartMessage) emptyCartMessage.style.display = 'block';
      } else {
        if (emptyCartMessage) emptyCartMessage.style.display = 'none';
        if (checkoutWrapper) checkoutWrapper.style.display = 'grid';
      }
      const checkoutItems = document.getElementById('checkoutItems');
      let subtotal = 0;
      checkoutItems.innerHTML = cartItemss.map(item => {
        const itemTotal = item.numericPrice * item.quantity;
        subtotal += itemTotal;
        return `
      <div class="checkout-item">
      <img src="${item.image}" alt="${item.name}">
      <div class="items-details">
        <h3>${item.brand} ${item.name}</h3>
        <p>Ref: ${item.ref_code}</p>
        <p>${item.quantity} x ${convertAndFormatPrice(item.numericPrice)}</p>
        <input type="hidden" class="item-quantity" data-id="${item.id}" value="${item.quantity}" min="1">
      </div>
    </div>
  `;
      }).join('');
      document.getElementById('checkoutSubtotal').textContent = convertAndFormatPrice(subtotal);


      // If no country is selected, force shipping and tax to 0
      if (document.getElementById('country').value === '') {
        currentShippingCost = 0;
        currentTaxRate = 0;
        document.getElementById('checkoutShipping').textContent = convertAndFormatPrice(0);
        document.getElementById('checkoutTax').textContent = convertAndFormatPrice(0);
        document.getElementById('checkoutDiscount').textContent = '-' + convertAndFormatPrice(0);
        updateTotal();
      }

      /**********************
       * Coupon Application
       **********************/
      document.getElementById('applyCoupon').addEventListener('click', () => {
        const couponInput = document.getElementById('promo_code').value.trim();
        const cartItems = getStorage('<?= CART_KEY ?>') || [];
        if (couponInput === 'SAVE10') {
          const subtotalUSD = cartItems.reduce((acc, item) => acc + (item.numericPrice * item.quantity), 0);
          const discountUSD = subtotalUSD * 0.10; // 10% discount
          document.getElementById('checkoutDiscount').dataset.usd = discountUSD;
          document.getElementById('checkoutDiscount').textContent = `-${convertAndFormatPrice(discountUSD)}`;
          showNotification('Coupon applied successfully!');
          updateTotal();
          saveFormData();
        } else {
          showNotification('Invalid coupon code.', true);
        }
      });

      /**********************
       * Prevent Enter Key from Submitting Form Early
       **********************/
      document.getElementById('multiStepForm').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
          const activeStep = document.querySelector('.step.active');
          if (activeStep && activeStep.id !== 'step-4') {
            e.preventDefault();
            const nextButton = activeStep.querySelector('button[id^="next-"]');
            if (nextButton) {
              nextButton.click();
            }
          }
        }
      });

      /**********************
       * Credit Card Input Formatting
       **********************/
      const cardNumberInput = document.getElementById('card_number');
      if (cardNumberInput) {
        cardNumberInput.addEventListener('input', (e) => {
          let value = e.target.value;
          value = value.replace(/\D/g, '');
          value = value.substring(0, 16);
          const parts = [];
          for (let i = 0; i < value.length; i += 4) {
            parts.push(value.substring(i, i + 4));
          }
          e.target.value = parts.join(' ');
        });
      }

      /**********************
       * Final Form Submission – Payment Verification & Order Confirmation
       **********************/
      document.getElementById('multiStepForm').addEventListener('submit', (e) => {
        e.preventDefault();
        saveFormData();

        // Show the loading spinner
        document.getElementById('loading-spinner').style.display = 'flex';

        const paymentMethod = document.getElementById('payment_method').value;
        const otpVerified = document.getElementById('netbank_otp_verified').value === '1';

        // Validate payment method specific requirements
        if (paymentMethod === 'net_banking' && !otpVerified) {
          showNotification('Please complete Net Banking OTP verification.', true);
          document.getElementById('loading-spinner').style.display = 'none';
          return;
        }

        const checkoutTotalElement = document.getElementById('checkoutTotal');

        // Extract the numeric value from the element's text content
        const totalConverted = parseFloat(checkoutTotalElement.textContent.replace(/[^0-9.-]+/g, ""));

        // Update the element's text content using your conversion/formatting function
        checkoutTotalElement.textContent = convertAndFormatPrice(totalConverted);


        // Gather form data
        const formData = {
          cart: getStorage(CART_KEY),
          customer: {
            firstName: document.getElementById('first_name').value,
            lastName: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            company: document.getElementById('company').value,
            country: document.getElementById('country').value,
            state: document.getElementById('state').value,
            city: document.getElementById('city').value,
            address: document.getElementById('address').value,
            zip: document.getElementById('zip').value,
            coupon: document.getElementById('promo_code').value,
            orderNotes: document.getElementById('order_notes').value,
            billing: {
              sameAsShipping: document.getElementById('same_as_shipping').checked,
              address: document.getElementById('billing_address') ? document.getElementById('billing_address').value : '',
              city: document.getElementById('billing_city') ? document.getElementById('billing_city').value : '',
              zip: document.getElementById('billing_zip') ? document.getElementById('billing_zip').value : '',
              country: document.getElementById('billing_country') ? document.getElementById('billing_country').value : ''
            },
            paymentMethod: document.getElementById('payment_method').value
          },
          total_amount: totalConverted,
          checkoutSubtotal: parseFloat(document.getElementById('checkoutSubtotal').textContent.replace(/[^0-9.-]+/g, "")),
          checkoutShipping: parseFloat(document.getElementById('checkoutShipping').textContent.replace(/[^0-9.-]+/g, "")),
          checkoutTax: parseFloat(document.getElementById('checkoutTax').textContent.replace(/[^0-9.-]+/g, "")),
          checkoutDiscount: parseFloat(document.getElementById('checkoutDiscount').dataset.usd) || 0,
          currency: getCurrentCurrency(),
          paymentMethod: paymentMethod,
          netbankOTPVerified: otpVerified
        };

        if (formData.customer.paymentMethod === 'credit_card') {
          const rawCardNumber = document.getElementById('card_number').value;
          const cardNumber = rawCardNumber.replace(/\s+/g, '');
          const expDate = document.getElementById('exp_date').value;
          const cvc = document.getElementById('cvc').value.trim();
          if (cardNumber.length !== 16) {
            showNotification('Card number must be 16 digits.', true);
            return;
          }
          if (!luhnCheck(cardNumber)) {
            showNotification('Invalid credit card number.', true);
            return;
          }
          if (!isValidExpiry(expDate)) {
            showNotification('Credit card is expired or expiry date is invalid.', true);
            return;
          }
          if (!/^\d{3}$/.test(cvc)) {
            showNotification('CVC must be 3 digits.', true);
            return;
          }
          formData.customer.cardNumber = cardNumber;
          formData.customer.expDate = expDate;
          formData.customer.cvc = cvc;
        }

        setTimeout(() => {
          if ((paymentMethod === 'credit_card' && formData.customer.cardNumber === "4242424242424242") ||
            (paymentMethod === 'net_banking' && otpVerified)) {
            const fakeTransactionId = "TXN" + Date.now();
            fetch('/app/controllers/saveTransaction.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                  transactionData: formData,
                  transaction_id: fakeTransactionId,
                  payment_status: 'success'
                })
              })
              .then(res => res.json())
              .then(response => {
                if (response.success) {
                  fetch('/app/controllers/payment_processor.php', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({
                        transactionData: formData,
                        paymentToken: "dummy_token",
                        transaction_id: fakeTransactionId
                      })
                    })
                    .then(emailRes => emailRes.json())
                    .then(emailResponse => {
                      if (emailResponse.success) {
                        setStorage('<?= CART_KEY ?>', []);
                        localStorage.removeItem('checkoutFormData');
                        window.location.href = 'order-tracking.php?transaction_id=' + fakeTransactionId;
                        document.getElementById('loading-spinner').style.display = 'none';
                      } else {
                        showNotification('Order processed but failed to send confirmation email.', true);
                        window.location.href = 'order-tracking.php?transaction_id=' + fakeTransactionId;
                        document.getElementById('loading-spinner').style.display = 'none';
                      }
                    });
                } else {
                  showNotification('Error saving transaction. Please try again.', true);
                }
              });
          } else if (formData.customer.paymentMethod !== 'credit_card' || formData.customer.paymentMethod !== 'net_banking') {
            showNotification('Payment method not supported yet.', true);
            document.getElementById('loading-spinner').style.display = 'none';
          } else {
            showNotification('Payment failed. Please check your card details.', true);
            document.getElementById('loading-spinner').style.display = 'none';
          }
        }, 1000);
      });


      /**********************
       * Shipping & Tax Calculation (Using Global Tax Rate)
       **********************/
      const checkoutShipping = document.getElementById('checkoutShipping');
      const checkoutTax = document.getElementById('checkoutTax');
      const checkoutTotal = document.getElementById('checkoutTotal');

      function calculateShippingAndTax(countryCode) {
        let shippingCost = 0;
        let taxRate = 0;
        switch (countryCode) {
          case 'US':
            shippingCost = 5;
            taxRate = 0.07;
            break;
          case 'GB':
            shippingCost = 10;
            taxRate = 0.20;
            break;
          case 'CH':
            shippingCost = 15;
            taxRate = 0.08;
            break;
          case 'IN':
            shippingCost = 20;
            taxRate = 0.18;
            break;
          default:
            shippingCost = 0;
            taxRate = 0;
            break;
        }
        currentShippingCost = shippingCost;
        currentTaxRate = taxRate;
        checkoutShipping.textContent = convertAndFormatPrice(shippingCost);
        updateTotal();
      }
      countrySelect.addEventListener('change', () => {
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        const countryCode = selectedOption.getAttribute('data-code');
        if (countryCode) {
          calculateShippingAndTax(countryCode);
        }
      });

      /**********************
       * Persist form data on every input change & initial load
       **********************/
      document.getElementById('multiStepForm').addEventListener('input', saveFormData);
      loadFormData();

      document.querySelectorAll('.item-quantity').forEach(input => {
        input.addEventListener('change', (e) => {
          const itemId = e.target.dataset.id;
          const newQuantity = parseInt(e.target.value);
          if (newQuantity < 1) {
            e.target.value = 1;
            return;
          }

          // Update the cart item quantity
          const cartItems = getStorage('<?= CART_KEY ?>') || [];
          const itemIndex = cartItems.findIndex(item => item.id === itemId);
          if (itemIndex !== -1) {
            cartItems[itemIndex].quantity = newQuantity;
            setStorage('<?= CART_KEY ?>', cartItems);
            updateTotal();
          }
        });
      });

      const useSavedBtn = document.getElementById('useSavedAddress');
      if (useSavedBtn) {
        useSavedBtn.addEventListener('click', applySavedAddress);
      }
    });
  </script>


  <!-- Additional JS Files -->

  <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
  <script src="/src/assets/js/index.js" async></script>
  <script src="/src/assets/js/currency-language.js" async></script>
  <script src="/src/assets/js/cookie-monitor.js" async></script>
  <script src="/src/assets/js/imagePreview.js" async></script>
</body>

</html>