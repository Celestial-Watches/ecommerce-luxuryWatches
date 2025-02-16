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

        input,
        select,
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus {
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

        .items-details{
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

        .total-summary {
            padding-top: 1.5rem;
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

            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Street Address</label>
                <input type="text" name="address" required>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" required>
                </div>
                <div class="form-group">
                    <label>ZIP Code</label>
                    <input type="text" name="zip" required>
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

            <div class="payment-methods">
                <h3>Payment Method</h3>
                <div class="form-group">
                    <select name="payment_method" id="paymentMethod" required>
                        <option value="">Select Payment Method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <div id="creditCardFields" style="display: none;">
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" name="card_number" placeholder="4242 4242 4242 4242">
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input type="month" name="exp_date">
                        </div>
                        <div class="form-group">
                            <label>CVC</label>
                            <input type="text" name="cvc" placeholder="123">
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
                    <strong>Total:</strong>
                    <span id="checkoutTotal"></span>
                </div>
            </div>
        </aside>
    </div>

    <?php include '../../PHP/components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cartItems = getStorage('<?= CART_KEY ?>');
            const checkoutItems = document.getElementById('checkoutItems');
            let total = 0;

            checkoutItems.innerHTML = cartItems.map(item => {
                const itemTotal = item.numericPrice * item.quantity;
                total += itemTotal;

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

            document.getElementById('checkoutTotal').textContent = convertAndFormatPrice(total);
        });

        // Payment method toggle
        document.getElementById('paymentMethod').addEventListener('change', function() {
            const ccFields = document.getElementById('creditCardFields');
            ccFields.style.display = this.value === 'credit_card' ? 'block' : 'none';
        });

        // Form submission
        document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                cart: getStorage('<?= CART_KEY ?>'),
                customer: {
                    firstName: document.querySelector('[name="first_name"]').value,
                    lastName: document.querySelector('[name="last_name"]').value,
                    email: document.querySelector('[name="email"]').value,
                    address: document.querySelector('[name="address"]').value,
                    city: document.querySelector('[name="city"]').value,
                    zip: document.querySelector('[name="zip"]').value,
                    country: document.querySelector('[name="country"]').value,
                    paymentMethod: document.querySelector('[name="payment_method"]').value
                }
            };

            try {
                const response = await fetch('/app/controllers/processOrder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    // Clear cart after successful purchase
                    setStorage('<?= CART_KEY ?>', []);
                    window.location.href = '/order-confirmation';
                } else {
                    showNotification('Payment failed. Please try again.', true);
                }
            } catch (error) {
                showNotification('Error processing payment. Please try again.', true);
            }
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