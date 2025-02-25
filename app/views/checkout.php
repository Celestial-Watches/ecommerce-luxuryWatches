<?php
session_start();
define('ALLOW_ACCESS', true);
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
}

define('CART_KEY', 'cart_items');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Luxury Watches | Checkout</title>

  <!-- IONICONS -->
  <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
  <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js" integrity="sha512-a+SUDuwNzXDvz4XrIcXHuCf089/iJAoN4lmrXJg18XnduKK6YlDHNRalv4yd1N40OKI80tFidF+rqTFKGPoWFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- JS Libraries -->
  <script src="/src/assets/js/navigation.js" async></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

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
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
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
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    .step {
      display: none;
      animation: fadeIn 0.5s ease-in-out;
    }
    .step.active {
      display: block;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
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
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
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

  <div class="checkout-wrapper">
    <!-- Left Navigation Panel -->
    <nav class="checkout-nav">
      <ul>
        <li data-step="1" class="active">
          1. Shipping Information
          <span class="tooltip">Enter your name, address & contact details.</span>
        </li>
        <li data-step="2">
          2. Billing &amp; Payment
          <span class="tooltip">Provide billing info & choose payment method.</span>
        </li>
        <li data-step="3">
          3. Order Review &amp; Discounts
          <span class="tooltip">Apply promo codes & add order notes.</span>
        </li>
        <li data-step="4">
          4. Confirmation &amp; Completion
          <span class="tooltip">Final confirmation & tracking details.</span>
        </li>
      </ul>
    </nav>
    <!-- Center Multi–Step Form -->
    <div class="checkout-content">
      <form id="multiStepForm">
        <!-- Step 1: Shipping Information -->
        <div class="step active" id="step-1">
          <h2 style="margin-bottom: 10px;">Shipping Information</h2>
          <div class="form-grid">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" id="last_name" name="last_name" required>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="+1234567890" required>
          </div>
          <div class="form-group">
            <label for="company">Company Name (optional)</label>
            <input type="text" id="company" name="company" placeholder="Your company name">
          </div>
          <!-- Dynamic Location Fields: Country, State, City -->
          <div class="form-group">
            <label for="country">Country</label>
            <!-- Note: For demonstration, a few options with ISO codes are hard-coded -->
            <select id="country" name="country" required>
              <option value="">Select Country</option>
              <option value="United States" data-code="US">United States</option>
              <option value="United Kingdom" data-code="GB">United Kingdom</option>
              <option value="Switzerland" data-code="CH">Switzerland</option>
              <option value="India" data-code="IN">India</option>
              <!-- You can add more countries or load dynamically from a public API -->
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
          <div class="form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
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
          <div class="form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
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
            <select id="payment_method" name="payment_method" required>
              <option value="">Select Payment Method</option>
              <option value="credit_card">Credit/Debit Card</option>
              <option value="paypal">PayPal</option>
              <option value="upi">UPI</option>
              <option value="razorpay">Razorpay</option>
            </select>
          </div>
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
                <input type="text" id="cvc" name="cvc" placeholder="123">
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
  
  <?php include '../../PHP/components/footer.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Track highest completed step
      let maxStepAllowed = 1;
      
      // Validate required fields in a step
      function validateStep(stepNumber) {
        const step = document.getElementById('step-' + stepNumber);
        const requiredElements = step.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        requiredElements.forEach(input => {
          if (!input.checkValidity()) {
            input.reportValidity();
            valid = false;
          }
        });
        return valid;
      }
      
      // Show specified step and update navigation
      function showStep(stepNumber) {
        document.querySelectorAll('.step').forEach(step => step.classList.remove('active'));
        document.getElementById('step-' + stepNumber).classList.add('active');
        document.querySelectorAll('.checkout-nav li').forEach(item => {
          item.classList.toggle('active', item.getAttribute('data-step') === stepNumber.toString());
        });
      }
      
      // Navigation click restrictions
      document.querySelectorAll('.checkout-nav li').forEach(item => {
        item.addEventListener('click', () => {
          const step = parseInt(item.getAttribute('data-step'));
          if (step <= maxStepAllowed) {
            showStep(step);
          } else {
            showNotification('Please complete previous steps first.', true);
          }
        });
      });
      
      // Next/Back buttons with validation
      document.getElementById('next-1').addEventListener('click', () => {
        if (!validateStep(1)) return;
        maxStepAllowed = Math.max(maxStepAllowed, 2);
        showStep(2);
      });
      document.getElementById('back-2').addEventListener('click', () => showStep(1));
      document.getElementById('next-2').addEventListener('click', () => {
        if (!validateStep(2)) return;
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
      
      // Toggle billing address fields
      const sameAsShipping = document.getElementById('same_as_shipping');
      const billingSection = document.getElementById('billing-section');
      sameAsShipping.addEventListener('change', () => {
        billingSection.style.display = sameAsShipping.checked ? 'none' : 'block';
      });
      
      // Toggle credit card fields
      const paymentMethod = document.getElementById('payment_method');
      const creditCardFields = document.getElementById('credit_card_fields');
      paymentMethod.addEventListener('change', () => {
        creditCardFields.style.display = paymentMethod.value === 'credit_card' ? 'block' : 'none';
      });
      
      // --- Dynamic Location API Integration ---
      const countrySelect = document.getElementById('country');
      const stateSelect = document.getElementById('state');
      const citySelect = document.getElementById('city');
      const zipInput = document.getElementById('zip');
      
      // When country changes, fetch states from CountriesNow API
      countrySelect.addEventListener('change', () => {
        const countryName = countrySelect.value;
        // Reset state & city dropdowns
        stateSelect.innerHTML = '<option value="">Select State</option>';
        stateSelect.disabled = true;
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;
        
        if (!countryName) return;
        
        fetch('https://countriesnow.space/api/v0.1/countries/states', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ country: countryName })
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
      
      // When state changes, fetch cities from CountriesNow API
      stateSelect.addEventListener('change', () => {
        const countryName = countrySelect.value;
        const stateName = stateSelect.value;
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;
        
        if (!stateName) return;
        
        fetch('https://countriesnow.space/api/v0.1/countries/state/cities', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ country: countryName, state: stateName })
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
      
      // When ZIP code is entered, attempt to auto-fill state and city using Zippopotam.us
      zipInput.addEventListener('blur', () => {
        const zipCode = zipInput.value.trim();
        if (!zipCode) return;
        // Get the ISO country code from the selected country option
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        const countryCode = selectedOption.getAttribute('data-code');
        if (!countryCode) return;
        fetch('https://api.zippopotam.us/' + countryCode + '/' + zipCode)
          .then(res => {
            if (!res.ok) throw new Error('No data for ZIP code');
            return res.json();
          })
          .then(data => {
            // Assume the first place is the desired one
            const place = data.places[0];
            // If state and city are available, try to set them (if they exist in the dropdowns)
            if (place['state'] && stateSelect.options.length > 1) {
              for (let i = 0; i < stateSelect.options.length; i++) {
                if (stateSelect.options[i].textContent.toLowerCase() === place['state'].toLowerCase()) {
                  stateSelect.selectedIndex = i;
                  stateSelect.dispatchEvent(new Event('change'));
                  break;
                }
              }
            }
            if (place['place name'] && citySelect.options.length > 1) {
              // Wait a moment for cities to load then select
              setTimeout(() => {
                for (let i = 0; i < citySelect.options.length; i++) {
                  if (citySelect.options[i].textContent.toLowerCase() === place['place name'].toLowerCase()) {
                    citySelect.selectedIndex = i;
                    break;
                  }
                }
              }, 500);
            }
          })
          .catch(err => {
            console.error(err);
            // It's okay if ZIP code lookup fails; user can still choose manually.
          });
      });
      
      // Dynamic Product Summary (using your original cart logic)
      const cartItems = getStorage('<?= CART_KEY ?>') || [];
      const checkoutItems = document.getElementById('checkoutItems');
      let subtotal = 0;
      checkoutItems.innerHTML = cartItems.map(item => {
        const itemTotal = item.numericPrice * item.quantity;
        subtotal += itemTotal;
        return `
          <div class="checkout-item">
            <img src="${item.image}" alt="${item.name}">
            <div class="items-details">
              <h3>${item.brand} ${item.name}</h3>
              <p>Ref: ${item.ref_code}</p>
              <p>${convertAndFormatPrice(item.numericPrice)} × ${item.quantity}</p>
            </div>
          </div>
        `;
      }).join('');
      document.getElementById('checkoutSubtotal').textContent = convertAndFormatPrice(subtotal);
      const shippingCost = 5; 
      document.getElementById('checkoutShipping').textContent = convertAndFormatPrice(shippingCost);
      let discount = 0;
      document.getElementById('checkoutDiscount').textContent = '-' + convertAndFormatPrice(discount);
      let tax = 0.08 * (subtotal - discount);
      document.getElementById('checkoutTax').textContent = convertAndFormatPrice(tax);
      let total = subtotal - discount + shippingCost + tax;
      document.getElementById('checkoutTotal').textContent = convertAndFormatPrice(total);
      
      // Coupon Application (original logic)
      document.getElementById('applyCoupon').addEventListener('click', () => {
        const couponInput = document.getElementById('promo_code').value.trim();
        if(couponInput === 'SAVE10'){
          discount = subtotal * 0.10;
          document.getElementById('checkoutDiscount').textContent = '-' + convertAndFormatPrice(discount);
          tax = 0.08 * (subtotal - discount);
          document.getElementById('checkoutTax').textContent = convertAndFormatPrice(tax);
          total = subtotal - discount + shippingCost + tax;
          document.getElementById('checkoutTotal').textContent = convertAndFormatPrice(total);
          showNotification('Coupon applied successfully!');
        } else {
          showNotification('Invalid coupon code.', true);
        }
      });
      
      // Final form submission with Razorpay integration (logic unchanged)
      document.getElementById('multiStepForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = {
          cart: getStorage('<?= CART_KEY ?>'),
          customer: {
            firstName: document.getElementById('first_name').value,
            lastName: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            company: document.getElementById('company').value,
            // Shipping information including dynamic location fields:
            country: countrySelect.value,
            state: stateSelect.value,
            city: citySelect.value,
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
          }
        };
        
        if(formData.customer.paymentMethod === 'razorpay'){
          try {
            const orderResponse = await fetch('/app/controllers/createRazorpayOrder.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify(formData)
            });
            const orderData = await orderResponse.json();
            const options = {
              key: orderData.razorpayKey,
              amount: orderData.amount,
              currency: orderData.currency,
              name: "Luxury Watches",
              description: "Order Payment",
              image: "/celestial-logo.pm",
              order_id: orderData.orderId,
              handler: function(response){
                fetch('/app/controllers/processRazorpayOrder.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({
                    razorpayPaymentId: response.razorpay_payment_id,
                    razorpayOrderId: response.razorpay_order_id,
                    razorpaySignature: response.razorpay_signature,
                    formData: formData
                  })
                }).then(res => {
                  if(res.ok) {
                    setStorage('<?= CART_KEY ?>', []);
                    window.location.href = '/order-confirmation';
                  } else {
                    showNotification('Payment verification failed.', true);
                  }
                });
              },
              prefill: {
                name: formData.customer.firstName + " " + formData.customer.lastName,
                email: formData.customer.email,
                contact: formData.customer.phone
              },
              theme: { color: "#c5a47e" }
            };
            const rzp1 = new Razorpay(options);
            rzp1.open();
          } catch (error) {
            showNotification('Error initiating Razorpay payment. Please try again.', true);
          }
        } else {
          try {
            const response = await fetch('/app/controllers/processOrder.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify(formData)
            });
            if (response.ok) {
              setStorage('<?= CART_KEY ?>', []);
              window.location.href = '/order-confirmation';
            } else {
              showNotification('Payment failed. Please try again.', true);
            }
          } catch (error) {
            showNotification('Error processing payment. Please try again.', true);
          }
        }
      });
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
