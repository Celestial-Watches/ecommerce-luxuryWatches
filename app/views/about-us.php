<?php define('ALLOW_ACCESS', true); ?>
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




    <!-- ============= FONTS=============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        .logg-button::before {
            content: "";
            position: absolute;
            width: 100px;
            height: 100%;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0) 70%);
            top: 0;
            left: -100px;
            opacity: 0.6;
        }

        /* Banner Container */
        .banner {
            position: relative;
            width: 100%;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://cdn1.ethoswatches.com/media/desktop/cmspage/about-header@2x.jpg') right center no-repeat;
            background-size: cover;
        }

        /* Content Overlay */
        .banner-content {
            position: absolute;
            color: #fff;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .banner-content .subheading {
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: #c2b7b3;
            font-family: sans-serif;
        }

        .banner-content .main-heading {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            font-family: sans-serif;
        }

        .banner-content .separator {
            width: 40px;
            height: 1px;
            background: #808080;
            margin-top: 15px;
        }

        .banner-about-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            border-top: 2px solid #fcf0ec;
            border-bottom: 2px solid #fcf0ec;
            width: 100%;
            margin-top: 30px;
            box-sizing: border-box;
        }

        .about-container {
            max-width: 800px;
            line-height: 1.6;
            font-size: 18px;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }


        /* Container */
        .reasons-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        /* Heading */
        .reasons-container h2 {
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 40px;
        }

        /* Grid Layout */
        .reasons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px 60px;
        }

        /* Each Reason Item */
        .reason-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        /* Icon Style */
        .reason-item .icon {
            font-size: 40px;
            color: #333;
            margin-bottom: 36px !important;
        }

        /* Headings */
        .reason-item h3 {
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 8px;
        }

        /* Description */
        .reason-item p {
            font-size: 12px;
            line-height: 2;
            color: #666;
        }

        /* 
        .carousel-control-prev {
            left: -85px;
            overflow: hidden;
        }

        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3E%3Cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
        }

        .carousel-control-next {
            width: 3%;
        }

        .carousel-control-next .carousel-control-next-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        } */

        .carousel {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .carousel-images {
            display: flex;
            transition: transform 0.5s ease;
            height: 100%;
        }

        .carousel-images img {
            height: 100%;
            width: 100vw;
            margin: 0 auto;
            flex-shrink: 0;
        }

        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: red;
            color: white;
            font-size: 24px;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            z-index: 10;
        }

        .carousel-control.prev {
            left: 10px;
        }

        .carousel-control.next {
            right: 10px;
        }

        .carousel-control:hover {
            background-color: darkred;
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .carousel {
                height: 80vh;
            }
        }

        @media (max-width: 768px) {
            .carousel {
                height: 45vh;
            }

            .carousel-control {
                font-size: 18px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .carousel {
                height: 35vh;
                margin-top: 15px;
            }

            .carousel-control {
                font-size: 16px;
                padding: 6px;
            }
        }

        .store-locator {
            text-align: center;
            padding: 50px 20px;
            background-color: #ffffff;
        }

        .section-title {
            font-size: 14px !important;
            line-height: 22px !important;
            margin-bottom: 10px;
        }

        .section-subtitle {
            font-size: 24px;
            font-weight: 400 !important;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .store-info {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-bottom: 40px;
            position: relative;
        }

        .t-shape-line {
            width: 2px;
            background-color: #e4e4e4;
            position: relative;
            flex-shrink: 0;
            height: 240px;
            margin: 0 20px;
        }

        .t-shape-line::before {
            content: '';
            display: block;
            width: 50px;
            height: 2px;
            background-color: #f4f4f4;
            position: absolute;
            top: 0;
            left: -23px;
        }

        .store-count {
            width: auto;
        }

        .store-count,
        .city-count {
            margin: 30px 70px;
            max-width: 350px;
            text-align: center;
            place-items: center;
        }

        .store-icon,
        .city-icon {
            font-size: 50px;
            margin-bottom: 60px;
            width: 50px;
        }

        .store-title,
        .city-title {
            font-size: 16px;
            margin: 10px 0;
            font-weight: 400;
        }

        .store-description,
        .city-description {
            font-size: 12px;
            color: #808080;
        }

        .map-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .map-image {
            width: 100%;
            height: auto;
        }

        .icon_cities {
            background: url(https://cdn2.ethoswatches.com/static/frontend/Ethos-v2/destkop/en_US/Magento_Cms/images/location.svg) no-repeat;
        }

        .brand-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(4, 1fr);
            gap: 10px;
            margin: 10px;
            padding-right: 0;
            padding-left: 0;
        }

        .brand-grid {
            border: 1px solid #f4f4f4;
            width: 100%;
            height: 100%;
        }

        @media (max-width: 768px) {
            .brand-container {
                grid-template-columns: repeat(4, 1fr);
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .brand-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Watch care */

        .watch-care-section {
            background: url('https://cdn1.ethoswatches.com/media/desktop/cmspage/watch-care-bg@2x.jpg') no-repeat center center;
            background-size: cover !important;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        /* Content styling */
        .watch-care-content {
            max-width: 750px;
            padding: 20px;
            text-align: center;
            line-height: 28px !important;
            margin-left: 50%;
            margin-top: 10%;
            /* position: absolute; */
        }

        .watch-care-logo {
            margin-bottom: 20px;
        }

        .watch-care-heading {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .watch-care-divider {
            width: 50px;
            border: 1px solid #ccc;
            margin: 10px auto 20px auto;
        }

        .watch-care-description,
        .watch-care-contact-info {
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 15px;
            color: #666;
            font-family: sans-serif;
        }

        .watch-care-contact-info span {
            display: block;
        }

        .learn-more-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #9d0000;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500 !important;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .learn-more-button:hover {
            background-color: #a30000;
        }

        /* Responsive styles */
        @media (max-width: 1200px) {
            .watch-care-section {
                background-position: 40% center;
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .watch-care-section {
                flex-direction: column;
                align-items: center;
                background-size: cover;
            }

            .watch-care-content {
                max-width: 90%;
                padding: 15px;
            }

            .watch-care-heading {
                font-size: 20px;
            }

            .watch-care-description,
            .watch-care-contact-info {
                font-size: 11px;
            }

            .learn-more-button {
                padding: 10px 20px;
                font-size: 11px;
            }

            .store-count,
            .city-count {
                padding: 30px 30px;
            }
        }

        @media (max-width: 480px) {

            .about-container{
                font-size: 12px;
            }

            .about-container > p{
                width: 300px;
            }

            .reasons-container h2 {
                font-size: 16px;
            }

            .store-title,
            .city-title {
                font-size: 10px;
            }

            .store-count,
            .city-count {
                padding: 0px 30px;
                place-items: center;
            }

            .store-info {
                display: block;
            }

            .t-shape-line {
                display: none;
            }

            .store-description,
            .city-description {
                font-size: 14px;
                width: 275px;
            }

            .reasons-grid {
                gap: 0px 20px;
                grid-template-columns: repeat(1, 1fr);
            }

            .reason-item h3{
                text-wrap: nowrap;
            }

            .reason-item{
                padding: 10px;
            }

            .reasons-container{
                padding: 0;
            }

            .watch-care-section {
                background: none;
                min-height: 0;
            }

            .watch-care-content {
                max-width: 100%;
                padding: 10px;
                margin-left: 0;
            }

            .watch-care-heading {
                font-size: 18px;
            }

            .watch-care-description,
            .watch-care-contact-info {
                font-size: 10px;
                line-height: 1.4;
            }

            .learn-more-button {
                padding: 8px 16px;
                font-size: 10px;
            }
        }

        /* How to reach us */

        .helpline {
            padding: 65px 0;
        }

        .help-container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .help-row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }

        .inner-container {
            width: 100%;
            text-align: center;
            padding: 0 15px;
        }

        .help-banner-content {
            /* margin-bottom: 65px; */
            place-items: center;
        }

        .helpline-subtitle {
            font-size: 12px;
        }

        .helpline-title {
            color: #000000;
            font-size: 18px;
            font-weight: normal;
            line-height: 3;
        }

        .v-line {
            border-top: 1px solid;
            width: 40px;
            margin: 10px auto;
        }

        .helpline-description {
            font-size: 12px;
            width: 75%;
            margin: 0 auto;
        }

        .lux_helpline {
            margin-top: 65px;
            margin-bottom: 15px;
            display: flex;
        }

        .helpline_content {
            flex: 0 0 33.333333%;
            max-width: 31.333333%;
            padding: 0 15px;
            line-height: 2;
            font-size: 12px;
        }

        .helpline-link {
            display: block;
            text-decoration: none;
        }

        .helpline-icon {
            width: 40px;
        }

        .helpline-label {
            display: block;
        }

        .color_00 {
            color: #000000;
        }

        .color_80 {
            color: #808080;
        }

        .h-line {
            border-left: 1px solid #808080;
            height: 110px;
            margin: 0 15px;
        }

        .helpline-info {
            display: block;
        }

        .help-desc-title {
            width: 80%;
            line-height: 22px;
            margin-top: 15px;
        }

        /* Helpline */

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
            font-size: 16px;
            margin-bottom: 8px;
        }

        .luxury-helpline-numbers {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .luxury-phone-number {
            font-size: 18px;
            color: #fff;
            font-weight: bold;
        }

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
            .luxury-phone-number{
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
    </style>
</head>

<body>
    <?php include '../../PHP/components/navbar.php'; ?>
    <div class="banner">
        <div class="banner-content">
            <div class="subheading">About Celestial Watches</div>
            <div class="main-heading">Who We Are</div>
            <div class="separator"></div>
        </div>
    </div>

    <div class="banner-about-container">
        <div class="about-container">
            <p>
                With more than 60 stores in India and over 20 premium luxury watch brands, Celestial Watches is India’s largest chain of luxury watch boutiques. We take pride in helping our customers choose the perfect watch for themselves and their loved ones while protecting them from rampant malpractices in India such as smuggled, fake, and refurbished watches.
            </p>
        </div>
    </div>

    <div class="reasons-container">
        <h2>6 Great Reasons to Buy a Watch from Celestial</h2>
        <div class="reasons-grid">
            <div class="reason-item">
                <i class="icon ri-shield-check-line"></i>
                <h3>Authorised Retailer</h3>
                <p>Celestial is an authorised retailer with over 20 luxury watch brands. Every watch that we sell comes with a brand warranty and also gets our Celestial stamp of assurance.</p>
            </div>
            <div class="reason-item">
                <i style="font-size: 40px; margin-bottom: 15px;" class="ri-shield-keyhole-line"></i>
                <h3>Trust</h3>
                <p>Employing over 500 people in India, Celestial ethics are an integral part of our DNA. You can rest assured that your horological investment is a genuine piece warranted by the respective brand.</p>
            </div>
            <div class="reason-item">
                <i class="icon ri-trophy-line"></i>
                <h3>Largest Selection</h3>
                <p>With more than 60 stores and over 20 premium luxury watch brands, offering 5000+ varied watches at any given time, we have one of the largest selections in every brand.</p>
            </div>
            <div class="reason-item">
                <i class="icon ri-store-2-line"></i>
                <h3>Knowledgeable Staff and Great Boutiques</h3>
                <p>Well-trained staff and great-looking boutiques ensure that we make shopping for watches an enjoyable and unforgettable experience.</p>
            </div>
            <div class="reason-item">
                <i class="icon ri-customer-service-2-line"></i>
                <h3>Dedicated After-Sales Staff</h3>
                <p>We value your watch as much as you do. A dedicated team and state-of-the-art facilities in multiple cities ensure that your watch ticks for generations.</p>
            </div>
            <div class="reason-item">
                <i class="icon ri-gift-line"></i>
                <h3>Loyalty Programme</h3>
                <p>As a part of Celestial’ Club ECHO Loyalty Programme, you not only get access to points that you can collect and redeem regularly but also get invited to watch collector events and wine-tasting sessions, get gifts, rewards & more.</p>
            </div>
        </div>
    </div>

    <div class="carousel">
        <div class="carousel-images">
            <img src="https://cdn1.ethoswatches.com/media/desktop/cmspage/about-slider-3@2x.jpg" alt="Image 1" />
            <img src="https://cdn1.ethoswatches.com/media/desktop/cmspage/about-slider-2@2x.jpg" alt="Image 2" />
            <img src="https://cdn1.ethoswatches.com/media/desktop/cmspage/about-slider-1@2x.jpg" alt="Image 3" />
        </div>
        <button class="carousel-control prev" onclick="prevSlide()">&#10094;</button>
        <button class="carousel-control next" onclick="nextSlide()">&#10095;</button>
    </div>

    <!-- <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="10000">
                <img src="	https://cdn1.ethoswatches.com/media/desktop/cmspage/about-slider-3@2x.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item" data-bs-interval="2000">
                <img src="	https://cdn1.ethoswatches.com/media/desktop/cmspage/about-slider-2@2x.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="	https://cdn1.ethoswatches.com/media/desktop/cmspage/about-slider-1@2x.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div> -->

    <section class="store-locator">
        <p class="section-title">WHERE TO FIND US</p>
        <h2 class="section-subtitle">LOCATE AN CW STORE</h2>
        <div class="store-info">
            <div class="store-count">
                <div class="icon store-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 22.784">
                        <g id="Group_1377" data-name="Group 1377" transform="translate(0 0.1)">
                            <path id="Path_1753" data-name="Path 1753" d="M8.094,74.856h5.953V69.495H8.094v5.361Zm6.369.831H7.678a.417.417,0,0,1-.416-.416V69.079a.417.417,0,0,1,.416-.416h6.784a.417.417,0,0,1,.416.416v6.192a.41.41,0,0,1-.416.416Zm3.4,4.592h5.662V70.69H17.86v9.59Zm6.494,0h2.556V65.931H5V80.279H17.029V70.274a.417.417,0,0,1,.416-.416h6.494a.417.417,0,0,1,.416.416V80.279Zm7.231.831H.416a.416.416,0,0,1,0-.831H4.166V65.516a.417.417,0,0,1,.416-.416H27.325a.417.417,0,0,1,.416.416V80.279h3.844a.416.416,0,0,1,0,.831Z" transform="translate(0 -58.426)" />
                            <path id="Path_1754" data-name="Path 1754" d="M14.937,6.764h1.974L18.189.831h-1.61L14.937,6.764Zm2.306.831H14.387a.434.434,0,0,1-.332-.166.416.416,0,0,1-.073-.364L15.851.3a.447.447,0,0,1,.416-.3H18.7a.389.389,0,0,1,.322.156A.393.393,0,0,1,19.1.509L17.659,7.273a.424.424,0,0,1-.416.322Z" transform="translate(-12.516 -0.09)" />
                            <path id="Path_1755" data-name="Path 1755" d="M63.05,6.764h1.995L65.668.831H64.048l-1,5.932Zm2.369.831H62.562a.426.426,0,0,1-.322-.145.4.4,0,0,1-.094-.332L63.289.343A.412.412,0,0,1,63.694,0h2.431a.45.45,0,0,1,.312.135.406.406,0,0,1,.1.322l-.717,6.764a.4.4,0,0,1-.405.374Z" transform="translate(-55.684 -0.09)" />
                            <path id="Path_1756" data-name="Path 1756" d="M111.162,6.674h2.005L113.136.742h-1.631l-.343,5.932Zm2.41.831h-2.857a.409.409,0,0,1-.3-.135.43.43,0,0,1-.114-.312l.395-6.764A.42.42,0,0,1,111.11-.1h2.431a.417.417,0,0,1,.416.416l.031,6.764a.377.377,0,0,1-.125.291.354.354,0,0,1-.291.135Z" transform="translate(-98.84)" />
                            <path id="Path_1757" data-name="Path 1757" d="M70.449.831H49.016a.416.416,0,1,1,0-.831H70.449a.416.416,0,1,1,0,.831Z" transform="translate(-43.551 -0.09)" />
                            <path id="Path_1758" data-name="Path 1758" d="M156.34,6.764h1.995L157.659.831h-1.631l.312,5.932Zm2.462.831h-2.857a.42.42,0,0,1-.416-.395L155.186.436A.391.391,0,0,1,155.3.125.417.417,0,0,1,155.592,0h2.431a.424.424,0,0,1,.416.364l.769,6.764a.406.406,0,0,1-.1.322.384.384,0,0,1-.3.145Z" transform="translate(-139.062 -0.09)" />
                            <path id="Path_1759" data-name="Path 1759" d="M198,6.764h1.984L198.661.831h-1.61L198,6.764Zm2.5.831h-2.857a.4.4,0,0,1-.405-.353L196.146.478a.387.387,0,0,1,.094-.332A.421.421,0,0,1,196.551,0h2.431a.413.413,0,0,1,.405.322L200.9,7.1a.4.4,0,0,1-.083.353.41.41,0,0,1-.322.145Z" transform="translate(-175.762 -0.09)" />
                            <path id="Path_1760" data-name="Path 1760" d="M239.727,6.674h1.964L239.716.742h-1.59l1.6,5.932Zm2.535.831H239.4A.421.421,0,0,1,239,7.194L237.181.43a.422.422,0,0,1,.405-.53h2.431a.415.415,0,0,1,.395.281l2.244,6.784a.414.414,0,0,1-.395.54Z" transform="translate(-212.527 0)" />
                        </g>
                    </svg>
                </div>
                <h4 class="store-title">60 STORES & COUNTING</h4>
                <p class="store-description">Our endeavour is to make Celestial Watches and the brands we retail in, as accessible to you as possible, with locations that are convenient for all.</p>
            </div>

            <div class="t-shape-line"></div>

            <div class="city-count">
                <div class="icon city-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30.758 32">
                        <g id="icons8-geo_fence" transform="translate(-1 -0.063)">
                            <path id="Path_2105" data-name="Path 2105" d="M16.379.063A8.943,8.943,0,0,0,7.448,8.994c0,4.08,2.14,8.758,4.245,12.476A62.968,62.968,0,0,0,15.9,27.937a.593.593,0,0,0,.961,0,64.76,64.76,0,0,0,4.205-6.528c2.1-3.74,4.245-8.421,4.245-12.416A8.943,8.943,0,0,0,16.379.063Zm0,1.2a7.715,7.715,0,0,1,7.73,7.73c0,3.559-2.023,8.17-4.085,11.835a57.533,57.533,0,0,1-3.645,5.727,54.9,54.9,0,0,1-3.645-5.667c-2.06-3.64-4.085-8.24-4.085-11.895A7.715,7.715,0,0,1,16.379,1.264Zm0,3.9a4.466,4.466,0,1,0,4.466,4.466A4.476,4.476,0,0,0,16.379,5.169Zm0,1.242a3.224,3.224,0,1,1-3.224,3.224A3.212,3.212,0,0,1,16.379,6.41Zm-6.308,15.5a22.352,22.352,0,0,0-6.328,1.742A6.675,6.675,0,0,0,1.8,24.974a2.649,2.649,0,0,0-.8,1.8A3.011,3.011,0,0,0,2.422,29.1a11.35,11.35,0,0,0,3.344,1.562,36.552,36.552,0,0,0,10.613,1.4,36.552,36.552,0,0,0,10.613-1.4A11.35,11.35,0,0,0,30.337,29.1a3.011,3.011,0,0,0,1.422-2.323,2.654,2.654,0,0,0-.8-1.8,6.675,6.675,0,0,0-1.942-1.322,22.352,22.352,0,0,0-6.328-1.742.649.649,0,1,0-.2,1.282,21.526,21.526,0,0,1,5.967,1.622,5.681,5.681,0,0,1,1.582,1.061,1.3,1.3,0,0,1,.441.9c0,.368-.245.791-.9,1.282a10.315,10.315,0,0,1-2.964,1.382,35.6,35.6,0,0,1-10.233,1.342A35.6,35.6,0,0,1,6.146,29.439a10.315,10.315,0,0,1-2.964-1.382c-.656-.491-.9-.914-.9-1.282a1.3,1.3,0,0,1,.441-.9A5.681,5.681,0,0,1,4.3,24.813a21.526,21.526,0,0,1,5.967-1.622.649.649,0,1,0-.2-1.282Z" />
                        </g>
                    </svg>
                </div>
                <h4 class="city-title">ACROSS 23 CITIES</h4>
                <p class="city-description">With more than 60 stores prominently located in all major cities, Celestial Watches is continuously growing its presence Pan India.</p>
            </div>
        </div>
        <div class="map-container">
            <img src="https://cdn1.ethoswatches.com/media/desktop/cmspage/globe-pin.svg" alt="World Map" class="map-image">
        </div>
    </section>

    <section class="store-locator">
        <p class="section-title">WHO WE REPRESENT</p>
        <h2 class="section-subtitle">Luxury Brands available at Celestial</h2>

        <div class="v-line brand-line"></div>


        <div class="brand-container">
            <div class="brand-grid div1">
                <li class="logo-rolex">
                    <a class="role" href="https://www.ethoswatches.com/rolex/" title="Rolex Watches">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203"
                            height="109" viewBox="0 0 203 109" style="max-width: 80px;">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_1" data-name="Rectangle 1" width="606" height="353"
                                        transform="translate(0.16 0.298)"></rect>
                                </clipPath>
                                <clipPath id="clip-Rolex">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Rolex" clip-path="url(#clip-Rolex)">

                                <g id="Rolex-2" data-name="Rolex" transform="translate(-201.16 -122.298)"
                                    clip-path="url(#clip-path)">
                                    <g id="Group_88" data-name="Group 88" transform="translate(209.313 122.546)">
                                        <g id="rolex-3" data-name="rolex">
                                            <path id="Path_425"
                                                d="M466.678,1324.795h.876l9.42,31.767h0c1.1,3.5,6.133,6.135,12.486,6.135,6.133,0,11.391-2.63,12.487-6.135h0l9.419-31.765h.657a3.178,3.178,0,1,0-3.286-3.065,3.12,3.12,0,0,0,1.971,2.848l-10.734,23.22,1.753-31.325h0a3.286,3.286,0,1,0-3.286-3.286,2.891,2.891,0,0,0,2.41,3.068l-7.228,30.668-3.5-33.736a3.314,3.314,0,1,0-3.724-2.848,3.258,3.258,0,0,0,2.848,2.848l-3.5,33.734-7.228-30.448a3.5,3.5,0,0,0,2.409-3.067,3.286,3.286,0,1,0-3.286,3.286h0l1.753,31.325L468.432,1324.8a2.931,2.931,0,0,0,1.753-2.847,3.287,3.287,0,1,0-6.574,0C463.393,1323.267,464.926,1324.795,466.678,1324.795Zm22.563,28.916c5.7,0,10.3,1.533,10.3,3.284s-4.6,3.287-10.3,3.287-10.3-1.534-10.3-3.287S483.545,1353.711,489.241,1353.711Z"
                                                transform="translate(-394.609 -1303.33)" fill="#fff"></path>
                                            <path id="Path_426"
                                                d="M467.747,1338.3a16.867,16.867,0,1,0,17.086,16.648A16.866,16.866,0,0,0,467.747,1338.3Zm7.228,26.505a4.3,4.3,0,0,1-1.971,1.97,8.91,8.91,0,0,1-4.162,1.754H467.09a6,6,0,0,1-4.162-1.754,8.433,8.433,0,0,1-1.971-1.97,16.6,16.6,0,0,1-2.848-9.639,17.243,17.243,0,0,1,2.629-9.2,7.728,7.728,0,0,1,3.944-3.5h.22c.22,0,.438-.22.658-.22a6.85,6.85,0,0,1,4.819,0c.22,0,.438.22.657.22h.22a7.736,7.736,0,0,1,3.944,3.5,17.249,17.249,0,0,1,2.63,9.206,16.6,16.6,0,0,1-2.848,9.637Z"
                                                transform="translate(-409.479 -1265.575)" fill="#fff"></path>
                                            <path id="Path_427"
                                                d="M498.073,1358.076h-2.41v4.82a5.144,5.144,0,0,1-5.259,5.04h-10.07V1341.21h4.6v-2.41H468.5v2.41h4.6v26.726h-4.6v2.41h29.573v-7.668h0Z"
                                                transform="translate(-388.764 -1264.978)" fill="#fff"></path>
                                            <path id="Path_428"
                                                d="M515.525,1338.8H484.2v2.41h4.6v26.726h-4.6v2.409h31.325v-11.172h-2.409v3.723h0a5.332,5.332,0,0,1-5.259,5.261H496.033v-13.363h3.724a3.926,3.926,0,0,1,3.944,3.944h0v1.315h0v.439h2.191v-5.7h0v-1.971h0v-5.7H503.7v1.532h0a3.838,3.838,0,0,1-3.724,3.724h-3.944v-11.171h11.61a5.33,5.33,0,0,1,5.259,5.259h0v3.287h2.41v-3.071Z"
                                                transform="translate(-370.073 -1264.978)" fill="#fff"></path>
                                            <path id="Path_429"
                                                d="M536.5,1367.939c-2.19,0-2.848-.658-4.6-2.849l-10.518-12.923,6.79-8.324h0c1.753-2.19,2.409-2.849,4.819-2.849h1.753V1338.8H522.268v2.192H524.9a1,1,0,0,1,1.1.876v.22a.809.809,0,0,1-.22.657l-6.134,7.229-4.819-5.912-1.1-1.315a.81.81,0,0,1-.22-.658,1,1,0,0,1,.876-1.1h2.848V1338.8H501.676v2.192h.657c1.971,0,2.63.876,4.162,2.629l9.2,11.39-8.323,9.859h0c-1.753,2.19-2.41,2.848-4.82,2.848H500.8v2.19h13.363v-2.19h-3.286a1,1,0,0,1-1.1-.879v-.217a.808.808,0,0,1,.219-.657l7.448-8.763,6.134,7.667.876,1.1a.812.812,0,0,1,.22.658,1,1,0,0,1-.876,1.095h-2.848v2.191h17.085v-2.191l-1.533.22Z"
                                                transform="translate(-350.309 -1264.978)" fill="#fff"></path>
                                            <path id="Path_430"
                                                d="M467.43,1367.937l-2.19-6.133a10.272,10.272,0,0,0-3.286-5.259,7.185,7.185,0,0,0-3.5-1.532,8.164,8.164,0,0,0-1.315-16.211H432.82v2.409H437.2v26.726h-4.6v2.411h16.429v-2.411h-4.6v-12.924h4.6a14.807,14.807,0,0,1,3.067.437,6.568,6.568,0,0,1,2.19.877,12.475,12.475,0,0,1,2.409,2.409c.22.437.438.657.438,1.094a34.275,34.275,0,0,1,3.067,10.519h10.3v-2.41ZM452.753,1352.6h-7.667v-11.608h7.667a5.806,5.806,0,1,1,.22,11.61Z"
                                                transform="translate(-431.505 -1264.979)" fill="#fff"></path>
                                            <path id="Path_431"
                                                d="M466.667,1323.894h.876l9.419,31.763h0c1.1,3.505,6.133,6.134,12.487,6.134,6.133,0,11.391-2.629,12.486-6.134h0l9.419-31.763h.657a3.179,3.179,0,1,0-3.286-3.067,3.119,3.119,0,0,0,1.971,2.848l-10.734,23.22,1.753-31.324h0a3.286,3.286,0,1,0-3.286-3.287,2.891,2.891,0,0,0,2.409,3.067l-7.228,30.667-3.5-33.735a3.314,3.314,0,1,0-3.724-2.848,3.258,3.258,0,0,0,2.848,2.848l-3.5,33.735L478.5,1315.57a3.5,3.5,0,0,0,2.409-3.067,3.286,3.286,0,1,0-3.286,3.287h0l1.753,31.324-10.953-23.219a2.931,2.931,0,0,0,1.753-2.848,3.287,3.287,0,0,0-6.574,0,2.9,2.9,0,0,0,2.848,2.848Zm22.563,29.136c5.7,0,10.3,1.532,10.3,3.286s-4.6,3.286-10.3,3.286-10.3-1.533-10.3-3.286,4.6-3.284,10.3-3.284Z"
                                                transform="translate(-394.598 -1304.402)" fill="#a37e2c"></path>
                                            <path id="Path_432"
                                                d="M467.747,1337.5a16.867,16.867,0,1,0,17.086,16.649A16.868,16.868,0,0,0,467.747,1337.5Zm7.228,26.5a4.3,4.3,0,0,1-1.971,1.972,8.907,8.907,0,0,1-4.162,1.752H467.09a5.979,5.979,0,0,1-4.161-1.752,8.409,8.409,0,0,1-1.972-1.972,16.6,16.6,0,0,1-2.848-9.638,17.234,17.234,0,0,1,2.629-9.2,7.729,7.729,0,0,1,3.945-3.5h.219c.22,0,.438-.22.658-.22a6.838,6.838,0,0,1,4.819,0c.218,0,.438.22.657.22h.22a7.732,7.732,0,0,1,3.945,3.5,17.232,17.232,0,0,1,2.629,9.2,16.6,16.6,0,0,1-2.853,9.642Z"
                                                transform="translate(-409.479 -1266.527)" fill="#006039"></path>
                                            <path id="Path_433"
                                                d="M498.073,1357.177h-2.41V1362a5.142,5.142,0,0,1-5.259,5.038h-10.07v-26.724h4.6V1337.9H468.5v2.409h4.6v26.724h-4.6v2.409h29.573v-7.667h0Z"
                                                transform="translate(-388.764 -1266.051)" fill="#006039"></path>
                                            <path id="Path_434"
                                                d="M515.525,1338H484.2v2.411h4.6v26.726h-4.6v2.409h31.325v-11.173h-2.409v3.724h0a5.33,5.33,0,0,1-5.259,5.259H496.033v-13.363h3.724a3.93,3.93,0,0,1,3.944,3.946h0v1.315h0v.437h2.191V1354h0v-1.97h0v-5.7H503.7v1.534h0a3.838,3.838,0,0,1-3.724,3.724h-3.944v-11.172h11.61a5.33,5.33,0,0,1,5.259,5.258h0v3.287h2.41v-3.07Z"
                                                transform="translate(-370.073 -1265.93)" fill="#006039"></path>
                                            <path id="Path_435"
                                                d="M536.5,1367.137c-2.19,0-2.848-.655-4.6-2.848l-10.518-12.925,6.79-8.324h0c1.753-2.191,2.409-2.848,4.819-2.848h1.753V1338H522.268v2.191H524.9a1,1,0,0,1,1.1.877v.219a.811.811,0,0,1-.22.658l-6.134,7.23-4.819-5.915-1.1-1.315a.811.811,0,0,1-.22-.658,1,1,0,0,1,.876-1.1h2.848V1338H501.676v2.191h.657c1.971,0,2.63.877,4.162,2.631l9.2,11.39-8.323,9.858h0c-1.753,2.191-2.41,2.849-4.82,2.849H500.8v2.191h13.363v-2.191h-3.286a1,1,0,0,1-1.1-.877v-.218a.811.811,0,0,1,.219-.658l7.448-8.762,6.134,7.667.876,1.1a.811.811,0,0,1,.22.658,1,1,0,0,1-.876,1.1h-2.848v2.191h17.085v-2.191l-1.533.219Z"
                                                transform="translate(-350.309 -1265.93)" fill="#006039"></path>
                                            <path id="Path_436"
                                                d="M467.43,1367.039l-2.19-6.135a10.28,10.28,0,0,0-3.286-5.26,7.2,7.2,0,0,0-3.5-1.534,8.164,8.164,0,0,0-1.315-16.209H432.82v2.409H437.2v26.725h-4.6v2.408h16.429v-2.408h-4.6V1354.11h4.6a14.848,14.848,0,0,1,3.067.439,6.509,6.509,0,0,1,2.19.876,12.487,12.487,0,0,1,2.41,2.411c.219.437.438.657.438,1.1a34.283,34.283,0,0,1,3.067,10.518h10.3v-2.409Zm-14.677-15.116h-7.667v-11.61h7.667a5.891,5.891,0,0,1,5.915,5.915,5.707,5.707,0,0,1-5.915,5.695Z"
                                                transform="translate(-431.505 -1266.051)" fill="#006039"></path>
                                            <rect id="_x3C_Tranche_x3E__2_" width="188.387" height="107.994" fill="none"></rect>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>

                    </a>
                </li>
            </div>
            <div class="brand-grid div2">
                <li>

                    <a class="alpi" href="https://www.ethoswatches.com/brands/alpina-watches.html" title="Alpina Watches" onclick="brandslogo('1','Alpina')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 82px;
  ">
                            <defs>
                                <clipPath id="clip-Alpina">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Alpina" clip-path="url(#clip-Alpina)">

                                <g id="Alpina-01" transform="translate(-29.2 -53.376)">
                                    <g id="Group_1" data-name="Group 1" transform="translate(111.173 57.6)">
                                        <path id="Path_1" data-name="Path 1" d="M123.121,57.6,104.1,90.445h37.932Zm0,32.071A10.174,10.174,0,1,1,133.3,79.5,10.147,10.147,0,0,1,123.121,89.671Z" transform="translate(-104.1 -57.6)" fill="#a61e2c"></path>
                                    </g>
                                    <path id="Path_2" data-name="Path 2" d="M91.6,153.091v-5.529a1.8,1.8,0,0,1-.664.332,2.455,2.455,0,0,1-1,.111H89.83v-.442h0a2.358,2.358,0,0,0,1.327-.332,1.078,1.078,0,0,0,.442-1h.553V153.2H91.6Zm6.193-3.649c-.332-.221-.553-.332-.664-.664a2.083,2.083,0,0,1-.221-.885,1.831,1.831,0,0,1,.553-1.327,2.039,2.039,0,0,1,1.438-.442,1.877,1.877,0,0,1,1.438.442,1.831,1.831,0,0,1,.553,1.327,2.083,2.083,0,0,1-.221.885c-.111.221-.442.442-.664.664a1.72,1.72,0,0,1,.885.664,1.868,1.868,0,0,1,.332,1.106,2.4,2.4,0,0,1-.553,1.548,2.765,2.765,0,0,1-3.318,0,1.8,1.8,0,0,1-.553-1.548,1.868,1.868,0,0,1,.332-1.106C97.129,149.773,97.46,149.552,97.792,149.442Zm-.553,1.769a1.512,1.512,0,0,0,1.548,1.548,1.282,1.282,0,0,0,1.106-.442,1.6,1.6,0,0,0,0-2.212,1.965,1.965,0,0,0-1.106-.442,1.51,1.51,0,0,0-1.216.442A2.618,2.618,0,0,0,97.239,151.211ZM97.571,148a1.256,1.256,0,0,0,.332.885,1.659,1.659,0,0,0,1.991,0,1.256,1.256,0,0,0,.332-.885,1.089,1.089,0,0,0-.332-.885,1.659,1.659,0,0,0-1.991,0C97.682,147.23,97.571,147.562,97.571,148Zm7.52,1.438c-.332-.221-.553-.332-.664-.664a2.083,2.083,0,0,1-.221-.885,1.831,1.831,0,0,1,.553-1.327,2.039,2.039,0,0,1,1.438-.442,1.877,1.877,0,0,1,1.438.442,1.831,1.831,0,0,1,.553,1.327,2.083,2.083,0,0,1-.221.885c-.111.221-.442.442-.664.664a1.72,1.72,0,0,1,.885.664,1.868,1.868,0,0,1,.332,1.106,2.4,2.4,0,0,1-.553,1.548,2.765,2.765,0,0,1-3.318,0,1.8,1.8,0,0,1-.553-1.548,1.868,1.868,0,0,1,.332-1.106C104.427,149.773,104.759,149.552,105.091,149.442Zm-.442,1.769a1.512,1.512,0,0,0,1.548,1.548,1.282,1.282,0,0,0,1.106-.442,1.6,1.6,0,0,0,0-2.212,1.965,1.965,0,0,0-1.106-.442,1.51,1.51,0,0,0-1.216.442A1.651,1.651,0,0,0,104.649,151.211ZM104.87,148a1.256,1.256,0,0,0,.332.885,1.659,1.659,0,0,0,1.991,0,1.256,1.256,0,0,0,.332-.885,1.089,1.089,0,0,0-.332-.885,1.659,1.659,0,0,0-1.991,0C104.98,147.23,104.87,147.562,104.87,148Zm6.3,3.1h.664a1.51,1.51,0,0,0,.442,1.216,1.282,1.282,0,0,0,1.106.442,1.51,1.51,0,0,0,1.216-.442,1.689,1.689,0,0,0,.442-1.106,1.282,1.282,0,0,0-.442-1.106,2.358,2.358,0,0,0-1.327-.332h-.332v-.553h.221a1.987,1.987,0,0,0,1.216-.332,1.4,1.4,0,0,0,.442-1,1.256,1.256,0,0,0-.332-.885,1.352,1.352,0,0,0-1-.332,1.42,1.42,0,0,0-1.106.442,2.567,2.567,0,0,0-.442,1.106h-.664a1.92,1.92,0,0,1,2.1-2.1,2.039,2.039,0,0,1,1.438.442,1.831,1.831,0,0,1,.553,1.327,2.083,2.083,0,0,1-.221.885,2.4,2.4,0,0,1-.774.664,1.72,1.72,0,0,1,.885.664,1.868,1.868,0,0,1,.332,1.106,2.4,2.4,0,0,1-.553,1.548,2.219,2.219,0,0,1-3.871-1.659Zm17.915,1.991-.111-1.216a3.137,3.137,0,0,1-1,1.106,3.6,3.6,0,0,1-1.548.442,2.856,2.856,0,0,1-2.322-1,4.353,4.353,0,0,1-.885-2.765,4.138,4.138,0,0,1,.885-2.765,2.938,2.938,0,0,1,2.433-1,3.284,3.284,0,0,1,1.991.553,2.1,2.1,0,0,1,.885,1.659h-.664a1.6,1.6,0,0,0-.774-1.216,2.34,2.34,0,0,0-1.548-.442,2.278,2.278,0,0,0-1.88.774,4.4,4.4,0,0,0,0,4.645,2.362,2.362,0,0,0,1.88.885,2.6,2.6,0,0,0,1.88-.664,2.7,2.7,0,0,0,.664-1.991h-2.322v-.553h2.986V153.2h-.553Zm3.981,0V145.9h4.645v.553h-4.092v2.654h3.649v.553h-3.649v2.765h4.2v.553H133.07Zm8.073,0V145.9h.774l4.092,6.193V145.9h.664v7.188H145.9l-4.092-6.193v6.193Zm8.958,0V145.9h4.645v.553h-4.092v2.654H154.3v.553h-3.649v2.765h4.2v.553H150.1Zm9.732,0-2.544-7.188h.664l2.212,6.3,2.212-6.3h.664l-2.433,7.188Zm5.972,0V145.9h4.645v.553h-4.092v2.654h3.649v.553h-3.649v2.765h4.2v.553H165.8ZM101,137.83l8.073-29.748.221-3.871h5.2l-.111,1.991c1.88-1.548,4.755-2.433,8.515-2.433H124c4.313,0,6.746.664,7.852,2.212,1.438,1.991.664,4.977-.553,9.179l-.774,2.765c-1.88,6.857-3.428,11.059-12.939,11.059h-1.327c-3.539,0-5.64-.664-6.635-2.1l-2.986,11.059H101ZM121.458,107.2c-6.193,0-6.967,1.769-8.515,7.52l-.885,3.318c-.774,3.1-1.327,5.087-.553,6.193.664.774,2.1,1.216,4.755,1.216,6.193,0,6.967-1.769,8.515-7.52l.885-3.318c.774-2.986,1.327-5.087.442-6.193C125.661,107.639,124.112,107.2,121.458,107.2ZM97.35,128.43v-2.986H92.705a.956.956,0,0,1-.664-.332.731.731,0,0,1-.111-.664L100,94.811h-5.64l-8.073,29.97a3.071,3.071,0,0,0,.442,2.433,3.113,3.113,0,0,0,2.322,1.106H97.35Zm-27.758,0a3.112,3.112,0,0,1-2.322-1.106,2.924,2.924,0,0,1-.442-2.544l1.88-6.967H54.994L43.6,128.319H37.3L73.573,94.7h7.3L72.91,124.338a.679.679,0,0,0,.111.664.788.788,0,0,0,.664.332H78v2.986h-8.4Zm.553-14.819,3.649-13.271L59.418,113.611Zm71.44,14.819a3.113,3.113,0,0,1-2.322-1.106,2.456,2.456,0,0,1-.442-2.433l5.529-20.68h5.64l-5.419,20.238a.679.679,0,0,0,.111.664.788.788,0,0,0,.664.332h4.645v2.986h-8.4Zm3.76-27.979,1.438-5.419h5.861l-1.438,5.419Zm41.36,27.979v-2.986h-4.645a.956.956,0,0,1-.664-.332.731.731,0,0,1-.111-.664l2.212-8.073.664-2.544c.885-3.207,1.438-6.082,0-7.962-1.216-1.548-3.649-2.322-7.631-2.322-4.534,0-7.52.774-9.511,2.433l.111-1.991h-5.087l-.221,3.871-5.529,20.459h5.64l3.539-12.939c1.769-6.414,2.544-8.294,8.737-8.294,2.654,0,3.981.332,4.645,1.106.774,1,.332,2.875-.332,5.308l-2.986,11.169a3.071,3.071,0,0,0,.442,2.433,3.112,3.112,0,0,0,2.322,1.106h8.4Zm15.372.442c-4.645,0-7.188-.664-8.294-2.212-.885-1.216-1-2.986-.221-5.861,1.991-7.188,6.746-7.741,13.271-7.741h7.078c.553-2.1.885-3.871.221-4.755-.664-.774-2.1-1.216-4.755-1.216-3.981,0-6.082.442-8.184,1.769l-.332.221-2.1-2.544.442-.221c3.1-1.88,5.972-2.544,10.838-2.654,4.977,0,7.962.774,9.289,2.544,1.548,1.991.885,4.755,0,8.184l-2.765,10.064a1.572,1.572,0,0,0,.221.664.845.845,0,0,0,.553.221H223.2v2.986h-9.068a3.021,3.021,0,0,1-2.1-.885,2.636,2.636,0,0,1-.664-1.216c-1.438,1.327-4.092,2.544-8.737,2.544Zm5.529-12.607c-5.087,0-7.188.221-8.515,4.866-.442,1.548-.442,2.544,0,3.1.664.774,2.212,1.216,4.977,1.216,5.861,0,6.967-1.659,8.294-6.635l.664-2.544Z" transform="translate(0 3.928)" fill="#231f20"></path>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div3">
                <li>

                    <a class="ange" href="https://www.ethoswatches.com/brands/angelus-watches.html" title="Angelus Watches" onclick="brandslogo('2','Angelus')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 75px;
  ">
                            <defs>
                                <clipPath id="clip-Angelus">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Angelus" clip-path="url(#clip-Angelus)">

                                <g id="Angelus-2" data-name="Angelus" transform="translate(-56.118 -29.689)">
                                    <g id="Group_68" transform="translate(60.518 48.086)">
                                        <g id="angelus-2-2" data-name="angelus-2" transform="translate(0 0)">
                                            <path id="Path_1099" d="M125.862,11.5H-66V-30.682l.468-.134L29.931-61.5h.134l95.664,30.685.134,42.317ZM-64.8,10.3H124.525V-29.946L29.931-60.3-64.8-29.946Z" transform="translate(66.869 61.5)" stroke="#000" stroke-width="0.7"></path>
                                            <path id="Path_1100" d="M222.3-16.7" transform="translate(-28.7 46.649)"></path>
                                            <path id="Path_1101" d="M-67.3-16.7" transform="translate(67.3 46.649)"></path>
                                            <path id="Path_1102" d="M123.47,8.841H-59.3V-24.851l.468-.134L32.152-54.6h.134L123.47-24.985ZM-58.1,7.638H122.267V-23.915L32.152-53.4-58.1-23.915Z" transform="translate(64.648 59.213)" stroke="#000" stroke-width="0.8"></path>
                                            <path id="Path_1103" d="M-33.394,15.669h7.888L-32.057-9.2l-3.409,1.07-5.95,1.671L-47.7,15.669h5.482l1.337-4.947H-34.6ZM-39.21,5.106l1.939-6.819L-35.8,5.173H-39.21Z" transform="translate(60.803 44.163)"></path>
                                            <path id="Path_1104" d="M5.739-23v14.44L-1.281-20.588-8.3-18.249V10.5h5.816V-6.215L5.739,10.5h5.214V-24.8Z" transform="translate(47.742 49.334)"></path>
                                            <path id="Path_1105" d="M41.508-18.163v-7.019a2.426,2.426,0,0,0-2.34-2.407h-.8a2.426,2.426,0,0,0-2.407,2.34v.067h0V2.494A2.426,2.426,0,0,0,38.3,4.9h.8a2.426,2.426,0,0,0,2.407-2.34V2.494h0V-6.865H38.7v-4.613H50.734v8.223a12.286,12.286,0,0,1-12.1,12.568A12.342,12.342,0,0,1,26-2.787V-19.633A12.383,12.383,0,0,1,38.166-32.2,12.327,12.327,0,0,1,50.734-20.1v.468h0V-18.3Z" transform="translate(36.373 51.788)"></path>
                                            <path id="Path_1106" d="M78.761.838V-13h8.022v-4.345H78.627V-31.184h8.958V-37H68.8V6.921H87.852V.838H78.761Z" transform="translate(22.184 53.378)"></path>
                                            <path id="Path_1107" d="M114.759,1.733V-30.891L105.4-34.3V7.883h17.582V1.8h-8.223Z" transform="translate(10.051 52.483)"></path>
                                            <path id="Path_1108" d="M148.635-19.454V2.139a2.608,2.608,0,0,1-5.214.134v-23.6L135.2-24V2.54a9.329,9.329,0,1,0,18.651.468h0V-17.85Z" transform="translate(0.174 49.069)"></path>
                                            <path id="Path_1109" d="M184.9,17.4" transform="translate(-16.302 35.345)" opacity="0.5"></path>
                                            <path id="Path_1110" d="M189.837,9.368,188.5,8.9Z" transform="translate(-17.496 38.163)" opacity="0.5"></path>
                                            <path id="Path_1111" d="M191.36,10.809c0,3.744-2.54,5.682-6.551,5.682H171.572V11.879h9.961a1.385,1.385,0,0,0,1.2-.869v-.2c0-.735-.735-1.07-1.2-1.2s-1.939-.735-1.939-.735h0c-.267-.134-2.54-.869-3.409-1.2A15.053,15.053,0,0,1,171.1,4.659,6.059,6.059,0,0,1,168.9-.288c0-3.877,3.142-6.551,6.685-7.287a16.747,16.747,0,0,1,4.479,0c1.471.267,7.42,2.206,7.888,2.206h0V-.154a32.442,32.442,0,0,0-6.551-2.54,4.6,4.6,0,0,0-1.671-.134,2.083,2.083,0,0,0-2.072,2.072,1.611,1.611,0,0,0,.468,1.2c.134.134.468.267.6.468a45.526,45.526,0,0,0,6.418,2.808,10.611,10.611,0,0,1,1.337.468,10.482,10.482,0,0,1,3.276,2.072,8.9,8.9,0,0,1,1.6,4.546Z" transform="translate(-10.996 43.674)"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>

            </div>
            <div class="brand-grid div4">
                <li>

                    <a class="coru" href="https://www.ethoswatches.com/brands/corum.html" title="Corum Watches" onclick="brandslogo('15','Corum')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
    max-width: 86px;
">
                            <defs>
                                <clipPath id="clip-Corum">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Corum" clip-path="url(#clip-Corum)">
                                <rect width="203" height="109" fill="#fff"></rect>
                                <g id="Group_13" data-name="Group 13" transform="translate(14 3)">
                                    <path id="Path_75" data-name="Path 75" d="M842.754,781.744v6.448l-.645.43c-2.149-3.224-7.309-7.953-13.757-7.953-2.794,0-8.383,1.5-8.383,9.028,0,3.439,3.654,12.682,13.972,12.682,5.8,0,10.318-4.944,11.392-6.448l1.075.43c-1.29,4.3-6.878,10.318-15.047,10.318A15.277,15.277,0,0,1,816.1,791.632,15.092,15.092,0,0,1,831.361,776.8a17.952,17.952,0,0,1,11.393,4.944" transform="translate(-816.1 -720.913)"></path>
                                    <path id="Path_76" data-name="Path 76" d="M847.877,776.9c-8.6,0-15.477,6.664-15.477,14.832s6.878,14.832,15.477,14.832,15.477-6.664,15.477-14.832S856.474,776.9,847.877,776.9m8.813,24.29c-4.084,3.654-11.177,2.579-15.906-2.579-4.944-5.159-5.589-12.467-1.5-16.121s11.178-2.58,15.906,2.58c4.729,5.374,5.589,12.467,1.5,16.121" transform="translate(-797.363 -720.798)"></path>
                                    <path id="Path_77" data-name="Path 77" d="M850.2,777.2v29.018h5.374V792.676a7.851,7.851,0,0,1,3.869.645,38.068,38.068,0,0,1,3.654,3.009c1.935,2.149,3.654,5.159,5.8,7.093a11.344,11.344,0,0,0,5.374,2.58,23.365,23.365,0,0,0,5.374.215c.215,0,.215,0,.215-.215s0-.215-.215-.215a8.984,8.984,0,0,1-4.944-2.58c-4.084-3.654-4.084-6.878-10.747-10.533h4.3c7.953,0,9.458-4.729,9.458-7.953,0-6.663-4.944-7.523-10.747-7.523Zm5.374,13.542V779.349h9.887c2.579,0,6.879.43,6.879,5.589,0,4.514-2.794,5.8-4.944,5.8Z" transform="translate(-776.902 -720.453)"></path>
                                    <path id="Path_78" data-name="Path 78" d="M891.264,777.315v16.121c0,6.018-3.01,13.327-12.467,13.327-4.3,0-10.1-1.934-12.252-8.6a16.751,16.751,0,0,1-.645-4.729V777.315h5.374v16.766c0,7.953,7.093,8.383,7.953,8.383,6.019,0,8.813-3.439,8.813-10.1V777.1l3.224.215Z" transform="translate(-758.854 -720.568)"></path>
                                    <path id="Path_79" data-name="Path 79" d="M912.883,806.1V777.3h-5.159s-8.168,16.766-9.887,19.99L886.229,777.3H881.5v28.8h3.439V785.039L896.547,806.1l11.177-22.57V806.1Z" transform="translate(-740.922 -720.338)"></path>
                                    <path id="Path_80" data-name="Path 80" d="M869.946,786.052c-.645-.645-.86-.86-1.72-.86a1.927,1.927,0,0,0-1.935,1.935c0,1.075.215,1.29,1.5,1.935,0,.215.215.645.215.86a3.869,3.869,0,0,1-7.738,0c0-.43,0-.43.215-.645,1.075-.86,1.719-1.075,1.719-2.15a1.927,1.927,0,0,0-1.935-1.934c-.86,0-1.29.645-1.935.86a3.676,3.676,0,0,1-3.654-3.869,3.853,3.853,0,0,1,3.869-3.869,3.761,3.761,0,0,1,3.01,1.5c.645.645,1.074,1.72,2.58,1.72s1.934-1.075,2.579-1.72a3.76,3.76,0,0,1,3.009-1.5,3.853,3.853,0,0,1,3.869,3.869,3.677,3.677,0,0,1-3.654,3.869m7.953-4.3a6.238,6.238,0,0,0-6.234-6.234,5.868,5.868,0,0,0-5.159,2.58v-1.075a1.54,1.54,0,0,0,.86-1.29,1.179,1.179,0,0,0-.86-1.29v-5.589h2.15v-2.149h1.719v2.149h4.084v-2.794h-2.15v-1.5h2.15v-2.58h-4.084V763.7h-1.934v-1.719h-1.719v-1.72h1.719v-1.719h1.719v1.719h4.084v-2.579H872.1v-1.5h2.149v-3.009h-4.084v2.15h-1.719v-2.15h-2.15v-.215h0a2.149,2.149,0,1,0-4.3,0h0v.215h-2.149v2.15h-1.72v-2.15h-4.084v3.009h2.149v1.5h-2.149v2.579h4.084v-1.719h1.72v1.719h1.719v1.72h-1.719V763.7h-1.72v-1.719h-4.084v2.579h2.149v1.5h-2.149v2.794h4.084v-2.15h1.72v2.15h2.149v5.589a1.451,1.451,0,0,0-.86,1.29,1.54,1.54,0,0,0,.86,1.29V778.1a5.869,5.869,0,0,0-5.159-2.58,6.238,6.238,0,0,0-6.234,6.234,6.453,6.453,0,0,0,4.944,6.234,1.7,1.7,0,0,0-1.075,1.719,1.927,1.927,0,0,0,1.935,1.935,1.531,1.531,0,0,0,1.29-.645v.43a6.516,6.516,0,0,0,6.663,6.448,6.656,6.656,0,0,0,6.663-6.448V791a3.055,3.055,0,0,0,1.29.43,1.926,1.926,0,0,0,.86-3.654,6.289,6.289,0,0,0,4.729-6.019" transform="translate(-776.442 -750.8)"></path>
                                    <path id="Path_81" data-name="Path 81" d="M818.334,796.4h.645l1.935,4.514h-.86l-.43-1.075H817.69l-.43,1.075h-.86Zm.86,2.794-.645-1.935-.645,1.935Z" transform="translate(-815.755 -698.383)"></path>
                                    <path id="Path_82" data-name="Path 82" d="M820.3,796.4h.86l1.29,3.439,1.29-3.439h.86l-1.934,4.729h-.645Z" transform="translate(-811.272 -698.383)"></path>
                                    <path id="Path_83" data-name="Path 83" d="M826.035,796.4h.645l1.935,4.514h-.86l-.43-1.075H825.39l-.43,1.075h-.86Zm1.075,2.794-.645-1.935-.645,1.935Z" transform="translate(-806.904 -698.383)"></path>
                                    <path id="Path_84" data-name="Path 84" d="M828.4,796.4h1.075l2.149,3.439h0V796.4h.86v4.729H831.41l-2.149-3.654h0v3.654h-.86Z" transform="translate(-801.961 -698.383)"></path>
                                    <path id="Path_85" data-name="Path 85" d="M834.005,797.26h-1.5v-.86h3.654v.86h-1.29v3.869h-.86Z" transform="translate(-797.248 -698.383)"></path>
                                    <rect id="Rectangle_1" data-name="Rectangle 1" width="1.505" height="0.645" transform="translate(42.56 100.812)"></rect>
                                    <path id="Path_86" data-name="Path 86" d="M843.214,800.814a6.282,6.282,0,0,1-1.935.43,1.938,1.938,0,0,1-1.074-.215c-.215-.215-.645-.215-.86-.43s-.43-.43-.43-.645a1.611,1.611,0,0,1-.215-1.075,1.94,1.94,0,0,1,.215-1.075c.215-.215.215-.645.43-.86a1.117,1.117,0,0,1,.86-.43,1.611,1.611,0,0,1,1.074-.215,1.939,1.939,0,0,1,1.075.215,1.631,1.631,0,0,1,.86.43l-.645.645c-.215-.215-.43-.215-.43-.43-.215,0-.43-.215-.645-.215a.79.79,0,0,0-.645.215.462.462,0,0,0-.43.43c-.215.215-.215.215-.215.43s-.215.43-.215.645a.79.79,0,0,0,.215.645.751.751,0,0,0,.215.43l.43.43c.215,0,.43.215.645.215h.645c.215,0,.43,0,.43-.215v-.86h-.86v-.645h1.719l-.215,2.15Z" transform="translate(-790.121 -698.497)"></path>
                                    <path id="Path_87" data-name="Path 87" d="M844.935,796.4h.645l1.934,4.514h-.86l-.43-1.075H844.29l-.43,1.075H843Zm1.075,2.794-.645-1.935-.645,1.935Z" transform="translate(-785.178 -698.383)"></path>
                                    <path id="Path_88" data-name="Path 88" d="M847.3,796.4h2.149c.215,0,.43.215.645.215s.215.215.43.43c0,.215.215.43.215.645,0,.43,0,.645-.215.86a1.117,1.117,0,0,1-.86.43l1.29,1.935H849.88l-1.075-1.935h-.645v1.935h-.86V796.4Zm1.5,1.934h.645a.211.211,0,0,0,.215-.215v-.645l-.215-.215h-1.074v1.29h.43v-.215Z" transform="translate(-780.236 -698.383)"></path>
                                    <path id="Path_89" data-name="Path 89" d="M851.43,796.4h1.72a1.292,1.292,0,0,1,.86.215c.215,0,.43.215.645.43s.43.43.43.645a1.612,1.612,0,0,1,.215,1.075,1.938,1.938,0,0,1-.215,1.075l-.645.645a1.117,1.117,0,0,1-.86.43c-.215,0-.645.215-.86.215H851l.43-4.729Zm1.29,3.869h.645c.215,0,.43-.215.645-.215l.43-.43c0-.215.215-.43.215-.645a.79.79,0,0,0-.215-.645.462.462,0,0,0-.43-.43.789.789,0,0,0-.645-.215h-1.29v3.01l.645-.43Z" transform="translate(-775.982 -698.383)"></path>
                                    <path id="Path_90" data-name="Path 90" d="M855.4,796.4h3.009v.86H856.26v1.075h1.935v.645H856.26v1.29h2.149v.86H855.4Z" transform="translate(-770.925 -698.383)"></path>
                                    <path id="Path_91" data-name="Path 91" d="M864.38,797.475c0-.215-.215-.215-.43-.215h-.86l-.215.215v.215c0,.215,0,.215.215.43.215,0,.215.215.43.215a528.866,528.866,0,0,0,.86.43c.215,0,.215.215.43.43a1.075,1.075,0,0,1,0,1.29.462.462,0,0,1-.43.43.666.666,0,0,1-.43.215H863.3a1.294,1.294,0,0,1-.86-.215c-.215,0-.43-.215-.645-.43l.645-.645c0,.215.215.215.43.43a.751.751,0,0,1,.43.215h.215a.21.21,0,0,0,.215-.215l.215-.215v-.215c0-.215,0-.215-.215-.43a.75.75,0,0,0-.43-.215,2118.11,2118.11,0,0,1-.86-.43c-.215,0-.215-.215-.43-.43a1.074,1.074,0,0,1,0-1.29c0-.215.215-.215.43-.43a.79.79,0,0,1,.645-.215h.645a.79.79,0,0,1,.645.215c.215,0,.43.215.645.43Z" transform="translate(-763.567 -698.382)"></path>
                                    <rect id="Rectangle_2" data-name="Rectangle 2" width="0.86" height="4.514" transform="translate(106.616 98.018)"></rect>
                                    <path id="Path_92" data-name="Path 92" d="M868.5,796.4h1.075l2.15,3.439h0V796.4h.86v4.729h-1.075l-2.365-3.654h0v3.654H868.5Z" transform="translate(-755.866 -698.383)"></path>
                                    <path id="Path_93" data-name="Path 93" d="M876.039,797.59c-.215-.215-.43-.215-.43-.43h-.43a.79.79,0,0,0-.645.215.462.462,0,0,0-.43.43c-.215.215-.215.215-.215.43s-.215.43-.215.645a.79.79,0,0,0,.215.645.75.75,0,0,0,.215.43l.43.43c.215,0,.43.215.645.215a.789.789,0,0,0,.645-.215.463.463,0,0,0,.43-.43l.645.43a2.955,2.955,0,0,1-.86.645,1.292,1.292,0,0,1-.86.215,1.937,1.937,0,0,1-1.074-.215c-.215-.215-.645-.215-.86-.43s-.43-.43-.43-.645a1.61,1.61,0,0,1-.215-1.075,1.937,1.937,0,0,1,.215-1.075c.215-.214.215-.645.43-.86a1.117,1.117,0,0,1,.86-.43,1.61,1.61,0,0,1,1.074-.215,1.293,1.293,0,0,1,.86.215c.215.215.43.215.645.645Z" transform="translate(-751.153 -698.497)"></path>
                                    <path id="Path_94" data-name="Path 94" d="M876.8,796.4h3.009v.86H877.66v1.075h1.935v.645H877.66v1.29h2.365v.86H876.8Z" transform="translate(-746.325 -698.383)"></path>
                                    <path id="Path_95" data-name="Path 95" d="M884.69,797.26l-.86.86-.43-.43,1.289-1.289h.645v4.729h-.645Z" transform="translate(-738.738 -698.383)"></path>
                                    <path id="Path_96" data-name="Path 96" d="M887.745,801.129l1.075-1.72h-.43a.789.789,0,0,1-.645-.215c-.215,0-.215-.215-.43-.215-.215-.215-.215-.215-.215-.43V797.9a.79.79,0,0,1,.215-.645.463.463,0,0,1,.43-.43c.215-.215.215-.215.43-.215s.43-.215.645-.215h.645a.751.751,0,0,1,.43.215l.43.43a1.075,1.075,0,0,1,0,1.29c0,.215-.215.43-.215.645l-1.075,1.935-1.29.215Zm1.719-3.224v-.43a.21.21,0,0,0-.215-.215l-.215-.215h-.86a.211.211,0,0,0-.215.215l-.215.215v.86a.211.211,0,0,0,.215.215l.215.215h.86a.21.21,0,0,0,.215-.215l.215-.215v-.43" transform="translate(-734.485 -698.382)"></path>
                                    <path id="Path_97" data-name="Path 97" d="M893.824,797.145H891.89V798h1.075a.75.75,0,0,1,.43.215c.215.215.215.215.215.43s.215.43.215.645a.79.79,0,0,1-.215.645.463.463,0,0,1-.43.43.666.666,0,0,1-.43.215c-.215,0-.43.215-.645.215-.43,0-.645,0-.86-.215a2.97,2.97,0,0,1-.645-.86l.86-.215c0,.215.215.215.215.43.215,0,.215.215.43.215h.215a.211.211,0,0,0,.215-.215l.215-.215v-.645a.21.21,0,0,0-.215-.215.751.751,0,0,0-.43-.215h-.86c-.215,0-.215,0-.43.215V796.5h2.579l.43.645Z" transform="translate(-730.462 -698.267)"></path>
                                    <path id="Path_98" data-name="Path 98" d="M897.625,797.145H895.69V798h1.074a.75.75,0,0,1,.43.215c.215.215.215.215.215.43s.215.43.215.645a.79.79,0,0,1-.215.645.463.463,0,0,1-.43.43.667.667,0,0,1-.43.215c-.215,0-.43.215-.645.215-.43,0-.645,0-.86-.215a2.97,2.97,0,0,1-.645-.86l.86-.215c0,.215.215.215.215.43.215,0,.215.215.43.215h.215a.21.21,0,0,0,.215-.215l.215-.215v-.645a.21.21,0,0,0-.215-.215.75.75,0,0,0-.43-.215h-.86c-.215,0-.215,0-.43.215V796.5h2.579l.43.645Z" transform="translate(-726.094 -698.267)"></path>
                                </g>
                            </g>
                        </svg> </a>
                </li>

            </div>
            <div class="brand-grid div5">
                <li>

                    <a class="arno" href="https://www.ethoswatches.com/brands/arnold-and-son-watches.html" title="Arnold & Son Watches" onclick="brandslogo('4','Arnold & Son')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 86px;
  ">
                            <defs>
                                <clipPath id="clip-Arnold_Son">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Arnold_Son" data-name="Arnold &amp; Son" clip-path="url(#clip-Arnold_Son)">

                                <g id="Arnold_Son-01" data-name="Arnold &amp; Son-01" transform="translate(-87.7 -40.7)">
                                    <path id="Path_10" data-name="Path 10" d="M185.041,143.33a26.13,26.13,0,0,0-.086,3.011v4.818c0,1.721.172,1.893,1.548,1.893h.172v.6c-1.032-.086-1.548-.086-2.409-.086-.774,0-1.376,0-2.495.086v-.6c1.548-.086,1.635-.172,1.635-1.893v-6.71a4.283,4.283,0,0,1-1.462.516l-.344-.516a22.451,22.451,0,0,0,3.011-1.548Z" transform="translate(-12.702 -13.023)" fill="#010101"></path>
                                    <path id="Path_11" data-name="Path 11" d="M200.054,143.5l.172.258-4.387,10.065h-1.721c.086-.172.344-.6.688-1.376l3.957-7.656h-2.323c-1.548,0-2.065.086-2.409.43a1.873,1.873,0,0,0-.43.688H193l.516-2.323,6.538-.086Z" transform="translate(-14.295 -13.107)" fill="#010101"></path>
                                    <path id="Path_12" data-name="Path 12" d="M213.122,143.416a7.174,7.174,0,0,0-4.3,5.162,3.716,3.716,0,0,1,2.323-.946,2.829,2.829,0,0,1,2.839,2.925,3.308,3.308,0,0,1-3.441,3.269c-2.065,0-3.441-1.548-3.441-3.785,0-1.807.774-3.183,2.581-4.9a9.187,9.187,0,0,1,3.355-2.237Zm-4.473,6.8c0,1.893.688,2.925,1.979,2.925a1.888,1.888,0,0,0,1.807-2.065,2.268,2.268,0,0,0-1.893-2.495,2.434,2.434,0,0,0-1.721.774A1.551,1.551,0,0,0,208.648,150.212Z" transform="translate(-16.265 -13.023)" fill="#010101"></path>
                                    <path id="Path_13" data-name="Path 13" d="M224.687,143.516l1.721-.516v6.624h1.29v1.118h-1.29v2.925h-1.548v-2.925H220.3l.172-1.118Zm.086,1.118-3.441,5.076h3.441Z" transform="translate(-18.109 -13.037)" fill="#010101"></path>
                                    <path id="Path_14" data-name="Path 14" d="M96.034,122.469a33.8,33.8,0,0,0-1.2,3.527c-.43,1.721-.172,1.893,1.807,2.151v.6H90.7v-.6c1.721-.344,2.065-.516,3.011-2.925l5.506-13.764.688-.258,1.807,4.9c1.118,3.355,2.323,6.624,3.355,9.291.86,2.151,1.118,2.495,2.839,2.667v.6h-6.452v-.6c1.807-.258,2.065-.43,1.807-1.634-.172-.516-.86-2.323-1.548-4.129Zm5.248-1.118-2.237-6.624h-.086c-.946,2.237-1.807,4.473-2.581,6.624Z" transform="translate(0 -8.594)" fill="#010101"></path>
                                    <path id="Path_15" data-name="Path 15" d="M137.811,116.045c0-2.065-.086-2.323-.6-2.925-.43-.6-1.118-.86-2.409-.946v-.6l3.871-.086L150.2,125.25h.086l-.086-6.882a22.873,22.873,0,0,0-.344-4.731c-.172-.946-.946-1.462-2.839-1.548v-.6l6.8-.086v.6c-1.462.172-2.065.6-2.323,1.548a27.385,27.385,0,0,0-.258,4.817l.086,10.753h-.774L139.015,115.1h-.086l.086,6.968a32.281,32.281,0,0,0,.344,4.817c.172.946.946,1.462,2.753,1.549v.6l-6.8.086v-.6c1.548-.172,2.237-.6,2.409-1.549a28.078,28.078,0,0,0,.258-4.817Z" transform="translate(-6.162 -8.622)" fill="#010101"></path>
                                    <path id="Path_16" data-name="Path 16" d="M168.6,111a8.623,8.623,0,0,1,8.431,8.947c-.172,5.592-4.129,9.291-9.119,9.2a8.554,8.554,0,0,1-8.517-9.033c0-4.559,3.269-9.291,9.2-9.119Zm-.688.86c-3.183-.086-5.764,2.495-5.936,7.57-.172,5.162,2.495,8.775,6.452,8.861,3.183.086,5.85-2.409,5.936-7.312.086-6.022-2.495-9.119-6.452-9.119Z" transform="translate(-9.599 -8.565)" fill="#010101"></path>
                                    <path id="Path_17" data-name="Path 17" d="M184.967,114.869c0-2.237-.258-2.667-2.581-2.667v-.6H189.7v.6c-2.323.172-2.581.516-2.581,2.667v10.667c0,1.376.172,2.065.6,2.323a6.6,6.6,0,0,0,5.764-.516,11.814,11.814,0,0,0,1.462-2.667l.688.172c-.172.946-.774,3.441-.946,4.473H182.3v-.6c2.323-.172,2.581-.43,2.581-2.753l.086-11.1Z" transform="translate(-12.8 -8.649)" fill="#010101"></path>
                                    <path id="Path_18" data-name="Path 18" d="M203.209,114.769c0-2.237-.258-2.581-2.323-2.667v-.6h7.742c3.183,0,5.85.6,7.656,2.237a8.241,8.241,0,0,1,2.323,5.764,8.491,8.491,0,0,1-3.355,7.226,13.518,13.518,0,0,1-8.173,2.323H200.8v-.6c2.323-.172,2.667-.516,2.667-2.667Zm2.237,10.753c0,1.979.43,2.667,2.667,2.667,5.592,0,7.828-3.871,7.828-8,0-2.925-1.032-5.592-3.183-6.8a10.448,10.448,0,0,0-5.162-1.118,12.129,12.129,0,0,0-1.893.172c-.172.086-.258.258-.258.774Z" transform="translate(-15.385 -8.636)" fill="#010101"></path>
                                    <path id="Path_19" data-name="Path 19" d="M300.611,116.045c0-2.065-.086-2.323-.6-2.925-.43-.6-1.118-.86-2.409-.946v-.6l3.871-.086L313,125.25h.086v-6.882a22.864,22.864,0,0,0-.344-4.731c-.172-.946-.946-1.462-2.839-1.548v-.6l6.8-.086v.6c-1.462.172-2.151.6-2.323,1.548a27.387,27.387,0,0,0-.258,4.817l.086,10.839h-.774l-11.614-14.022h-.086l.086,6.968a23.281,23.281,0,0,0,.344,4.817c.172.946.946,1.462,2.753,1.549v.6l-6.8.086v-.6c1.548-.172,2.237-.6,2.409-1.548a28.075,28.075,0,0,0,.258-4.818Z" transform="translate(-28.911 -8.622)" fill="#010101"></path>
                                    <path id="Path_20" data-name="Path 20" d="M261.116,123.7c.43,1.462,1.548,4.387,4.559,4.473a2.979,2.979,0,0,0,3.183-3.183c0-2.323-1.721-3.269-3.355-4.129-1.118-.6-4.215-1.979-4.129-4.9.086-2.495,1.979-4.559,5.334-4.559a9.55,9.55,0,0,1,3.183.688c.086.946.258,1.979.43,3.7l-.688.086c-.43-1.548-1.118-3.527-3.441-3.613a2.639,2.639,0,0,0-2.753,2.839c0,1.893,1.29,2.839,3.183,3.785,1.721.86,4.387,2.151,4.3,5.248-.086,2.925-2.323,4.99-5.678,4.9a17.292,17.292,0,0,1-2.667-.43,4.508,4.508,0,0,1-1.29-.6,40.368,40.368,0,0,1-.688-4.129Z" transform="translate(-23.741 -8.622)" fill="#010101"></path>
                                    <path id="Path_21" data-name="Path 21" d="M285.2,111a8.623,8.623,0,0,1,8.431,8.947c-.172,5.592-4.129,9.291-9.119,9.2A8.554,8.554,0,0,1,276,120.118c0-4.559,3.269-9.291,9.2-9.119Zm-.688.86c-3.183-.086-5.764,2.495-5.936,7.57-.172,5.162,2.495,8.775,6.452,8.861,3.183.086,5.85-2.409,5.936-7.312.086-6.022-2.495-9.119-6.452-9.119Z" transform="translate(-25.892 -8.565)" fill="#010101"></path>
                                    <path id="Path_22" data-name="Path 22" d="M246.882,127.573a7.5,7.5,0,0,1-3.527-1.893l-.086-.086.086-.086a5.829,5.829,0,0,0,1.807-4.129A3.982,3.982,0,0,0,243.7,118.2l-.086-.086.172-.172.172.086a7.7,7.7,0,0,0,2.667.43h0c.946,0,1.979-.688,1.979-1.29v-1.2c0-.258,0-.43-.086-.43a.3.3,0,0,0-.172-.086h0c-.172,0-.172.172-.344.43a1.88,1.88,0,0,1-.258.43c-.774,1.29-2.753.946-4.559.688a7.071,7.071,0,0,0-1.807-.172,3.842,3.842,0,0,0-2.925,1.118l.43.43a1.81,1.81,0,0,1,1.29-.43h0a3.124,3.124,0,0,1,2.237.946,4.851,4.851,0,0,1,1.29,3.269,3.783,3.783,0,0,1-1.032,2.925l-.086.086h-.086c-.516-.258-9.291-7.828-9.291-10.839a1.635,1.635,0,0,1,.43-1.118,2.464,2.464,0,0,1,1.807-.688h0c1.118,0,2.237.516,2.323,1.2.086.43.172.516.344.516s.258-.086.344-.43l.344-1.721a6.572,6.572,0,0,0-2.839-.688h0c-2.237,0-4.645,1.548-4.645,3.957a4.687,4.687,0,0,0,1.118,2.667l.086.172h-.086a5.1,5.1,0,0,0-4.129,4.817c0,4.129,3.871,5.678,7.484,5.678h.086a10.906,10.906,0,0,0,5.678-1.634h.172c.172.172.344.172.43.344a8.488,8.488,0,0,0,2.667,1.29c2.065.43,4.129.258,7.656-1.548l-.172-.258C249.29,127.831,248,127.745,246.882,127.573Zm-6.28-.774a7.981,7.981,0,0,1-3.785,1.118h0c-4.387,0-6.366-2.753-6.366-5.506a3.282,3.282,0,0,1,2.237-3.441h.086v.086A51.242,51.242,0,0,0,240.6,126.8Z" transform="translate(-19.227 -8.622)" fill="#010101"></path>
                                    <path id="Path_23" data-name="Path 23" d="M129.977,128.347h-.43a.645.645,0,0,1-.43-.086h-.172c-.172,0-.172-.086-.344-.172-.086,0-.086-.086-.172-.086a.3.3,0,0,1-.172-.086,4.107,4.107,0,0,1-1.721-1.118,15.771,15.771,0,0,1-2.151-2.581c-.86-1.2-1.893-3.011-2.323-3.785,2.065-.946,3.355-2.323,3.355-4.731a3.8,3.8,0,0,0-1.721-3.441,2.691,2.691,0,0,0-.86-.43,8.18,8.18,0,0,0-3.441-.43h-6.538v.6h0c.688.086,1.118.172,1.548.258.774.344.86.946.86,2.409l-.086,10.925c0,2.237-.258,2.667-2.581,2.667v.6h7.14v-.6h0a14.151,14.151,0,0,1-1.548-.258c-.774-.344-.946-.946-.946-2.495v-4.473h.774a3.6,3.6,0,0,1,1.548.258,1.369,1.369,0,0,1,.774.774,22.252,22.252,0,0,0,1.635,2.667v.086l.086.086c.172.172.258.344.43.43.172.258.43.43.516.688,1.29,1.721,2.581,2.667,4.129,2.839.172,0,.258.086.43.086a8.433,8.433,0,0,0,1.979-.086.517.517,0,0,0,.344-.086l.086-.43Zm-8.259-8.689a4.759,4.759,0,0,1-2.925.688h-1.376v-7.4c0-.516.172-.688.258-.688a3.777,3.777,0,0,1,1.29-.172,5.1,5.1,0,0,1,1.979.43c1.29.516,2.151,1.807,2.151,3.785A4.058,4.058,0,0,1,121.719,119.659Z" transform="translate(-3.06 -8.622)" fill="#010101"></path>
                                    <g id="Group_4" data-name="Group 4" transform="translate(174.146 49.7)">
                                        <path id="Path_24" data-name="Path 24" d="M212.64,81.942l-2.667,1.118s.43.172.43.43a1.039,1.039,0,0,1,.086.946,7.234,7.234,0,0,1-.946,1.721,6.546,6.546,0,0,1-2.667,2.065,1.887,1.887,0,0,1-2.667-1.635v-10.5s-.086-.43.172-.6a.674.674,0,0,1,.6-.172h2.323v-1.29h-2.323a2.015,2.015,0,0,0,.6-1.462,2.065,2.065,0,1,0-4.129,0,2.245,2.245,0,0,0,.6,1.462h-2.237v1.29h2.323s.43-.086.6.172a1.06,1.06,0,0,1,.172.6v10.5a1.842,1.842,0,0,1-2.667,1.634,9.214,9.214,0,0,1-2.667-2.065,4.348,4.348,0,0,1-.946-1.721,1.141,1.141,0,0,1,.086-.946.843.843,0,0,1,.43-.43l-2.667-1.118-.688,2.667a1.123,1.123,0,0,1,.688-.086,1.419,1.419,0,0,1,.86.688,8.584,8.584,0,0,0,1.462,2.237,8.282,8.282,0,0,0,2.753,2.065,13.553,13.553,0,0,0,2.667.86,1.73,1.73,0,0,1,.946.6,1.761,1.761,0,0,1,.43,1.118,2.6,2.6,0,0,1,.43-1.118,2.711,2.711,0,0,1,.946-.6,22.671,22.671,0,0,0,2.667-.86,8,8,0,0,0,2.753-2.065,10.3,10.3,0,0,0,1.462-2.237,1.183,1.183,0,0,1,.86-.688.961.961,0,0,1,.688.086ZM203.693,73.6a.955.955,0,0,1-.946-.946.9.9,0,0,1,.946-.946.946.946,0,0,1,0,1.893Z" transform="translate(-188.552 -52.606)" fill="#010101"></path>
                                        <path id="Path_25" data-name="Path 25" d="M203.162,66.9A13.217,13.217,0,0,0,190,80.062,13.037,13.037,0,0,0,200.151,92.88a10.753,10.753,0,0,0,6.022,0,13.165,13.165,0,0,0-3.011-25.98Zm0,25.808a12.56,12.56,0,1,1,12.56-12.56A12.575,12.575,0,0,1,203.162,92.708Z" transform="translate(-188.021 -52.103)" fill="#010101"></path>
                                        <path id="Path_26" data-name="Path 26" d="M217.9,77.745a15.241,15.241,0,0,0-9.549-14.022l1.29-2.065c.172-.172.172-.258.172-.258a6.1,6.1,0,0,0,1.118-3.011,3.287,3.287,0,0,0-1.462-2.667,4.922,4.922,0,0,0-2.925-.946,9.286,9.286,0,0,0-1.635.258,8,8,0,0,0-.86.43,1.038,1.038,0,0,0-.946-.688V53.657h.86c.086,0,.086.086.172.086h0a2.679,2.679,0,0,1,.6.774,2.531,2.531,0,0,1,1.376-1.29,2.855,2.855,0,0,1-1.376-1.29,6.067,6.067,0,0,1-.6.774h0c-.086,0-.086.086-.172.086h-.946v-.946a.3.3,0,0,1,.086-.172h0a2.679,2.679,0,0,1,.774-.6,2.855,2.855,0,0,1-1.29-1.376,2.855,2.855,0,0,1-1.29,1.376,6.066,6.066,0,0,1,.774.6h0c0,.086.086.086.086.172v1.032h-.774c-.086,0-.086-.086-.172-.086h0a2.678,2.678,0,0,1-.6-.774,2.531,2.531,0,0,1-1.376,1.29A2.855,2.855,0,0,1,200.6,54.6a6.064,6.064,0,0,1,.6-.774h0c.086,0,.086-.086.172-.086h.86v1.118a1.8,1.8,0,0,0-.86.688c-.344-.172-.6-.43-.946-.43a4.5,4.5,0,0,0-1.548-.258,5.321,5.321,0,0,0-2.839.946,3.177,3.177,0,0,0-1.462,2.667,5.378,5.378,0,0,0,1.118,3.011l1.2,1.807h0l.43.516A15.03,15.03,0,0,0,196.3,91.423a10.243,10.243,0,0,0-2.667,1.118v.43h18.238v-.43a10.243,10.243,0,0,0-2.667-1.118A15.529,15.529,0,0,0,217.9,77.745ZM203.185,57.873l.516-.86.172-.172a2.354,2.354,0,0,1,.43-.344,6.416,6.416,0,0,1,1.032-.6,3.344,3.344,0,0,1,1.29-.172,4.226,4.226,0,0,1,2.323.688,2.028,2.028,0,0,1,.946,1.807,4.462,4.462,0,0,1-.946,2.323,6.889,6.889,0,0,0-.688,1.118c-.6-.258-1.2-.43-1.893-.688.172-.43.258-.6.43-.688a5.278,5.278,0,0,1,1.29-.43c-.258-.43-1.118-2.323-1.118-2.323s-1.807,1.032-2.237,1.29c0,0,.516.688.774,1.118.172.258.086.516,0,.946a5.809,5.809,0,0,0-1.2-.172.776.776,0,0,0,.086-.43,1.4,1.4,0,0,0-1.032-1.376Zm.086,3.871h.172a13.506,13.506,0,0,1,3.785.86h0l.172.086c.086,0,.086,0,.172.086l-.43.6a14.055,14.055,0,0,0-8.689,0l-.43-.516.43-.172h0a12.326,12.326,0,0,1,3.527-.774h.344C202.325,61.744,202.755,61.744,203.271,61.744Zm-6.452-.946h0a.266.266,0,0,1-.086-.172,5.027,5.027,0,0,1-.946-2.409,2.493,2.493,0,0,1,.946-1.807,3.717,3.717,0,0,1,2.151-.688,5.077,5.077,0,0,1,1.2.172c.43.172.774.43,1.118.6.172.172.258.172.43.344l.172.172.43.774v1.032a1.4,1.4,0,0,0-1.032,1.376.776.776,0,0,0,.086.43,5.809,5.809,0,0,1-1.2.172,1.265,1.265,0,0,1-.086-.946c.258-.43.774-1.118.774-1.118-.43-.258-2.237-1.29-2.237-1.29s-.946,1.807-1.118,2.323l1.29.43a.9.9,0,0,1,.43.774,15.509,15.509,0,0,0-1.807.688A4.556,4.556,0,0,1,196.819,60.8Zm10.925,30.023a11.32,11.32,0,0,1-2.581.688,11.679,11.679,0,0,1-2.495.258,12.284,12.284,0,0,1-2.495-.258c-.86-.172-1.721-.43-2.581-.688a14.033,14.033,0,1,1,18.926-13.162A13.2,13.2,0,0,1,207.744,90.821Z" transform="translate(-187.7 -49.7)" fill="#010101"></path>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div6">
                <li>

                    <a class="baum" href="https://www.ethoswatches.com/brands/baume-mercier.html" title="Baume & Mercier Watches" onclick="brandslogo('5','Baume & Mercier')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 90px;
  ">
                            <defs>
                                <clipPath id="clip-Baume_Mercier">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Baume_Mercier" data-name="Baume &amp; Mercier" clip-path="url(#clip-Baume_Mercier)">
                                <rect width="203" height="109" fill="#fff"></rect>
                                <g id="Group_28" data-name="Group 28" transform="translate(-786.9 -29.1)">
                                    <g id="Group_26" data-name="Group 26" transform="translate(790.9 107.863)">
                                        <path id="Path_207" data-name="Path 207" d="M794.8,95.706V92.1l-1.2,2.7H793l-1.2-2.7v3.6h-.9V90.3h.751l1.5,3.3,1.5-3.3h.751v5.406Z" transform="translate(-790.9 -89.699)" fill="#231f20"></path>
                                        <path id="Path_208" data-name="Path 208" d="M799.7,95.706l-.3-1.051h-2.1l-.3,1.051h-.9l1.952-5.406h.6l1.952,5.406Zm-1.352-4.2-.9,2.4H799.1Z" transform="translate(-788.291 -89.699)" fill="#231f20"></path>
                                        <path id="Path_209" data-name="Path 209" d="M801,95.706V90.3h.751v5.406Z" transform="translate(-785.832 -89.699)" fill="#231f20"></path>
                                        <path id="Path_210" data-name="Path 210" d="M805.552,95.907a2.487,2.487,0,0,1-1.952-.751l.6-.6a1.514,1.514,0,0,0,1.352.451c.751,0,1.051-.3,1.051-.9,0-.3,0-.451-.15-.6s-.3-.15-.6-.3l-.6-.15a1.54,1.54,0,0,1-1.5-1.5,1.7,1.7,0,0,1,1.8-1.652,2.051,2.051,0,0,1,1.652.6l-.451.451a1.705,1.705,0,0,0-1.2-.451c-.6,0-1.051.3-1.051.9a.552.552,0,0,0,.15.451c.15.15.3.15.6.3l.6.15c.45.15.751.15,1.051.451a1.363,1.363,0,0,1,.451,1.051C807.5,95.306,806.754,95.907,805.552,95.907Z" transform="translate(-784.528 -89.9)" fill="#231f20"></path>
                                        <path id="Path_211" data-name="Path 211" d="M811.559,95.106a2.236,2.236,0,0,1-1.352.6,1.464,1.464,0,0,1-1.352-.6c-.6-.6-.451-1.2-.451-2.1,0-1.051,0-1.652.451-2.1a2.236,2.236,0,0,1,1.352-.6,1.464,1.464,0,0,1,1.352.6c.6.6.45,1.2.45,2.1C812.01,94.054,812.01,94.655,811.559,95.106Zm-.6-3.754a1.089,1.089,0,0,0-1.5,0c-.3.3-.3.6-.3,1.652s0,1.352.3,1.652a1.089,1.089,0,0,0,1.5,0c.3-.3.3-.6.3-1.652C811.259,91.8,811.109,91.5,810.958,91.351Z" transform="translate(-782.125 -89.699)" fill="#231f20"></path>
                                        <path id="Path_212" data-name="Path 212" d="M816.4,95.706l-2.553-3.754v3.754H813.1V90.3h.751l2.553,3.754V90.3h.751v5.406Z" transform="translate(-779.761 -89.699)" fill="#231f20"></path>
                                        <path id="Path_213" data-name="Path 213" d="M824.1,95.156a2.1,2.1,0,0,1-1.352.451H820.8V90.2h1.952a1.836,1.836,0,0,1,1.352.451,2.691,2.691,0,0,1,.6,2.1C824.7,93.8,824.7,94.555,824.1,95.156Zm-.6-3.754a1.1,1.1,0,0,0-.9-.3h-1.051v3.9H822.6a1.269,1.269,0,0,0,.9-.3c.3-.3.3-.9.3-1.652S823.8,91.7,823.5,91.4Z" transform="translate(-775.898 -89.749)" fill="#231f20"></path>
                                        <path id="Path_214" data-name="Path 214" d="M825.5,91.8V90.3h.9v.751Z" transform="translate(-773.539 -89.699)" fill="#231f20"></path>
                                        <path id="Path_215" data-name="Path 215" d="M831.4,95.706V93.3h-2.253v2.4H828.4V90.3h.751v2.253H831.4V90.3h.751v5.406Z" transform="translate(-772.084 -89.699)" fill="#231f20"></path>
                                        <path id="Path_216" data-name="Path 216" d="M836.459,95.106a2.236,2.236,0,0,1-1.352.6,1.464,1.464,0,0,1-1.352-.6c-.6-.6-.45-1.2-.45-2.1,0-1.051,0-1.652.45-2.1a2.236,2.236,0,0,1,1.352-.6,1.464,1.464,0,0,1,1.352.6c.6.6.45,1.2.45,2.1C837.06,94.054,837.06,94.655,836.459,95.106Zm-.6-3.754a1.089,1.089,0,0,0-1.5,0c-.3.3-.3.6-.3,1.652s0,1.352.3,1.652a1.089,1.089,0,0,0,1.5,0c.3-.3.3-.6.3-1.652C836.159,91.8,836.159,91.5,835.858,91.351Z" transform="translate(-769.631 -89.699)" fill="#231f20"></path>
                                        <path id="Path_217" data-name="Path 217" d="M841,95.706l-1.2-2.253h-1.051v2.253H838V90.3h2.1a1.464,1.464,0,0,1,1.652,1.5,1.633,1.633,0,0,1-1.051,1.5l1.2,2.4Zm-.9-4.655h-1.2V92.7h1.2a.8.8,0,0,0,.9-.9A.841.841,0,0,0,840.1,91.051Z" transform="translate(-767.268 -89.699)" fill="#231f20"></path>
                                        <path id="Path_218" data-name="Path 218" d="M842.8,95.706V90.3h.751v4.655H846.1v.751Z" transform="translate(-764.859 -89.699)" fill="#231f20"></path>
                                        <path id="Path_219" data-name="Path 219" d="M850.159,95.106a2.236,2.236,0,0,1-1.352.6,1.464,1.464,0,0,1-1.352-.6c-.6-.6-.451-1.2-.451-2.1,0-1.051,0-1.652.451-2.1a2.237,2.237,0,0,1,1.352-.6,1.464,1.464,0,0,1,1.352.6c.6.6.451,1.2.451,2.1C850.61,94.054,850.61,94.655,850.159,95.106Zm-.6-3.754a1.089,1.089,0,0,0-1.5,0c-.3.3-.3.6-.3,1.652s0,1.352.3,1.652a1.089,1.089,0,0,0,1.5,0c.3-.3.3-.6.3-1.652C849.859,91.8,849.709,91.5,849.558,91.351Z" transform="translate(-762.757 -89.699)" fill="#231f20"></path>
                                        <path id="Path_220" data-name="Path 220" d="M854.909,95.106a1.928,1.928,0,0,1-1.5.6,1.464,1.464,0,0,1-1.352-.6c-.6-.6-.45-1.2-.45-2.1,0-1.051,0-1.652.45-2.1a2.237,2.237,0,0,1,1.352-.6,1.846,1.846,0,0,1,1.952,1.652h-.9a1.2,1.2,0,0,0-1.952-.6c-.3.3-.3.6-.3,1.652s0,1.352.3,1.652a1.147,1.147,0,0,0,.751.3,1.3,1.3,0,0,0,.9-.451,1.61,1.61,0,0,0,.3-.9v-.3h-1.2v-.751h1.952V93.3A2.2,2.2,0,0,1,854.909,95.106Z" transform="translate(-760.449 -89.699)" fill="#231f20"></path>
                                        <path id="Path_221" data-name="Path 221" d="M856.4,95.706V90.3h3.454v.751h-2.7v1.5H859.4V93.3h-2.253v1.652h2.7v.751Z" transform="translate(-758.035 -89.699)" fill="#231f20"></path>
                                        <path id="Path_222" data-name="Path 222" d="M863.8,95.706l-1.2-2.253h-1.051v2.253H860.8V90.3h2.1a1.464,1.464,0,0,1,1.652,1.5,1.633,1.633,0,0,1-1.051,1.5l1.2,2.4Zm-.9-4.655h-1.2V92.7h1.2a.8.8,0,0,0,.9-.9C863.8,91.351,863.5,91.051,862.9,91.051Z" transform="translate(-755.828 -89.699)" fill="#231f20"></path>
                                        <path id="Path_223" data-name="Path 223" d="M865.6,95.706V90.3h.751v5.406Z" transform="translate(-753.419 -89.699)" fill="#231f20"></path>
                                        <path id="Path_224" data-name="Path 224" d="M868.5,95.706V90.3h3.454v.751H869.4v1.5h2.253V93.3H869.4v1.652h2.553v.751Z" transform="translate(-751.964 -89.699)" fill="#231f20"></path>
                                        <path id="Path_225" data-name="Path 225" d="M878.909,95.106a1.928,1.928,0,0,1-1.5.6,1.464,1.464,0,0,1-1.352-.6c-.6-.6-.45-1.2-.45-2.1,0-1.051,0-1.652.45-2.1a2.236,2.236,0,0,1,1.352-.6,1.846,1.846,0,0,1,1.952,1.652h-.751a1.2,1.2,0,0,0-1.952-.6c-.3.3-.3.6-.3,1.652s0,1.352.3,1.652a1.147,1.147,0,0,0,.751.3,1.3,1.3,0,0,0,.9-.451,1.611,1.611,0,0,0,.3-.9v-.3h-1.2v-.751h1.952V93.3A2.887,2.887,0,0,1,878.909,95.106Z" transform="translate(-748.407 -89.699)" fill="#231f20"></path>
                                        <path id="Path_226" data-name="Path 226" d="M880.4,95.706V90.3h3.454v.751h-2.7v1.5H883.4V93.3h-2.253v1.652h2.7v.751Z" transform="translate(-745.993 -89.699)" fill="#231f20"></path>
                                        <path id="Path_227" data-name="Path 227" d="M888.1,95.706l-2.553-3.754v3.754H884.8V90.3h.751l2.553,3.754V90.3h.751v5.406Z" transform="translate(-743.786 -89.699)" fill="#231f20"></path>
                                        <path id="Path_228" data-name="Path 228" d="M889.9,95.706V90.3h3.454v.751h-2.7v1.5H892.9V93.3h-2.253v1.652h2.7v.751Z" transform="translate(-741.227 -89.699)" fill="#231f20"></path>
                                        <path id="Path_229" data-name="Path 229" d="M896.4,95.706h-.6L894,90.3h.9l1.2,3.9,1.2-3.9h.9Z" transform="translate(-739.17 -89.699)" fill="#231f20"></path>
                                        <path id="Path_230" data-name="Path 230" d="M898.7,95.706V90.3h3.454v.751h-2.7v1.5H901.7V93.3h-2.253v1.652h2.7v.751Z" transform="translate(-736.811 -89.699)" fill="#231f20"></path>
                                        <path id="Path_231" data-name="Path 231" d="M906.951,95.706V91.2l-1.051.9v-.9l1.051-.9h.751v5.406Z" transform="translate(-733.199 -89.699)" fill="#231f20"></path>
                                        <path id="Path_232" data-name="Path 232" d="M911.352,95.706a1.544,1.544,0,0,1-1.652-1.5,1.189,1.189,0,0,1,.751-1.2,1.342,1.342,0,0,1-.6-1.2,1.5,1.5,0,0,1,1.5-1.5,1.419,1.419,0,0,1,1.5,1.5,1.56,1.56,0,0,1-.6,1.2A1.338,1.338,0,0,1,913,94.2,1.543,1.543,0,0,1,911.352,95.706Zm0-2.553a.9.9,0,1,0,.9.9A.969.969,0,0,0,911.352,93.153Zm0-2.253a.751.751,0,1,0,.751.751A.709.709,0,0,0,911.352,90.9Z" transform="translate(-731.292 -89.699)" fill="#231f20"></path>
                                        <path id="Path_233" data-name="Path 233" d="M915.452,95.756a1.46,1.46,0,0,1-1.652-1.5h.751c0,.6.45.751.9.751a.866.866,0,0,0,.9-.9.8.8,0,0,0-.9-.9h-.15v-.6h.15c.6,0,.751-.3.751-.751,0-.6-.3-.9-.751-.9a.709.709,0,0,0-.751.751h-.751a1.543,1.543,0,0,1,1.652-1.5,1.419,1.419,0,0,1,1.5,1.5,1.56,1.56,0,0,1-.6,1.2,1.189,1.189,0,0,1,.751,1.2A1.791,1.791,0,0,1,915.452,95.756Z" transform="translate(-729.235 -89.749)" fill="#231f20"></path>
                                        <path id="Path_234" data-name="Path 234" d="M919.6,95.706a1.419,1.419,0,0,1-1.5-1.5V91.8a1.5,1.5,0,0,1,1.5-1.5,1.419,1.419,0,0,1,1.5,1.5v2.4A1.5,1.5,0,0,1,919.6,95.706Zm.751-3.9a.841.841,0,0,0-.751-.9c-.451,0-.751.3-.751.9v2.4a.841.841,0,0,0,.751.9c.45,0,.751-.3.751-.9Z" transform="translate(-727.078 -89.699)" fill="#231f20"></path>
                                    </g>
                                    <g id="Group_27" data-name="Group 27" transform="translate(790.9 90.142)">
                                        <path id="Path_235" data-name="Path 235" d="M797.207,91.566H790.9V78.2h6.007c2.853,0,4.655,1.352,4.655,3.6a3.119,3.119,0,0,1-2.1,2.853,3.275,3.275,0,0,1,2.4,3.154C802.013,90.214,800.061,91.566,797.207,91.566ZM796.757,80H793v4.055h3.754c1.652,0,2.7-.751,2.7-1.952S798.409,80,796.757,80Zm.3,5.707H793v4.055h4.055c1.8,0,2.7-.9,2.7-2.1C799.76,86.61,798.859,85.709,797.057,85.709Z" transform="translate(-790.9 -78.05)" fill="#231f20"></path>
                                        <path id="Path_236" data-name="Path 236" d="M816.656,91.716c-3.154,0-5.556-1.8-5.556-4.655V78.2h2.4v8.71c0,1.8,1.2,2.853,3.154,2.853s3.154-1.051,3.154-2.853V78.2h2.4v8.86C822.063,89.914,819.66,91.716,816.656,91.716Z" transform="translate(-780.765 -78.05)" fill="#231f20"></path>
                                        <path id="Path_237" data-name="Path 237" d="M834,91.566V78.2h9.611V80h-7.358v3.9h6.307v1.8h-6.307v4.055h7.358v1.8Z" transform="translate(-769.275 -78.05)" fill="#231f20"></path>
                                        <path id="Path_238" data-name="Path 238" d="M853.811,91.616l-1.2-1.352a6.066,6.066,0,0,1-3.9,1.352c-2.853,0-4.505-1.5-4.505-3.9,0-1.8,1.352-2.853,2.7-3.754a4.81,4.81,0,0,1-1.5-2.853c0-1.652,1.352-3,3.454-3a3,3,0,0,1,3.3,3,2.855,2.855,0,0,1-1.5,2.4s-.751.451-1.051.6l3,3.3a4.8,4.8,0,0,0,.751-2.7h1.952a6.01,6.01,0,0,1-1.352,4.055l2.4,2.7h-2.553v.15Zm-5.556-6.458c-1.051.6-1.952,1.352-1.952,2.553a2.3,2.3,0,0,0,2.553,2.253,4.119,4.119,0,0,0,2.7-1.051Zm.751-5.256a1.26,1.26,0,0,0-1.352,1.352c0,.6.3,1.051,1.051,1.8a5.99,5.99,0,0,1,.6-.451,1.6,1.6,0,0,0,1.051-1.352C850.507,80.5,849.907,79.9,849.006,79.9Z" transform="translate(-764.157 -78.1)" fill="#231f20"></path>
                                        <path id="Path_239" data-name="Path 239" d="M868.6,91.566V78.2h9.611V80h-7.359v3.9h6.307v1.8h-6.307v4.055h7.359v1.8Z" transform="translate(-751.914 -78.05)" fill="#231f20"></path>
                                        <path id="Path_240" data-name="Path 240" d="M886.46,91.566l-3.154-5.556h-2.853v5.556H878.2V78.2h5.857c2.853,0,4.656,1.652,4.656,3.9a3.707,3.707,0,0,1-3,3.6l3.454,5.857ZM883.756,80H880.3v4.2h3.454c1.5,0,2.553-.751,2.553-2.1A2.28,2.28,0,0,0,883.756,80Z" transform="translate(-747.097 -78.05)" fill="#231f20"></path>
                                        <path id="Path_241" data-name="Path 241" d="M893.406,91.716a5.881,5.881,0,0,1-3.9-1.352c-1.5-1.352-1.5-2.853-1.5-5.406s0-4.055,1.5-5.406a5.882,5.882,0,0,1,3.9-1.352,5.226,5.226,0,0,1,5.406,4.055H896.56a2.833,2.833,0,0,0-3-2.253,3.689,3.689,0,0,0-2.253.751c-.751.751-.9,1.5-.9,4.2s.15,3.454.9,4.2a3.689,3.689,0,0,0,2.253.751,2.833,2.833,0,0,0,3-2.253h2.253A5.226,5.226,0,0,1,893.406,91.716Z" transform="translate(-742.18 -78.05)" fill="#231f20"></path>
                                        <path id="Path_242" data-name="Path 242" d="M898.2,91.566V78.2h2.253V91.566Z" transform="translate(-737.062 -78.05)" fill="#231f20"></path>
                                        <path id="Path_243" data-name="Path 243" d="M903.3,91.566V78.2h9.611V80h-7.359v3.9h6.307v1.8h-6.307v4.055h7.359v1.8Z" transform="translate(-734.503 -78.05)" fill="#231f20"></path>
                                        <path id="Path_244" data-name="Path 244" d="M920.96,91.566l-3.154-5.556h-2.853v5.556H912.7V78.2h5.857c2.853,0,4.655,1.652,4.655,3.9a3.707,3.707,0,0,1-3,3.6l3.454,5.857ZM918.256,80H914.8v4.2h3.454c1.5,0,2.553-.751,2.553-2.1A2.279,2.279,0,0,0,918.256,80Z" transform="translate(-729.787 -78.05)" fill="#231f20"></path>
                                    </g>
                                    <path id="Path_245" data-name="Path 245" d="M808.86,78.3H804.5l-4.2,13.215h2.4l1.051-3.9h5.707l1.2,3.9h2.253ZM804.2,85.959l1.652-5.857h1.652l1.5,5.857Z" transform="translate(4.716 12.142)" fill="#231f20"></path>
                                    <path id="Path_246" data-name="Path 246" d="M821.7,78.3V91.515h2.1V81.454l4.655,5.406,4.505-5.556V91.515h2.1V78.3h-1.952l-4.655,5.707L823.652,78.3Z" transform="translate(15.454 12.142)" fill="#231f20"></path>
                                    <path id="Path_247" data-name="Path 247" d="M856.1,78.3V91.515h2.1V81.454l4.505,5.406,4.655-5.556V91.515h2.1V78.3h-1.952l-4.806,5.707L858.052,78.3Z" transform="translate(32.714 12.142)" fill="#231f20"></path>
                                    <path id="Path_248" data-name="Path 248" d="M872.179,67.015c0-4.806-4.656-8.86-10.662-9.461V54.1h-3.754v3.454c-6.007.751-10.662,4.655-10.662,9.461s4.655,8.86,10.662,9.461v3.3h3.754v-3.3C867.524,75.725,872.179,71.821,872.179,67.015ZM851,66.865c0-3.754,2.853-6.908,6.758-7.809V74.524A7.759,7.759,0,0,1,851,66.865Zm10.512,7.809V59.206a8.165,8.165,0,0,1,6.908,7.809C868.425,70.769,865.421,73.923,861.517,74.674Z" transform="translate(28.198 0)" fill="#231f20"></path>
                                </g>
                            </g>
                        </svg> </a>
                </li>

            </div>
            <div class="brand-grid div7">
                <li>

                    <a class="bella" href="https://www.ethoswatches.com/brands/bell-and-ross.html" title="Bell & Ross Watches" onclick="brandslogo('6','Bell & Ross')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 90px;
  ">
                            <defs>
                                <clipPath id="clip-Bell_Ross">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Bell_Ross" data-name="Bell &amp; Ross" clip-path="url(#clip-Bell_Ross)">

                                <g id="Logo" transform="translate(-723 -1187.7)">
                                    <path id="Path_1_00000088115703937258060100000012264538981387565209_" d="M1749.505,1348.579l-3.105.625s.677,5.448,8.119,5.448,7.252-6.09,7.252-6.09.434-4.06-4.892-5.43c-.365-.087-.746-.174-1.163-.243-3.123-.5-5.569-.9-5.76-2.5-.191-1.787,1.683-2.915,3.608-2.915,3.661,0,4.372,2.151,4.563,2.637l3-.607s-1.075-4.806-7.408-4.806-6.853,4.754-6.853,5.621-.174,4.632,5.5,5.656,6.211,1.6,6.211,3.088c0,1.6-1.388,2.828-4.025,2.811-2.516-.017-4.32-.677-5.049-3.3" transform="translate(-841.717 -99.181)" fill="#010101"></path>
                                    <path id="Path_2" d="M1103.162,1264.925c-12.3,0-22.276-10.34-22.276-23.109s9.976-23.092,22.276-23.092,22.276,10.34,22.276,23.092c0,12.769-9.958,23.109-22.276,23.109m0-50.225c-14.434,0-26.162,12.144-26.162,27.117s11.711,27.116,26.162,27.116,26.145-12.144,26.145-27.116c.017-14.972-11.693-27.117-26.145-27.117" transform="translate(-288.452)" fill="#010101"></path>
                                    <path id="Path_3" d="M1014.263,1324.842h3.522V1299.2h-3.522s-.017,23.317,0,25.642" transform="translate(-236.593 -69.84)" fill="#010101"></path>
                                    <path id="Path_4" d="M966.5,1324.842h3.522V1299.2H966.5v25.642" transform="translate(-197.122 -69.84)" fill="#010101"></path>
                                    <path id="Path_5" d="M737.23,1321.7h-5.656v-8.518l6.124-.017a4.306,4.306,0,0,1,4.268,4.354,1.589,1.589,0,0,1-.017.278s.173,3.9-4.719,3.9m-5.656-19.275,5.274-.017s4.424-.278,4.424,3.609a3.921,3.921,0,0,1-3.921,3.886h-.1l-5.656.017Zm9.351,8.9a5.885,5.885,0,0,0,4.06-5.812c-.295-6.714-7.686-6.246-7.686-6.246H728v25.625h10.288a6.987,6.987,0,0,0,2.637-13.567" transform="translate(0 -69.889)" fill="#010101"></path>
                                    <path id="Path_6" d="M1150.73,1291.629a3.057,3.057,0,0,1,.174-2.342,5.23,5.23,0,0,1,3.14-2.585c.052.017,5.725,6.523,5.725,6.523a7.357,7.357,0,0,1-4.476,1.614,4.594,4.594,0,0,1-4.563-3.21m3.678-16.152h0a3.072,3.072,0,0,1,5.708,1.51c0,1.926-1.908,2.88-3.313,3.591l-.1.052c-.173-.121-3.244-2.984-2.29-5.153m2.429-6.246h0c-5.864.086-7.824,4.094-7.842,4.146-2.273,4.615,1.475,9.074,1.509,9.108.018.226-.347.364-.989.624-1.926.763-5.916,2.325-5.916,7.616,0,6.61,5.43,10.063,10.808,10.063a17.406,17.406,0,0,0,9.2-3.262c2.55,2.03,4.06,2.429,5.847,2.429a13.345,13.345,0,0,0,3.418-.5l-.035-4.788c-3.713.607-4.736-1.423-4.754-1.457a26.525,26.525,0,0,0,4.771-9.212l-6,.069a12.079,12.079,0,0,1-2.515,5.135c-2.655-2.655-4.407-4.806-4.424-4.823,3.7-1.6,5.656-4.216,5.656-7.634a7.346,7.346,0,0,0-2.064-5.083,8.921,8.921,0,0,0-6.662-2.429" transform="translate(-343.497 -45.059)" fill="#010101"></path>
                                    <path id="Path_7" d="M1431.283,1310.7h-5.344v-8.293h5.916s4.216-.226,4.216,3.886-3.851,4.407-4.788,4.407m4.025,2.585a7.483,7.483,0,0,0,4.528-7.1c0-7.061-7.443-6.992-7.443-6.992H1422.4v25.642h3.556V1314h5.76l4.441,10.861h3.852Z" transform="translate(-573.928 -69.84)" fill="#010101"></path>
                                    <path id="Path_8" d="M857.089,1337c4.858,0,4.615,5.534,4.615,5.534h-9.178s.382-5.534,4.563-5.534m7.946,8.223s.815-10.826-7.651-10.826-8.484,9.733-8.484,9.733c0,3.938,1.77,10.184,8.085,10.184a7.762,7.762,0,0,0,7.755-5.378l-2.967-.59c-1.613,3.643-4.407,3.261-4.407,3.261-4.979,0-4.858-6.4-4.858-6.4Z" transform="translate(-99.925 -98.933)" fill="#010101"></path>
                                    <path id="Path_9" d="M1543.648,1351.193c-4.945,0-4.7-7.1-4.7-7.1s-.243-7.408,4.7-7.408,4.771,6.7,4.771,7.408.069,7.1-4.771,7.1m8.275-7.287c-.278-10.409-8.154-9.924-8.154-9.924-8.744.4-8.38,9.889-8.38,9.889.226,10.6,7.981,10.08,7.981,10.08,8.813-.26,8.553-10.045,8.553-10.045" transform="translate(-667.313 -98.586)" fill="#010101"></path>
                                    <path id="Path_10" d="M1648.005,1348.579l-3.105.625s.677,5.448,8.119,5.448,7.234-6.09,7.234-6.09.434-4.06-4.892-5.43c-.364-.087-.746-.174-1.162-.243-3.123-.5-5.569-.9-5.76-2.5-.191-1.787,1.7-2.915,3.609-2.915,3.66,0,4.372,2.151,4.563,2.637l3-.607s-1.076-4.806-7.408-4.806-6.835,4.754-6.835,5.621-.174,4.632,5.5,5.656,6.211,1.6,6.211,3.088c0,1.6-1.388,2.828-4.025,2.811-2.516-.017-4.32-.677-5.049-3.3" transform="translate(-757.826 -99.181)" fill="#010101"></path>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div8">
                <li>

                    <a class="bian" href="https://www.ethoswatches.com/brands/bianchet-watches.html" title="Bianchet Watches" onclick="brandslogo('7','Bianchet')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 100px;
  ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_57" data-name="Rectangle 57" width="368" height="214" transform="translate(0.155 0.423)"></rect>
                                </clipPath>
                                <clipPath id="clip-Bianchet">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Bianchet" clip-path="url(#clip-Bianchet)">
                                <rect width="203" height="109" fill="#fff"></rect>
                                <g id="Layer_1" transform="translate(-127.4 24.8)">
                                    <g id="Group_108" data-name="Group 108" transform="translate(140.4 -10.8)">
                                        <g id="Group_107" data-name="Group 107" transform="translate(20.469 74.483)">
                                            <path id="Path_524" data-name="Path 524" d="M49.353,161.932H47.79a.2.2,0,0,1-.19-.19v-.853a.2.2,0,0,1,.19-.19h4.5a.2.2,0,0,1,.19.19v.853a.2.2,0,0,1-.19.19H50.727v5.78a.2.2,0,0,1-.19.19H49.59a.2.2,0,0,1-.19-.19v-5.78Z" transform="translate(-47.6 -160.558)" fill="#12100d"></path>
                                            <path id="Path_525" data-name="Path 525" d="M65.3,160.5a3.7,3.7,0,1,1-3.7,3.7A3.7,3.7,0,0,1,65.3,160.5Zm0,6.065a2.369,2.369,0,0,0,0-4.738,2.369,2.369,0,1,0,0,4.738Z" transform="translate(-54.967 -160.453)" fill="#12100d"></path>
                                            <path id="Path_526" data-name="Path 526" d="M82.3,160.89a.2.2,0,0,1,.19-.19h.995a.2.2,0,0,1,.19.19v4.217a1.494,1.494,0,1,0,2.985,0V160.89a.2.2,0,0,1,.19-.19h1a.2.2,0,0,1,.19.19v4.264a2.843,2.843,0,1,1-5.686,0V160.89Z" transform="translate(-65.859 -160.558)" fill="#12100d"></path>
                                            <path id="Path_527" data-name="Path 527" d="M101.1,160.89a.2.2,0,0,1,.19-.19h2.938a2.224,2.224,0,0,1,2.227,2.227,2.283,2.283,0,0,1-1.516,2.085l1.421,2.606c.095.142,0,.284-.19.284h-1.09c-.095,0-.142-.047-.142-.095l-1.374-2.7h-1.137v2.606a.2.2,0,0,1-.19.19h-.948a.2.2,0,0,1-.19-.19V160.89Zm3.032,3.08a1.019,1.019,0,0,0,0-2.037h-1.658v2.037Z" transform="translate(-75.751 -160.558)" fill="#12100d"></path>
                                            <path id="Path_528" data-name="Path 528" d="M119.1,160.89a.2.2,0,0,1,.19-.19h2.369a2.007,2.007,0,0,1,2.18,1.9,1.874,1.874,0,0,1-1.137,1.611,1.86,1.86,0,0,1,1.327,1.658,2.092,2.092,0,0,1-2.227,1.99h-2.464a.2.2,0,0,1-.19-.19V160.89Zm2.416,2.843a.911.911,0,0,0,.9-.948.834.834,0,0,0-.9-.853h-1.09v1.8Zm.142,2.938a.921.921,0,0,0,.948-.948,1,1,0,0,0-1.042-.9h-1.137v1.848Z" transform="translate(-85.223 -160.558)" fill="#12100d"></path>
                                            <path id="Path_529" data-name="Path 529" d="M135.6,160.89a.2.2,0,0,1,.19-.19h.948a.2.2,0,0,1,.19.19v6.775a.2.2,0,0,1-.19.19h-.948a.2.2,0,0,1-.19-.19Z" transform="translate(-93.905 -160.558)" fill="#12100d"></path>
                                            <path id="Path_530" data-name="Path 530" d="M145.8,160.89a.2.2,0,0,1,.19-.19h.948a.2.2,0,0,1,.19.19v5.78h2.606a.2.2,0,0,1,.19.19v.853a.2.2,0,0,1-.19.19H145.99a.2.2,0,0,1-.19-.19Z" transform="translate(-99.272 -160.558)" fill="#12100d"></path>
                                            <path id="Path_531" data-name="Path 531" d="M159.7,160.89a.2.2,0,0,1,.19-.19h.948a.2.2,0,0,1,.19.19v5.78h2.606a.2.2,0,0,1,.19.19v.853a.2.2,0,0,1-.19.19H159.89a.2.2,0,0,1-.19-.19Z" transform="translate(-106.586 -160.558)" fill="#12100d"></path>
                                            <path id="Path_532" data-name="Path 532" d="M175.8,160.5a3.7,3.7,0,1,1-3.7,3.7A3.7,3.7,0,0,1,175.8,160.5Zm0,6.065a2.369,2.369,0,0,0,0-4.738,2.369,2.369,0,0,0,0,4.738Z" transform="translate(-113.111 -160.453)" fill="#12100d"></path>
                                            <path id="Path_533" data-name="Path 533" d="M193.047,160.69a.2.2,0,0,1,.19-.19h.237l4.264,4.549h0v-4.217a.2.2,0,0,1,.19-.19h.948a.2.2,0,0,1,.19.19v6.918a.2.2,0,0,1-.19.19h-.237l-4.312-4.691h0v4.406a.2.2,0,0,1-.19.19h-.948a.2.2,0,0,1-.19-.19V160.69Z" transform="translate(-124.108 -160.453)" fill="#12100d"></path>
                                            <path id="Path_534" data-name="Path 534" d="M219.686,160.832c-.047-.142.047-.237.19-.237h.948c.095,0,.142.095.19.142l1.09,4.075h.047l1.611-4.217c0-.047.095-.095.19-.095h.19a.246.246,0,0,1,.19.095l1.658,4.217h.047l1.042-4.075c0-.095.095-.142.19-.142h.948c.142,0,.237.095.19.237l-1.943,6.918c0,.095-.095.142-.19.142h-.142a.246.246,0,0,1-.19-.095l-1.848-4.643h-.047l-1.8,4.643a.246.246,0,0,1-.19.095h-.142c-.095,0-.142-.047-.19-.142Z" transform="translate(-138.144 -160.453)" fill="#12100d"></path>
                                            <path id="Path_535" data-name="Path 535" d="M240.686,167.512l3.175-6.918a.246.246,0,0,1,.19-.095h.095a.246.246,0,0,1,.19.095l3.175,6.918c.047.142,0,.284-.19.284h-.9a.26.26,0,0,1-.284-.19l-.521-1.09h-3.08l-.521,1.09a.32.32,0,0,1-.284.19h-.9C240.733,167.8,240.639,167.655,240.686,167.512Zm4.454-2.18-1.042-2.274h-.047l-.995,2.274Z" transform="translate(-149.194 -160.453)" fill="#12100d"></path>
                                            <path id="Path_536" data-name="Path 536" d="M259.153,161.932H257.59a.2.2,0,0,1-.19-.19v-.853a.2.2,0,0,1,.19-.19h4.5a.2.2,0,0,1,.19.19v.853a.2.2,0,0,1-.19.19h-1.564v5.78a.2.2,0,0,1-.19.19h-.948a.2.2,0,0,1-.19-.19v-5.78Z" transform="translate(-157.995 -160.558)" fill="#12100d"></path>
                                            <path id="Path_537" data-name="Path 537" d="M275.1,160.5a3.4,3.4,0,0,1,2.464.948.178.178,0,0,1,0,.284l-.616.663c-.095.095-.19.095-.237,0a2.4,2.4,0,0,0-1.564-.616,2.3,2.3,0,0,0-2.274,2.369,2.336,2.336,0,0,0,2.322,2.369,2.52,2.52,0,0,0,1.564-.569.156.156,0,0,1,.237,0l.616.663c.095.095.047.19,0,.284a3.587,3.587,0,0,1-2.511.995,3.7,3.7,0,1,1,0-7.391Z" transform="translate(-165.362 -160.453)" fill="#12100d"></path>
                                            <path id="Path_538" data-name="Path 538" d="M290.2,160.89a.2.2,0,0,1,.19-.19h.948a.2.2,0,0,1,.19.19v2.7h3.364v-2.7a.2.2,0,0,1,.19-.19h.948a.2.2,0,0,1,.19.19v6.775a.2.2,0,0,1-.19.19h-.948a.2.2,0,0,1-.19-.19v-2.843h-3.364v2.843a.2.2,0,0,1-.19.19h-.948a.2.2,0,0,1-.19-.19Z" transform="translate(-175.254 -160.558)" fill="#12100d"></path>
                                            <path id="Path_539" data-name="Path 539" d="M309.8,160.89a.2.2,0,0,1,.19-.19h4.17a.2.2,0,0,1,.19.19v.853a.2.2,0,0,1-.19.19h-3.032v1.706h2.559a.2.2,0,0,1,.19.19v.853a.2.2,0,0,1-.19.19h-2.559v1.8h3.032a.2.2,0,0,1,.19.19v.853a.2.2,0,0,1-.19.19h-4.17a.2.2,0,0,1-.19-.19Z" transform="translate(-185.567 -160.558)" fill="#12100d"></path>
                                            <path id="Path_540" data-name="Path 540" d="M324.639,166.749l.379-.616a.25.25,0,0,1,.332-.095,3.331,3.331,0,0,0,1.564.616.868.868,0,0,0,.948-.805c0-.521-.426-.9-1.327-1.232-.948-.379-1.943-1-1.943-2.227a2.066,2.066,0,0,1,2.322-1.99,3.248,3.248,0,0,1,2.037.711.229.229,0,0,1,.047.332l-.379.569a.318.318,0,0,1-.379.142,3.444,3.444,0,0,0-1.421-.569.818.818,0,0,0-.9.711c0,.474.379.805,1.232,1.137.995.379,2.132,1,2.132,2.322a2.175,2.175,0,0,1-2.369,2.037,3.5,3.5,0,0,1-2.274-.805C324.592,166.939,324.544,166.891,324.639,166.749Z" transform="translate(-193.347 -160.4)" fill="#12100d"></path>
                                        </g>
                                        <path id="Path_541" data-name="Path 541" d="M174.639,102v13.077a.8.8,0,0,0,.19.616,1.236,1.236,0,0,0,.616.332v.426h-5.4v-.426a1.236,1.236,0,0,0,.616-.332,1.055,1.055,0,0,0,.19-.616V102h-5.354a.755.755,0,0,0-.569.19,1.236,1.236,0,0,0-.332.616h-.426V98.347h.426a1.236,1.236,0,0,0,.332.616,1.016,1.016,0,0,0,.569.19h14.451a.692.692,0,0,0,.569-.19,1.236,1.236,0,0,0,.332-.616h.426V102.8h-.426a1.236,1.236,0,0,0-.332-.616,1.016,1.016,0,0,0-.569-.19Zm-27.718,6.87V113.6h11.087a.755.755,0,0,0,.569-.19,1.236,1.236,0,0,0,.332-.616h.426v4.454h-.426a1.236,1.236,0,0,0-.332-.616,1.016,1.016,0,0,0-.569-.19H142.326v-.426a1.236,1.236,0,0,0,.616-.332,1.055,1.055,0,0,0,.19-.616V100.48a.8.8,0,0,0-.19-.616,1.236,1.236,0,0,0-.616-.332v-.426h15.351a.755.755,0,0,0,.569-.19,1.5,1.5,0,0,0,.332-.616H159v4.454h-.426a1.236,1.236,0,0,0-.332-.616,1.016,1.016,0,0,0-.569-.19h-10.8v4.075h7.012a.755.755,0,0,0,.569-.19,1.236,1.236,0,0,0,.332-.616h.426v4.359h-.426a1.236,1.236,0,0,0-.332-.616.755.755,0,0,0-.569-.19h-6.965Zm-15.825.047h-9.95v6.207a.755.755,0,0,0,.19.569,1.5,1.5,0,0,0,.616.332v.426h-5.4v-.426a1.236,1.236,0,0,0,.616-.332,1.016,1.016,0,0,0,.19-.569V100.48a.755.755,0,0,0-.19-.569,1.236,1.236,0,0,0-.616-.332v-.426h5.4v.426a1.236,1.236,0,0,0-.616.332,1.016,1.016,0,0,0-.19.569v5.591h9.95V100.48a.755.755,0,0,0-.19-.569,1.236,1.236,0,0,0-.616-.332v-.426h5.4v.426a1.236,1.236,0,0,0-.616.332,1.016,1.016,0,0,0-.19.569V115.12a.755.755,0,0,0,.19.569,1.236,1.236,0,0,0,.616.332v.426h-5.4v-.426a1.236,1.236,0,0,0,.616-.332.755.755,0,0,0,.19-.569Zm-22.838,1.611,2.416,3.6-.332.237a.9.9,0,0,0-.569-.332,4.256,4.256,0,0,0-1.279.616,16.991,16.991,0,0,1-3.648,1.706,13.036,13.036,0,0,1-3.554.426,11.1,11.1,0,0,1-6.728-1.943,8.017,8.017,0,0,1-2.416-2.8,9.1,9.1,0,0,1-1.042-4.217,8.429,8.429,0,0,1,1.848-5.449c1.848-2.322,4.643-3.506,8.339-3.506a12.44,12.44,0,0,1,3.506.426A16.99,16.99,0,0,1,108.448,101a4.949,4.949,0,0,0,1.279.569.9.9,0,0,0,.569-.332l.332.237-2.416,3.6-.332-.237c0-.142.047-.237.047-.332a.827.827,0,0,0-.332-.663,3.551,3.551,0,0,0-1.137-.758,9.491,9.491,0,0,0-4.738-1.279,7.089,7.089,0,0,0-4.738,1.469,5.624,5.624,0,0,0-2.085,4.549,5.756,5.756,0,0,0,2.085,4.549,7.089,7.089,0,0,0,4.738,1.469,9.362,9.362,0,0,0,4.738-1.279,7.713,7.713,0,0,0,1.137-.758,1.1,1.1,0,0,0,.332-.711.925.925,0,0,0-.047-.332ZM69.738,104.6V115.12a.8.8,0,0,0,.19.616,1.236,1.236,0,0,0,.616.332v.426h-5.07v-.426a1.236,1.236,0,0,0,.616-.332,1.055,1.055,0,0,0,.19-.616V100.48a.755.755,0,0,0-.19-.569,1.236,1.236,0,0,0-.616-.332v-.426h5.875v.426c-.379.095-.521.284-.521.569a.977.977,0,0,0,.332.663l9.571,9.9V100.48a.755.755,0,0,0-.19-.569,1.236,1.236,0,0,0-.616-.332v-.426h5.07v.426a1.236,1.236,0,0,0-.616.332,1.016,1.016,0,0,0-.19.569V115.12a.8.8,0,0,0,.19.616,1.236,1.236,0,0,0,.616.332v.426H79.546v-.426c.332-.095.521-.237.521-.521,0-.19-.19-.474-.616-.9ZM52.4,109.671l-2.8-6.112-2.843,6.112Zm1.042,2.322h-7.77l-1.185,2.464a2.4,2.4,0,0,0-.237.805c0,.379.284.616.853.758v.426H39.367v-.426a1.412,1.412,0,0,0,.758-.332,3.3,3.3,0,0,0,.616-1l6.349-13.267a2.689,2.689,0,0,0,.284-.995q0-.569-.711-.853v-.426h6.112v.426c-.521.142-.758.379-.758.758a2.334,2.334,0,0,0,.237.853L58.841,114.5a4.782,4.782,0,0,0,.711,1.137,1.8,1.8,0,0,0,.853.426v.426h-6.4v-.426c.569-.095.853-.332.853-.805a2.74,2.74,0,0,0-.237-.805ZM29.607,115.12V100.48a.755.755,0,0,0-.19-.569,1.236,1.236,0,0,0-.616-.332v-.426h5.4v.426a1.236,1.236,0,0,0-.616.332,1.016,1.016,0,0,0-.19.569V115.12a.8.8,0,0,0,.19.616,1.5,1.5,0,0,0,.616.332v.426H28.849v-.426a1.236,1.236,0,0,0,.616-.332A1.312,1.312,0,0,0,29.607,115.12ZM9,113.6h6.4a4.992,4.992,0,0,0,2.559-.474,2.347,2.347,0,0,0,0-3.7,4.992,4.992,0,0,0-2.559-.474H9Zm0-7.581h6.633a3.7,3.7,0,0,0,2.085-.426,1.87,1.87,0,0,0,.663-1.564,1.846,1.846,0,0,0-.616-1.564A3.933,3.933,0,0,0,15.629,102H9v4.027ZM19.751,107.4a4.014,4.014,0,0,1,2.89,4.075,4.458,4.458,0,0,1-1.706,3.7,5.535,5.535,0,0,1-1.99.948,11.81,11.81,0,0,1-2.8.284H4.4v-.426a1.236,1.236,0,0,0,.616-.332,1.055,1.055,0,0,0,.19-.616V100.432a.8.8,0,0,0-.19-.616,1.236,1.236,0,0,0-.616-.332v-.426H16.1a13.207,13.207,0,0,1,2.7.237,5.741,5.741,0,0,1,1.8.853,4.059,4.059,0,0,1,1.516,3.459A3.9,3.9,0,0,1,19.751,107.4Z" transform="translate(-4.4 -53.241)" fill="#12100d"></path>
                                        <path id="Path_542" data-name="Path 542" d="M157.169,5.569A1.7,1.7,0,0,0,155.7,3.958V3.2h11.8V8.128H163.47v7.486h14.688c2.322,0,3.411-1.374,3.411-3.6,0-2.559-1.611-3.838-4.075-3.838V3.2c2.8,0,5.686.237,7.96,2.037a7.564,7.564,0,0,1,2.748,6.3c0,3.411-1.611,5.354-4.6,6.775,3.6,1.374,5.638,3.506,5.638,7.534a8.008,8.008,0,0,1-3.08,6.728c-2.464,1.943-5.638,2.322-8.718,2.322V29.875c2.843,0,5.022-1.469,5.022-4.454,0-2.748-1.611-4.217-4.312-4.217H163.47V29.97H167.5V34.9H155.7V34.14c.948-.237,1.469-.758,1.469-1.611Z" transform="translate(-84.013 -3.2)" fill="#12100d" fill-rule="evenodd"></path>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>

            </div>
            <div class="brand-grid div9">
                <li>

                    <a class="bove" href="https://www.ethoswatches.com/brands/bovet-watches.html" title="Bovet Watches" onclick="brandslogo('8','Bovet')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 80px;
  ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_15" data-name="Rectangle 15" width="417" height="243" transform="translate(0.301 -0.221)"></rect>
                                </clipPath>
                                <clipPath id="clip-Bovet_1822">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Bovet_1822" data-name="Bovet 1822" clip-path="url(#clip-Bovet_1822)">

                                <g id="Bovet" transform="translate(-107.301 -66.779)" clip-path="url(#clip-path)">
                                    <g id="Group_122" data-name="Group 122" transform="translate(115.285 74.407)">
                                        <path id="Bovet-01" d="M185.543,76.988a.93.93,0,0,1-.208.032H185.1v-.989c0-.04,0-.062.017-.081a.872.872,0,0,1,.167,0h.178a.378.378,0,0,1,.155.045.5.5,0,0,1,.3.482.567.567,0,0,1-.079.3.531.531,0,0,1-.3.214m1.4,1.167a.833.833,0,0,1-.282-.085,1.135,1.135,0,0,1-.265-.227c-.053-.079-.115-.167-.189-.263s-.134-.189-.2-.263a2.337,2.337,0,0,0-.215-.223h0a.878.878,0,0,0,.4-.2.486.486,0,0,0,.17-.378.567.567,0,0,0-.327-.567,1.008,1.008,0,0,0-.327-.089,3.728,3.728,0,0,0-.41-.019h-.964v.079a.567.567,0,0,1,.29.108.285.285,0,0,1,.058.167v1.463a1.714,1.714,0,0,1,0,.223.367.367,0,0,1-.053.16.289.289,0,0,1-.139.069.486.486,0,0,1-.165.026v.1h1.147v-.1a.568.568,0,0,1-.215-.032.208.208,0,0,1-.108-.085.328.328,0,0,1-.036-.131v-.756h.079a.209.209,0,0,1,.089,0,.465.465,0,0,1,.147.079.625.625,0,0,1,.134.128,2.209,2.209,0,0,1,.242.3c.053.081.121.189.2.3l.074.125a.5.5,0,0,0,.045.063l.023.04.04.063h.718a1.968,1.968,0,0,1-.656.541,1.86,1.86,0,0,1-.872.2,1.8,1.8,0,0,1-.735-.155,1.983,1.983,0,0,1-.6-.406,1.891,1.891,0,0,1-.406-.6,1.859,1.859,0,0,1,0-1.482,1.968,1.968,0,0,1,.406-.6,1.945,1.945,0,0,1,.6-.417,1.836,1.836,0,0,1,1.473,0,1.968,1.968,0,0,1,.6.417,2,2,0,0,1,.417.6,1.945,1.945,0,0,1-.165,1.809m.41-1.89a2.108,2.108,0,0,0-.465-.694,2.194,2.194,0,0,0-.695-.459,2.157,2.157,0,0,0-.849-.17,2.121,2.121,0,0,0-.84.17,2.18,2.18,0,0,0-1.161,1.151,2.115,2.115,0,0,0-.174.849,2.079,2.079,0,0,0,.174.843,2.292,2.292,0,0,0,.473.695,2.134,2.134,0,0,0,1.528.633,2.165,2.165,0,0,0,1.545-.633,2.218,2.218,0,0,0,.465-.694,2.134,2.134,0,0,0,.167-.843,2.169,2.169,0,0,0-.167-.849m-176.5,5.928a4.554,4.554,0,0,0-3.779,2.134l-.019.031.214.147.019-.026A4.236,4.236,0,0,1,10.4,82.5c1.121,0,1.662.656,1.662,1.968a3.514,3.514,0,0,1-1.715,2.968,6.286,6.286,0,0,1-3.322.983,3.781,3.781,0,0,1-3.911-3.611c0-3.1,2.112-4.032,4.348-5.023l.4-.174a2.378,2.378,0,0,0,1.513-2.4c0-1.513.722-4.348,3.452-4.348,1.488,0,2.242.656,2.242,1.934a2.9,2.9,0,0,1-2.647,2.847,3,3,0,0,1-.983-.1l-.034-.024-.031.327H11.4a4.159,4.159,0,0,0,1.013.134,3.477,3.477,0,0,0,3.69-3.14c0-1.6-1.545-2.292-3.072-2.292a5.17,5.17,0,0,0-3.9,1.5A5.008,5.008,0,0,0,7.86,77.5c0,1.728-.157,1.921-1.134,2.361-2.514,1.134-5.116,2.292-5.116,5.325-.008,2.244,2.023,3.7,5.165,3.7a8.157,8.157,0,0,0,4.375-1.458,4.215,4.215,0,0,0,2.161-3.081A2.167,2.167,0,0,0,10.865,82.2M77.3,77.377a.756.756,0,1,0,.359-.694.756.756,0,0,0-.359.694m14.192,8.7A5.643,5.643,0,0,1,88.533,88.1a.945.945,0,0,1-.883-1.063c0-2.458,2.932-6.111,4.915-6.111a.84.84,0,0,1,.9.924,6.819,6.819,0,0,1-1.983,4.22m-62.007,0A5.643,5.643,0,0,1,26.527,88.1a.945.945,0,0,1-.883-1.063c0-2.458,2.932-6.111,4.915-6.111a.843.843,0,0,1,.9.924,6.825,6.825,0,0,1-1.987,4.22M55.356,80.93a.531.531,0,0,1,.55.59c0,1.513-2.951,2.675-4.223,2.908.656-1.4,2.508-3.5,3.673-3.5m7.443,0a.527.527,0,0,1,.548.59c0,1.513-2.951,2.675-4.223,2.908.656-1.4,2.508-3.5,3.675-3.5m31.51,1.117h-.019c0-.911-.656-1.452-1.683-1.452A6.208,6.208,0,0,0,89,82.253a8.241,8.241,0,0,0-2.57,3.558c-.151.16-.3.327-.442.465-1.442,1.465-2.458,2.092-3.42,2.092a.524.524,0,0,1-.59-.594A7.079,7.079,0,0,1,83.537,85.2a7.917,7.917,0,0,0,1.89-3.278A1.323,1.323,0,0,0,84,80.572c-1.89,0-3.879,2.39-5.187,3.97l-.265.327,2.972-4.108L80,80.83h0c-.6,1-1.393,2.222-2.236,3.513l-.456.7a19.831,19.831,0,0,1-2.39,2.269,3.987,3.987,0,0,1-2.106,1.036c-.3,0-.437-.17-.437-.55,0-.625.773-1.731,2.836-4.688l1.607-2.292.032-.053H75.341v.017c-.535.809-1.01,1.513-1.414,2.106a3.214,3.214,0,0,1-1.908.964c-.508,0-.862-.437-.862-1.063a3.056,3.056,0,0,1,1.085-2.1l.053-.053h-.139c-.246-.019-.474-.04-.756-.04a.728.728,0,0,0-.8.479V81.1a23.682,23.682,0,0,0-4.252,3.82l2.853-4.159H67.758v.017c-.817,1.242-1.656,2.548-2.563,3.97a9.87,9.87,0,0,1-5.634,3.518c-.785,0-1.063-.756-1.063-1.442a4.738,4.738,0,0,1,.541-2.092c1.736-.378,5.509-1.688,5.509-3.2,0-.594-.456-.945-1.215-.945A7.789,7.789,0,0,0,57.6,84.933,9.83,9.83,0,0,1,52.119,88.3c-.785,0-1.063-.756-1.063-1.442a4.738,4.738,0,0,1,.548-2.095c1.736-.378,5.509-1.688,5.509-3.214,0-.594-.456-.945-1.215-.945a6.144,6.144,0,0,0-3.859,1.879l-.215.2a8.768,8.768,0,0,0-1.548,2.027c-.5.6-.983,1.134-1.416,1.576-1.442,1.465-2.458,2.092-3.416,2.092a.527.527,0,0,1-.594-.594,7.079,7.079,0,0,1,1.558-2.575,7.917,7.917,0,0,0,1.89-3.278,1.323,1.323,0,0,0-1.439-1.348c-1.89,0-3.879,2.39-5.187,3.97l-.265.327,2.953-4.121-1.513.069h-.069c-.594.983-1.384,2.2-2.215,3.482-.157.246-.327.486-.474.731a19.387,19.387,0,0,1-2.39,2.269,4.011,4.011,0,0,1-2.106,1.036c-.3,0-.437-.17-.437-.55,0-.625.773-1.731,2.836-4.688.469-.673.983-1.437,1.6-2.292l.036-.053h-1.51v.017c-.6.9-1.111,1.639-1.561,2.317L35.8,84.236a11.647,11.647,0,0,1-6,3.934l1.968-2.951c.956-1.469,1.968-2.986,3-4.411l.04-.055H33.176l-.826,1.276h-.023c0-.911-.656-1.452-1.686-1.452a6.208,6.208,0,0,0-3.607,1.662,8.2,8.2,0,0,0-2.57,3.554c-.151.16-.3.327-.446.469-1.442,1.465-2.458,2.092-3.416,2.092a.527.527,0,0,1-.594-.594,7.079,7.079,0,0,1,1.558-2.575,7.94,7.94,0,0,0,1.89-3.278,1.323,1.323,0,0,0-1.442-1.348c-1.89,0-3.879,2.39-5.187,3.97l-.259.312,2.943-4.118L18,80.794h-.019c-.594.983-1.384,2.2-2.215,3.486-.9,1.374-1.826,2.8-2.624,4.134l-.032.049h1.486v-.017c1.055-2.187,5.116-7.581,7.3-7.581.39,0,.616.189.616.5,0,.694-.777,1.656-1.6,2.679a6.707,6.707,0,0,0-1.843,3.214A1.382,1.382,0,0,0,20.6,88.634c1.032,0,2.193-.731,3.759-2.367a3.413,3.413,0,0,0-.062.59,1.658,1.658,0,0,0,1.667,1.777,5.9,5.9,0,0,0,3.866-2.486c-.249.347-.531.767-.833,1.214-1.968,2.917-4.948,7.328-8.352,7.328-2.344,0-2.692-1.38-2.692-2.2,0-1.259.613-1.968,1.686-1.968h.208v-.236l-.076-.019a2.66,2.66,0,0,0-.625-.079,2.329,2.329,0,0,0-2.473,2.269c0,1.513,1.558,2.581,3.781,2.581,4.571,0,7.562-4.348,8.7-5.987a4.305,4.305,0,0,1,.437-.584A11.093,11.093,0,0,0,34.99,85.44a4.113,4.113,0,0,0-.785,2.1,1.111,1.111,0,0,0,1.215,1.106,7.014,7.014,0,0,0,3.83-2.325c-.537.84-.945,1.492-1.312,2.095l-.032.049h1.465V88.45c1.055-2.187,5.116-7.581,7.3-7.581.39,0,.616.189.616.5,0,.694-.777,1.656-1.6,2.679a6.707,6.707,0,0,0-1.843,3.214,1.382,1.382,0,0,0,1.528,1.374c1.4,0,2.911-1.439,4.068-2.7.125-.134.259-.282.406-.45a3.768,3.768,0,0,0-.242,1.255,1.773,1.773,0,0,0,2,1.89A8.989,8.989,0,0,0,57.254,85.6a3.7,3.7,0,0,0-.208,1.147,1.773,1.773,0,0,0,2,1.89,9.008,9.008,0,0,0,5.361-2.764c-.526.833-1.066,1.688-1.59,2.535l-.032.049h1.535v-.019a11.2,11.2,0,0,1,1.76-2.951,25.808,25.808,0,0,1,4.306-4.006h0a2.01,2.01,0,0,0-.24.956,1.535,1.535,0,0,0,1.62,1.686,2.853,2.853,0,0,0,1.656-.625c-1.452,2.153-2.045,3.085-2.045,4.036a1.111,1.111,0,0,0,1.215,1.106,7.014,7.014,0,0,0,3.83-2.325c-.541.843-.945,1.5-1.312,2.095l-.031.049h1.465v-.017c1.055-2.187,5.116-7.581,7.3-7.581.39,0,.616.189.616.5,0,.694-.777,1.656-1.6,2.679a6.679,6.679,0,0,0-1.843,3.214,1.382,1.382,0,0,0,1.528,1.374c1.032,0,2.193-.731,3.759-2.367a3.117,3.117,0,0,0-.063.59,1.658,1.658,0,0,0,1.667,1.777,5.911,5.911,0,0,0,3.866-2.486c-.253.347-.535.772-.833,1.215C88.976,90.276,86,94.685,82.6,94.685c-2.344,0-2.692-1.38-2.692-2.2,0-1.259.613-1.968,1.686-1.968H81.8v-.236l-.076-.019a2.666,2.666,0,0,0-.629-.079A2.329,2.329,0,0,0,78.63,92.45c0,1.513,1.558,2.581,3.781,2.581,4.571,0,7.562-4.348,8.7-5.991L91.139,89c.115-.17.327-.479.514-.741.147-.208.272-.39.312-.442.594-.875,1.181-1.773,1.751-2.647.956-1.469,1.945-2.986,3-4.411l.036-.055H95.116l-.826,1.276Zm38.974-4.684a.756.756,0,1,0,.756-.756.756.756,0,0,0-.756.756m16.258,10.719c-.492,0-.772-.378-.772-1.042,0-2.647,3-6.134,4.772-6.134a.945.945,0,0,1,.983,1.013c0,2.581-3.456,6.161-4.974,6.161M180.811,80.9a.531.531,0,0,1,.548.59c0,1.513-2.951,2.675-4.227,2.908.656-1.4,2.508-3.5,3.681-3.5m2.316,3.847a9.862,9.862,0,0,1-5.625,3.509c-.781,0-1.058-.756-1.058-1.442a4.751,4.751,0,0,1,.548-2.095c1.736-.378,5.509-1.688,5.509-3.2,0-.594-.456-.945-1.22-.945a7.732,7.732,0,0,0-5.652,4.159,15.948,15.948,0,0,1-1.872,1.741A7.137,7.137,0,0,1,170,88.254c-.817,0-1.111-.735-1.111-1.424a6.828,6.828,0,0,1,1.688-3.934,4.7,4.7,0,0,1,2.836-2c.5,0,.756.259.756.794a2.622,2.622,0,0,1-.223.924l-.019.049h.023c.347-.026,1.458-.189,1.458-1.017,0-.675-.613-1.081-1.639-1.081a7.683,7.683,0,0,0-5.807,4.375c-.433.5-.833.945-1.191,1.312-1.442,1.465-2.458,2.092-3.416,2.092a.527.527,0,0,1-.594-.594,7.053,7.053,0,0,1,1.561-2.575,7.917,7.917,0,0,0,1.89-3.278,1.323,1.323,0,0,0-1.439-1.348c-1.89,0-3.879,2.39-5.187,3.97l-.263.327,2.934-4.057.04-.058-1.513.069h-.017c-.6.983-1.389,2.21-2.227,3.5l-.635.972c-1.639,1.853-3.214,3.049-4.044,3.049-.393,0-.567-.17-.567-.55,0-1.026,2.019-3.875,4.159-6.885l.112-.157h-1.547l-.738,1.312a1.786,1.786,0,0,0-1.906-1.492c-2.079,0-4.82,2.718-5.709,4.974-.809.845-2.817,2.8-4.032,2.8-.3,0-.437-.17-.437-.55,0-.625.773-1.731,2.836-4.688.469-.673,1-1.437,1.6-2.292l.036-.053h-1.513v.017c-.6.9-1.111,1.66-1.567,2.327-.233.327-.446.656-.635.945-2.115,2.66-4.129,4.312-5.246,4.312-.3,0-.459-.189-.459-.526,0-.781.8-1.968,4.159-6.995,1.354-2.014,3.036-4.537,5.267-7.9l.032-.053h-1.635v.017c-2.514,4.013-4.348,6.781-5.695,8.8-.934,1.406-1.607,2.42-2.079,3.214-1.875,2.18-3.527,3.433-4.537,3.433-.3,0-.459-.189-.459-.526,0-.781.8-1.968,4.159-6.995,1.354-2.014,3.04-4.537,5.267-7.9l.032-.053h-1.639v.017c-2.513,4.01-4.348,6.773-5.689,8.8-1,1.513-1.724,2.6-2.206,3.424-.694.756-3.056,3.229-4.429,3.229-.3,0-.437-.17-.437-.55,0-.625.773-1.731,2.836-4.688.469-.673,1-1.437,1.6-2.292l.036-.053h-1.513v.013c-.537.809-1.013,1.513-1.414,2.106a3.214,3.214,0,0,1-1.908.964c-.5,0-.862-.437-.862-1.063a3.045,3.045,0,0,1,1.089-2.1l.045-.053h-.131c-.246-.019-.479-.04-.767-.04a.728.728,0,0,0-.8.479v.023a23.609,23.609,0,0,0-4.236,3.841l2.82-4.108.04-.051h-1.458v.017c-1.482,2.269-3.036,4.7-4.9,7.689l-.032.049h1.535a11.2,11.2,0,0,1,1.758-2.951,25.941,25.941,0,0,1,4.306-4.023h0a2.05,2.05,0,0,0-.236.956,1.528,1.528,0,0,0,1.616,1.686,2.853,2.853,0,0,0,1.656-.625c-1.452,2.153-2.045,3.085-2.045,4.036a1.111,1.111,0,0,0,1.215,1.1c1.312,0,3.079-1.558,4.1-2.586a3.309,3.309,0,0,0-.351,1.414c0,.756.469,1.174,1.323,1.174s1.875-.594,3.354-1.968c.189-.189.417-.4.656-.639a3.348,3.348,0,0,0-.367,1.442c0,.756.473,1.174,1.323,1.174s1.875-.594,3.352-1.968c.263-.242.541-.527.84-.849a3.492,3.492,0,0,0-.633,1.7A1.111,1.111,0,0,0,143.43,88.7c1.312,0,3.036-1.528,4.01-2.505a3.346,3.346,0,0,0-.108.794c0,1.072.567,1.7,1.528,1.7,1.479,0,3.316-1.89,3.731-2.331a3.352,3.352,0,0,0-.3,1.2,1.115,1.115,0,0,0,1.282,1.134,5.643,5.643,0,0,0,3.488-2.144c-.486.756-.862,1.365-1.193,1.915l-.031.049H157.3v-.023c1.055-2.187,5.1-7.581,7.3-7.581.39,0,.613.189.613.5,0,.694-.777,1.656-1.6,2.679a6.707,6.707,0,0,0-1.843,3.214A1.38,1.38,0,0,0,163.3,88.68c1.4,0,2.908-1.439,4.066-2.7l.263-.285a4.1,4.1,0,0,0-.208,1.227,1.736,1.736,0,0,0,1.968,1.754,7.848,7.848,0,0,0,4.8-2.248q.508-.424,1.031-.945a3.857,3.857,0,0,0-.255,1.312,1.772,1.772,0,0,0,2,1.89c2.709,0,5.522-2.853,6.32-3.728a.151.151,0,0,0,0-.219.157.157,0,0,0-.227.017M40.868,78.139a.738.738,0,0,0,.756-.773.756.756,0,1,0-1.5,0,.735.735,0,0,0,.756.773m75.429,2.416a6.384,6.384,0,0,0,2.807-1.31,3.542,3.542,0,0,0,1.38-2.758,3.3,3.3,0,0,0-1.425-2.794,6.3,6.3,0,0,1,2.718-.983h.011v-.327h-.036a9.509,9.509,0,0,0-3.1,1.068,8.866,8.866,0,0,0-4.289-.934,12.3,12.3,0,0,0-7.991,2.883,8.154,8.154,0,0,0-3.161,5.834,3.934,3.934,0,0,0,.9,2.584,3.259,3.259,0,0,0,2.558,1.1c2.662,0,4.053-2.522,4.053-5.013a9.315,9.315,0,0,0-.112-1.482v-.031l-.308.026v.032c.04.437.089.929.089,1.406,0,2.785-1.528,4.726-3.718,4.726-1.7,0-2.584-1.758-2.584-3.5a7.618,7.618,0,0,1,3-5.683,11.343,11.343,0,0,1,7.153-2.55,6.125,6.125,0,0,1,3.853.983c-2.34,1.479-3.853,4-5.785,7.184l-.858,1.416-.189.3c-2.2,3.607-3.531,5.786-6.4,5.786-.452,0-1.338-.155-1.5-.738A.756.756,0,0,0,104.1,87a.777.777,0,0,0-.794-.858.911.911,0,0,0-.929.983c0,.983,1.068,1.7,2.537,1.7,4.327,0,6.885-4.42,9.367-8.7a24.9,24.9,0,0,1,4.193-5.938,2.754,2.754,0,0,1,.731,1.833,4.514,4.514,0,0,1-1.272,3.254,4.263,4.263,0,0,1-3.1,1.2h-.079v.327h.144a3.327,3.327,0,0,1,2.388.735,2.318,2.318,0,0,1,.613,1.715c0,3.477-2.115,5.063-4.079,5.063a1.439,1.439,0,0,1-1.55-1.376c0-.55.115-1.229.656-1.323h.058l-.044-.045a.883.883,0,0,0-.656-.278,1.312,1.312,0,0,0-1.312,1.393c0,1.167,1.174,1.968,2.853,1.968a5.732,5.732,0,0,0,3.639-1.528,5.01,5.01,0,0,0,1.82-3.715,2.809,2.809,0,0,0-3.007-2.886M148.9,78.111a.738.738,0,0,0,.756-.773.756.756,0,1,0-1.5,0,.735.735,0,0,0,.756.773M14.073,20.88l4.843-.514c6.764-.7,8.929,2.157,8.929,7.161a10.475,10.475,0,0,1-8.367,10.417c-1.826.433-3.626.875-5.4,1.354V20.88Zm0-17.674h5.645a5.815,5.815,0,0,1,6.2,6.378,6.1,6.1,0,0,1-3.97,6.407,17.336,17.336,0,0,1-7.87,1.611V3.2Zm-7.639,38.3c-1.639.508-3.236,1.068-4.817,1.639v2.563a213.335,213.335,0,0,1,24.009-7.1c7.184-1.7,9.938-6.959,9.938-11.687,0-5.9-3.634-8.97-8.853-9.509,3.446-1.276,6.459-3.847,6.459-7.772-.012-7.737-6.048-8.6-12.287-8.6H1.21V3.183H6.456L6.439,41.506Zm99.381,20.47a10.82,10.82,0,0,0,1.2-2.875.393.393,0,0,0-.357-.378c-.625,0-.956,2.106-3.55,2.106H99.285c-.679,0-.856-.147-.856-.327,0-.684,6.968-7.541,6.968-11.562a4.016,4.016,0,0,0-4.335-4.1,6.189,6.189,0,0,0-5.66,3.592c0,.189.1.367.417.367.473,0,1.5-1.809,3.675-1.809a2.887,2.887,0,0,1,3.112,3.125c0,2.469-2.607,6.312-4.053,8.22-3.242,4.263-3.66,4.59-3.66,5.07,0,.253.347.282.656.282.586,0,1.667-.112,3.322-.112,2.764,0,4.813.112,5.5.112.813,0,1.187-1.193,1.446-1.7M64.582,63.269c0,.079.023.406.208.406.625,0,2.751-.112,3.754-.112s2.424.112,3.233.112,1.344.081,1.344-.469c0-.983-3.049.55-3.049-2.581V47.875c0-1.049.2-2.269.2-2.768a.253.253,0,0,0-.259-.269,9.915,9.915,0,0,1-4.634,2.125c-.312.081-.983.139-.983.694,0,.079.081.465.378.465a11.807,11.807,0,0,1,1.639-.272c.365,0,.858-.032.858,1.6V60.882c0,2.647-2.7,1.323-2.7,2.39m17.432-8.6,3.607,1.439A3.663,3.663,0,0,1,88.338,59.7c0,2.292-2.014,3.259-3.97,3.259-2.807,0-4.263-2.458-4.263-5.18a2.917,2.917,0,0,1,1.89-3.1M80.809,48.7a2.99,2.99,0,0,1,3.152-2.934,3.472,3.472,0,0,1,3.9,3.577,2.57,2.57,0,0,1-2.575,2.836c-.794,0-4.477-1.242-4.477-3.479M84.021,63.86c3.334,0,6.518-1.686,6.518-5.59,0-3.24-1.686-4.348-4-5.429v-.049a3.872,3.872,0,0,0,3.278-3.736c0-2.847-2.836-4.227-5.209-4.227a5.352,5.352,0,0,0-5.785,5.161,4.425,4.425,0,0,0,2.183,4.045,5.191,5.191,0,0,0-3.056,4.679c0,2.125,1.181,5.144,6.076,5.144M93.6,32.866h1.758l12.575-29.68h3.4V1.039H101.45V3.186h3.575L96.695,22.7,88.258,3.185h5.085V1.039H74.628V3.222h5.861l13.1,29.644Zm27.224-1.683c-1.374-.139-2.79-.285-4.229-.439v2.08a267.731,267.731,0,0,1,28.891,4.2L145.458,25.5l-1.393-.544c-.351,10.139-6.785,8.129-7.437,8.08-.157,0-3.634-.463-8.83-1.063V17.17c5.22,0,6.919,1.174,7.354,6.558h1.365V10.462h-1.365c-.24,5.18-2.79,4.616-7.354,4.414V3.2h8.195a7.709,7.709,0,0,1,7.725,8.155h1.758V1.041H116.321V3.213h4.492Zm43.9-28.013L164.7,39.342q-2.82-.83-5.705-1.594v2.4c6.558,1.747,12.666,3.736,18.506,5.921V43.6c-1.594-.6-3.214-1.193-4.885-1.76l.019-38.668c7.246,0,9.91,5.121,9.991,12.2l2,.473V1.041H153V12.615h1.7c0-5.246,2.411-9.509,10.019-9.439m-53.6,60.212c0,.253.327.282.656.282.586,0,1.639-.112,3.312-.112,2.764,0,4.827.112,5.5.112.826,0,1.187-1.193,1.446-1.7a10.842,10.842,0,0,0,1.206-2.875.4.4,0,0,0-.353-.378c-.635,0-.945,2.106-3.55,2.106h-3.82c-.686,0-.869-.147-.869-.327,0-.684,6.974-7.541,6.974-11.562a4.016,4.016,0,0,0-4.347-4.093,6.189,6.189,0,0,0-5.66,3.592c0,.189.108.367.417.367.486,0,1.513-1.809,3.692-1.809a2.879,2.879,0,0,1,3.1,3.125c0,2.469-2.6,6.312-4.044,8.22-3.24,4.263-3.66,4.59-3.66,5.07M49.243,10.655a8.341,8.341,0,1,1,16.676,0v13.4a8.339,8.339,0,1,1-16.677,0Zm8.33,24.029a17.033,17.033,0,0,0,16.737-17.33,16.737,16.737,0,1,0-33.461,0,17.054,17.054,0,0,0,16.737,17.33" transform="translate(-1.21 -0.147)" fill="#231f20"></path>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>


            </div>
            <div class="brand-grid div10">
                <li>

                    <a class="brei" href="https://www.ethoswatches.com/brands/breitling.html" title="Breitling Watches" onclick="brandslogo('9','Breitling')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 86px;
  ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_16" data-name="Rectangle 16" width="357" height="209" transform="translate(0.279 -0.065)"></rect>
                                </clipPath>
                                <clipPath id="clip-Breitling_1884">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Breitling_1884" data-name="Breitling 1884" clip-path="url(#clip-Breitling_1884)">

                                <g id="Breitling" transform="translate(-77.279 -49.935)" clip-path="url(#clip-path)">
                                    <rect id="Rectangle_33" width="357" height="265" transform="translate(0.279 -28.065)" fill="none"></rect>
                                    <rect id="Rectangle_33-2" width="357" height="265" transform="translate(0.279 -28.065)" fill="none"></rect>
                                    <g id="Group_3" data-name="Group 3" transform="translate(84.761 60.274)">
                                        <path id="Path_708" d="M12300.789,1137.1c-6.613,0-11.141,3.775-11.141,10.571s4.715,10.194,11.141,10.194c6.793,0,9.809-2.643,9.809-2.832v-9.06h-9.453v3.208H12307v3.964a11.865,11.865,0,0,1-6.234,1.134c-3.773,0-7.551-1.7-7.551-6.8a6.945,6.945,0,0,1,6.613-7.173h.941a15.248,15.248,0,0,1,6.418,1.511l1.508-3.209A22.722,22.722,0,0,0,12300.789,1137.1Zm-178.957.377v20.009h12.645c5.477,0,6.609-3.02,6.609-6.229a4.766,4.766,0,0,0-2.828-4.343,4.237,4.237,0,0,0,1.7-3.775c0-4.153-2.266-5.474-6.98-5.474Zm158.379,0v13.592l-11.895-13.214-.375-.378h-2.645v20.009h3.586v-13.78l12.457,13.78h2.457v-19.821Zm-107.6,0v20.009h16.8v-3.4h-13.4v-5.1h9.625v-3.209h-9.625v-4.908h13.211v-3.4Zm33.594,0v3.4h7.367v16.612h3.582v-16.612h7.367v-3.4Zm-10.191,0v20.009h3.586v-19.821Zm-48.324,0v20.009h3.586v-8.305h5.27l5.094,8.305h4.152l-5.473-8.305a5.644,5.644,0,0,0,5.664-5.475v-.566c0-4.908-3.777-5.852-7.176-5.852l-11.133.189Zm83.629,0v20.009h15.668v-3.4h-12.09v-16.612h-3.582Zm21.328,0v20.009h3.586v-19.821Zm-127.422,3.209h7.742c2.074,0,3.395.755,3.395,2.454a2.215,2.215,0,0,1-1.887,2.642h-9.625Zm26.051,0h7.551c1.324,0,3.59.377,3.59,3.02,0,2.076-2.078,2.454-3.59,2.454h-7.551Zm-26.051,8.306h8.684c1.887,0,3.586.378,3.586,2.643a2.541,2.541,0,0,1-2.453,2.643h-9.816Zm78.527,18.311a6.813,6.813,0,0,0-5.285,1.888,3.86,3.86,0,0,0,.379,5.1l.379.377-.379.188-.566.567a4.078,4.078,0,0,0-.945,3.209c.191,2.077,1.891,4.342,6.609,4.342h0c4.531,0,6.418-2.268,6.609-4.342a3.478,3.478,0,0,0-1.133-3.209l-.57-.567-.375-.188.375-.377a3.589,3.589,0,0,0,.188-5.1h0a6.812,6.812,0,0,0-5.281-1.888Zm15.668,0a6.817,6.817,0,0,0-5.285,1.888,3.118,3.118,0,0,0-.941,2.83,2.7,2.7,0,0,0,1.133,2.268l.375.378-.379.189-.566.566a4.078,4.078,0,0,0-.945,3.209c.191,2.076,1.891,4.342,6.609,4.342h0c4.531,0,6.422-2.268,6.605-4.342a4.075,4.075,0,0,0-.941-3.209l-.562-.566-.383-.188.383-.377a3.615,3.615,0,0,0,.375-5.1h0a7.247,7.247,0,0,0-5.516-1.89Zm-28.312.377V1182.4h2.828V1167.68Zm41.148,0-4.152,9.25v2.644h8.3v2.831h2.645v-2.831h1.7v-2.644h-1.7v-9.25Zm-28.5,2.268a4.5,4.5,0,0,1,3.4.944c.191.189.379.566.191.755-.191.755-1.324,1.51-3.594,1.51s-3.586-.755-3.586-1.51c0-.377,0-.566.191-.755a4.49,4.49,0,0,1,3.395-.944Zm15.48,0a4.5,4.5,0,0,1,3.4.943c.188.19.375.568.188.756,0,.755-1.324,1.51-3.586,1.51s-3.59-.755-3.59-1.51c0-.377,0-.567.188-.755a4.508,4.508,0,0,1,3.4-.944Zm14.535.378h2.453v6.607h-5.477Zm-30.012,5.662a6.79,6.79,0,0,1,3.773,1.134c.375.189.375.755.375,1.134-.184,1.321-2.262,1.887-4.148,1.887s-3.965-.566-4.156-1.887a1.411,1.411,0,0,1,.379-1.134,4.559,4.559,0,0,1,3.77-1.136Zm15.477,0a6.784,6.784,0,0,1,3.777,1.134,1.368,1.368,0,0,1,.375,1.134c0,1.321-2.266,1.887-4.152,1.887s-3.965-.566-4.152-1.887a2.668,2.668,0,0,1,.383-1.134A4.555,4.555,0,0,1,12219.234,1175.988Z" transform="translate(-12121.832 -1095.193)" fill="#07213f"></path>
                                        <path id="Path_709" d="M12183.051,1131.7c-1.133-.566,0-1.51.949-1.51a6.22,6.22,0,0,1,3.02,1.321c-.187,0-2.828.944-3.969.189m-14.531,13.591c-3.586.944-2.641-6.982,1.887-13.214a29,29,0,0,1,8.3-8.306c-.75,9.25-6.227,20.576-10.191,21.52m22.465-25.673a11.058,11.058,0,0,0-8.492.568h0a12.59,12.59,0,0,0-.379-5.286l-3.395.944a32.173,32.173,0,0,1,.184,6.04h0c-8.684,5.1-14.723,13.592-16.234,19.255-1.887,6.229,3.211,8.306,7.363,5.286,10.191-7.551,12.27-24.729,12.27-24.729a7.669,7.669,0,0,1,6.984.755c3.59,3.209-.941,7.929-.941,7.929a6.393,6.393,0,0,0-3.59-1.7c-3.586-.377-5.473,4.153-.562,4.531a12.116,12.116,0,0,0,3.77-.566c.762,1.51.57,8.494-4.148,10.57-4.34,1.888-5.852-.755-5.852-.755a12.334,12.334,0,0,0-2.27,3.209,6.4,6.4,0,0,0,7.176,1.888,11.348,11.348,0,0,0,6.8-6.982,10.269,10.269,0,0,0-.2-8.5c6.609-4.53,5.672-10.57,1.516-12.458" transform="translate(-12085.873 -1114.9)" fill="#ffc72c"></path>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div11">
                <li>

                    <a class="cuer" href="https://www.ethoswatches.com/brands/cuervo-y-sobrinos-watches.html" title="Cuervo y Sobrinos Watches" onclick="brandslogo('16','Cuervo y Sobrinos')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 120px;
  ">
                            <defs>
                                <clipPath id="clip-Cuervo-y-Sobrinos">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Cuervo-y-Sobrinos" clip-path="url(#clip-Cuervo-y-Sobrinos)">
                                <rect width="203" height="109" fill="#fff"></rect>
                                <g id="Cuervo-y-Sobrinos-Logo" transform="translate(-62.154 -42.879)">
                                    <g id="Group_109" data-name="Group 109" transform="translate(70.863 96.683)">
                                        <path id="Path_543" data-name="Path 543" d="M1155.851,780.87c-.127-.622,5.382-11.629,11.54-14.484,3.958-1.836,11.827,1.577,5.484,9.024s-16.885,6.14-17.025,5.459M1176.1,775.1a6.73,6.73,0,0,0,2.207-4.866,4.877,4.877,0,0,0-3.847-5.289c-7.015-1.075-14.285,5.232-14.907,5.771-2.059,1.561-6.529,8.536-7.6,10.233-3.773-1.1-8.95-5.315-6.485-8.763,3.056-4.27,8.91-1.216,9.109-1.216s.2-.254.2-.254a1.343,1.343,0,0,0-.284-.254,10.258,10.258,0,0,0-4.355-1.867c-8.515-1.131-8.571,5.232-8.316,6.279.961,5.177,9.9,6.675,9.9,6.675s-1.083,1.89-2.206,3.592c-7.242,10.975-11.425,15.73-17.063,16.949a14.945,14.945,0,0,1-6.274-.023l9.273-18.759-.4-.086-1.669,1.216-1.5.934a16.708,16.708,0,0,1-1.864.819c-3.963,1.3-3.255-2.177-3.085-4.156.011-.133-.34-.029-.34-.029l-2.206,1.3-1.981,1.046v.482l1.839-.906s.537-.282.537,0-.014,1.811.015,2.15c.009.114.368,2.207,3.465,1.414.918-.235,2.263-.735,2.263-.735s1.131-.34.792.4c-.166.361-3.206,7.476-6.26,14.632-5.651-1.64-9.749-6.206-8.081-10.105-.226-.567-1.286,1.305-.821,2.771,1.427,4.48,5.271,7.166,8.438,8.422-3.01,7.052-5.891,13.811-5.891,13.811s-.026.144.042.169c.113.042.2-.15.2-.15l6.658-13.471a9.974,9.974,0,0,0,2.439.5c1.751.086,10.752.424,20.338-11.484,3.933-4.887,6.835-10.634,7.077-10.614.679.057,12.1,1.678,20.649-6.534" transform="translate(-1043.279 -764.823)" fill="#231f20"></path>
                                        <path id="Path_544" data-name="Path 544" d="M80.2,805.645l.066-.071a13.918,13.918,0,0,1,3.3-3.506l.038-.03.228-.165c.065-.046.131-.094.2-.139l.046-.031a18.617,18.617,0,0,1,5.545-2.475l.277-.079c2.819-.83,5.126-.553,6.494.78a4.366,4.366,0,0,1,.988,4.325,9.261,9.261,0,0,1-6.988,5.991c-4,.89-10.8-1.142-11.339-1.457a1.115,1.115,0,0,1-.156-.114,14.544,14.544,0,0,1,1.3-3.029M93.45,821.528a8.184,8.184,0,0,0-2.461,1.3s-.139.45.226.34c4.992-2.81,11.033.849,7.694,4.5-6.483,7.081-26.685-3.249-20.274-17.925a5.774,5.774,0,0,0,.842.189c.6.113,7.606,1.761,11.766,1.274,17-1.988,8.316-19.319-6.816-10.494a34,34,0,0,0-4.6,3.493A26.853,26.853,0,0,0,77,807.509c-7.064-5.287,3.213-12,9.322-11.466a.177.177,0,0,0,.029-.255c-7.7-.661-11.368,2.055-12.22,2.8-6.494,5.542-1.949,8.753,1.955,10.307-8.305,13.754,7.191,25.9,18.462,22.9,10.14-2.575,6.286-12.05-1.1-10.268" transform="translate(-70.863 -793.537)" fill="#231f20"></path>
                                        <path id="Path_545" data-name="Path 545" d="M2243.188,980.195a.768.768,0,1,0-.768-.768.768.768,0,0,0,.768.768" transform="translate(-2091.245 -963.773)" fill="#231f20"></path>
                                        <path id="Path_546" data-name="Path 546" d="M370.975,1017.655c-.468,3.648-3.4,3.97-4.955,2.68-1.492-1.236-.744-3.41.738-4.4,1.864-1.244,4.342-.955,4.217,1.718v0m-35.653-.146c.933-.707,2.83-1.866,3.2-.736.326,1-.791,2.15-.791,2.15a5.506,5.506,0,0,1-2.6,1.8c-.085.035-.317.1-.317.1a2.414,2.414,0,0,1,.516-3.309m38.285.806c-1.348.183-2.132.051-2.1-.293a3.314,3.314,0,0,0-.527-2.454c-1.1-1.443-2.924-.858-3.585-.591-2.3.932-2.854,2.136-4.424,2.22-1.046.057-2.708-1.619-3.077-2.219-.462,0,.87,3.11-1.223,5.078a3.544,3.544,0,0,1-2.9,1.1,5.2,5.2,0,0,1,.113-4.936c.041-.076-.015-.085-.155-.113a2.78,2.78,0,0,0-.666.268l-1.584.707a9.246,9.246,0,0,1-2.392.824,1.237,1.237,0,0,0-.127-1.177c-.2-.354-1.258-.567-2.56-.085s-2.729,1.1-2.729,1.1l.9-1.626h-2.135a10.423,10.423,0,0,1-3.042,4.115,9.352,9.352,0,0,1-5.207,1.2c-.89-.066-1.1-.363-1.111-.391a4.408,4.408,0,0,0,.423-.151l.077-.027s.009-.007.01-.011c2.313-1,4.627-3.91,2.541-4.836-.979-.434-7.284.41-4.936,5.021.165.216-.7.486-1.14.592a.1.1,0,0,0-.02,0c-3.631.64-4.1-.482-3.4-2.519a14.125,14.125,0,0,1,2.065-3.055h-1.923s-.509,2.6-6.166,4.978c-.711.3-2.433.537-1.3-1.7.876-1.386,1.895-2.885,1.895-2.885l.31-.509-1.923.2s-.226,1.33-3.932,2.773c.029.255.2.282.2.282l1.866-.678s-1.98,3.337,1.1,3.507c1.754.2,5.375-2.32,5.375-2.32a1.881,1.881,0,0,0,1.894,2.49,19.875,19.875,0,0,0,4.238-.31,6.239,6.239,0,0,0,.939-.213c.189-.062.358-.124.519-.184a5.236,5.236,0,0,0,3.017.608,17.87,17.87,0,0,0,3.933-.974.472.472,0,0,1,.282-.058c.171.058-.155.707-.155.707l-.155.354h2.135a7.4,7.4,0,0,1,5.587-5.021c.354-.085.042.1-.042.269a1.114,1.114,0,0,0,1.16,1.513,1.683,1.683,0,0,0,1.067-.368,6.662,6.662,0,0,0,1.14-.241c.594-.169,1.442-.509,1.442-.509a6.717,6.717,0,0,0,1.244,4.781,5.826,5.826,0,0,0,5.918-6.37c0,.166,1.742,1.959,3.435,1.41-1.2,1.6.086,4.244,2.684,4.607a4.358,4.358,0,0,0,4.9-3.551,3.7,3.7,0,0,0,2.232.2,1.892,1.892,0,0,1-.015-.537" transform="translate(-300.488 -997.241)" fill="#231f20"></path>
                                        <path id="Path_547" data-name="Path 547" d="M1783,883.236l-.187.324a2.578,2.578,0,0,1-.382.462c-.035.037-.065.075-.1.111a4.473,4.473,0,0,1-2.846,1.216,1.07,1.07,0,0,1-.171.007,2.9,2.9,0,0,1-1.791-.475c-1.152-.881-.739-3.631,3.358-4.714,2.956-.78,2.99,1.463,2.12,3.071m-52.157.626a4.234,4.234,0,0,1-2.78,1.059,1.955,1.955,0,0,1-1.7-2.832,4.028,4.028,0,0,1,2.522-1.981c1.716-.544,3.014.575,3.011,1.633a2.958,2.958,0,0,1-1.051,2.121m24.616-14.456c3.423,1.358-3.083,6.987-10.3,6.9a23.9,23.9,0,0,1-2.984-.354l-.65-.141s6.294-8.161,13.931-6.407m39.587,8.345a29,29,0,0,1-3.946,2.432s-3.805,1.853-6.11,2.022a5.744,5.744,0,0,1-1.128-.027,2.506,2.506,0,0,0-.1-2,2.8,2.8,0,0,0-2.913-.547,16.012,16.012,0,0,0-5.287,2.9c-.952.8-5.345,3.218-5.816,2.427-.281-.472.849-1.64.849-1.64s1.584-1.584.933-2.744c-.778-1.138-4.492.368-6.617,1.357.009-.013.017-.028.027-.042a5.147,5.147,0,0,0,.594-1.386s-2.165-.155-2.221.072c-1.188,2.941-6.788,4.723-7.538,4.355-1.131-.65,2.771-4.469,2.771-4.469h-2.206c-1.3,1.191-2.269,1.1-2.623,1.011a1.542,1.542,0,0,0,.049-.332c.028-2.107-2.517-1-2.517-1l-1.838.876s.282-1.061,0-.947a24.761,24.761,0,0,1-3.537.849c-.89.142-2.154.234-2.781.274-.089-.865-.76-1.494-1.406-1.265-1.244.481-.708,2.008.085,1.754,1.046.283-.934,4.1-3.933,3.479-2.828-.735,3.394-8.882,3.394-8.882s.623.085,1.527.2c8.74,1.33,16.972-5.121,15.275-7.3-3.929-4.677-17.638,4.236-21.632,11.525a5.63,5.63,0,0,1-3.9,1.113c-.031-1.866-2.159-2.746-3.8-2.372a8.364,8.364,0,0,0-3.821,2.379,6.349,6.349,0,0,1-1.7.141c-.028.254-.52.489-.183.566a7.92,7.92,0,0,0,1.768-.042,3.582,3.582,0,0,0,0,1.23c.594,2.957,5.61,3.319,7.529-1l.143-.418a16.728,16.728,0,0,0,3.358-.331,5.734,5.734,0,0,0-.452,2.964c.537,2.151,5.261,1.641,7.58-2.743a2.078,2.078,0,0,0,.155-.575c1.344-.088,2.854-.283,3.437-.345.664-.071.538.1.538.1l-2.433,4.668,1.839-.127s.678-1.344,1.272-2.475c1.1-1.867,2.037-2.151,2.037-2.151a8.51,8.51,0,0,1,1.612-.48c-.471,1.245,1.243,1.924,2.015,1.2a10.863,10.863,0,0,0,1.577.042c-2.517,3.225-.566,4.13,0,4.272,2.12.523,6.25-3.055,6.817-3.45s.453-.142.453-.142l-1.64,3.253s.254,0,2.093-.141a14.89,14.89,0,0,1,1.811-3.279,28.9,28.9,0,0,1,3.507-1.3c3.62-.99-3.678,5.375.311,5.006a22.836,22.836,0,0,0,6.334-2.278c-.106.678.217,1.416,1.247,2.193,2.207,1.5,6.374.079,7.746-3.317.031-.079.064-.168.094-.264,1.387.355,4.055-.634,4.055-.634l3.747-1.527s.065.994.057,1.161a5.464,5.464,0,0,1-.933,3.21,2.639,2.639,0,0,1-2.519.961c-.735-.071-1.073-.8-.876-.806.564-.028.8-.61.763-1.117a.988.988,0,0,0-1.1-.819,1.217,1.217,0,0,0-1.089,1.8c.764,1.612,4.653,3.224,7.694-1.217a2.39,2.39,0,0,0,.282-2.615,2.263,2.263,0,0,1-.126-1.288,2.71,2.71,0,0,1,.622-.764s.862-.693.976-.835.014-.3-.255-.354" transform="translate(-1607.88 -860.707)" fill="#231f20"></path>
                                    </g>
                                    <path id="Path_548" data-name="Path 548" d="M1291.211,464.9l2.631-3.727,2.74,1.451v2.54l-5.371,2.276V464.9Zm7.211,1.475V461.53l-5.27-2.825v-2.136h3.525v1.446h1.839V454.73h-7.2V459.8l.9.489-1.922,2.723-2.285-3.236h-2.251l3.616,5.124v2.549l-5.4-2.261v-8.611h3.044v1.706h1.838v-3.544h-6.722v11.673l8.208,3.394,8.084-3.431Z" transform="translate(-1126.944 -379.634)" fill="#231f20"></path>
                                    <path id="Path_549" data-name="Path 549" d="M1168.858,80.672c-.241.093-.482.205-.736.324-1.355.629-2.751,1.278-4.1-.9a3.777,3.777,0,0,0,2.667.669,4.251,4.251,0,0,0,1.363-.341c.75-.283,1.352-.508,2.042.184a1.95,1.95,0,0,0-1.234.068m-4.992-7.044c1.763.568,2.12,1.057,2.148,1.768.031.749-.534,1.083-1.187,1.469a4.058,4.058,0,0,0-.961.7Zm-.276-1.158a.525.525,0,0,0-.159-.048l-1.809-.006c3.665-4.164,3.551-5.792,1.857-9.239l3.607,2.223a3.618,3.618,0,0,1,.565,2.751c-.324,1.571-1.69,3.024-4.061,4.32m-.76,12.692c0,2.455-1.073,3.488-1.539,3.821a13.17,13.17,0,0,1-2.856,1.067c-2.375.729-4.609,1.533-5.414,2.517-.53-.65-1.935-1.44-5.45-2.519a13.042,13.042,0,0,1-2.854-1.068c-.462-.33-1.536-1.362-1.536-3.818V73.419h19.649V85.161Zm-20.262-12.737a.492.492,0,0,0-.13.038c-2.358-1.291-3.722-2.737-4.049-4.3a3.642,3.642,0,0,1,.563-2.761l3.606-2.223c-1.692,3.445-1.8,5.076,1.86,9.239h-1.754l-.1.008Zm-.422,5.114a4.2,4.2,0,0,0-.935-.673c-.654-.387-1.218-.721-1.188-1.469.029-.707.383-1.2,2.123-1.759Zm-4.23,3.456c-.255-.118-.5-.23-.736-.323a1.961,1.961,0,0,0-.718-.138,2.025,2.025,0,0,0-.517.07c.691-.692,1.292-.467,2.042-.184a4.256,4.256,0,0,0,1.363.341,3.768,3.768,0,0,0,2.667-.669c-1.35,2.182-2.747,1.533-4.1.9m.833-3.028c-.816-.4-1.74-.849-1.869-1.54a1.91,1.91,0,0,1,.321-1.493c.674-.964,2.275-1.69,4.634-2.1.1.056.2.112.3.167-1.978.617-2.677,1.241-2.723,2.374-.045,1.116.8,1.619,1.488,2.022.469.278.912.539,1.017.932a1.3,1.3,0,0,1-.171,1.167,2.113,2.113,0,0,1-1.465.627,1.075,1.075,0,0,0,.163-.721c-.1-.656-.874-1.034-1.692-1.433m4.719-15.235c3.3,1.229,4.853.912,6.832-.211-.1.246-.251.576-.447.975a.907.907,0,0,0,.068,1.07c-2.111,1.958-3.857,2.964-7.456.316a23.26,23.26,0,0,1,1-2.15m7.1,3.088a3.165,3.165,0,0,0,.445,1.7,5.085,5.085,0,0,1-3.472,1.092c-1.514.035-1.564,1.447-1.527,3.8h-.777c-2.949-3.262-3.614-4.813-2.982-6.93,3.851,2.76,5.9,1.544,8.071-.463a1.236,1.236,0,0,1,.242.8m-.166-2.053c.309-.627.5-1.087.6-1.336h4.039c.1.248.288.709.6,1.336.141.285.085.344-.152.591a1.9,1.9,0,0,0-.626,1.462,2.837,2.837,0,0,1-1.538,2.524v-2.5h-.613v2.5a2.824,2.824,0,0,1-1.52-2.526,1.9,1.9,0,0,0-.628-1.462c-.236-.247-.292-.306-.152-.591m.039-5.966a10.139,10.139,0,0,1,.139-4.852,21.182,21.182,0,0,1,.871-2.354,14.14,14.14,0,0,1,1.266,7.07V57.8h-2.276Zm2.578-10.085a16.967,16.967,0,0,1,1.241,2.17,14.179,14.179,0,0,0-1.241,3.4,14.064,14.064,0,0,0-1.236-3.415,17.226,17.226,0,0,1,1.236-2.159m1.924,14.1h-3.853V60.353h3.853Zm-3.953-2.079a11.641,11.641,0,0,1-.412-1.326h4.875a11.855,11.855,0,0,1-.411,1.326h-4.052Zm4.462-6.792a10.122,10.122,0,0,1,.139,4.852h-2.258v-.136a14.158,14.158,0,0,1,1.26-7.056,21.7,21.7,0,0,1,.86,2.34m3.014,16.279h.01c.755.017.962.4.931,3.189h-5.516c1.615-1.428,3.846-3.19,4.574-3.19m-8-.618a6.4,6.4,0,0,0,.912-.6,7.106,7.106,0,0,0,1.474,1.165l.166.106.165-.106A7.053,7.053,0,0,0,1154.645,68a6.508,6.508,0,0,0,.915.6l-2.542,1.565-2.549-1.561Zm1.674,3.808h-5.516c-.032-2.517.1-3.17.931-3.189h.01c.729,0,2.96,1.761,4.574,3.19m-3.289-3.308a6.271,6.271,0,0,0,.918-.211l3.249,1.993,3.242-1.994a6.19,6.19,0,0,0,.918.212,30.065,30.065,0,0,0-4.16,3.27,30.276,30.276,0,0,0-4.167-3.27m14.926-3.621c.632,2.118-.031,3.667-2.981,6.929h-.777c.036-2.356-.015-3.767-1.529-3.8a5.087,5.087,0,0,1-3.478-1.1,3.13,3.13,0,0,0,.451-1.7,1.225,1.225,0,0,1,.244-.794c2.169,2.006,4.22,3.22,8.07.462m-1.212-2.756a23.277,23.277,0,0,1,1,2.15c-3.6,2.648-5.345,1.642-7.456-.316a.91.91,0,0,0,.068-1.07c-.2-.4-.344-.729-.448-.975,1.979,1.124,3.536,1.44,6.832.211m6.267,12.2a1.913,1.913,0,0,1,.32,1.493c-.127.691-1.053,1.142-1.869,1.54s-1.591.776-1.692,1.433a1.084,1.084,0,0,0,.163.721,2.116,2.116,0,0,1-1.465-.627,1.3,1.3,0,0,1-.171-1.167c.106-.394.548-.656,1.017-.932.684-.4,1.534-.906,1.488-2.022-.046-1.132-.745-1.756-2.723-2.374.1-.055.2-.111.3-.167,2.359.413,3.961,1.139,4.636,2.1m-1,4.911a3.786,3.786,0,0,1-1.167.3l-.107,0c-.2-.2-.39-.457-.358-.659.052-.34.715-.663,1.355-.975.946-.461,2.019-.984,2.2-1.98a2.506,2.506,0,0,0-.422-1.956c-.709-1.015-2.176-1.763-4.362-2.226a6.793,6.793,0,0,0,3.275-4.081,4.2,4.2,0,0,0-.716-3.3l-.033-.042-4.755-2.929-.141.055c-3.7,1.428-5.07.849-7.047-.358v-1.6a11.575,11.575,0,0,0,.536-1.7h1.458V57.8h-1.327a10.633,10.633,0,0,0-.185-5.031,18.813,18.813,0,0,0-2.78-5.769l-.24-.308-.241.306A18.76,18.76,0,0,0,1150,52.767a10.647,10.647,0,0,0-.185,5.031h-1.326v.614h1.458a11.492,11.492,0,0,0,.536,1.7v1.6c-1.979,1.206-3.352,1.786-7.047.358l-.141-.055-4.754,2.929-.033.042a4.193,4.193,0,0,0-.717,3.3,6.792,6.792,0,0,0,3.276,4.081c-2.187.464-3.653,1.211-4.363,2.226a2.506,2.506,0,0,0-.421,1.956c.183,1,1.256,1.519,2.2,1.98.64.313,1.3.636,1.355.975.031.2-.161.457-.359.659l-.106,0a3.8,3.8,0,0,1-1.167-.3c-1.081-.406-2.306-.866-3.664,1.751l.519.323a1.763,1.763,0,0,1,1.9-.677c.221.086.454.194.7.308a5.483,5.483,0,0,0,2.239.667,2.8,2.8,0,0,0,2.249-1.241V85.3c0,2.829,1.3,4.083,1.861,4.492a13.545,13.545,0,0,0,3.148,1.2,25.242,25.242,0,0,1,3.779,1.454c.155.076.308.157.457.244a5.418,5.418,0,0,1,.9.614,7.584,7.584,0,0,1,.731.654,7.131,7.131,0,0,1,.724-.667,5.623,5.623,0,0,1,.933-.63l.071-.039c.087-.048.18-.1.275-.145l.007,0a24.683,24.683,0,0,1,3.825-1.481,13.567,13.567,0,0,0,3.149-1.2c.559-.408,1.86-1.66,1.86-4.49V80.947c1.543,1.986,3.231,1.2,4.514.6.246-.114.478-.222.7-.308a1.743,1.743,0,0,1,1.9.677l.519-.323c-1.358-2.617-2.583-2.157-3.664-1.751" transform="translate(-989.627 0)" fill="#231f20"></path>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div12">
                <li>

                    <a class="bvlg" href="https://www.ethoswatches.com/brands/bvlgari.html" title="BVLGARI Watches" onclick="brandslogo('11','BVLGARI')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 90px;
  ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_18" data-name="Rectangle 18" width="269" height="158" transform="translate(0.459 -0.478)"></rect>
                                </clipPath>
                                <clipPath id="clip-Bulgari">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Bulgari" clip-path="url(#clip-Bulgari)">

                                <g id="Bvlgari" transform="translate(-33.459 -23.522)" clip-path="url(#clip-path)">
                                    <path id="bvlgari-2" data-name="bvlgari" d="M13878.6,1335.526v.522h-6.092v-.522c1.742,0,1.566-.522,1.566-2.61v-13.221c-.172-.7-.52-.7-1.566-.87v-.524h6.266v.522c-1.566,0-1.566.522-1.566,2.611v11.485C13877.03,1335,13877.03,1335.526,13878.6,1335.526Zm-18.445.7c-6.611,0-6.09-1.044-7.133-2.611-1.219-1.914-2.262-4.7-4.525-4.7h-5.395v5.22c0,1.218.7,1.394,1.74,1.394v.7h-6.264v-.7c.871,0,1.568,0,1.568-.87v-14.1c0-1.218-.523-1.566-1.568-1.566v-.522h8a31.608,31.608,0,0,1,5.74.174c2.783.7,4.521,2.088,4.521,4.35s-1.215,4.351-5.566,5.221c2.957.348,3.309,1.566,4.352,3.48s1.738,3.828,4.521,3.828Zm-17.053-8.875a37.713,37.713,0,0,0,7.658-.348,3.371,3.371,0,0,0,2.607-3.48,2.824,2.824,0,0,0-1.912-2.784c-1.914-1.045-5.742-.7-8.354-.7Zm-21.924,4.35h-10.443l-1.043,1.74a3.5,3.5,0,0,0-.7,1.394c0,.871.521,1.045,1.395.871v.522h-5.4v-.523a1.913,1.913,0,0,0,1.738-1.044l1.219-2.262,7.311-13.921h2.438l7.656,14.095c1.391,2.61,1.391,2.958,2.785,2.958v.7h-6.613v-.7c1.566,0,1.74-.522.871-1.914l-1.219-2.088v.174Zm-.873-1.914-4.348-8.353-4.178,8.353Zm-22.969-.87c-1.74,0-1.912.174-1.912,1.914v2.61c-.7,1.74-3.309,2.784-5.223,3.306a21.911,21.911,0,0,1-4.7.7,14.59,14.59,0,0,1-9.572-2.959,7.573,7.573,0,0,1-3.127-6.09,8.482,8.482,0,0,1,3.65-6.96,14,14,0,0,1,9.049-2.784,18.557,18.557,0,0,1,4.35.521,15.979,15.979,0,0,1,4.006,1.567l.867,4-.521.174a9.22,9.22,0,0,0-3.654-3.655,9.638,9.638,0,0,0-4.871-1.218,11.117,11.117,0,0,0-5.744,1.74,7.351,7.351,0,0,0-2.781,9.919,5.47,5.47,0,0,0,2.438,2.437,10.778,10.778,0,0,0,6.092,1.74,14.8,14.8,0,0,0,4-.7,4.312,4.312,0,0,0,2.783-2.088v-1.566c0-.522.174-1.914-.348-2.262a2.447,2.447,0,0,0-1.564-.174v-.7h6.785v.522Zm-42.98-9.745v.522c-.869,0-.869,0-1.219.174s-.35.522-.35,1.218v13.747h8.352a4.431,4.431,0,0,0,4.184-1.913l.52.173-1.916,3.654H13748.1v-.522c1.043,0,1.568-.174,1.568-1.394V1321.95a7.423,7.423,0,0,0-.174-1.915c-.176-.347-.523-.522-1.393-.522v-.522Zm-32.715.174v.7c-1.043,0-1.74.174-1.219,1.394.348.7.521,1.218.873,1.914l5.916,11.137c.7-1.74,6.609-12.7,6.787-13.573s-1.395-.7-1.395-.7v-.7h5.221v.7c-.35,0-1.043-.174-1.219.174s-1.391,2.436-2.607,5.047c-1.914,3.828-4.523,8.875-6.09,12.005h-2.437l-7.482-14.269a10.546,10.546,0,0,0-1.566-2.61,1.532,1.532,0,0,0-1.219-.174l0-.692Zm-19.838,8.353a14.4,14.4,0,0,1,3.652,1.044,3.278,3.278,0,0,1,1.914,3.655,4,4,0,0,1-1.912,3.48c-1.742,1.394-6.092,1.394-8.7,1.394h-9.562v-.7c.871,0,1.043,0,1.393-.522a9.985,9.985,0,0,0,.172-2.262v-11.833c0-1.044,0-1.394-.348-1.74a1.477,1.477,0,0,0-1.219-.348l0-.522h12.355c2.438.174,5.74,1.044,5.74,4.177a3.728,3.728,0,0,1-1.389,2.958,4.168,4.168,0,0,1-2.09,1.218Zm-10.092-.522h4.176a9.707,9.707,0,0,0,5.4-1.044,2.8,2.8,0,0,0,1.041-2.262,2.111,2.111,0,0,0-1.393-2.262,14.248,14.248,0,0,0-5.4-.522h-3.826Zm0,8.179h5.045c3.654,0,7.311-.174,7.311-3.133a2.787,2.787,0,0,0-1.393-2.436c-1.393-1.044-3.654-1.044-6.787-1.044h-4.178Z" transform="translate(-13648.077 -1249.057)" fill-rule="evenodd"></path>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div13">

                <li>

                    <a class="carl" href="https://www.ethoswatches.com/brands/carl-f-bucherer.html" title="Carl F. Bucherer Watches" onclick="brandslogo('12','Carl F. Bucherer')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 97px;
  ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_20" data-name="Rectangle 20" width="338" height="196" transform="translate(-0.685 0.185)"></rect>
                                </clipPath>
                                <clipPath id="clip-Carl_F.Bucherer">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Carl_F.Bucherer" data-name="Carl F.Bucherer" clip-path="url(#clip-Carl_F.Bucherer)">

                                <g id="Carl_F._Bucherer" data-name="Carl F. Bucherer" transform="translate(-66.315 -43.185)" clip-path="url(#clip-path)">
                                    <rect id="Rectangle_33" width="338" height="248" transform="translate(-0.685 -25.815)" fill="none"></rect>
                                    <path id="CFB" d="M13551.118,1239.5h-4.97v-7.276h1.066v6.212h3.9Zm5.68.177a2.927,2.927,0,0,1-3.018-2.662v-4.792h1.065v4.082c0,1.42.711,2.307,1.95,2.307s1.951-.71,1.951-2.132v-4.26h1.067v4.082a2.837,2.837,0,0,1-2.488,3.194h0a.635.635,0,0,1-.529.177Zm9.938,0a3.727,3.727,0,1,1,0-7.454h0a3.53,3.53,0,0,1,2.837,1.243h0l-.71.71h0a2.932,2.932,0,0,0-2.133-.888,2.581,2.581,0,0,0-2.663,2.484v.177a2.733,2.733,0,0,0,2.661,2.662,2.941,2.941,0,0,0,2.134-.887h0l.709.71h0a4.179,4.179,0,0,1-2.837,1.241Zm11.536-.177h-5.325v-7.276h5.325v1.066h-4.265v2.132h3.729v1.066h-3.729v2.132h4.265Zm19.521,0h-.887l-4.266-5.324v5.5h-1.062V1232.4h.889l4.259,5.324V1232.4h1.066Zm9.406,0h-5.324v-7.276h5.324v1.066h-4.265v2.132h3.727v1.066h-3.727v2.132h4.265Zm10.116,0h-1.067v-6.212h-1.419v-1.066h2.485Zm6.387.177c-1.6,0-2.838-.888-2.838-2.132a2.128,2.128,0,0,1,1.241-1.774,2.033,2.033,0,0,1-1.061-1.6,2.463,2.463,0,0,1,3.014-1.952,2.254,2.254,0,0,1,1.951,1.952,1.54,1.54,0,0,1-1.065,1.6,2.131,2.131,0,0,1,1.242,1.775c.177,1.242-.887,2.132-2.484,2.132Zm0-3.372c-.889,0-1.775.533-1.775,1.242s.712,1.242,1.775,1.242,1.773-.532,1.773-1.242-.884-1.242-1.771-1.242Zm0-3.195c-.889,0-1.418.533-1.418,1.066a1.364,1.364,0,0,0,1.418,1.242,1.348,1.348,0,0,0,1.42-1.066v-.177c0-.71-.529-1.066-1.417-1.066Zm8.518,6.564c-1.6,0-2.836-.888-2.836-2.132a2.124,2.124,0,0,1,1.241-1.775,2.036,2.036,0,0,1-1.066-1.6,2.467,2.467,0,0,1,3.017-1.952,2.261,2.261,0,0,1,1.953,1.952,1.548,1.548,0,0,1-1.066,1.6,1.759,1.759,0,0,1,1.243,1.774c.178,1.242-.887,2.132-2.483,2.132Zm0-3.372c-.887,0-1.774.533-1.774,1.242s.712,1.242,1.774,1.242,1.775-.532,1.775-1.242-.885-1.242-1.773-1.242Zm0-3.195c-.887,0-1.419.532-1.419,1.066a1.371,1.371,0,0,0,1.419,1.242h0a1.345,1.345,0,0,0,1.416-1.066l0-.177c.01-.7-.72-1.064-1.448-1.064Zm8.519,6.564c-1.6,0-2.839-.888-2.839-2.132a2.136,2.136,0,0,1,1.245-1.775,2.045,2.045,0,0,1-1.067-1.6,2.649,2.649,0,0,1,3.192-1.952h0a2.72,2.72,0,0,1,1.95,1.953,1.547,1.547,0,0,1-1.062,1.6,1.761,1.761,0,0,1,1.237,1.775c.183,1.242-1.061,2.132-2.657,2.132Zm0-3.372c-.888,0-1.774.532-1.774,1.242s.71,1.242,1.774,1.242,1.774-.533,1.774-1.242-.9-1.235-1.794-1.235Zm0-3.195c-.888,0-1.42.532-1.42,1.066a1.373,1.373,0,0,0,1.42,1.242,1.346,1.346,0,0,0,1.417-1.066l0-.177c-.037-.7-.71-1.062-1.439-1.062Zm-52.354,6.389h-.532a1.707,1.707,0,0,1-1.6-1.066l-.353-.888c-.355-.888-.709-.888-1.241-.888h-1.6v2.662H13582v-7.276h3.2a2.38,2.38,0,0,1,2.662,1.952v.355a2.068,2.068,0,0,1-1.42,1.953c.177.177.177.354.355.532l.354.532c.354.533.71.888,1.243.888h.178Zm-5.325-3.727h1.953c1.067,0,1.6-.532,1.6-1.242s-.532-1.242-1.6-1.242h-1.953Zm15.8-65.133-.71.177-.178-.355a8,8,0,0,0-3.549-3.727,17.708,17.708,0,0,0-5.146-1.775l-.533-.177.356-.532a16.458,16.458,0,0,1,2.486-2.484l.175-.178h.178a11.162,11.162,0,0,1,6.744,8.341Zm3.194,5.679a13.18,13.18,0,0,0,2.488-4.969,15.3,15.3,0,0,0,0-5.679v-.177h-.179a11.477,11.477,0,0,0-3.549-.533h-.71l.354.533a13.7,13.7,0,0,1,1.774,5.146,8.524,8.524,0,0,1-.71,5.147l-.176.355.531.532Zm-2.661,6.212a11.184,11.184,0,0,0,5.326-1.241,14.494,14.494,0,0,0,4.433-3.55l.177-.178v-.177a16.6,16.6,0,0,0-1.771-3.017l-.356-.533-.178.532a15.1,15.1,0,0,1-3.015,4.437,8.865,8.865,0,0,1-4.442,2.661h-.352l.177,1.066Zm-6.565,1.775a13.708,13.708,0,0,0,4.259,3.55,14.626,14.626,0,0,0,5.5,1.242h.177v-.177a11.073,11.073,0,0,0,1.239-3.372l.181-.532-.532.177a13.637,13.637,0,0,1-5.325.532,9.9,9.9,0,0,1-4.972-1.775v-.178Zm-5.5-3.9a9.721,9.721,0,0,0,0,5.5,13.065,13.065,0,0,0,2.481,4.97l.18.176h.177a10.712,10.712,0,0,0,3.373-1.066l.531-.176-.531-.355a13.7,13.7,0,0,1-3.906-3.9,8.309,8.309,0,0,1-1.593-4.969v-.355l-.711.177Zm-.175-6.744a10.062,10.062,0,0,0-4.264,3.371,10.506,10.506,0,0,0-2.308,5.147v.177l.176.177a17.665,17.665,0,0,0,3.018,1.952l.531.177v-.532a18.413,18.413,0,0,1,.713-5.324,10.624,10.624,0,0,1,2.839-4.437l.177-.178-.355-.71Zm4.969-4.437a11.157,11.157,0,0,0-5.326-1.242,14.63,14.63,0,0,0-5.5,1.243h-.177v.355a12.1,12.1,0,0,0,.355,3.549l.179.533.531-.355a15.434,15.434,0,0,1,4.614-2.839,8.461,8.461,0,0,1,5.146-.532h.356l.354-.71Zm10.65,37.624h5.145v1.066l-1.774.532v7.809a4.686,4.686,0,0,1-3.9,4.969h-1.066c-3.727,0-5.5-1.952-5.5-4.969v-7.808l-1.952-.355v-1.242h6.389v1.066l-1.774.354v7.454c0,2.662,1.419,3.9,3.194,3.9a3.178,3.178,0,0,0,3.194-3.195h0v-7.986l-1.775-.532Zm32.3,1.42,1.949-.355v-1.066h-6.386v1.066l1.949.355v4.792h-5.854v-4.792l1.95-.355v-1.066h-6.387v1.066l1.949.355v11.009l-1.949.355v1.066h6.387v-1.066l-1.95-.355V1214.3h5.854v4.969l-1.949.355v1.066h6.386v-1.066l-1.949-.355Zm-82.524,10.116a1.779,1.779,0,0,1-1.953.888h-3.017v-11.181l2.129-.355v-.888h-6.563v1.066l1.952.354v11.008l-1.952.354v1.066h11.357l.532-4.614-1.065-.177Zm18.635-.355a1.882,1.882,0,1,0,2.661,0A6.734,6.734,0,0,0,13571.176,1218.024Zm21.829-2.307c0,2.484-1.6,4.792-5.683,4.792h-8.342v-1.066l2.839-.355v-14.73l-2.839-.355v-1.066h7.631c3.549,0,5.5,1.775,5.5,4.26a4.165,4.165,0,0,1-3.375,4.082h0a4.362,4.362,0,0,1,4.264,4.437Zm-8.7-5.147h2.307c1.953,0,3.018-1.066,3.018-3.372a2.987,2.987,0,0,0-2.837-3.195h-.532a6.014,6.014,0,0,0-1.776.177Zm6.037,5.147c0-2.307-1.421-3.549-3.907-3.549h-2.13v7.1a8.645,8.645,0,0,0,2.13.177,3.67,3.67,0,0,0,3.9-2.84h0Zm-48.275,3.9h0l.177.888h-1.953c-1.949,0-2.661-.888-3.19-2.662l-.713-1.952c-.354-1.066-.886-1.242-2.128-1.242h-.533v4.614l1.95.355v1.066h-6.562v-1.066l1.952-.355v-11.181l-1.952-.355v-.888h6.212c3.194,0,4.968,1.42,4.968,3.549,0,1.775-1.419,3.195-3.727,3.549l1.774.355h0l.71,1.6a18.071,18.071,0,0,0,1.243,2.662,15.807,15.807,0,0,0,1.773,1.066Zm-4.26-9.051a2.526,2.526,0,0,0-2.128-2.662h-1.777v5.324h1.243a2.575,2.575,0,0,0,2.837-2.307h0c-.175,0-.175-.177-.175-.354Zm137.719,7.809a1.772,1.772,0,0,1-1.951.887h-3.2V1214.3h1.243c.887,0,.887.177,1.244,1.066l.176.355.354.888h.888v-6.031h-.888l-.354.888c0,.177,0,.177-.175.355-.357.888-.357,1.066-1.245,1.066h-1.242v-4.792h2.663a2.021,2.021,0,0,1,2.13,1.066l1.065,1.952.888-.177-.354-4.082h-10.826v1.066l1.951.355v11l-1.951.355v1.066h11.36l.528-4.614-1.061-.177Zm-10.47,1.242h0l.174.888h-1.95c-1.951,0-2.661-.888-3.194-2.662l-.712-1.952c-.354-1.066-.888-1.242-2.132-1.242h-.533v4.614l1.953.355v1.066h-6.564v-1.066l1.952-.355v-11.181l-1.952-.355v-.888h6.212c3.194,0,4.969,1.42,4.969,3.549,0,1.775-1.42,3.195-3.726,3.549l1.773.355h0l.71,1.6a19.222,19.222,0,0,0,1.421,2.662Zm-4.437-9.051a2.384,2.384,0,0,0-2.133-2.662h-1.777v5.325h1.066a2.587,2.587,0,0,0,2.844-2.307h0v-.354Zm31.768,9.051v.888h-1.952c-1.952,0-2.662-.888-3.194-2.662l-.71-1.952c-.355-1.066-.888-1.242-2.129-1.242h-.533v4.614l1.952.355v1.066h-6.564v-1.066l1.949-.355v-11.181l-1.949-.355v-.888h6.21c3.192,0,4.968,1.42,4.968,3.549,0,1.775-1.418,3.195-3.725,3.549l1.774.355h0l.71,1.6a18.077,18.077,0,0,0,1.243,2.662,3.2,3.2,0,0,0,1.6,1.066Zm-4.614-9.051a2.517,2.517,0,0,0-2.127-2.663h-1.777v5.324h1.066a2.574,2.574,0,0,0,2.836-2.307h0c.178-.177.178-.177,0-.355Zm-39.4,7.809a1.772,1.772,0,0,1-1.952.887h-3.2V1214.3h1.066c.887,0,.887.177,1.242,1.066,0,.177,0,.177.178.355l.355.888h.886v-6.031h-.709l-.355.888c0,.177,0,.177-.177.355-.354.888-.354,1.066-1.243,1.066h-1.243v-4.792h2.663a2.03,2.03,0,0,1,2.134,1.066l1.061,1.952.889-.177-.179-4.082h-11v1.066l1.949.355v11l-1.949.355v1.066h11.357l.532-4.614-1.066-.177Zm-121.745.888,1.951.355v1.066h-6.565v-1.066l1.953-.355-.888-2.662h-4.97l-.888,2.484,1.776.532v1.066h-5.325v-1.066l1.775-.532,4.082-12.246h2.836Zm-4.083-4.082-1.949-6.034-2.13,6.034Zm45.968-11c1.42,0,2.132.177,2.663,1.066s1.419,2.662,1.419,2.662l1.063-.177-.533-4.969h-13.485v1.066l2.838.355v14.73l-2.838.355v1.066h8.161v-1.066l-2.837-.355v-6.744h1.419c.889,0,1.063.177,1.418,1.242,0,.177,0,.177.177.355l.354,1.066h.889v-6.744h-1.062l-.358,1.066c0,.177-.175.177-.175.355-.354,1.066-.533,1.242-1.42,1.242h-1.421v-6.564h3.728Zm-56.437,11.358a6.078,6.078,0,0,1-5.682,4.082c-3.015,0-6.387-2.307-6.387-8.164,0-4.614,2.663-7.986,6.391-7.986a5.488,5.488,0,0,1,5.322,4.082v.178l1.242-.177-.354-4.082h0a15.755,15.755,0,0,0-6.389-1.243c-5.68,0-8.874,3.727-8.874,9.406a8.5,8.5,0,0,0,7.809,9.051h1.243a13.805,13.805,0,0,0,6.563-1.6h0l.533-3.727Zm107.905,1.242a4.426,4.426,0,0,1-4.266,3.017c-2.131,0-4.612-1.6-4.612-6.211,0-2.84,1.244-6.034,4.612-6.034a4.161,4.161,0,0,1,4.083,3.195h0l1.065-.177-.354-3.195h0a11.9,11.9,0,0,0-4.968-1.066c-4.438,0-6.923,3.017-6.923,7.454a6.581,6.581,0,0,0,6.034,7.1h1.066a9.844,9.844,0,0,0,4.966-1.242h0l.355-3.017-1.065-.177v.355Z" transform="translate(-13426.617 -1102.683)" fill="#a07f5f"></path>
                                </g>
                            </g>
                        </svg> </a>
                </li>

            </div>
            <div class="brand-grid div14">

                <li>

                    <a class="parm" href="https://www.ethoswatches.com/brands/parmigiani.html" title="Parmigiani Watches" onclick="brandslogo('55','Parmigiani')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="width: 90px;">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_45" data-name="Rectangle 45" width="322" height="188" transform="translate(-0.249 -0.229)"></rect>
                                </clipPath>
                                <clipPath id="clip-Parmigiani">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Parmigiani" clip-path="url(#clip-Parmigiani)">

                                <g id="Parmigiani-2" data-name="Parmigiani" transform="translate(-58.751 -38.771)" clip-path="url(#clip-path)">
                                    <g id="Group_109" data-name="Group 109" transform="translate(62.693 75.012)">
                                        <g id="parmigiani-3" data-name="parmigiani" transform="translate(0)">
                                            <path id="Path_520" d="M12959.4,2551.913v-.3l-2.712-1.055-.9-18.7,2.713-1.055v-.3h-6.483l-6.183,13.57h0l-6.182-13.57h-6.635v.3l2.715,1.056-.9,18.7-2.111,1.056v.3h7.389v-.3l-3.017-1.056.452-14.628h.15l6.786,14.325h.6l6.786-14.777h.151l.6,15.079-2.712,1.055v.3Zm-69.512-14.628c0-4.373-3.469-6.786-8.293-6.786H12871.2v.3l2.715,1.055v18.7l-2.715,1.056v.3h10.255v-.3l-3.322-1.056v-6.33h3.621c4.522,0,8.145-2.714,8.145-6.937m42.22,14.628v-.3l-1.809-1.056-6.032-7.992c3.167-.9,5.58-2.563,5.58-6.031,0-3.77-3.619-6.183-8.293-6.183h-10.1v.3l2.713,1.056v18.7l-2.41,1.055v.3h9.2v-.3l-2.713-1.055v-18.551h2.412a4.5,4.5,0,0,1,4.522,4.373v.6a4.534,4.534,0,0,1-3.917,4.976H12919v.3l7.088,9.65Zm123.044,0v-.3l-2.564-1.056v-18.7l2.715-1.055v-.3h-7.992v.3l3.015,1.055v14.774h0l-12.515-16.134h-6.334v.3l3.167,1.206v18.547l-2.411,1.055v.3h7.688v-.3l-3.014-1.055v-14.777h0l12.515,16.134h5.729Zm-150.637-1.357-2.715,1.056v.3h9.2v-.3l-1.96-1.056-7.391-18.7,2.563-1.055v-.3h-9.65v.3l2.563,1.055-7.539,18.7-2.712,1.056v.3h8.443v-.3l-3.167-1.056,2.112-5.73h8.145Zm120.479,0-2.715,1.056v.3h9.2v-.3l-1.959-1.056-7.539-18.7,2.563-1.055v-.3h-9.651v.3l2.564,1.055-7.539,18.7-1.962,1.056v.3h7.54v-.3l-3.318-1.056,2.112-5.73h7.991Zm-30.459.3v-6.187l2.563-1.055v-.3H12986.7v.3l3.621,1.056v5.881a8.822,8.822,0,0,1-3.466.6c-5.884,0-8.445-4.675-8.445-9.8,0-5.579,2.563-10.1,8.443-10.1a11.356,11.356,0,0,1,4.975,1.055l2.112,4.823h.452v-5.881a23.547,23.547,0,0,0-7.844-1.661c-8.145,0-12.514,4.977-12.514,11.46s4.072,11.46,12.517,11.46a16.234,16.234,0,0,0,7.991-1.661m72.68,1.056v-.3l-2.715-1.056v-18.7l2.715-1.056v-.3h-9.5v.3l2.713,1.055v18.7l-2.712,1.056v.3Zm-95.9,0v-.3l-2.715-1.056v-18.7l2.715-1.056v-.3h-9.651v.3l2.715,1.055v18.7l-2.715,1.056v.3Zm36.338,0v-.3l-2.41-1.056v-18.7l2.713-1.056v-.3h-9.5v.3l2.714,1.055v18.7l-2.714,1.056v.3Zm-122.136-14.777c0,3.317-1.657,5.579-4.976,5.579h-2.263v-10.85h2.263c3.014,0,4.976,1.809,4.976,5.278m12.815-3.016h0l3.469,9.2h-6.937Zm120.33,0h0l3.468,9.2h-6.937Z" transform="translate(-12871.2 -2529.59)" fill="#a58d57"></path>
                                            <path id="Path_521" d="M12916.143,2553.572h1.508l.453.9h.15v-2.411h-.15l-.453.905h-1.506v-2.865h2.111l.6,1.357h.3V2549.5h-5.276v.151l.9.452v6.031l-.9.453v.151h3.469v-.151l-1.207-.453v-2.562Zm10.857,3.317v-2.262h-.3l-.605,1.661h-1.808v-6.031l1.055-.3v-.151h-3.468v.151l.9.3v6.031l-.9.3v.151Zm8.293,0v-2.111h-.3l-.605,1.508h-2.26v-2.865h1.355l.453.9h.15v-2.415h-.3l-.452.905h-1.358v-2.564h1.961l.6,1.357h.3v-1.96h-5.125v.151l.9.3v6.182l-.9.3v.151Zm6.937.151a2.668,2.668,0,0,1-3.016-2.111v-4.823l-.9-.3v-.151h3.164v.151l-.9.3v4.222a1.712,1.712,0,0,0,1.809,1.96,1.809,1.809,0,0,0,1.964-1.507v-.151h0v-4.523l-1.056-.3v-.151H12946v.151l-.9.3v4.373c.149,1.661-.9,2.563-2.865,2.563m18.545-.754v-6.183l.905-.3v-.151h-3.166v.151l.905.3v6.182l-.9.3v.151h3.164v-.151Zm-4.523.6h0l-.753-.452-2.264-2.563a1.963,1.963,0,0,0,1.814-1.961c0-1.206-1.209-2.11-2.869-2.11h-3.317v.151l.905.3v6.183l-.9.3v.151h3.165v-.151l-.9-.3v-6.031h.754a1.485,1.485,0,0,1,1.507,1.356v.3a1.772,1.772,0,0,1-1.357,1.661h-.754v.151l2.715,3.317Zm13.874,0v-2.111h-.3l-.6,1.508h-2.261v-2.865h1.356l.452.905h.15v-2.419h-.15l-.452.905h-1.356v-2.564h1.959l.6,1.357h.3v-1.96h-5.129v.151l.905.3v6.031l-.9.452v.151Zm10.4,0h0l-.754-.452-2.262-2.563a1.962,1.962,0,0,0,1.811-1.961,2.405,2.405,0,0,0-2.716-2.11h-3.317v.151l.9.3v6.183l-.9.3v.151h3.167v-.151l-.9-.3v-6.031h.905a1.485,1.485,0,0,1,1.508,1.356v.3a1.763,1.763,0,0,1-1.356,1.661h-.756v.151l2.715,3.317Z" transform="translate(-12849.506 -2519.487)" fill="#a58d57"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div15">
                <li>

                    <a class="ciga" href="https://www.ethoswatches.com/brands/ciga-design.html" title="CIGA Design Watches" onclick="brandslogo('14','CIGA Design')">

                        <svg width="150" height="109" viewBox="0 0 198 44" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 90px;">
                            <path d="M10.5356 39.5804C10.5356 41.1321 11.241 41.8375 13.216 41.8375C17.0249 41.8375 18.8588 40.2857 18.8588 37.7464C18.8588 35.2071 17.7303 32.8089 13.216 32.8089H10.5356V39.5804ZM10.5356 31.3982H11.8053C15.6142 31.3982 17.4481 30.1286 17.4481 27.5893C17.4481 24.9089 15.7553 23.3571 13.0749 23.3571C11.5231 23.3571 10.5356 23.9214 10.5356 25.4732V31.3982ZM4.46955 25.6143C4.46955 24.0625 3.62313 22.9339 0.942768 22.9339V21.9464H14.4856C20.2696 21.9464 23.9374 23.9214 23.9374 27.3071C23.9374 30.2696 21.2571 31.6804 17.8713 31.9625C21.6803 32.3857 25.3481 33.7964 25.3481 37.3232C25.3481 40.0036 23.091 43.3893 15.7553 43.3893H0.801697V42.4018C3.48205 42.4018 4.32848 41.5554 4.32848 40.0036V25.6143H4.46955Z" fill="#000" />
                            <path d="M27.1821 43.3893V42.4018C29.4392 42.4018 30.1446 41.6964 30.1446 40.4268V29.2821C30.1446 28.0125 29.4392 27.3071 27.1821 27.3071V26.3196H38.1856V27.3071C35.9285 27.3071 35.2231 28.0125 35.2231 29.2821V40.1446C35.2231 41.2732 35.7874 41.9786 37.3392 41.9786C38.7499 41.9786 40.1606 42.1196 41.2892 41.2732C42.6999 40.2857 43.5464 38.4518 43.9696 36.9H45.2392L44.2517 43.3893H27.1821Z" fill="#000" />
                            <path d="M70.7731 26.3196H78.2499L88.1249 36.3357V29.1411C88.1249 27.8714 87.5606 27.1661 85.1624 27.1661V26.1786H92.7803V27.1661C90.5231 27.1661 90.0999 27.7304 90.0999 29.1411V43.2482H88.4071L75.7106 30.1286H75.5696V40.2857C75.5696 41.5554 76.1338 42.2607 78.9553 42.2607V43.2482H70.7731V42.2607C73.0303 42.2607 73.5946 41.5554 73.5946 40.2857V29.1411C73.5946 27.8714 73.0303 27.1661 70.7731 27.1661V26.3196Z" fill="#000" />
                            <path d="M123.393 34.3607H124.521C127.484 34.3607 128.754 33.2321 128.754 30.975C128.754 29 127.625 27.5893 125.368 27.5893C124.239 27.5893 123.393 28.0125 123.393 29.2821V34.3607ZM115.634 43.3893V42.4018C117.891 42.4018 118.596 41.6964 118.596 40.4268V29.2821C118.596 28.0125 117.891 27.3071 115.634 27.3071V26.3196H126.637C130.87 26.3196 133.973 27.8714 133.973 30.8339C134.114 33.9375 131.011 35.7714 126.92 35.6304H123.393V40.2857C123.393 41.5554 124.239 42.2607 126.637 42.2607V43.2482H115.634V43.3893Z" fill="#000" />
                            <path d="M155.98 43.3893V42.4018C158.237 42.4018 158.943 41.6964 158.943 40.4268V29.2821C158.943 28.0125 158.237 27.3071 155.98 27.3071V26.3196H166.984V27.3071C164.727 27.3071 164.021 28.0125 164.021 29.2821V40.4268C164.021 41.6964 164.727 42.4018 166.984 42.4018V43.3893H155.98Z" fill="#000" />
                            <path d="M169.241 21.9464H178.693L191.107 34.6429H191.248V25.6143C191.248 23.9214 190.402 22.9339 187.439 22.9339V21.9464H197.032V22.9339C194.352 22.9339 193.787 23.9214 193.787 25.6143V43.3893H191.671L175.589 26.8839H175.448V40.0036C175.448 41.5554 176.154 42.4018 179.68 42.4018V43.3893H169.241V42.4018C172.062 42.4018 172.768 41.5554 172.768 40.0036V25.6143C172.768 24.0625 172.204 22.9339 169.241 22.9339V21.9464Z" fill="#000" />
                            <path d="M141.45 31.5393L144.13 36.4768H138.911L141.45 31.5393ZM151.466 40.7089L143.707 26.0375H142.014L135.243 39.2982C134.255 41.4143 133.691 42.1196 131.434 42.4018V43.3893H139.898V42.4018H138.487C137.359 42.4018 136.936 41.6964 136.936 41.1321C136.936 40.5678 137.077 40.1446 137.218 39.7214L138.064 38.0286H144.695L146.105 40.7089C146.246 40.9911 146.387 41.1321 146.387 41.5553C146.387 42.1196 145.964 42.5428 144.412 42.5428H143.425V43.5303H154.287V42.5428C152.736 42.2607 152.171 41.8375 151.466 40.7089Z" fill="#000" />
                            <path d="M113.236 38.1696H112.812C111.402 40.2857 108.439 41.9786 105.336 41.9786C101.245 41.9786 99.1285 39.2982 99.1285 34.925C99.1285 30.6928 100.821 27.3071 104.489 27.3071C108.016 27.3071 109.709 29 111.261 32.5268H112.53V26.4607H111.261L110.132 27.4482C108.439 26.7428 106.464 26.0375 104.207 26.0375C97.9999 26.0375 93.6267 30.1286 93.6267 34.7839C93.6267 39.5803 97.0124 43.6714 104.63 43.6714C108.298 43.6714 111.261 42.5428 113.095 40.4268V38.1696H113.236Z" fill="#000" />
                            <path d="M56.1017 31.5393L58.7821 36.4768H53.7035L56.1017 31.5393ZM66.1178 40.7089L58.3589 26.0375H56.666L50.0357 39.1571C49.0482 41.2732 48.4839 41.9786 46.2267 42.2607V43.2482H54.691V42.2607H53.2803C52.1517 42.2607 51.7285 41.5553 51.7285 40.9911C51.7285 40.4268 51.8696 40.0036 52.0107 39.5803L52.8571 37.8875H59.4875L60.8982 40.5678C61.0392 40.85 61.1803 40.9911 61.1803 41.4143C61.1803 41.9786 60.7571 42.4018 59.2053 42.4018H58.2178V43.3893H69.0803V42.4018C67.3875 42.2607 66.8232 41.8375 66.1178 40.7089Z" fill="#000" />
                            <path d="M100.257 11.0839H101.245C103.361 11.0839 104.348 10.2375 104.348 8.96786C104.348 7.69821 103.643 6.2875 101.245 6.2875H100.257V11.0839ZM100.257 5.3H100.962C103.078 5.3 103.502 4.45357 103.502 3.18393C103.502 1.91428 102.514 1.06786 101.103 1.06786H100.257V5.3ZM96.8713 0.221428H101.809C104.912 0.221428 107.028 1.06786 107.028 3.18393C107.028 5.15893 105.195 5.72321 104.63 5.86428C105.477 5.86428 107.875 6.42857 107.875 8.68571C107.875 10.3786 106.605 11.9304 102.514 11.9304H96.8713V0.221428Z" fill="#000" />
                            <path d="M90.9464 0.221428V1.06786H92.4982V10.9429H90.6642L90.3821 11.9304H95.1785V0.221428H90.9464Z" fill="#000" />
                            <path d="M87.2785 13.7643L87.8428 14.6107V18.8429H85.8678V14.8929H84.5982L84.316 13.7643H87.2785Z" fill="#000" />
                            <path d="M88.8303 13.7643L89.1125 14.8929H93.4857L90.3821 18.8429H92.6392L95.8839 14.8929V13.7643H88.8303Z" fill="#000" />
                            <path d="M103.079 16.1625C103.502 16.1625 104.489 16.4446 104.489 17.4321C104.489 18.1375 104.066 18.8429 102.232 18.8429H96.5892L96.8714 17.7143H101.668C102.091 17.7143 102.091 17.2911 102.091 17.15C102.091 17.0089 101.95 16.5857 101.668 16.5857H99.6928V15.8804H101.668C102.091 15.8804 102.091 15.5982 102.091 15.3161C102.091 15.0339 101.95 14.8929 101.668 14.8929H97.2946L97.0125 13.7643H102.232C103.925 13.7643 104.207 14.3286 104.207 15.0339C104.207 16.0214 103.361 16.1625 103.079 16.1625Z" fill="#000" />
                            <path d="M106.182 13.7643V16.7268H110.837C111.12 16.7268 111.261 17.0089 111.261 17.2911C111.261 17.4321 111.12 17.8554 110.837 17.8554H105.759L105.477 18.9839H111.12C112.248 18.9839 113.377 18.5607 113.377 17.4321C113.377 16.3036 112.389 16.0214 111.402 16.0214H108.157V14.8929H112.248L112.53 13.7643H106.182Z" fill="#000" />
                        </svg>

                    </a>
                </li>
            </div>
            <div class="brand-grid div16">
                <li>
                    <a href="https://www.breguet.com/en" class="flex h-full" title="Homepage">
                        <div class="hidden h-auto w-full lg:block [&>svg]:h-full">
                            <svg width="154" height="100" viewBox="0 0 154 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 120px;">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M119.47 10.769L118.61 11.1528L111.702 17.7698C111.126 17.386 110.551 17.2936 109.883 17.386C108.539 17.6774 107.58 19.2055 107.871 20.8402C107.964 21.224 108.163 21.6078 108.347 21.8921L87.0466 43.3848C85.9947 42.2334 84.4595 41.6577 83.0167 41.9491C82.0572 42.141 81.2896 42.7167 80.7139 43.3848L65.9377 31.8708C66.222 31.2027 66.4139 30.3356 66.222 29.4756C65.9377 27.4643 64.2106 26.121 62.4835 26.5048C62.0073 26.5972 61.6235 26.7962 61.2397 27.0805L54.7151 22.4749L53.9475 22.3825L54.2389 23.2425L59.9959 28.9994L59.9896 29.0386C59.8992 29.6013 59.8084 30.166 59.9959 30.8189C60.2873 32.8303 62.0073 34.1736 63.7344 33.7898C64.3101 33.5979 64.7863 33.406 65.1701 33.0222C65.362 32.7379 65.4615 32.7379 65.5539 32.8303C65.5539 32.8303 71.0194 37.6278 72.9384 39.2554C74.287 40.3992 76.2042 41.9256 77.4246 42.8972C77.9408 43.3082 78.3324 43.6199 78.5035 43.7615C79.0792 44.3372 79.8468 44.9129 79.8468 44.9129C79.5554 45.7728 79.463 46.8318 79.6549 47.7913C80.2306 50.5703 82.6258 52.2974 85.0281 51.8212C87.4233 51.345 88.9585 48.7508 88.3828 45.9719C88.2904 45.4957 88.0985 45.0124 87.8071 44.5362L108.816 22.5744C109.392 23.0506 110.16 23.342 110.927 23.1501C112.363 22.8587 113.323 21.3306 112.939 19.7954C112.939 19.504 112.846 19.3192 112.747 19.1273L119.172 11.6432L119.47 10.769ZM63.0592 32.1622C62.0073 32.3541 60.9483 31.4941 60.7564 30.2432C60.5645 28.8999 61.2326 27.7486 62.2916 27.4643C63.3435 27.2724 64.4025 28.1323 64.5944 29.3832C64.8787 30.7265 64.1111 31.8779 63.0592 32.1622ZM85.3124 48.8574C84.2605 49.0493 83.301 48.2817 83.1091 47.1304C82.9172 45.979 83.5853 44.9271 84.5448 44.7352C85.5043 44.4509 86.4638 45.2114 86.7481 46.3628C86.9471 47.5141 86.2719 48.6655 85.3124 48.8574ZM111.119 21.8992C110.259 22.0911 109.392 21.423 109.2 20.364C109.008 19.3121 109.584 18.3526 110.444 18.1607C111.304 17.9688 112.171 18.6369 112.363 19.6959C112.562 20.7478 111.986 21.7073 111.119 21.8992ZM48.122 61.6081C48.3382 61.2973 48.553 60.9799 48.7663 60.6558C53.5857 53.2627 56.3134 50.1844 60.2723 47.9497C61.2557 48.3483 62.1921 49.2082 62.1921 51.0608C62.1921 54.43 58.9448 58.1892 55.7986 60.0522C55.066 59.5976 54.2008 59.4048 53.4713 59.4048C52.3199 59.4048 50.8843 59.9805 50.8843 60.6486C50.8843 61.0324 51.0762 61.6081 52.6113 61.6081C53.2074 61.6081 53.931 61.4401 54.7224 61.1382C54.9541 61.5629 55.0989 62.1753 55.0989 63.058C55.0989 68.0474 47.9987 74.3801 42.6255 74.3801C41.2901 74.3801 38.7931 73.6541 38.1658 71.318C41.4174 69.3162 44.6998 66.3723 47.7227 62.1723C48.3733 62.4154 49.0505 62.9699 49.0505 64.117C49.0505 66.8036 44.9212 69.8669 42.1422 69.7745C46.6554 70.0588 50.2019 66.1284 50.2019 63.8185C50.2019 63.0094 49.449 61.9275 48.122 61.6081ZM54.4341 60.7412C54.5401 60.8509 54.6371 60.9818 54.7224 61.1382C55.222 60.9475 55.7486 60.7036 56.2873 60.4149C56.9416 60.9942 57.4017 61.85 57.4017 63.0509C57.4017 67.3651 50.3014 74.8492 42.2417 74.8492C39.9288 74.8492 37.8636 73.6744 37.1414 71.9186C33.0097 74.2217 28.9776 75.0482 25.646 75.0482C19.4128 75.0482 14.6154 72.3616 14.6154 67.6636C14.6154 62.0061 22.0994 56.633 28.624 56.633C31.6944 56.633 34.381 58.552 34.381 61.331C34.381 63.918 31.9858 67.9479 25.7455 67.9479C23.3503 67.9479 21.5237 67.1803 21.5237 67.1803C21.5237 67.1803 23.5351 67.5641 25.2622 67.5641C30.5359 67.5641 32.9382 64.4013 32.9382 61.5229C32.9382 57.493 29.1002 57.1092 28.3326 57.1092C21.9999 57.1092 16.5343 62.0985 16.5343 67.5641C16.5343 72.454 21.7156 74.6644 25.646 74.6644C28.1133 74.6644 32.1667 74.309 36.8885 70.9188C36.8753 70.7928 36.8685 70.6648 36.8685 70.535C36.8685 67.3139 40.6848 62.7449 45.4116 61.7235C49.095 56.4545 53.6621 51.115 59.757 47.7801C59.1385 47.6189 58.5638 47.6066 58.2617 47.6066C52.6966 47.6066 49.2425 52.1127 45.8878 56.7183C42.6255 61.2243 36.4847 67.1732 29.2921 68.9003C36.6766 66.2137 41.5736 60.7481 45.0278 55.9507C48.3825 51.4446 53.7556 46.7395 59.4202 46.7395C59.9381 46.7395 60.5768 46.8258 61.2173 47.0346C62.9296 46.2219 64.757 45.5674 66.7124 45.1119C64.7669 45.8498 63.134 46.5044 61.6854 47.2101C63.0112 47.7762 64.2177 48.9163 64.2177 50.9613C64.2177 54.3711 59.9717 58.44 56.2873 60.4149C56.1339 60.2791 55.9699 60.1585 55.7986 60.0522C55.3381 60.3249 54.8798 60.557 54.4341 60.7412ZM54.4341 60.7412C53.9221 60.2113 53.203 60.1795 52.6042 60.1795C51.8366 60.1795 51.4528 60.3714 51.4528 60.6557C51.4528 61.0395 52.0285 61.2314 52.3128 61.2314C52.948 61.2314 53.6726 61.0561 54.4341 60.7412ZM45.0706 62.2162C40.9081 63.2109 38.0958 67.5047 38.0214 70.0557C40.2724 68.241 42.6533 65.7187 45.0706 62.2162ZM115.725 67.756C115.632 67.756 115.533 67.8484 115.433 67.9479C112.171 71.2101 109.101 73.6053 107.281 73.6053C106.037 73.6053 105.554 73.2215 105.554 71.6863C105.554 71.3949 105.554 71.1106 105.647 70.8263C109.101 70.734 113.991 68.4312 113.991 65.9365C113.991 65.4603 113.706 64.6927 112.363 64.6927C110.252 64.6927 106.99 66.8036 104.978 69.0069C101.716 72.7453 99.2215 73.7048 98.6458 73.7048C98.0701 73.7048 97.3025 73.321 98.8377 71.2101L103.536 64.8775H101.233L98.9301 67.9479C95.8597 72.0701 93.4645 73.7972 92.2136 73.7972C91.5455 73.7972 90.778 73.321 92.306 71.2101L97.1035 64.8775H94.7083C92.1213 68.3317 90.778 69.7673 84.5377 73.4134L90.8703 64.8775H88.5676L87.6081 66.3132C87.4162 64.9699 86.5562 64.5861 85.2129 64.5861C82.9101 64.5861 80.0316 66.4056 78.2121 68.4241C75.3336 71.4944 72.1709 73.7972 70.4438 73.7972C69.3919 73.7972 68.9086 73.5129 68.9086 72.1696C68.9086 71.6934 69.001 71.2101 69.1005 70.8263C72.7466 70.8263 76.7764 67.6636 76.7764 65.9365C76.7764 65.4603 76.4921 64.7851 75.1488 64.7851C72.7537 64.7851 68.9157 67.4717 67.3805 70.0588C65.6534 69.1988 66.3286 66.7965 69.2995 64.8775H66.5205L60.4792 71.3025C62.2063 68.9074 63.642 66.7965 63.642 66.0289C63.642 65.4532 63.2582 64.7851 62.2987 64.7851C61.1473 64.7851 57.3093 67.2798 55.4899 69.4831C58.8445 66.5122 61.5311 65.3608 61.8225 65.3608C62.3982 65.3608 62.2063 65.9365 61.9149 66.3203L55.8737 74.38H58.2688L65.3691 66.896C65.0777 68.3317 65.7529 69.7745 67.1886 70.4425C66.8048 71.0182 66.6129 71.6863 66.6129 72.262C66.6129 73.6977 67.9562 74.5648 69.3919 74.5648C71.2114 74.5648 73.8055 73.0296 76.3002 70.9187C76.0159 71.4944 75.9164 72.0701 75.9164 72.4539C75.9164 73.3139 76.5845 74.5648 78.1197 74.5648C79.6549 74.5648 81.6663 73.6053 83.3934 72.262L81.382 75.2329C81.2343 75.3068 81.0866 75.3943 80.9336 75.485L80.9329 75.4854L80.9326 75.4856L80.9325 75.4856C80.6883 75.6304 80.4307 75.7831 80.1382 75.901C72.9456 79.9309 67.281 83.4775 67.281 86.3559C67.281 87.3154 67.8567 87.9835 69.1005 87.9835C72.2633 87.9835 77.1602 83.378 82.5334 76.0858L83.8767 74.3587C87.5228 72.3473 89.7261 70.9045 91.7446 68.8931L90.8846 70.0445C88.4894 73.3068 90.8846 74.2663 91.9436 74.2663C93.2869 74.2663 94.9144 73.1149 96.4496 71.6792C95.9734 73.3068 96.9258 74.2663 98.1767 74.2663C99.6124 74.2663 101.823 72.7311 103.642 71.1035C103.45 71.4873 103.351 71.8711 103.351 72.2549C103.351 73.8825 104.403 74.6501 105.746 74.6501C106.99 74.6501 109.008 73.7901 110.927 72.4468C111.979 71.6792 113.131 70.8192 114.282 69.7602L111.119 74.3658H113.23L121.722 62.0985H126.663C126.855 62.0985 126.855 61.999 126.656 62.0061C126.656 62.0061 124.617 61.9487 121.881 61.8697L127.907 53.1645C127.669 53.4065 127.078 54.1765 126.224 55.2899L126.224 55.2901L126.224 55.2902C124.967 56.9282 123.14 59.3091 121.028 61.845C115.873 61.6955 108.902 61.4877 107.949 61.4304C106.322 61.4304 106.03 61.3309 105.554 60.8547L104.694 62.0985H120.816C119.233 63.99 117.497 65.9562 115.725 67.756ZM111.311 65.3608C112.289 65.3608 112.278 65.8579 112.272 66.1564C112.271 66.1791 112.271 66.2007 112.271 66.2208C112.271 68.0403 108.724 70.343 105.746 70.4425C106.521 67.5641 109.684 65.3608 111.311 65.3608ZM73.8979 65.3608C74.9498 65.3608 74.9498 66.2208 74.9498 66.4127C74.9498 68.616 71.1119 70.343 69.1929 70.4425C69.9676 67.4717 72.6542 65.3608 73.8979 65.3608ZM79.463 73.5129C78.7878 73.5129 78.2192 73.4134 78.2192 71.9777C78.2192 69.0069 81.6734 65.2613 84.2605 65.2613C85.9876 65.2613 86.1795 66.4127 86.1795 66.9884C86.1795 70.4425 81.4744 73.5129 79.463 73.5129ZM78.3116 79.6537C75.3407 83.8755 71.5952 87.7135 69.2924 87.7135C68.7167 87.7135 68.2334 87.5216 68.2334 86.8535C68.2334 83.8826 74.0827 79.8527 80.323 76.299C80.4121 76.2561 80.4813 76.2331 80.5484 76.2109C80.6257 76.1852 80.7002 76.1604 80.7992 76.1071L78.3116 79.6537ZM133.955 65.4532C133.805 65.5656 133.604 65.7841 133.347 66.0629C132.29 67.2114 130.29 69.3836 127.047 69.3836C125.704 69.3836 124.744 68.616 124.744 67.2727C124.744 63.918 129.733 61.3238 133.38 61.3238C135.107 61.3238 138.177 61.6152 138.177 65.5456C138.177 70.7269 133.287 74.181 128.39 74.181C126.779 74.181 125.476 73.8713 124.122 73.5492C122.656 73.2007 121.13 72.8377 119.087 72.8377C114.289 72.8377 112.562 75.1405 111.986 76.5762C112.278 75.901 113.998 73.6977 118.411 73.6977C119.934 73.6977 121.338 74.051 122.754 74.407C124.192 74.7688 125.641 75.1334 127.239 75.1334C132.42 75.1334 139.52 70.4355 139.52 65.4461C139.52 62.859 137.985 60.7481 134.147 60.7481C129.449 60.7481 123.692 64.2947 123.692 67.7489C123.692 68.6089 124.46 70.0516 126.855 70.0516C130.501 70.0588 133.38 66.2208 133.955 65.4532ZM87.3051 81.4225C87.4361 81.4587 87.7147 81.5357 87.7147 82.3402V85.7873C87.7147 86.6034 87.428 86.7307 87.2995 86.7468H87.2385C87.2385 86.7468 87.2624 86.7514 87.2995 86.7468H90.4012C91.6521 86.7468 93.2797 85.7873 93.2797 84.1597C93.2797 83.4916 92.8959 81.3807 90.4012 81.3807H87.2385C87.2385 81.4041 87.2648 81.4114 87.3051 81.4225ZM89.2498 81.5726H89.6337C91.645 81.5726 91.9364 83.4916 91.9364 84.0673C91.9364 85.702 90.8774 86.562 89.8255 86.562H89.3493C89.058 86.562 88.9656 86.2777 88.9656 85.9863V81.7645C88.9656 81.665 89.058 81.5726 89.2498 81.5726ZM99.321 83.3922C99.6124 83.1079 100.089 82.916 100.664 82.916C101.432 82.916 102.484 83.5912 102.484 84.835C102.484 86.0788 101.432 86.8464 100.373 86.8464C99.9891 86.8464 99.6053 86.754 99.321 86.4626V88.0901C99.321 88.722 99.4783 88.8397 99.5984 88.8577H97.8525C97.9624 88.8397 98.1696 88.722 98.1696 88.0901V83.9679C98.1696 83.2998 97.7858 83.2998 97.6934 83.2998L99.321 82.916V83.3922ZM97.7858 88.8577C97.7858 88.8577 97.8128 88.8642 97.8525 88.8577H97.7858ZM99.5984 88.8577H99.7048L99.6942 88.858L99.6797 88.8589C99.6572 88.8604 99.629 88.8623 99.5984 88.8577ZM99.321 86.0788C99.4134 86.3702 99.6124 86.5549 99.9962 86.5549C100.664 86.5549 101.148 85.9792 101.148 84.9274C101.148 83.8755 100.572 83.2998 100.096 83.2998C99.8043 83.2998 99.52 83.4917 99.3281 83.6836V86.0788H99.321ZM109.245 86.71C109.139 86.6808 108.916 86.6191 108.916 85.8869V82.8236L107.381 83.2074C107.473 83.2074 107.765 83.2074 107.765 83.8755V85.8869C107.765 86.6368 107.584 86.6816 107.462 86.7119C107.429 86.7201 107.4 86.7272 107.381 86.7468H109.3C109.3 86.725 109.278 86.719 109.245 86.71ZM108.241 81.8642C108.525 81.8642 108.816 81.6723 108.816 81.4804C108.816 81.2885 108.525 81.0966 108.241 81.0966C107.949 81.0966 107.665 81.2885 107.665 81.4804C107.672 81.6723 107.956 81.8642 108.241 81.8642ZM107.096 86.562C106.997 86.562 106.713 86.562 106.713 86.1782V83.0226H105.34C105.302 83.016 105.277 83.0226 105.277 83.0226H105.34C105.449 83.0416 105.661 83.1703 105.661 83.8826V85.9935C105.469 86.4768 104.893 86.5692 104.701 86.5692C104.602 86.5692 104.126 86.4697 104.126 85.6097V83.0226H102.7C102.669 83.018 102.641 83.0199 102.617 83.0214C102.607 83.022 102.598 83.0226 102.59 83.0226H102.7C102.821 83.0406 102.974 83.1583 102.974 83.7902V85.2259L102.974 85.2761L102.974 85.324V85.3249C102.969 85.914 102.961 86.8534 104.317 86.8534C104.801 86.8534 105.369 86.6616 105.661 86.2777V86.7539L107.096 86.562ZM97.6934 84.4512H94.7225V84.6431C94.7225 85.9864 95.4902 86.4626 96.1582 86.4626C96.6344 86.4626 97.1177 86.1783 97.402 85.8869C97.1177 86.5549 96.4425 87.0382 95.5825 87.0382C94.2393 87.0382 93.4717 85.8868 93.4717 85.0269C93.4717 83.5912 95.0069 82.8236 95.7744 82.8236C96.4425 82.8236 97.5939 83.2074 97.6934 84.4512ZM95.7744 83.1079C95.1064 83.1079 94.9145 83.776 94.8149 84.2593H96.7339C96.7339 83.5912 96.2506 83.1079 95.7744 83.1079ZM111.119 87.0382C112.178 87.0382 112.846 86.4625 112.846 85.7945C112.846 85.0429 112.056 84.7292 111.716 84.5941C111.664 84.5734 111.622 84.5568 111.595 84.5436C110.828 84.2593 110.536 84.0674 110.536 83.6836C110.536 83.3922 110.828 83.1079 111.212 83.1079C111.403 83.1079 111.979 83.1079 112.555 83.8755V83.2074C111.979 82.916 111.602 82.916 111.311 82.916C110.252 82.916 109.584 83.3993 109.584 84.0674C109.584 84.8385 110.201 85.0725 110.672 85.251L110.674 85.2516C110.702 85.2622 110.729 85.2727 110.756 85.283C110.781 85.2924 110.805 85.3018 110.828 85.3112C110.882 85.3379 110.935 85.3638 110.987 85.3892C111.489 85.6332 111.88 85.8234 111.88 86.1712C111.88 86.5478 111.496 86.8392 111.02 86.8392C110.444 86.8392 109.968 86.4554 109.776 85.9792V86.6544C110.16 86.9458 110.543 87.0382 111.119 87.0382ZM116.4 86.9459C116.416 86.9302 116.434 86.9172 116.455 86.9031C116.554 86.834 116.691 86.7388 116.691 86.1783V82.1484C116.691 81.5208 116.497 81.4063 116.375 81.335C116.348 81.319 116.324 81.3053 116.308 81.2884L117.843 80.9046V86.1783C117.843 86.7091 118.027 86.8182 118.149 86.8905C118.18 86.9092 118.208 86.9255 118.227 86.9459H116.4ZM127.623 81.0894C126.564 82.6246 124.936 85.5031 124.36 86.8464V86.8393H123.507C123.599 86.7041 123.711 86.503 123.865 86.2261L123.865 86.2259C124.036 85.9206 124.257 85.5229 124.559 85.0198C125.124 84.1375 125.649 83.2154 125.997 82.6038C126.179 82.2842 126.313 82.0494 126.379 81.9494H123.884C123.593 81.9494 123.308 82.0489 123.117 82.2408L123.692 80.8975C123.877 81.0894 124.069 81.0894 124.168 81.0894H127.623ZM119.947 86.8464C120.615 85.5031 122.15 82.6246 123.209 81.0894H119.854C119.662 81.0894 119.47 81.0894 119.278 80.8975L118.802 82.2408C118.994 82.0489 119.186 81.9494 119.47 81.9494H121.965C121.863 82.1036 121.601 82.5785 121.262 83.1905L121.261 83.1927L121.26 83.1934L121.26 83.194L121.259 83.1949C120.959 83.7383 120.598 84.3892 120.238 85.0198C119.78 85.6709 119.502 86.1453 119.31 86.4718L119.31 86.4721L119.31 86.4721L119.31 86.4724C119.219 86.6267 119.148 86.748 119.087 86.8393H119.947V86.8464ZM129.925 84.9345C129.925 86.0788 129.257 86.754 128.49 86.754C128.198 86.754 127.623 86.754 126.962 85.894L127.054 86.6616C127.729 86.9459 128.397 86.9459 128.589 86.9459C129.932 86.9459 131.176 86.0859 131.176 84.7426C131.176 83.8755 130.409 82.8236 129.165 82.8236C128.689 82.8236 128.113 83.0084 127.729 83.2998L128.013 81.9565H130.217L130.792 80.9046C130.501 81.0965 130.217 81.0965 130.117 81.0965H127.814L127.338 83.8755C127.623 83.4917 128.106 83.2074 128.582 83.2074C129.449 83.2074 129.925 84.1669 129.925 84.9345ZM86.08 46.5618C86.1724 47.3294 85.7886 48.097 85.1205 48.1894C84.4524 48.3813 83.8767 47.8056 83.6848 47.038C83.5924 46.2704 83.9762 45.5028 84.6443 45.4104C85.3124 45.318 85.8881 45.7942 86.08 46.5618Z" fill="#112236" />
                            </svg>
                    </a>
                </li>
            </div>
            <div class="brand-grid div17">
                <li>

                    <a class="jaeg" href="https://www.ethoswatches.com/brands/jaeger-lecoultre.html" title="Jaeger-LeCoultre Watches" onclick="brandslogo('37','Jaeger-LeCoultre')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
                          max-width: 110px;
                      ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_33" data-name="Rectangle 33" width="246" height="144" transform="translate(-0.409 -0.488)"></rect>
                                </clipPath>
                                <clipPath id="clip-JLC">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="JLC" clip-path="url(#clip-JLC)">

                                <g id="JLC-2" data-name="JLC" transform="translate(-20.591 -16.512)" clip-path="url(#clip-path)">
                                    <g id="Group_31" data-name="Group 31" transform="translate(25.744 47.811)">
                                        <g id="download_2_" data-name="download (2)" transform="translate(0 0)">
                                            <path id="Path_104" data-name="Path 104" d="M69.343,76.811A7.7,7.7,0,0,0,73.8,75.422a.8.8,0,0,0,.368-.572V73.337a.661.661,0,0,1,.572-.654l.327-.041a.264.264,0,0,0,.245-.245c0-.163.082-.531-.2-.531H70.487c-.286,0-.2.368-.2.531a.264.264,0,0,0,.245.245l.45.082a.621.621,0,0,1,.572.613v1.389a.371.371,0,0,1-.123.286,2.892,2.892,0,0,1-1.961.776c-1.961.041-2.7-1.8-2.779-4.7s.572-4.781,2.534-4.822a3.5,3.5,0,0,1,3.514,2.207.276.276,0,0,0,.245.163H73.1l.736-.123c.082,0,.123-.082.163-.163V68.27l-.736-2.37c-.041-.2-.368-.163-.531-.163a.276.276,0,0,0-.245.163.4.4,0,0,1-.654.245,5.607,5.607,0,1,0-2.493,10.665Zm6.947-.041h8.663a.728.728,0,0,0,.654-.409l.695-1.635a.182.182,0,0,0-.123-.245H86.1c-.286.041-.613-.082-.776.163l-.368.49a1.706,1.706,0,0,1-1.308.654H80.5a.445.445,0,0,1-.45-.368v-4.7a.386.386,0,0,1,.409-.409h1.226a1.064,1.064,0,0,1,.981.531l.286.531a.276.276,0,0,0,.245.163c.163-.041.368-.041.531-.082.082,0,.123-.082.163-.163v-.082l-.9-2.82a.254.254,0,0,0-.245-.163c-.163,0-.572-.082-.613.163l-.163.49a.719.719,0,0,1-.776.49H80.54a.42.42,0,0,1-.45-.409V67.126a.386.386,0,0,1,.409-.409h2.86a1.493,1.493,0,0,1,1.267.736l.245.368c.123.245.49.123.776.163a.187.187,0,0,0,.2-.163v-.082l-.531-1.43a.7.7,0,0,0-.654-.449H76.331c-.286,0-.163.49-.2.695a.264.264,0,0,0,.245.245l.654.082a.632.632,0,0,1,.572.654v7.642a.594.594,0,0,1-.572.613l-.654.082a.264.264,0,0,0-.245.245c0,.2-.123.654.163.654ZM52.875,66.554a.264.264,0,0,0,.245.245l.654.082a.632.632,0,0,1,.572.654v7.642a.661.661,0,0,1-.572.654l-.654.082a.264.264,0,0,0-.245.245c.041.163-.082.572.2.613h8.663a.728.728,0,0,0,.654-.409l.695-1.635a.182.182,0,0,0-.123-.245h-.082c-.286.041-.613-.082-.776.163l-.368.49a1.706,1.706,0,0,1-1.308.654H57.288a.445.445,0,0,1-.45-.368v-4.7a.386.386,0,0,1,.409-.409h1.267a1.064,1.064,0,0,1,.981.531l.286.531a.276.276,0,0,0,.245.163,2.391,2.391,0,0,0,.531-.082.319.319,0,0,0,.163-.163v-.082l-.9-2.82a.254.254,0,0,0-.245-.163c-.163,0-.531-.082-.613.163l-.2.368a.719.719,0,0,1-.776.49h-.654a.42.42,0,0,1-.45-.409V67.126a.386.386,0,0,1,.409-.409h2.86a1.493,1.493,0,0,1,1.267.736c.123.123.286.531.531.531h.49a.187.187,0,0,0,.2-.163v-.082l-.531-1.43a.7.7,0,0,0-.654-.449H53.079c-.327,0-.163.49-.2.695ZM108.2,76.77h7.682a.728.728,0,0,0,.654-.409l.695-1.635a.2.2,0,0,0-.082-.245h-.613a.276.276,0,0,0-.245.163l-.368.49a1.706,1.706,0,0,1-1.308.654h-2.166a.445.445,0,0,1-.45-.368V67.494a.628.628,0,0,1,.613-.613l.654-.082c.368-.041.2-.49.245-.736a.193.193,0,0,0-.2-.2H108.2a.219.219,0,0,0-.2.2v.49a.264.264,0,0,0,.245.245l.654.082a.621.621,0,0,1,.572.613v7.642a.661.661,0,0,1-.572.654l-.654.082c-.368.041-.2.49-.245.736A.187.187,0,0,0,108.2,76.77Zm10.42-.981-.654.082a.264.264,0,0,0-.245.245c-.041.2-.163.613.123.654h8.663a.728.728,0,0,0,.654-.409l.695-1.635a.182.182,0,0,0-.123-.245h-.082c-.286.041-.613-.082-.776.163l-.368.49a1.706,1.706,0,0,1-1.308.654h-3.147a.445.445,0,0,1-.45-.368v-4.7a.386.386,0,0,1,.409-.409h1.267a1.064,1.064,0,0,1,.981.531l.286.531a.276.276,0,0,0,.245.163c.163-.041.368-.041.531-.082.082,0,.123-.082.163-.163v-.082l-.9-2.82a.254.254,0,0,0-.245-.163c-.2,0-.531-.082-.613.163l-.163.49a.719.719,0,0,1-.776.49H122.1a.445.445,0,0,1-.45-.368v-1.88a.386.386,0,0,1,.409-.409h2.86a1.493,1.493,0,0,1,1.267.736c.123.123.286.531.531.531h.531a.187.187,0,0,0,.2-.163v-.082l-.531-1.43a.7.7,0,0,0-.654-.449H117.93a.219.219,0,0,0-.2.2c.041.245-.123.695.245.736l.654.082a.632.632,0,0,1,.572.654v7.642a.594.594,0,0,1-.572.613Zm37.513-9.031.654.082a.661.661,0,0,1,.572.654v4.9s-.286,4.372,4.822,4.372c4.127,0,4.209-3.8,4.209-4.413V67.535c0-.327.123-.572.449-.613l.49-.082a.264.264,0,0,0,.245-.245c0-.2.082-.695-.2-.695H164.27c-.327,0-.163.49-.2.695a.264.264,0,0,0,.245.245l.49.082a.548.548,0,0,1,.45.613v5.19s0,3.024-2.656,3.024c-2.942,0-2.7-3.392-2.7-3.392v-4.9a.59.59,0,0,1,.449-.654l.49-.082a.264.264,0,0,0,.245-.245c-.041-.2.082-.695-.2-.695H156.1c-.286.041-.163.531-.2.736a.264.264,0,0,0,.245.245Zm13.281,8.99-.654.082c-.368.041-.2.49-.245.736a.193.193,0,0,0,.2.2h7.682a.728.728,0,0,0,.654-.409l.695-1.635c.123-.368-.409-.245-.695-.245a.276.276,0,0,0-.245.163l-.368.49a1.706,1.706,0,0,1-1.308.654h-2.166a.445.445,0,0,1-.449-.368V67.494a.594.594,0,0,1,.572-.613l.654-.082c.368-.041.2-.49.245-.736a.193.193,0,0,0-.2-.2h-5.026a.219.219,0,0,0-.2.2v.49a.264.264,0,0,0,.245.245l.654.082a.621.621,0,0,1,.572.613v7.642a.736.736,0,0,1-.613.613Zm6.783-7.6a.127.127,0,0,1,.082-.041c.286,0,.613.082.776-.163l.368-.49a1.862,1.862,0,0,1,1.308-.736H180a.445.445,0,0,1,.449.368v8.009a.661.661,0,0,1-.572.654l-.654.082a.264.264,0,0,0-.245.245c.041.2-.082.695.2.695h5.026c.327-.041.163-.49.2-.695a.264.264,0,0,0-.245-.245l-.654-.082a.632.632,0,0,1-.572-.654V67.126a.386.386,0,0,1,.409-.409h1.308a2.073,2.073,0,0,1,1.308.776l.368.49c.163.245.45.123.776.163a.187.187,0,0,0,.2-.163V67.9l-.695-1.635a.728.728,0,0,0-.654-.409h-8.541a.728.728,0,0,0-.654.409l-.695,1.635a.182.182,0,0,0,.123.245Zm-136.4,7.6-.327.041c-.327.082-.2.49-.2.736a.193.193,0,0,0,.2.2h3.392a.193.193,0,0,0,.2-.2c-.041-.245.123-.695-.2-.736l-.082-.041a.654.654,0,0,1-.531-.736c0-.041,0-.041.041-.082l.286-.695a.589.589,0,0,1,.531-.327h4.127a.589.589,0,0,1,.531.327l.245.695a.656.656,0,0,1-.409.817h-.082l-.082.041c-.327.041-.163.49-.2.736a.193.193,0,0,0,.2.2h4.372c.327,0,.163-.409.2-.654a.229.229,0,0,0-.2-.245l-.2-.082a1.527,1.527,0,0,1-.9-.94l-3.024-8.581a.7.7,0,0,0-.654-.45H44.866a.663.663,0,0,0-.654.45l-3.351,8.581a1.323,1.323,0,0,1-1.062.94Zm5.149-7.642c.163-.531.45-.531.654,0l1.553,4.536s.123.327-.286.327H43.476c-.409,0-.245-.327-.286-.327Zm42.539-1.512a.264.264,0,0,0,.245.245c.45.082,1.226.082,1.226.695v7.682a.594.594,0,0,1-.572.613l-.654.082a.264.264,0,0,0-.245.245c.041.163-.082.572.2.613h5.026c.327,0,.163-.409.2-.654a.264.264,0,0,0-.245-.245l-.654-.082a.621.621,0,0,1-.572-.613V72.071a.386.386,0,0,1,.409-.409h.776a2.187,2.187,0,0,1,1.921,1.389s.572,1.062,1.226,2.288c.776,1.8,2.248,1.43,4.209,1.471.327,0,.163-.409.2-.695a.229.229,0,0,0-.2-.245l-.409-.041a1.8,1.8,0,0,1-1.553-1.1A12.547,12.547,0,0,0,95.741,71.5c-.245-.245.082-.286.082-.286,3.024-.736,2.86-2.738,2.86-2.738,0-2.615-3.31-2.574-3.31-2.574H87.691c-.327,0-.163.49-.2.695Zm4.413.082h2.166a1.948,1.948,0,1,1,0,3.882H91.982a.406.406,0,0,1-.45-.368V67.085h-.041a.386.386,0,0,1,.409-.409Zm62.889,4.577a5.654,5.654,0,0,0-3.106-5.026,5.537,5.537,0,1,0,3.106,5.026Zm-5.68-4.863c1.961-.041,2.7,1.716,2.82,4.7s-.531,4.781-2.534,4.822-2.7-1.757-2.82-4.7C146.535,68.23,147.189,66.432,149.109,66.391Zm39.147.368c.449.082,1.226.082,1.226.695v7.682a.559.559,0,0,1-.531.613l-.654.082c-.368.041-.2.49-.245.736a.193.193,0,0,0,.2.2h5.026c.327,0,.163-.449.2-.654a.264.264,0,0,0-.245-.245l-.613-.082a.7.7,0,0,1-.613-.654V72.03a.386.386,0,0,1,.409-.409h.776a2.287,2.287,0,0,1,1.921,1.389s.572,1.062,1.226,2.288c.776,1.8,2.248,1.43,4.209,1.471a.193.193,0,0,0,.2-.2c-.041-.245.123-.654-.2-.736l-.409-.041c-.163-.041-.9.041-1.553-1.144a12.547,12.547,0,0,0-2.288-3.228c-.245-.245.082-.286.082-.286,3.024-.736,2.86-2.738,2.86-2.738,0-2.615-3.31-2.574-3.31-2.574h-7.723c-.327,0-.2.49-.2.695a.264.264,0,0,0,.245.245Zm3.759,3.433V67.126a.386.386,0,0,1,.409-.409h2.166a1.948,1.948,0,1,1,0,3.882h-2.125c-.2.041-.409-.123-.45-.409Zm-90.84.041a.187.187,0,0,0-.2.163V71.58a.193.193,0,0,0,.2.2H106a.193.193,0,0,0,.2-.2V70.4a.187.187,0,0,0-.2-.163Zm38.085-8.867c-4.9-2.534-10.625,1.635-10.5,7.355-.123,5.844,5.884,10.093,10.829,7.192,0,0,.858-.572.981.409.082.245.45.163.613.163a.276.276,0,0,0,.245-.163l.981-3.065v-.082c0-.082-.082-.123-.163-.163-.082,0-.776-.163-.817-.163a.276.276,0,0,0-.245.163,5.725,5.725,0,0,1-4.618,2.615c-2.983.082-4.086-3.024-4.209-6.865s.776-7.029,3.759-7.11a5.009,5.009,0,0,1,4.658,2.452.276.276,0,0,0,.245.163h.123l.736-.123c.082,0,.123-.082.123-.163V63.9l-.981-3.065c-.082-.245-.45-.163-.613-.163a.276.276,0,0,0-.245.163c-.123,1.022-.9.531-.9.531ZM106.2,57.278A45.86,45.86,0,0,0,119.524,59.4a45.282,45.282,0,0,0,14.221-2.125l1.389,2a51.086,51.086,0,0,0,4.9-1.961L136.4,52.007a40.734,40.734,0,0,1-11.81,3.147V42h4.945l-3.065-4.5h-5.353l-1.185,1.716L118.747,37.5h-5.353L110.329,42h4.945V55.153a40.734,40.734,0,0,1-11.81-3.147l-3.637,5.312a51.087,51.087,0,0,0,4.9,1.961ZM120.464,40.2l1.226-1.8h4.332l1.8,2.615h-4.127v15.16a41.64,41.64,0,0,0,12.382-3.024l2.534,3.759c-1.022.45-2.043.858-3.106,1.226l-1.348-2a45.823,45.823,0,0,1-13.689,2.329ZM101.339,56.951l2.534-3.759a41.947,41.947,0,0,0,12.382,3.024V41.055h-4.127l1.8-2.615h4.332l1.226,1.8V58.5a44.523,44.523,0,0,1-13.689-2.288l-1.349,2c-1.022-.449-2.084-.858-3.106-1.267Z" transform="translate(-23.223 -37.5)"></path>
                                            <path id="Path_105" data-name="Path 105" d="M188.813,105.007a18.19,18.19,0,0,0-4.7,4.863v.041h-1.635a.445.445,0,0,1-.449-.368v-4.7a.386.386,0,0,1,.409-.409H183.7a1.065,1.065,0,0,1,.981.531l.286.531a.276.276,0,0,0,.245.163,2.391,2.391,0,0,0,.531-.082c.082,0,.123-.082.123-.163v-.082l-.9-2.82a.274.274,0,0,0-.286-.163h-.368c-.327,0-.327.409-.409.654a.719.719,0,0,1-.776.49h-.654a.42.42,0,0,1-.449-.409v-1.839a.386.386,0,0,1,.409-.409h2.86a1.493,1.493,0,0,1,1.267.736l.245.368c.163.245.49.123.776.163a.187.187,0,0,0,.2-.163v-.082l-.531-1.43a.7.7,0,0,0-.654-.45h-8.377c-.327,0-.2.49-.2.695a.264.264,0,0,0,.245.245l.654.082a.621.621,0,0,1,.572.613v7.642a.661.661,0,0,1-.572.654l-.654.082a.264.264,0,0,0-.245.245c.041.245-.082.654.2.654h5.149c-2.452,3.269-4.985,5.108-8.786,5.476H17.962c-3.8-.327-5.435-1.512-7.764-4.74l-.041-.041c5.19-1.1,5.639-4.985,5.884-7.764.2-2.329.163-5.108.163-5.108V97.161a.7.7,0,0,1,.572-.695l.654-.082a.273.273,0,0,0,.245-.286V95.4a.193.193,0,0,0-.2-.2h-6.13a.193.193,0,0,0-.2.2V96.1a.351.351,0,0,0,.245.286l.654.082a.672.672,0,0,1,.572.695v6.824c.041,2.125,0,5.6-3.351,6.375v-.041A17.4,17.4,0,0,0,4.8,105.783c-4.536-2.86-6.783,1.961-2.411,4.618a11.22,11.22,0,0,0,6.334,1.389h.041s1.716,2.288,2.37,3.228,2.166,3.719,8.745,3.719H172.467c8.622-.2,9.685-4.658,12.382-7.887,2.779,0,4.577-.163,6.334-1.267,4.413-2.656,2.166-7.478-2.37-4.577ZM4.354,109.951c-1.185-.695-1.308-1.349-1.062-1.757s.817-.654,2.043.041a9.323,9.323,0,0,1,2.534,2.37,6.509,6.509,0,0,1-3.514-.654Zm184.663-.695a8.235,8.235,0,0,1-3.351.572,9.521,9.521,0,0,1,2.493-2.288c1.226-.654,1.839-.449,2.043-.041S190.161,108.766,189.017,109.257Z" transform="translate(-0.038 -71.621)"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div18">
                <li>

                    <a class="jaco" href="https://www.ethoswatches.com/brands/jacob-and-co-watches.html" title="Jacob & Co. Watches" onclick="brandslogo('36','Jacob & Co.')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
                          max-width: 90px;
                      ">
                            <defs>
                                <clipPath id="clip-Jacob">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Jacob" clip-path="url(#clip-Jacob)">

                                <g id="Jacob_Co.-01" data-name="Jacob &amp; Co.-01" transform="translate(-95.606 -140.691)">
                                    <g id="Group_21" data-name="Group 21" transform="translate(151.9 148.1)">
                                        <path id="Path_67" data-name="Path 67" d="M234.258,196.043h0l-.091-.046a12.489,12.489,0,0,0-4.2-.731l-.777.046h-.274c-7.13.548-10.512,6.627-10.6,12.111v.229c0,3.7-.914,6.444-2.742,8.455l-.32.32a9.443,9.443,0,0,1-6.4,2.377l-.548-.046a9.478,9.478,0,0,1-1.92-.366l-.274-.091a.46.46,0,0,1-.137-.046,11.665,11.665,0,0,1-2.834-1.371l-.137-.137-.594-.457-.137-.137a9.928,9.928,0,0,1-3.154-5.439,12.592,12.592,0,0,1-.366-3.7c0-3.976,1.234-7.038,3.611-8.912a9.575,9.575,0,0,1,7.633-1.828,4.875,4.875,0,0,1,3.473,2.559,2.129,2.129,0,0,1,.366.914c0,.137.046.229.046.32a2.232,2.232,0,0,1-.183.868l-.091.366v.183l.046.731a1.482,1.482,0,0,0,1.463,1.143,3.167,3.167,0,0,0,.914-.137,1.573,1.573,0,0,0,1.005-1.005c.32-1.143-.823-2.788-1.325-3.473a10.036,10.036,0,0,0-7.861-3.428,13.053,13.053,0,0,0-9.643,4.342V182.332l.046-22.715c0-4.159,0-8.867,7.313-11.38h-.091c.046,0,.046,0,.091-.046H195.547l-10.558-.046c.046,0,.046,0,.091.046h-.091a12.659,12.659,0,0,1,4.25,2.285l.046.046c.091.091.229.183.32.274,2.7,2.559,2.7,5.8,2.7,8.775v42.367l-1.1-1.1c-3.473-3.519-14.534-14.671-19.47-20.43l-1.508-1.828c-.091-.091-6.856-7.587-1.371-13.026a12.106,12.106,0,0,1,10.923-2.971,12.417,12.417,0,0,1,9,7.952v-.091c0,.046,0,.046.046.046v-3.2l.046-3.382a21.173,21.173,0,0,0-19.79-.777c-.091.046-9.506,4.89-5.759,13.757a21.307,21.307,0,0,0,3.473,5.713l.32.366.091.091a.16.16,0,0,1-.091.046,38.581,38.581,0,0,0-3.885,2.377c-4.2,2.605-11.335,8-11.335,15.4,0,11.106,9.735,18.921,23.675,18.921l2.194-.091.457-.046,1.417-.137a31.205,31.205,0,0,0,8.135-1.965l.32-.137c.046,0,.868-.366.868-.366l1.143-.594.32-.183a24.66,24.66,0,0,0,4.707-3.473c.046.091.137.183.183.274,1.325,1.782,5.439,6.353,11.883,6.718h.868a11.746,11.746,0,0,0,9.415-4.342,16.723,16.723,0,0,0,1.874-3.062c.137.366.274.686.411,1.051a10.525,10.525,0,0,0,5.165,5.347,12.411,12.411,0,0,0,5.21,1.143,13.814,13.814,0,0,0,2.285-.229h.091c6.033-1.051,8.638-6.216,9.049-10.649.411-4.982-1.691-10.923-7.313-13.026m-28.976-47.852h.091a11.558,11.558,0,0,0-5.027,3.062,11.309,11.309,0,0,1,4.936-3.062M188.6,170.4v.091a12.916,12.916,0,0,0-.914-1.965,17.861,17.861,0,0,1,.914,1.874m-24.04,15.631c-2.7,2.148-6.4,5.941-7.267,11.2.868-5.256,4.525-9.049,7.267-11.2m18.556,31.9c-8.181,1.92-19.333-1.234-23.812-9.963a17.934,17.934,0,0,1-2.194-8.272c0-10.146,10.466-15.768,10.6-15.813,0,.046,24.314,25,24.314,25-.091,4.342-3.382,7.77-8.912,9.049m9.049-16.088h0v0Zm-7.267-53.7c-.046,0-.046,0-.091-.046l10.009.046Zm9.918,64.716.091-.091h0a.1.1,0,0,1-.091.091m3.2-13.254h0a13.467,13.467,0,0,1,1.143-1.143l-1.143,1.143m20.841,13.208.229-.457v.046a4.124,4.124,0,0,1-.229.411m10.969,6.307c-4.982,0-7.633-5.53-7.633-10.969,0-8.272,4.159-12.066,8-12.066,2.559,0,7.861,2.879,7.861,11.746,0,5.439-2.879,11.289-8.227,11.289" transform="translate(-151.9 -148.1)"></path>
                                    </g>
                                    <g id="Group_22" data-name="Group 22" transform="translate(152.357 227.396)">
                                        <path id="Path_68" data-name="Path 68" d="M158.522,321.874l-4.2-.046-.046.229c1.188.046,1.508.274,1.508,1.463V331.7c0,1.508-.548,1.6-.686,1.6-.32,0-.457-.183-.548-.5-.137-.594-.411-.914-.914-.914a.775.775,0,0,0-.731.823,1.131,1.131,0,0,0,1.28,1.051c1.965,0,3.016-1.737,3.016-5.027v-5.484h0a1.564,1.564,0,0,1,.046-.5c.137-.457.411-.64,1.28-.686v-.183Zm9,9.872L163.5,321.6l-.046.046h0a1,1,0,0,0-.137.229,5.342,5.342,0,0,1-1.051,1.005h0a4.984,4.984,0,0,0,.274.5l-3.291,8.638c-.366.96-.686,1.28-1.28,1.417l-.046.229,2.925.046.046-.229a1.413,1.413,0,0,0-.366-.091c-.411-.046-.96-.183-.96-.731a7.879,7.879,0,0,1,.457-1.691l.686-1.874h4.113c0,.046.686,1.874.686,1.874a5.158,5.158,0,0,1,.5,1.645c0,.64-.777.777-1.417.777l-.046.229v.046h4.3l.046-.229c-.777-.183-.96-.686-1.371-1.691M163.549,321.6h0s.091.229.229.548l-.229-.548h0m-2.651,7.175Zm2.834,0c-1.143,0-2.742-.046-2.788-.046l.046-.137c.32-.731,1.737-4.57,1.874-4.89.046.137,1.92,4.936,1.965,5.027l-1.1.046Zm1.1,0a.168.168,0,0,0-.046-.137l.046.137Zm9.643,4.8c-2.651,0-4.25-2.834-4.25-5.622,0-2.879,1.371-5.987,4.342-5.987,2.605,0,3.885,2.1,4.159,4.113l.183.046.046-3.839-.183-.046a8.57,8.57,0,0,0-.548.823c-.046,0-.046-.046-.091-.046a5.355,5.355,0,0,0-3.565-1.28,5.875,5.875,0,0,0-5.941,6.17c0,3.7,2.194,6.033,5.759,6.033a6.237,6.237,0,0,0,3.931-1.371h0a10.065,10.065,0,0,0,.411.96h.183l.046-3.7-.183-.046a4.287,4.287,0,0,1-4.3,3.793m3.7-.96c.046-.046.137-.091.183-.137h0c-.046.046-.137.091-.183.137m8.09-10.969c-1.965,0-5.713,1.28-5.713,6.261,0,2.194,1.188,5.941,5.622,5.941,4.707,0,5.941-3.793,5.941-5.987a5.8,5.8,0,0,0-5.85-6.216m-.091,11.929c-2.651,0-4.113-2.788-4.113-5.622,0-4.159,2.148-6.033,4.3-6.033,1.371,0,4.159,1.417,4.159,5.9.046,2.788-1.508,5.759-4.342,5.759m12.34-6.17a2.842,2.842,0,0,0,2.514-2.834,2.4,2.4,0,0,0-1.28-2.194,6.279,6.279,0,0,0-2.834-.5h-.366l-3.428.046-.046.229c1.005.091,1.234.183,1.234.96v9.506c0,.64-.411.777-1.234.823l-.046.229c.046.046,1.874.046,1.874.046l1.874.046c3.2,0,4.936-1.143,4.936-3.291,0-1.691-1.234-2.879-3.2-3.062m-2.788-5.347.137.046a6.336,6.336,0,0,1,2.148.229,2.363,2.363,0,0,1,1.508,2.422c0,2.422-2.011,2.651-2.925,2.651h-.914Zm1.463,11.38c-.914,0-1.463-.183-1.463-1.143v-4.616l.777.046c1.874,0,3.611.229,3.611,2.742a2.663,2.663,0,0,1-2.925,2.971m26.645.137c-2.651,0-4.25-2.834-4.25-5.622,0-2.879,1.371-5.987,4.342-5.987,2.605,0,3.885,2.1,4.159,4.113l.183.046.046-3.839-.183-.046a8.573,8.573,0,0,0-.548.823c-.046,0-.046-.046-.091-.046a5.355,5.355,0,0,0-3.565-1.28,5.875,5.875,0,0,0-5.941,6.17c0,2.971,1.417,5.073,3.885,5.759a3.222,3.222,0,0,1-2.7,1.417c-1.874,0-4.3-2.057-6.536-4.433.548-.731,1.371-1.92,1.371-1.92a2.63,2.63,0,0,1,1.92-1.28l.046-.183-2.879-.046-.046.183a.226.226,0,0,0,.137.046.944.944,0,0,1,.731.229l.091.274a19.938,19.938,0,0,1-1.554,2.559c-1.234-1.325-2.422-2.742-3.428-3.976.183-.091.914-.5.914-.5l.686-.366a2.161,2.161,0,0,0,1.188-1.874,2.33,2.33,0,0,0-2.377-2.057,3.307,3.307,0,0,0-1.874.64,2.568,2.568,0,0,0-.96,2.1,5.2,5.2,0,0,0,.868,2.605,4.238,4.238,0,0,0-2.651,3.793,3.067,3.067,0,0,0,3.062,3.2,4.142,4.142,0,0,0,3.382-1.828l.274-.366c0,.046.046.046.091.091,2.925,3.016,5.256,3.611,6.764,3.611a4.143,4.143,0,0,0,3.656-1.92,6.574,6.574,0,0,0,1.691.229,6.146,6.146,0,0,0,3.931-1.371h0a10.065,10.065,0,0,0,.411.96h.183l.046-3.7-.183-.046c-.5,2.377-2.057,3.839-4.3,3.839m-14.945-7.221h0a.956.956,0,0,0,.274-.183.956.956,0,0,1-.274.183m-1.508-2.742a1.307,1.307,0,0,1,.777-1.371,1.869,1.869,0,0,1,1.143-.091,1.907,1.907,0,0,1,1.554,1.874c0,.823-1.188,1.828-1.965,2.285-.868-1.005-1.508-1.737-1.508-2.7m1.371,9.232c-1.554,0-2.651-1.28-2.651-3.154a2.824,2.824,0,0,1,1.554-2.514,49.279,49.279,0,0,0,3.748,4.433,3.552,3.552,0,0,1-2.651,1.234m13.026,1.143a2.536,2.536,0,0,0,.274-.366h0a.711.711,0,0,1-.274.366m5.759-1.371c.046-.046.137-.091.183-.137h0a.631.631,0,0,0-.183.137m8.044-10.969c-1.965,0-5.713,1.28-5.713,6.261,0,2.194,1.188,5.941,5.622,5.941,4.707,0,5.941-3.793,5.941-5.987a5.8,5.8,0,0,0-5.85-6.216m-.091,11.929c-2.651,0-4.113-2.788-4.113-5.622,0-4.159,2.148-6.033,4.3-6.033,1.371,0,4.159,1.417,4.159,5.9.046,2.788-1.508,5.759-4.342,5.759" transform="translate(-152.9 -321.6)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
            <div class="brand-grid div19">
                <li>

                    <a class="hubl" href="https://www.ethoswatches.com/brands/hublot.html" title="Hublot Watches" onclick="brandslogo('33','Hublot')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
      max-width: 80px;
  ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_31" data-name="Rectangle 31" width="266" height="154" transform="translate(-0.493 0.296)"></rect>
                                </clipPath>
                                <clipPath id="clip-Hublot">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="Hublot" clip-path="url(#clip-Hublot)">

                                <g id="Hublot-2" data-name="Hublot" transform="translate(-30.507 -22.296)" clip-path="url(#clip-path)">
                                    <g id="hublot_80808-01" transform="translate(69.932 29.21)">
                                        <path id="Path_139" data-name="Path 139" d="M735.235,1359.638a11.291,11.291,0,0,1-1.921-.4,6.437,6.437,0,0,1-3.89-3.141,9.939,9.939,0,0,1-1.116-4.017c-.044-.509-.044-1.016-.044-1.522q-.007-6.637,0-13.274c0-.447,0-.448.436-.448h2.217c.444,0,.446,0,.446.439,0,4.395-.013,8.79.01,13.184a9.661,9.661,0,0,0,.462,3.141,4.145,4.145,0,0,0,3.37,2.9,8.46,8.46,0,0,0,1.926.177,6.447,6.447,0,0,0,3.386-.9,4.447,4.447,0,0,0,1.831-2.929,12.259,12.259,0,0,0,.238-2.728c.011-4.284,0-8.569,0-12.853,0-.424,0-.426.428-.426.779,0,1.558.01,2.337-.005.268-.005.338.088.338.346-.006,4.814.039,9.629-.025,14.443a8.642,8.642,0,0,1-1.886,5.456,6.412,6.412,0,0,1-2.389,1.739,10.492,10.492,0,0,1-3.159.821Z" transform="translate(-704.814 -1263.469)"></path>
                                        <path id="Path_140" data-name="Path 140" d="M2154.43,1337.619c-.129.007-.259.02-.387.02h-6.083c-.462,0-.462,0-.462.477v18.336c0,.109,0,.221,0,.329,0,.15-.063.218-.215.217q-1.273,0-2.547,0c-.152,0-.221-.067-.215-.217,0-.11,0-.221,0-.329v-18.306c0-.552.053-.506-.492-.507-2.048,0-4.1-.007-6.143.006-.309,0-.394-.094-.385-.392.02-.689.015-1.378,0-2.067,0-.25.059-.353.332-.353q8.181.01,16.361.006c.079,0,.158.013.238.02Q2154.431,1336.241,2154.43,1337.619Z" transform="translate(-2029.526 -1261.588)"></path>
                                        <path id="Path_141" data-name="Path 141" d="M791.157,129.474q0,7.537-.006,15.075c0,.288.075.3.312.212q3.666-1.432,7.343-2.846a.469.469,0,0,0,.349-.527q-.01-13.576-.005-27.153a2.952,2.952,0,0,0,0-.3c-.025-.242.067-.34.32-.332.489.015.979,0,1.469,0h9.442c.2,0,.421-.027.391.288a.1.1,0,0,0,.08.112c.353.011.27.275.27.476q0,10.685,0,21.369c0,.47,0,.477.4.257a39.875,39.875,0,0,0,5.84-3.916,28.353,28.353,0,0,0,2.22-1.994,22.7,22.7,0,0,0,4.688-6.758,21.552,21.552,0,0,0,1.626-5.565,23.3,23.3,0,0,0,.3-3.262c0-.265.064-.533.055-.8-.005-.161.088-.2.228-.2q2.143,0,4.284,0c.186,0,.347.123.538.148.116.015.086.167.08.26-.037.615-.057,1.233-.127,1.844-.11.946-.231,1.89-.407,2.827a31.807,31.807,0,0,1-1.988,6.548,27.4,27.4,0,0,1-2.849,5.2,29,29,0,0,1-1.876,2.354c-.667.759-1.386,1.467-2.12,2.156a32.769,32.769,0,0,1-3.67,2.951,49.873,49.873,0,0,1-5.981,3.6c-.346.177-.683.37-1.032.54a.314.314,0,0,0-.215.347,2.917,2.917,0,0,1,0,.3v34.376a2.38,2.38,0,0,0,0,.3c.037.287-.077.375-.367.375q-5.456-.013-10.91,0c-.221,0-.39-.133-.6-.148-.086-.005-.088-.1-.092-.166,0-.12,0-.24,0-.359V147.63c0-.395,0-.389-.353-.254q-3.361,1.272-6.726,2.538c-.908.34-.912.327-.912,1.307V177.2c0,.569.049.523-.511.523-3.557,0-7.114,0-10.671.009a5.188,5.188,0,0,1-.689-.146c-.088-.01-.094-.095-.1-.165,0-.12,0-.24,0-.359v-20.89c0-.08-.007-.16,0-.24.026-.277-.1-.248-.275-.144-.469.281-.94.559-1.409.839a34.945,34.945,0,0,0-4.152,2.909,25.5,25.5,0,0,0-3.643,3.611,21.914,21.914,0,0,0-2.742,4.255,21.22,21.22,0,0,0-1.767,5.429,27.383,27.383,0,0,0-.374,2.953c-.049.649-.03,1.3-.044,1.943,0,.215-.068.3-.293.3-1.5-.007-3,0-4.5,0-.162,0-.309-.007-.295-.229.008-.142-.074-.2-.206-.189-.241.023-.3-.113-.272-.312.084-.7.05-1.41.1-2.115.059-.8.147-1.582.253-2.369a26.73,26.73,0,0,1,.711-3.379,24.39,24.39,0,0,1,2.082-5.168,23.866,23.866,0,0,1,2.511-3.768,27.568,27.568,0,0,1,2.5-2.735,36.9,36.9,0,0,1,3.811-3.156,51.256,51.256,0,0,1,6.682-4.047c.372-.189.739-.392,1.112-.58a.305.305,0,0,0,.2-.327c-.009-.1,0-.2,0-.3V114.137c0-.585-.053-.53.53-.53h10.671c.206,0,.444-.044.417.292a.1.1,0,0,0,.084.11c.336.005.265.251.265.449Q791.157,121.967,791.157,129.474Z" transform="translate(-733.917 -113.604)"></path>
                                        <path id="Path_142" data-name="Path 142" d="M1128.045,1346.923a4.519,4.519,0,0,1,2.488,1.269,4.91,4.91,0,0,1,1.486,2.831,7.464,7.464,0,0,1-.248,3.542,6.21,6.21,0,0,1-4.638,4.1,8.246,8.246,0,0,1-2.067.25c-3.187-.008-6.375,0-9.562,0-.412,0-.414,0-.414-.41v-21.339c0-.409,0-.41.414-.41,3.127,0,6.255-.031,9.382.014a7.528,7.528,0,0,1,4.069,1.093,4.277,4.277,0,0,1,1.935,3.12,7.118,7.118,0,0,1-.1,2.864,4.5,4.5,0,0,1-2.027,2.667C1128.549,1346.654,1128.315,1346.769,1128.045,1346.923Zm-9.917,5.4c0,1.138.012,2.276-.007,3.415,0,.312.1.382.393.38q2.891-.019,5.781,0a6.9,6.9,0,0,0,1.974-.249,3.252,3.252,0,0,0,2.283-1.972,4.3,4.3,0,0,0,.077-3.059,2.821,2.821,0,0,0-1.63-1.72,8.131,8.131,0,0,0-3.088-.563c-1.817-.037-3.635-.005-5.451-.016-.265,0-.34.083-.337.343.01,1.146,0,2.294,0,3.443Zm.12-9.635v2.906c0,.387,0,.387.4.387,1.727,0,3.455,0,5.183,0a8.877,8.877,0,0,0,1.394-.124,2.968,2.968,0,0,0,2.442-1.817,3.7,3.7,0,0,0,.237-1.936,2.642,2.642,0,0,0-1.723-2.312,5.649,5.649,0,0,0-2.015-.356c-1.867-.027-3.734-.005-5.6-.014-.253,0-.324.083-.322.328.008.978,0,1.954,0,2.934Z" transform="translate(-1068.44 -1263.38)"></path>
                                        <path id="Path_143" data-name="Path 143" d="M1749.6,1350.2a11.215,11.215,0,0,1-4.733-.772,8.63,8.63,0,0,1-3.353-2.425,11,11,0,0,1-2.46-5.574c-.1-.648-.126-1.3-.193-1.955a13.714,13.714,0,0,1,.03-2.356,11.59,11.59,0,0,1,2.224-6.507,8.975,8.975,0,0,1,5.45-3.409,13.573,13.573,0,0,1,3.142-.29,11.063,11.063,0,0,1,5.288,1.354,8.688,8.688,0,0,1,3.116,3.105,12.439,12.439,0,0,1,1.62,4.706,17.358,17.358,0,0,1,.165,3.4,13.741,13.741,0,0,1-1.245,5.424,9.335,9.335,0,0,1-1.566,2.278,8.826,8.826,0,0,1-2.545,1.955,10.654,10.654,0,0,1-3.714,1.028C1750.346,1350.2,1749.869,1350.181,1749.6,1350.2Zm7.023-11.585a13.91,13.91,0,0,0-.106-2.082,9.951,9.951,0,0,0-1.25-3.794,5.975,5.975,0,0,0-3.029-2.628,8.038,8.038,0,0,0-4.131-.411,6.384,6.384,0,0,0-5,3.633,10.5,10.5,0,0,0-1,4.353,16.512,16.512,0,0,0,.037,2.18,11.992,11.992,0,0,0,.741,3.361,6.44,6.44,0,0,0,4.155,3.99,8.31,8.31,0,0,0,2.864.312,6.6,6.6,0,0,0,6.073-4.631A11.631,11.631,0,0,0,1756.622,1338.614Z" transform="translate(-1654.762 -1254.133)"></path>
                                        <path id="Path_144" data-name="Path 144" d="M355.033,1347.89v10.667c0,.413,0,.414-.409.414-.789,0-1.579-.013-2.367.007-.3.007-.39-.088-.389-.389.01-3.2.006-6.393.006-9.588,0-.4,0-.4-.39-.4q-5.319,0-10.639-.007c-.3,0-.387.084-.386.387.011,3.186.007,6.372.007,9.558,0,.429,0,.431-.424.431-.769,0-1.538-.011-2.307.006-.287.007-.376-.082-.374-.372.011-1.988.006-3.975.006-5.963v-15.371c0-.459,0-.46.454-.46.759,0,1.519.011,2.277-.006.288-.007.374.084.374.373-.01,2.777-.006,5.553-.005,8.33,0,.414,0,.414.405.414H351.48c.394,0,.394,0,.394-.4v-8.3c0-.414,0-.415.409-.416.789,0,1.579.012,2.367-.007.3-.007.39.084.389.388Q355.024,1342.541,355.033,1347.89Z" transform="translate(-337.36 -1263.439)"></path>
                                        <path id="Path_145" data-name="Path 145" d="M1477.909,1347.967v-10.639c0-.414,0-.416.409-.416h2.338c.412,0,.413,0,.413.412v18.4c0,.429,0,.43.419.43h10.131c.481,0,.481,0,.481.494v1.978c0,.447,0,.448-.437.448h-13.309c-.446,0-.446,0-.446-.438Q1477.909,1353.3,1477.909,1347.967Z" transform="translate(-1409.499 -1263.54)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>

            </div>
            <div class="brand-grid div20">
                <li>

                    <a class="iwc" href="https://www.ethoswatches.com/brands/iwc.html" title="IWC Schaffhausen Watches" onclick="brandslogo('35','IWC Schaffhausen')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="203" height="109" viewBox="0 0 203 109" style="
                          max-width: 80px;
                      ">
                            <defs>
                                <clipPath id="clip-path">
                                    <rect id="Rectangle_32" data-name="Rectangle 32" width="290" height="170" transform="translate(0.011 -0.41)"></rect>
                                </clipPath>
                                <clipPath id="clip-IWC">
                                    <rect width="203" height="109"></rect>
                                </clipPath>
                            </defs>
                            <g id="IWC" clip-path="url(#clip-IWC)">

                                <g id="IWC-2" data-name="IWC" transform="translate(-43.011 -29.59)" clip-path="url(#clip-path)">
                                    <g id="download" transform="translate(49.787 48.336)">
                                        <g id="Group_37" data-name="Group 37" transform="translate(40.186 0)">
                                            <path id="Path_146" data-name="Path 146" d="M.195,41.592V40.536c3.161-.175,4.624-.876,4.624-3.512V5.173c0-2.636-1.463-3.4-4.331-3.517V.605H15.243V1.658c-2.809.119-4.273.879-4.273,3.515V37.024c0,2.636,1.464,3.335,4.627,3.512v1.056ZM70.866,6.518,57.811,41.883H56.76L47.5,15.3,36.435,41.883H35.383L22.678,5.113c-.762-2.166-1.7-3.279-4.683-3.456V.605H33.218V1.658c-2.986.175-3.806,1.174-3.806,2.518a11.649,11.649,0,0,0,.822,3.1l7.96,23.13L46.1,11.144,43.872,4.586c-.7-1.991-1.813-2.751-4.447-2.928V.605H54.592V1.658c-3.515.059-3.923,1.231-3.923,2.168a8.958,8.958,0,0,0,.643,2.75l7.963,23.01L66.827,9.033A17.569,17.569,0,0,0,68.117,4.06c0-1.584-1.23-2.226-4.157-2.4V.605H77.018V1.658c-3.105.175-4.743,1.112-6.151,4.86Zm24.3,35.951c-12.7,0-20.024-8.958-20.024-21.373,0-7.492,4.51-20.9,20.2-20.9,7.084,0,8.957,2.75,11.416,2.75.937,0,1.582-.467,1.755-2.223h1.056l.466,12.473H108.81c-1.583-7.146-6.792-10.658-12.939-10.658A11.633,11.633,0,0,0,87.319,6.05C84.336,9.1,82.461,14.129,82.461,21.1c0,10.832,4.8,18.561,14.052,18.561,6.207,0,10.071-2.461,13.118-7.261l1.054.585c-3.105,6.031-8.254,9.488-15.519,9.488Z" transform="translate(-0.195 -0.195)" fill="#020302" fill-rule="evenodd"></path>
                                        </g>
                                        <path id="Path_147" data-name="Path 147" d="M179.935,47.088h2.144V38.267l6.838,8.822h1.827V34.683H188.6V43.26l-6.643-8.577h-2.02v12.4Zm-16.016,0H173.2V45.139h-7.106V41.808h6.218V39.857H166.1V36.634h7.016V34.683h-9.195v12.4Zm-10.682.177c2.6,0,4.431-1.383,4.431-3.7v-.035c0-2.054-1.349-3-3.97-3.633-2.392-.567-2.96-.991-2.96-1.949v-.035c0-.816.746-1.471,2.021-1.471a5.646,5.646,0,0,1,3.368,1.275l1.168-1.646a6.872,6.872,0,0,0-4.5-1.559c-2.462,0-4.235,1.469-4.235,3.6v.035c0,2.287,1.489,3.066,4.128,3.7,2.3.532,2.8,1.009,2.8,1.895v.035c0,.921-.851,1.559-2.2,1.559a5.739,5.739,0,0,1-3.915-1.611l-1.314,1.559a7.648,7.648,0,0,0,5.177,1.984Zm-16.213.017c3.245,0,5.335-1.86,5.335-5.563V34.683h-2.181v7.141c0,2.287-1.188,3.436-3.118,3.436-1.949,0-3.137-1.221-3.137-3.524V34.683h-2.179v7.141c0,3.6,2.054,5.459,5.279,5.459Zm-18.5-5.119,2.127-4.962,2.146,4.962h-4.268Zm-4.307,4.925h2.233l1.277-3H123.6l1.257,3h2.3L121.7,34.6h-2.02Zm-16.582,0h2.179V41.862h5.918v5.226h2.175V34.683h-2.175v5.158H99.816V34.683H97.64v12.4Zm-15.237,0h2.179V42.039h6.254V40.055h-6.25V36.671h7.049V34.685H82.4v12.4Zm-15.521,0h2.18V42.039h6.254V40.055H69.062V36.671h7.051V34.685H66.881v12.4ZM52.532,42.163,54.659,37.2,56.8,42.163Zm-4.307,4.925h2.233l1.277-3H57.6l1.257,3h2.3L55.7,34.6h-2.02Zm-16.1,0H34.3V41.862h5.919v5.226H42.4V34.683H40.216v5.158H34.3V34.683H32.121v12.4Zm-11,.212a6.4,6.4,0,0,0,5.032-2.2l-1.4-1.419a4.842,4.842,0,0,1-3.56,1.6,4.179,4.179,0,0,1-4.093-4.394v-.035a4.173,4.173,0,0,1,4.093-4.375,4.93,4.93,0,0,1,3.473,1.541l1.4-1.611a6.463,6.463,0,0,0-4.855-1.933,6.274,6.274,0,0,0-6.4,6.413v.035a6.235,6.235,0,0,0,6.307,6.38ZM5.172,47.265c2.6,0,4.429-1.383,4.429-3.7v-.035c0-2.054-1.347-3-3.968-3.633-2.392-.567-2.96-.991-2.96-1.949v-.035c0-.816.744-1.471,2.021-1.471A5.641,5.641,0,0,1,8.06,37.715l1.172-1.646a6.867,6.867,0,0,0-4.5-1.559C2.271,34.509.5,35.979.5,38.105v.035c0,2.287,1.489,3.066,4.128,3.7,2.3.532,2.8,1.009,2.8,1.895v.035c0,.921-.851,1.559-2.2,1.559a5.735,5.735,0,0,1-3.915-1.611L0,45.279a7.647,7.647,0,0,0,5.172,1.984Z" transform="translate(0 25.303)" fill="#020302" fill-rule="evenodd"></path>
                                    </g>
                                </g>
                            </g>
                        </svg> </a>
                </li>
            </div>
        </div>

    </section>

    <!-- watch care section -->

    <div class="watch-care-section">
        <div class="watch-care-content">
            <div class="watch-care-logo">
                <img src="https://cdn1.ethoswatches.com/media/logo/stores/1/e_logo_watch_care.png" alt="Ethos Logo" width="50">
            </div>
            <h2 class="watch-care-heading">CELESTIAL WATCH CARE</h2>
            <hr class="watch-care-divider">
            <p class="watch-care-description">We service and repair 20 of the world's top luxury watch brands. For all repair and service-related queries, please contact us between 10:30 am and 6:30 pm (Mon to Sat).</p>
            <div class="watch-care-contact-info">
                <span>Service Helpline: +91 11 4142 1691 | +91 93190 95793</span>
                <span>Email: Customercare@celestialwatches.com</span>
            </div>
            <a href="#" class="learn-more-button">KNOW MORE ABOUT CELESTIAL WATCH CARE</a>
        </div>
    </div>

    <!-- Reach us  -->

    <!-- <section class="helpline">
    <div class="container">
        <div class="row">
            <div class="inner-container text-center">
                <div class="banner-content w-100 pt-65 mb-65">
                    <p class="mb-10 font_12">How to reach us</p>
                    <h2 class="color_00 b-border b-color font_18 fWeight_regular">TALK TO A LUXURY WATCH CONSULTANT</h2>
                </div>
                <p class="font_12 w-75">The luxury watch helpline is your guide and concierge to luxury timepieces. Tell us the occasion and we'll match you a watch for it!</p>
                <div class="lux_helpline mt-65 mb-70">
                    <div class="help_1 helpline_content col-sm-4">
                        <a href="mailto:info@ethoswatches.com">
                            <img src="https://cdn2.ethoswatches.com/static/frontend/Ethos-v2/destkop/en_US/images/mail.svg" width="40" alt="Email Us"/>
                            <span class="color_00">Email Us</span>
                            <span class="color_80">info@ethoswatches.com</span>
                        </a>
                    </div>
                    

                    <div class="help_2 helpline_content col-sm-4">
                        <img src="https://cdn2.ethoswatches.com/static/frontend/Ethos-v2/destkop/en_US/images/shake_phone.svg" width="30" alt="Call Us"/>
                        <span class="color_00">Call Us</span>
                        <span class="color_80">+91 87250 28882</span><br/>
                        <span class="color_80">+91 87250 28899</span>
                    </div>

                    <div class="help_3 helpline_content col-sm-4">
                        <img src="https://cdn2.ethoswatches.com/static/frontend/Ethos-v2/destkop/en_US/images/live-chat.svg" width="35" alt="Live Chat"/>
                        <span class="color_00">Live Chat</span>
                        <span class="color_80">Monday-Friday, 10:00 am - 2:00 am</span><br/>
                        <span class="color_80">Saturday-Sunday, 10:00 am - 7:00 pm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

    <section class="helpline">
        <div class="help-container">
            <div class="help-row">
                <div class="inner-container">
                    <div class="help-banner-content">
                        <div class="helpline-subtitle mb-10 font_12">How to reach us</div>
                        <div class="helpline-title color_00 b-border b-color font_18 fWeight_regular">TALK TO A LUXURY WATCH CONSULTANT</div>
                        <div class="v-line" style="border-top: 1px solid;"></div>
                    </div>

                    <div class="helpline-description font_12 w-75">
                        <span class="help-desc-title">
                            The luxury watch helpline is your guide and concierge to luxury timepieces. Tell us the occasion and we'll match you a watch for it!
                        </span>
                    </div>

                    <div class="lux_helpline">
                        <div class="helpline_content">
                            <a href="mailto:celestialwatches69@gmail.com" class="helpline-link">
                                <img src="https://cdn2.ethoswatches.com/static/frontend/Ethos-v2/destkop/en_US/images/mail.svg" width="40" class="helpline-icon" alt="Email Us">
                                <div class="helpline-label color_00">Email Us</div>
                                <div class="helpline-info color_80">celestialwatches69@gmail.com</div>
                            </a>
                        </div>

                        <div class="h-line"></div>

                        <div class="helpline_content">
                            <img src="https://cdn2.ethoswatches.com/static/frontend/Ethos-v2/destkop/en_US/images/shake_phone.svg" width="35" class="helpline-icon" alt="Call Us">
                            <div class="helpline-label color_00">Call Us</div>
                            <div class="helpline-info color_80">+91 87250 28882</div>
                            <div class="helpline-info color_80">+91 87250 28899</div>
                        </div>

                        <div class="h-line"></div>

                        <div class="helpline_content">
                            <img src="https://cdn2.ethoswatches.com/static/frontend/Ethos-v2/destkop/en_US/images/live-chat.svg" width="30" class="helpline-icon" alt="Live Chat">
                            <div class="helpline-label color_00">Live Chat</div>
                            <div class="helpline-info color_80">Monday-Friday, 10:00 am - 2:00 am</div>
                            <div class="helpline-info color_80">Saturday-Sunday, 10:00 am - 7:00 pm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Helpline -->

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
                <div class="luxury-helpline-numbers" style="justify-content: end;">
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




    <!-- ================================ JS ================================  -->
    <script>
        const carouselImages = document.querySelector('.carousel-images');
        const images = document.querySelectorAll('.carousel-images img');
        const totalImages = images.length;
        let index = 1; // Start at the first "real" image

        // Clone the first and last images
        const firstClone = images[0].cloneNode(true);
        const lastClone = images[totalImages - 1].cloneNode(true);

        // Append and prepend the clones
        carouselImages.appendChild(firstClone);
        carouselImages.insertBefore(lastClone, images[0]);

        // Set initial position to show the first real slide
        carouselImages.style.transform = `translateX(-${100}vw)`;

        // Function to update carousel position
        function updateCarousel() {
            const slideWidth = window.innerWidth;
            carouselImages.style.transition = 'transform 0.5s ease';
            carouselImages.style.transform = `translateX(-${index * slideWidth}px)`;
        }

        // Handle transition to make the carousel loop seamlessly
        carouselImages.addEventListener('transitionend', () => {
            const slideWidth = window.innerWidth;
            if (index === 0) { // Moved to the last clone (before the first real image)
                carouselImages.style.transition = 'none'; // Disable transition for instant jump
                index = totalImages; // Jump to the last real image
                carouselImages.style.transform = `translateX(-${index * slideWidth}px)`;
            } else if (index === totalImages + 1) { // Moved to the first clone (after the last real image)
                carouselImages.style.transition = 'none'; // Disable transition for instant jump
                index = 1; // Jump to the first real image
                carouselImages.style.transform = `translateX(-${index * slideWidth}px)`;
            }
        });

        // Next button
        document.querySelector('.carousel-control.next').addEventListener('click', () => {
            if (index <= totalImages) { // Avoid incrementing beyond the clone
                index++;
                updateCarousel();
            }
        });

        // Previous button
        document.querySelector('.carousel-control.prev').addEventListener('click', () => {
            if (index >= 1) { // Avoid decrementing beyond the clone
                index--;
                updateCarousel();
            }
        });

        // Adjust carousel when window is resized
        window.addEventListener('resize', updateCarousel);
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
    <script src="/src/libs/swiper/swiper-bundle.min.js"></script>
    <script src="/src/assets/js/index.js"></script>
    <script src="/src/assets/js/currency-language.js"></script>
    <script src="/src/assets/js/cookie-monitor.js"></script>

</body>

</html>