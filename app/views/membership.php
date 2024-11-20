<?php
define('ALLOW_ACCESS', true);
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches | Exclusivity in Every Tick</title>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->

    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- ============= JS =============  -->
    <script src="/src/assets/js/navigation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>


    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="/src/assets/css/deskView.css" />
    <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/src/assets/css/google-header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />




    <!-- ============= FONTS=============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        .mem-banner-sec {
            margin-top: 5px !important;
            margin-bottom: 40px !important;
        }

        .mem-banner-container {
            position: relative;
            width: 100%;
            height: 540px;
            overflow: hidden;
        }

        .mem-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .mem-text-overlay {
            position: absolute;
            top: 0;
            right: 50px;
            width: 40%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .mem-heading {
            font-size: 3.5em;
            margin-bottom: 20px;
            font-family: '../../src/assets/fonts/luxury.regular.ttf';
        }

        .mem-desc {
            font-size: 14px;
            line-height: 1.6;
            font-family: sans-serif;
        }

        .mem-cta-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #ff6666;
            color: #ffffff;
            padding: 12px 24px;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            width: max-content;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .mem-banner-container {
                height: 350px;
            }

            .mem-text-overlay {
                width: 100%;
                height: auto;
                bottom: 0;
                top: 0;
                right: 0;
                text-align: center;
                padding: 20px;
                background: rgb(80 55 55 / 70%);
                place-items: center;
            }

            .mem-heading {
                font-size: 2em;
            }

            .mem-desc {
                font-size: 12px;
            }

            .mem-cta-button {
                font-size: 0.9em;
                padding: 10px 20px;
                margin-top: 15px;
            }
        }

        @media (max-width: 480px) {
            .mem-banner-container {
                height: 300px;
            }

            .mem-heading {
                font-size: 1.8em;
            }

            .mem-img {
                width: 250% !important;
            }

            .mem-desc {
                font-size: 11px;
            }

            .mem-cta-button {
                font-size: 0.8em;
                padding: 8px 18px;
            }

            .content p {
                font-size: 10px !important;
            }

            .content {
                width: 80% !important;
                padding: 50px;
            }

            .timeline-item.left::after,
            .timeline-item.right::after {
                top: -20px !important;
            }
        }

        /* Desc */

        /* Container for the centered content */
        .centered-container {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
        }

        .membership-banner {
            text-align: center;
            color: #000;
            padding: 40px 30px;
            max-width: 600px;
        }

        .membership-slogan {
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
            font-family: 'Playfair Display', serif;
        }

        .membership-description {
            font-size: 1rem;
            margin-top: 20px;
            line-height: 1.6;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        /* Membership plan */

        .plan {
            padding: 60px 20px;
            background-color: #ffffff;
            background-image: radial-gradient(circle, rgba(54, 55, 57, 0.2) 1.5px, transparent 1px);
            background-size: 60px 40px;
            background-position: 0 0;
            margin-bottom: 40px;
        }

        /* Grid container */
        .membership-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Left side: Watch Image */
        .watch-image img {
            width: 100%;
            height: auto;
            max-width: 100%;
            object-fit: cover;
        }

        /* Right side (membership details) */
        .membership-plan-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: white;
        }

        /* Membership title */
        .membership-plan-title {
            font-size: 30px;
            font-family: 'Playfair Display', serif;
            color: #000;
            margin-bottom: 2px;
            padding-left: 20px;
            padding-top: 10px;
            border-left: 5px solid #171a20;
            ;
        }

        /* Membership description */
        .membership-plan-description {
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0, 0, 14px;
        }

        /* Membership benefits list */
        .membership-plan {
            margin-bottom: 20px;
        }

        .membership-plan ul {
            padding-left: 20px;
        }

        .membership-plan li {
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            list-style-type: disc !important;
        }

        /* CTA Button */
        .plan-mem {
            background-color: #D4AF37;
            color: #fff;
            padding: 12px 25px;
            /* Reduced padding */
            font-size: 1.1rem;
            /* Reduced font size */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: fit-content;
            font-family: 'Roboto', sans-serif;
            transition: background-color 0.3s;
        }

        .plan-mem:hover {
            background-color: #b88a2f;
        }

        /* For tablets and smaller devices */
        @media (max-width: 991px) {
            .membership-container {
                grid-template-columns: 1fr;
                padding: 0 10px;
            }

            .membership-plan-title {
                font-size: 24px;
                padding-left: 10px;
            }

            .membership-plan-description {
                font-size: 13px;
            }

            .membership-plan li {
                font-size: 12px;
            }

            .mem-plan {
                font-size: 12px;
            }
        }

        /* For mobile screens */
        @media (max-width: 767px) {
            .membership-plan-title {
                font-size: 20px;
            }

            .membership-plan-description {
                font-size: 12px;
                margin-top: 5%;
                margin-bottom: 5%;
            }

            .membership-plan li {
                font-size: 11px;
            }

            .plan-mem {
                font-size: 1rem;
                padding: 10px 20px;
            }

            .membership-plan-details {
                width: auto !important;
            }

            .test-card-body p{
                font-size: 10px !important;
            }

            .section-header h1{
                font-size: 30px !important;
            }
        }

        /* Testimonial */
        /* Variables for storing colors */
        :root {
            --card-clr: #161922;
            --body-clr: #191d28;
            --primary-clr: #f0bf6a;
            --heading-clr: #dadada;
            --text-clr: #767a86;
        }

        .main-wrapper {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            font-size: 16px;
        }

        /* Testimonials section styles */
        .testimonials-section {
            width: 100%;
            padding: 0px 8%;
        }

        .testimonials-section .section-header {
            max-width: 700px;
            text-align: center;
            margin: 30px auto 40px;
            font-family: Lato, sans-serif;
        }

        .section-header h1 {
            position: relative;
            font-size: 36px;
            color: black;
            font-family: Lato, sans-serif;
        }

        .testimonials-container {
            position: relative;
        }

        .testimonials-container .testimonial-card {
            padding: 20px;
        }

        .testimonial-card .test-card-body {
            box-shadow: 1px 1px 20px rgb(177 162 162 / 12%);
            padding: 20px;
            border-radius: 10px;
        }

        .test-card-body .quote {
            display: flex;
            align-items: center;
        }

        .test-card-body .quote i {
            font-size: 45px;
            color: #00ff05;
            margin-right: 20px;
        }

        .test-card-body p {
            margin: 10px 0px 15px;
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-clr);
        }

        .test-card-body .ratings {
            margin-top: 20px;
        }

        .test-card-body .ratings i {
            font-size: 17px;
            color: var(--primary-clr);
            cursor: pointer;
        }

        .testimonial-card .profile {
            display: flex;
            align-items: center;
            margin-top: 25px;
        }

        .profile .profile-image {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }

        .profile .profile-image img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile .profile-desc {
            display: flex;
            flex-direction: column;
        }

        .profile-desc span:nth-child(1) {
            font-size: 18px;
            font-weight: 600;
            color: black;
            font-family: Lato, sans-serif;
        }

        .owl-nav {
            position: absolute;
            right: 20px;
            margin-top: 0 !important;
        }

        .owl-nav button {
            border-radius: 50% !important;
        }

        .owl-nav .owl-prev i,
        .owl-nav .owl-next i {
            padding: 10px !important;
            border-radius: 50%;
            font-size: 18px !important;
            background-color: var(--card-clr) !important;
            color: var(--primary-clr);
            cursor: pointer;
            transition: 0.4s;
        }

        .owl-nav .owl-prev i:hover,
        .owl-nav .owl-next i:hover {
            background-color: var(--primary-clr) !important;
            color: #e9e9e9;
        }

        .owl-dots {
            margin-top: 15px;
        }

        .owl-dots .owl-dot span {
            background-color: #434753 !important;
            padding: 6px !important;
        }

        .owl-dot.active span {
            background-color: var(--primary-clr) !important;
        }
    </style>


