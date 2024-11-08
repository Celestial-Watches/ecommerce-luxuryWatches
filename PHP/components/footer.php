<?php if (!defined('ALLOW_ACCESS')) {
    header("Location: ../../index.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer - Celestial Watches</title>
    <style>
        /* Importing Google font - Open Sans */
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');

        .footer {
            width: 100%;
        }

        .footer .footer-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 3.5rem;
            padding: 60px;
        }

        .footer-row .footer-col h4 {
            color: #000000;
            font-size: 14px;
            line-height: 18px;
            margin-bottom: 16px !important;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* .footer-col .links {
            margin-top: 20px;
        } */

        .footer-col .links li {
            list-style: none;
            margin-bottom: 10px;
        }

        .footer-col .links li a {
            text-decoration: none;
            color: #bfbfbf;
            font-size: 12px;
            line-height: 18px;
            color: #646364;
            letter-spacing: 0.1px;
        }

        .footer-col p {
            margin: 20px 0;
            color: #646364;
            max-width: 300px;
            font-size: 12px !important;
            line-height: 18px !important;
        }

        .footer-col form {
            display: flex;
            gap: 5px;
        }

        .footer-col input {
            background: #fff;
            background-clip: padding-box;
            border: 1px solid #c2c2c2;
            border-radius: 1px;
            font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            height: 40px;
            line-height: 1.42857143;
            padding: 0 9px;
            vertical-align: baseline;
            width: 100%;
            box-sizing: border-box;
            padding: 6.6px 20px !important;
            border-color: #fff !important;
            color: #646364 !important;
            padding-right: 140px !important;
        }

        .footer-col input::placeholder {
            color: #ccc;
        }

        .footer-col form button {
            border-color: #646364 !important;
            padding: 8px 24px !important;
            background: #646364 !important;
            right: 0;
            top: 0;
            font-size: 12px !important;
            line-height: 26px !important;
            color: white;
        }

        .footer-col form button:hover {
            background: #cecccc;
        }

        .footer-col .icons {
            display: flex;
            margin-top: 30px;
            gap: 30px;
            cursor: pointer;
        }

        .footer-col .icons i {
            color: #646364;
        }

        @media (max-width: 768px) {
            .footer {
                position: relative;
                bottom: 0;
                left: 0;
                transform: none;
                width: 100%;
                border-radius: 0;
            }

            .footer .footer-row {
                padding: 20px;
                gap: 1rem;
            }

            .footer-col form {
                display: block;
            }

            .footer-col form :where(input, button) {
                width: 100%;
            }

            .footer-col form button {
                margin: 10px 0 0 0;
            }
        }

        .luxury-helpline-section {
            background-color: #000;
            padding: 20px 0;
            border-bottom: 4px solid #9d0000;
        }

        .luxury-helpline-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            align-items: center;
            max-width: 1340px;
            margin: 0 auto;
            padding: 0 15px;
            color: #fff;
        }

        .luxury-helpline-content {
            text-align: center;
            /* Default center alignment */
        }

        .luxury-helpline-title {
            color: #9da4b1;
            font-size: 18px;
            margin-bottom: 8px;
            font-family: "mrs-eaves-xl-serif-narrow", serif !important;
            font-style: italic;
        }

        .luxury-helpline-numbers {
            font-size: 16px;
            line-height: 28px;
            margin-top: 4px;
            display: flex !important;
            white-space: nowrap;
            justify-content: center;
            align-items: center;
        }

        /* .luxury-phone-number {
            font-size: 18px;
            color: #fff;
            font-weight: bold;
        } */

        .luxury-separator {
            font-size: 18px;
            color: #fff;
            margin: 0 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .lux_helpline {
                display: block;
            }

            .helpline-label,
            .helpline-info {
                display: block;
                text-align: center;
            }

            .h-line {
                display: none;
            }

            .helpline_content {
                padding: 20px;
                max-width: none;
            }

            .luxury-helpline-container {
                grid-template-columns: repeat(1, 1fr);
                padding: 10px;
                row-gap: 20px;
                margin-bottom: 10%;
                place-items: center;
            }

            .luxury-phone-number {
                font-size: 16px;
            }

            .luxury-helpline-title {
                width: 100% !important;
                margin-left: 0 !important;
                text-align: center !important;
            }
        }

        @media (min-width: 768px)and (max-width: 1024px) {
            .luxury-phone-number {
                font-size: 12px;
            }

            .luxury-helpline-title {
                width: 80% !important;
                margin-left: 0 !important;
                text-align: center !important;
            }

        }


        .brand-line {
            margin: 30px auto;
        }

        #other-brands {
            display: none;
            /* margin-top: 10px; */
            /* Keeps it in the flow and separates it from the "More..." link */
        }
    </style>
</head>

