<?php
session_start();

define('ALLOW_ACCESS', true);

if(!isset($_SESSION['user_id'])){
    header('Location: /index.php');    
}

include '../../PHP/components/navbar.php';

define('CART_KEY', 'cart_items');
?>

<!DOCTYPE html>
<html lang="en">
<!-- IONICONS -->
<script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
<script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

<!-- Remix Icons / Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- JS -->
<script src="/src/assets/js/navigation.js" async></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="/src/assets/css/deskView.css" />
<link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
<link rel="stylesheet" href="/src/assets/css/google-header.css">

<!-- FONTS -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Watches | Checkout</title>
    <style>
        :root {
            --primary-color: #1a1a1a;
            --accent-color: #c5a47e;
            --light-bg: #f8f8f8;
        }
        .checkout-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 3rem;
        }
        .order-summary {
            background: var(--light-bg);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
        .checkout-form {
            display: grid;
            gap: 1.5rem;
        }
        .form-group {
            display: grid;
            gap: 0.5rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        .shipInputs,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .shipInputs:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        .payment-methods {
            display: grid;
            gap: 1rem;
            border-top: 1px solid #eee;
            padding-top: 1.5rem;
        }
        .checkout-item {
            display: flex;
            gap: 1rem;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            text-align: right;
            line-height: 1.8;
        }
        .checkout-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
        .items-details {
            padding: 10px;
        }
        .items-details h3 {
            font-size: 20px;
            font-weight: 500;
        }
        .items-details p {
            font-size: 14px;
            color: #777;
        }
        .total-summary div {
            margin-bottom: 0.5rem;
        }
        .checkoutBtn {
            background: var(--accent-color);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 4px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="checkout-container">
        <!-- Checkout Form -->
        <form class="checkout-form" id="checkoutForm">
            <h2>Shipping & Payment Details</h2>
            
            <!-- Personal Information -->
            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input class="shipInputs" type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input class="shipInputs" type="text" name="last_name" required>
                </div>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input class="shipInputs" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input class="shipInputs" type="tel" name="phone" placeholder="e.g., +1234567890" required>
            </div>
            <div class="form-group">
                <label>Company Name (optional)</label>
                <input class="shipInputs" type="text" name="company" placeholder="Your company name">
            </div>
            
            <!-- Shipping Address -->
            <div class="form-group">
                <label>Street Address</label>
                <input class="shipInputs" type="text" name="address" required>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>City</label>
                    <input class="shipInputs" type="text" name="city" required>
                </div>
                <div class="form-group">
                    <label>ZIP Code</label>
                    <input class="shipInputs" type="text" name="zip" required>
                </div>
            </div>
            <div class="form-group">
                <label>Country</label>
                <select name="country" required>
                    <option value="">Select Country</option>
                    <option value="US">United States</option>
                    <option value="UK">United Kingdom</option>
                    <option value="CH">Switzerland</option>
                    <!-- Add more countries -->
                </select>
            </div>
            
            <!-- Coupon Code -->
            <div class="form-group">
                <label>Coupon Code</label>
                <div style="display: flex; gap: 1rem;">
                    <input class="shipInputs" type="text" name="coupon_code" id="couponCode" placeholder="Enter coupon code">
                    <button type="button" id="applyCoupon" class="checkoutBtn">Apply</button>
                </div>
            </div>
            
            <!-- Billing Address Toggle -->
            <div class="form-group">
                <input type="checkbox" id="sameAsShipping" name="same_as_shipping" checked>
                <label for="sameAsShipping">Billing address same as shipping address</label>
            </div>
            <div id="billingAddress" style="display: none;">
                <h3>Billing Address</h3>
                <div class="form-group">
                    <label>Billing Street Address</label>
                    <input class="shipInputs" type="text" name="billing_address">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Billing City</label>
                        <input class="shipInputs" type="text" name="billing_city">
                    </div>
                    <div class="form-group">
                        <label>Billing ZIP Code</label>
                        <input class="shipInputs" type="text" name="billing_zip">
                    </div>
                </div>
                <div class="form-group">
                    <label>Billing Country</label>
                    <select name="billing_country">
                        <option value="">Select Country</option>
                        <option value="US">United States</option>
                        <option value="UK">United Kingdom</option>
                        <option value="CH">Switzerland</option>
                        <!-- More options -->
                    </select>
                </div>
            </div>
            
            <!-- Order Notes -->
            <div class="form-group">
                <label>Order Notes (optional)</label>
                <textarea class="shipInputs" name="order_notes" rows="4" placeholder="Any special instructions for your order"></textarea>
            </div>
            
            <!-- Payment Methods -->
            <div class="payment-methods">
                <h3>Payment Method</h3>
                <div class="form-group">
                    <select name="payment_method" id="paymentMethod" required>
                        <option value="">Select Payment Method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="razorpay">Razorpay</option>
                    </select>
                </div>
                <!-- Credit Card Fields (shown only if credit card is selected) -->
                <div id="creditCardFields" style="display: none;">
                    <div class="form-group">
                        <label>Card Number</label>
                        <input class="shipInputs" type="text" name="card_number" placeholder="4242 4242 4242 4242">
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input class="shipInputs" type="month" name="exp_date">
                        </div>
                        <div class="form-group">
                            <label>CVC</label>
                            <input class="shipInputs" type="text" name="cvc" placeholder="123">
                        </div>
                    </div>
                </div>
            </div>
            
            <button class="checkoutBtn" type="submit">Complete Purchase</button>
        </form>

        <!-- Order Summary -->
        <aside class="order-summary">
            <h2>Your Order</h2>
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
                    <span id="checkoutShipping">0</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Tax:</span>
                    <span id="checkoutTax">0</span>
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
            // Set default shipping cost (this can be dynamic)
            const shippingCost = 5; 
            document.getElementById('checkoutShipping').textContent = convertAndFormatPrice(shippingCost);
            // Default discount is 0
            let discount = 0;
            document.getElementById('checkoutDiscount').textContent = '-' + convertAndFormatPrice(discount);
            // Calculate tax (e.g., 8% of (subtotal - discount))
            let tax = 0.08 * (subtotal - discount);
            document.getElementById('checkoutTax').textContent = convertAndFormatPrice(tax);
            let total = subtotal - discount + shippingCost + tax;
            document.getElementById('checkoutTotal').textContent = convertAndFormatPrice(total);

            // Coupon application event
            document.getElementById('applyCoupon').addEventListener('click', () => {
                const couponInput = document.getElementById('couponCode').value.trim();
                // For example, coupon "SAVE10" gives 10% off the subtotal
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

            // Payment method toggle for credit card fields
            document.getElementById('paymentMethod').addEventListener('change', function() {
                const ccFields = document.getElementById('creditCardFields');
                ccFields.style.display = this.value === 'credit_card' ? 'block' : 'none';
            });

            // Billing address toggle
            document.getElementById('sameAsShipping').addEventListener('change', function() {
                const billingDiv = document.getElementById('billingAddress');
                billingDiv.style.display = this.checked ? 'none' : 'block';
            });
        });

        // Form submission handler with Razorpay integration
        document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Gather form data
            const formData = {
                cart: getStorage('<?= CART_KEY ?>'),
                customer: {
                    firstName: document.querySelector('[name="first_name"]').value,
                    lastName: document.querySelector('[name="last_name"]').value,
                    email: document.querySelector('[name="email"]').value,
                    phone: document.querySelector('[name="phone"]').value,
                    company: document.querySelector('[name="company"]').value,
                    address: document.querySelector('[name="address"]').value,
                    city: document.querySelector('[name="city"]').value,
                    zip: document.querySelector('[name="zip"]').value,
                    country: document.querySelector('[name="country"]').value,
                    coupon: document.querySelector('[name="coupon_code"]').value,
                    orderNotes: document.querySelector('[name="order_notes"]').value,
                    billing: {
                        sameAsShipping: document.querySelector('[name="same_as_shipping"]').checked,
                        address: document.querySelector('[name="billing_address"]') ? document.querySelector('[name="billing_address"]').value : '',
                        city: document.querySelector('[name="billing_city"]') ? document.querySelector('[name="billing_city"]').value : '',
                        zip: document.querySelector('[name="billing_zip"]') ? document.querySelector('[name="billing_zip"]').value : '',
                        country: document.querySelector('[name="billing_country"]') ? document.querySelector('[name="billing_country"]').value : ''
                    },
                    paymentMethod: document.querySelector('[name="payment_method"]').value
                }
            };

            // Check which payment method is selected
            if(formData.customer.paymentMethod === 'razorpay'){
                // Create a Razorpay order by calling the backend
                try {
                    const orderResponse = await fetch('/app/controllers/createRazorpayOrder.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(formData)
                    });
                    const orderData = await orderResponse.json();
                    
                    // Prepare Razorpay options using orderData returned from backend
                    const options = {
                        key: orderData.razorpayKey, // Your Razorpay API key from backend
                        amount: orderData.amount,   // Amount in paise
                        currency: orderData.currency,
                        name: "Luxury Watches",
                        description: "Order Payment",
                        image: "/celestial-logo.pm", // Replace with your logo URL
                        order_id: orderData.orderId, // Order ID created in Razorpay
                        handler: function(response){
                            // On successful payment, verify the payment on the backend
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
                                    // Clear cart and redirect on success
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
                        theme: {
                            color: "#c5a47e"
                        }
                    };

                    const rzp1 = new Razorpay(options);
                    rzp1.open();
                } catch (error) {
                    showNotification('Error initiating Razorpay payment. Please try again.', true);
                }
            } else {
                // Process other payment methods (credit card, PayPal, etc.)
                try {
                    const response = await fetch('/app/controllers/processOrder.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
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
    </script>
    
    <!-- Additional JS files -->
    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <script src="/src/assets/js/cookie-monitor.js" async></script>
    <script src="/src/assets/js/imagePreview.js" async></script>
</body>
</html>