</head>

<body>

    <?php include '../../PHP/components/navbar.php'; ?>

    <main class="membership" style="background-color: white;">
        <section class="mem-banner-sec">
            <div class="mem-banner-container">

                <!-- Background Image -->
                <img src="https://www.tagheuer.com/on/demandware.static/-/Library-Sites-TagHeuer-Shared/default/dwb72c3d38/images/collections/carrera/precious/TH-headline-banner-revamp-precious-1.jpg"
                    alt="Membership Image" loading="lazy" class="mem-img">

                <!-- Text Content Overlay -->
                <div class="mem-text-overlay">
                    <h1 class="mem-heading">Exclusive Membership Benefits</h1>
                    <p class="mem-desc">
                        Join our exclusive membership program and enjoy luxury and exclusivity tailored for true watch enthusiasts.

                    </p>
                    <a href="#target-section" class="mem-cta-button">
                        Become a Member
                    </a>
                </div>

            </div>
        </section>

        <section class="slogan-description" style="margin-bottom: 40px !important;">
            <div class="centered-container">
                <div class="membership-banner">
                    <h1 class="membership-slogan">Embrace the Timeless – Join Celestial Watches Lifetime Membership</h1>
                    <p class="membership-description">Step into a world where luxury meets exclusivity. Our lifetime membership offers unparalleled benefits, priority access to limited editions, personalized services, and invitations to exclusive events – a lifetime of excellence awaits you.</p>
                </div>
            </div>
        </section>

        <section class="plan" id="target-section">
            <div class="membership-container">
                <!-- Left side: Watch Image -->
                <div class="watch-image">
                    <img src="https://shop.watchgang.com/cdn/shop/files/black-tight_800x_21c926ab-7a74-4893-8fc9-dc79d40e67bd_800x.webp?v=1671056771"
                        loading="lazy"
                        sizes="(max-width: 320px) 55vw, (max-width: 767px) 87vw, (max-width: 991px) 90vw, (max-width: 1439px) 45vw, 540px"
                        srcset="https://shop.watchgang.com/cdn/shop/files/black-tight_800x_21c926ab-7a74-4893-8fc9-dc79d40e67bd_800x.webp?v=1671056771 500w, 
                        https://shop.watchgang.com/cdn/shop/files/black-tight_800x_21c926ab-7a74-4893-8fc9-dc79d40e67bd_800x.webp?v=1671056771 800w, 
                        https://shop.watchgang.com/cdn/shop/files/black-tight_800x_21c926ab-7a74-4893-8fc9-dc79d40e67bd_800x.webp?v=1671056771 805w"
                        alt="Luxury Watch">

                </div>

                <!-- Right side: Membership Details -->
                <div class="membership-plan-details">
                    <h2 class="membership-plan-title"> The Royal Collection Membership</h2>
                    <p class="membership-plan-description">Our most popular plan. You’ll find a variety of luxury watches, limited edition watches, and casual watches all with Swiss or precision Japanese movements. Experience the epitome of luxury, craftsmanship, and exclusivity with each timepiece we offer.</p>

                    <div class="membership-plan">
                        <ul>
                            <li>Get a new watch worth up to $500</li>
                            <li>Every shipment is a chance to win a Rolex!</li>
                            <li>Swiss or Japanese automatic or quartz movement</li>
                            <li>Each watch is guaranteed to be authentic and backed by Celestial Watches Warranty</li>
                            <li>Premium and Craft Watch Brands</li>
                        </ul>
                    </div>

                    <style>
                        .mem-plan {
                            background-color: black;
                            color: white;
                            padding: 20px;
                            width: fit-content;
                            font-weight: 500;
                            text-align: center;
                            letter-spacing: 2px;
                            text-decoration: none;
                            text-transform: uppercase;
                            font-size: 14px;
                            border: 1px solid black;
                            cursor: pointer;
                            transition: 0.3s ease-in-out;
                        }

                        .mem-plan:hover {
                            background-color: white;
                            color: black;
                        }
                    </style>

                    <div class="mem-plan">Join Now & Elevate Your Time</div>
                </div>
            </div>
        </section>

        <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">

        <style>
            .membership-benefits {
                text-align: center;
                background-color: white;
                position: relative;
            }

            .title-container {
                text-align: center;
                margin-bottom: 40px;
                position: relative;
                z-index: 2;
            }

            .benefits-title {
                font-size: 1.5rem;
                font-family: 'Playfair Display', serif;
                color: #333;
                margin-bottom: 20px;
            }

            .watch-image-timeline {
                width: 150px;
                height: auto;
                border-radius: 50%;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                margin: 0 auto;
            }

            .timeline-container {
                position: relative;
                max-width: 1200px;
                margin: 0 auto;
                counter-reset: item-counter;
                z-index: 2;
            }

            .timeline-item {
                display: flex;
                justify-content: center;
                margin: 20px 0;
                position: relative;
            }

            .timeline-item.left {
                justify-content: flex-start;
                text-align: right;
            }

            .timeline-item.right {
                justify-content: flex-end;
                text-align: left;
            }

            .content {
                background: #f5f5f5;
                padding: 35px;
                width: 51%;
                text-align: left;
            }

            .content h3 {
                font-size: 20px;
                font-family: 'Lora', serif;
                color: #333;
                margin-bottom: 10px;
                white-space: pre-line;
            }

            .content p {
                font-size: 1rem;
                color: #666;
                line-height: 1.6;
            }

            .timeline-container::before {
                content: "";
                position: absolute;
                width: 4px;
                background-color: #b8b8b8;
                top: 0;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
            }

            .timeline-item.left::after,
            .timeline-item.right::after {
                counter-increment: item-counter;
                content: counter(item-counter);
                position: absolute;
                font-size: 1.1rem;
                font-weight: bold;
                color: #333;
                top: 15px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #fff;
                border-radius: 50%;
                padding: 5px 15px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .timeline-item.left::after:hover,
            .timeline-item.right::after:hover {
                background-color: black;
                color: white;
            }

            .benefit-detail {
                font-size: 1rem;
                color: #666;
                line-height: 1.6;
            }

            /* Video Background Styling */
            section {
                background-color: white;
            }

            .video-background {
                padding-top: 10px;
                width: 100%;
                height: 100%;
                z-index: 1;
            }

            .video {
                object-fit: cover;
                width: 100%;
                height: 500px;
                opacity: 1;
            }
        </style>

        <section class="membership-benefits">
            <!-- Title and watch image at the top -->
            <div class="title-container">
                <img src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/238bfd03-2f60-4cbd-780d-d7985ccd5c00/w=200x200" alt="Luxury Watch" loading="lazy" class="watch-image-timeline">
                <h2 class="benefits-title">Membership Benefits</h2>
            </div>

            <!-- Timeline starts here -->
            <div class="timeline-container">
                <div class="timeline-item left">
                    <div class="content">
                        <h3>Special Offers</h3>
                        <p>As a member, you gain access to exclusive offers and discounts tailored to your luxury watch preferences.</p>
                    </div>
                </div>

                <div class="timeline-item right">
                    <div class="content">
                        <h3>Early Sale Access</h3>
                        <p>Get priority access to sales events and limited-time offers, ensuring you're among the first to secure rare finds.</p>
                    </div>
                </div>

                <div class="timeline-item left">
                    <div class="content">
                        <h3>Exclusive Discounts on Listing Fees</h3>
                        <p>Members enjoy reduced listing fees, providing an opportunity to showcase your collection at a lower cost.</p>
                    </div>
                </div>

                <div class="timeline-item right">
                    <div class="content">
                        <h3>Priority Customer Support</h3>
                        <p>Receive fast-tracked, dedicated support to ensure that your needs are addressed with the highest level of care.</p>
                    </div>
                </div>

                <div class="timeline-item left">
                    <div class="content">
                        <h3>Access to Analytics and Sales Reports</h3>
                        <p>Gain access to detailed analytics and sales reports, empowering you to make data-driven decisions for your watch collection.</p>
                    </div>
                </div>

                <div class="timeline-item right">
                    <div class="content">
                        <h3>Members-Only Events & Auctions</h3>
                        <p>Enjoy invitations to exclusive members-only events and auctions, where you can connect with like-minded collectors and enthusiasts.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonial-section" style="margin-bottom: 40px !important;">
            <div class="main-wrapper">
                <!-- Testimonial Section Starts -->
                <div class="testimonials-section">
                    <!-- Section Header Starts -->
                    <div class="section-header">
                        <h1>What Clients Say</h1>
                    </div>
                    <!-- Section Header Ends -->

                    <!-- Owl Carousel Slider Starts -->
                    <div class="owl-carousel owl-theme testimonials-container">
                        <!-- Item1 Starts -->
                        <div class="item testimonial-card">
                            <div class="test-card-body">
                                <div class="quote">
                                    <i class="fa fa-quote-left"></i>

                                </div>
                                <p>"I recently purchased a stunning limited edition watch from this site, and I couldn't be happier! The craftsmanship is flawless, and it arrived in perfect condition. It's an absolute statement piece on my wrist. Highly recommend to anyone looking for quality and exclusivity in their timepieces."</p>
                                <div class="ratings">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="profile-image">
                                    <img src="image1.jpg" loading="lazy" alt="Johnathan Smith">
                                </div>
                                <div class="profile-desc">
                                    <span>Johnathan Smith</span>
                                </div>
                            </div>
                        </div>
                        <!-- Item1 Ends -->

                        <!-- Item2 Starts -->
                        <div class="item testimonial-card">
                            <div class="test-card-body">
                                <div class="quote">
                                    <i class="fa fa-quote-left"></i>
                                </div>
                                <p>"Great experience buying from here! The customer service was exceptional, and the watch exceeded my expectations. The attention to detail on the dial is impeccable, and the overall design is timeless. Worth every penny for a luxury piece like this."</p>
                                <div class="ratings">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="profile-image">
                                    <img src="image2.jpg" loading="lazy" alt="Emily Carter">
                                </div>
                                <div class="profile-desc">
                                    <span>Emily Carter</span>
                                </div>
                            </div>
                        </div>
                        <!-- Item2 Ends -->

                        <!-- Item3 Starts -->
                        <div class="item testimonial-card">
                            <div class="test-card-body">
                                <div class="quote">
                                    <i class="fa fa-quote-left"></i>
                                </div>
                                <p>"Absolutely love my new tourbillon watch from Celestial Watches. The design is elegant, and the movement is smooth. My only wish was for faster delivery, but the wait was worth it. If you're into high-end timepieces, this is the place to shop."</p>
                                <div class="ratings">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="profile-image">
                                    <img src="image3.jpg" loading="lazy" alt="Michael Johnson">
                                </div>
                                <div class="profile-desc">
                                    <span>Michael Johnson</span>
                                </div>
                            </div>
                        </div>
                        <!-- Item3 Ends -->

                        <!-- Item4 Starts -->
                        <div class="item testimonial-card">
                            <div class="test-card-body">
                                <div class="quote">
                                    <i class="fa fa-quote-left"></i>

                                </div>
                                <p>"I am beyond impressed with the quality of the watch I purchased! The diamond-studded bezel gives it a luxurious touch, and it looks even more beautiful in person. The packaging was exquisite, and the watch arrived in pristine condition. I’ll definitely be back for more."</p>
                                <div class="ratings">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="profile-image">
                                    <img src="image4.jpg" loading="lazy" alt="Sophia Lee">
                                </div>
                                <div class="profile-desc">
                                    <span>Sophia Lee</span>
                                </div>
                            </div>
                        </div>
                        <!-- Item4 Ends -->

                        <!-- Item5 Starts -->
                        <div class="item testimonial-card">
                            <div class="test-card-body">
                                <div class="quote">
                                    <i class="fa fa-quote-left"></i>

                                </div>
                                <p>"Celestial Watches offers a fantastic selection of luxury timepieces. The watch I bought has a unique, limited edition design that's both sophisticated and stylish. My only complaint is that I wish there were more options in my budget range. Otherwise, a perfect experience!"</p>
                                <div class="ratings">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="profile-image">
                                    <img src="image5.jpg" loading="lazy" alt="Daniel Brown">
                                </div>
                                <div class="profile-desc">
                                    <span>Daniel Brown</span>
                                </div>
                            </div>
                        </div>
                        <!-- Item5 Ends -->

                        <!-- Item6 Starts -->
                        <div class="item testimonial-card">
                            <div class="test-card-body">
                                <div class="quote">
                                    <i class="fa fa-quote-left"></i>

                                </div>
                                <p>"I recently purchased a classic chronograph from this store, and I’m in love with it! It’s comfortable, elegant, and it makes a bold statement. The team was also very helpful in assisting me with the perfect fit. Highly recommend this website to all watch enthusiasts!"</p>
                                <div class="ratings">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="profile-image">
                                    <img src="image6.jpg" loading="lazy" alt="Olivia Davis">
                                </div>
                                <div class="profile-desc">
                                    <span>Olivia Davis</span>
                                </div>
                            </div>
                        </div>
                        <!-- Item6 Ends -->
                    </div>
                    <!-- Owl Carousel Slider Ends -->
                </div>
                <!-- Testimonial Section Ends -->
            </div>
        </section>

        <section class="membership-video">

            <div class="video-background">
                <img src="	https://media.gq.com/photos/57ffacbfbcbaa8b0566b4c5e/16:9/w_2560%2Cc_limit/best-watches-patek-01.jpg" loading="lazy" class="video" alt="">
            </div>
        </section>

        <?php include '../../PHP/components/footer.php' ?>

    </main>

    <!--   *****   JQuery Link   *****   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <!--   *****   Owl Carousel js Link  *****  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        // This is script file

        $(document).ready(function() {
            $('.testimonials-container').owlCarousel({
                loop: true,
                autoplay: true,
                autoplayTimeout: 6000,
                margin: 10,
                nav: true,
                navText: ["<i class='fa-solid fa-arrow-left'></i>", "<i class='fa-solid fa-arrow-right'></i>"],
                responsive: {
                    0: {
                        items: 1,
                        nav: false
                    },
                    600: {
                        items: 1,
                        nav: true
                    },
                    768: {
                        items: 2
                    },
                }
            });
        });
    </script>


    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <script src="/src/assets/js/cookie-monitor.js" async></script>

</body>

</html>