<body>
    <div class="luxury-helpline-section">
        <div class="luxury-helpline-container">
            <div class="luxury-helpline-content">
                <h3 class="luxury-helpline-title" style="text-align: justify;">Luxury Watch Helpline:</h3>
                <div class="luxury-helpline-numbers" style="justify-content: flex-start;">
                    <span class="luxury-phone-number">+91 87250 28899</span>
                    <span class="luxury-separator">•</span>
                    <span class="luxury-phone-number">+91 87250 28882</span>
                </div>
            </div>
            <div class="luxury-helpline-content">
                <h3 class="luxury-helpline-title">Online Orders Helpline:</h3>
                <div class="luxury-helpline-numbers" style="justify-content: center;">
                    <span class="luxury-phone-number">+91 98215 43088</span>
                    <span class="luxury-separator">•</span>
                    <span class="luxury-phone-number">+91 11 4011 5246</span>
                </div>
            </div>
            <div class="luxury-helpline-content">
                <h3 class="luxury-helpline-title" style="width: 50%;
    margin-left: 30%;">Customer Care Number:</h3>
                <div class="luxury-helpline-numbers">
                    <span class="luxury-phone-number">+91 87250 60021</span>
                </div>
            </div>
        </div>
    </div>

    <section class="footer" style="background-color: ghostwhite !important;">
        <div class="footer-row">

            <div class="footer-col">
                <h4>Luxury Brands</h4>
                <ul class="links">
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Patek%20Philippe">Patek Philippe</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Richard%20Mille">Richard Mille</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Audemars%20Piguet">Audemars Piguet</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Vacheron%20Constantin">Vacheron Constantin</a></li>
                    <li><a href="javascript:void(0);" id="more-link-brands" onclick="toggleSection('brands')">More...</a></li>
                </ul>
                <ul class="links" id="other-brands" style="display:none;">
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Jaeger-LeCoultre">Jaeger-LeCoultre</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=IWC%20Schaffhausen">IWC Schaffhausen</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Breguet">Breguet</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Cartier">Cartier</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Blancpain">Blancpain</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Hublot">Hublot</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Rolex">Rolex</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=A.%20Lange%20%26%20Söhne">A. Lange & Söhne</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=">Parmigiani Fleurier</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Parmigiani%20Fleurier">Greubel Forsey</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Roger%20Dubuis">Roger Dubuis</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=MB%26F">MB&F</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Ulysse%20Nardin">Ulysse Nardin</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Zenith">Zenith</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=F.P.%20Journe">F.P. Journe</a></li>
                    <li><a href="http://localhost:3000/app/views/brands.php?brand=Jacob%20%26%20Co.">Jacob & Co.</a></li>
                    <li><a href="javascript:void(0);" id="less-link-brands" onclick="toggleSection('brands')">Less...</a></li>
                </ul>
            </div>



            <div class="footer-col">
                <h4>Info</h4>
                <ul class="links">
                    <li><a href="http://localhost:3000/app/views/about-us.php">About Us</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">Customers</a></li>
                    <li><a href="#">Service</a></li>
                    <li><a href="#">Collection</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Customer Care</h4>
                <ul class="links">
                    <li><a href="http://localhost:3000/app/views/watch-care.php">Watch Care & Maintenance</a></li>
                    <li><a href="http://localhost:3000/app/views/authentication.php">Authentication & Certification</a></li>
                    <li><a href="http://localhost:3000/app/views/limited-editions.php">Limited Edition Watches</a></li>
                    <li><a href="http://localhost:3000/app/views/brand-partners.php">Our Brand Partners</a></li>
                    <li><a href="javascript:void(0);" id="more-link-care" onclick="toggleSection('care')">More...</a></li>
                </ul>
                <ul class="links"  id="other-care" style="display:none;">
                    <li><a href="http://localhost:3000/app/views/watch-trends.php">Watch Trends</a></li>
                    <li><a href="http://localhost:3000/app/views/gifting.php">Watch Gifting</a></li>
                    <li><a href="http://localhost:3000/app/views/warranty.php">Warranty & Returns</a></li>
                    <li><a href="javascript:void(0);" id="less-link-care" onclick="toggleSection('care')">Less...</a></li>
                </ul>
            </div>



            <div class="footer-col">
                <h4>Legal</h4>
                <ul class="links">
                    <li><a href="#">Customer Agreement</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">GDPR</a></li>
                    <li><a href="#">Security</a></li>
                    <li><a href="#">Testimonials</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Subscribe to our newsletter</h4>
                <p>
                    Be the first to hear about new arrivals, special offers, and invitations to private events.
                </p>
                <form action="http://localhost:3000/app/controllers/subscribe.php" method="post">
                    <input type="email" id="subscribe-email" name="email" placeholder="Your Email" required>
                    <button type="submit">SUBSCRIBE</button>
                </form>
                <div id="feedback-message" style="visibility: hidden;"></div>
                <div class="icons">
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-linkedin"></i>
                    <i class="fa-brands fa-github"></i>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleSection(section) {
            var moreLink, lessLink, otherSection;

            if (section === 'care') {
                moreLink = document.getElementById('more-link-care');
                lessLink = document.getElementById('less-link-care');
                otherSection = document.getElementById('other-care');
            } else if (section === 'brands') {
                moreLink = document.getElementById('more-link-brands');
                lessLink = document.getElementById('less-link-brands');
                otherSection = document.getElementById('other-brands');
            }

            if (otherSection.style.display === 'none') {
                otherSection.style.display = 'block'; // Show the extra items
                moreLink.style.display = 'none'; // Hide "More..."
                lessLink.style.display = 'inline'; // Show "Less..."
            } else {
                otherSection.style.display = 'none'; // Hide the extra items
                moreLink.style.display = 'inline'; // Show "More..."
                lessLink.style.display = 'none'; // Hide "Less..."
            }
        }
    </script>
</body>

</html>