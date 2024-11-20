<?php if (!defined('ALLOW_ACCESS')) {
    header("Location: ../../index.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Products</title>
    <script src="../../src/assets/js/scroll-animation.js" async></script>
    <style>
        .featured-main {
            background-color: #fcf8f5;
        }

        .featured-products {
            max-width: 1200px;
            margin: auto;
            text-align: center;
            padding: 20px;
            width: 100%;
        }

        .featured-title {
            margin: 20px 0;
            font-family: 'Oranienbaum', serif;
            font-weight: 700;
            font-size: 18px;
            line-height: 34px;
            color: #000000;
            font-weight: 500;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .slider {
            display: flex;
            overflow: hidden;
            position: relative;
            margin: 20px auto;
        }

        .slider-container {
            position: relative;
            overflow: hidden;
        }

        .product-grid {
            display: flex;
            transition: transform 0.3s ease-in-out;
            justify-content: flex-start;
            width: 100%;
            will-change: transform;
        }

        .card {
            width: 30%;
            background-color: #ffffff;
            /* border: 1px solid #ddd; */
            /* border-radius: 8px; */
            padding: 20px;
            margin: 0 5px;
            flex: 0 0 32.32%;
            transition: transform 0.3s ease-in-out, opacity 0.5s ease-in-out;
            text-align: center;
            background-image: linear-gradient(300deg, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0) 70%);
        }

        .card.reduce-opacity {
            opacity: 0.5;
        }

        .card.scale-down {
            transform: scale(0.95);
        }

        .card .featured-card {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .card .featured-text,
        .card .featured-price {
            text-align: left;
            font-size: 12px !important;
            text-transform: uppercase !important;
            font-family: "Univers LT Std", sans-serif !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-top: 10px;
        }

        .card .featured-price {
            font-size: 17px !important;
            font-weight: 400 !important;
            line-height: 25px;
            color: #000;
            margin-top: 8px;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .buy-button {
            width: 100%;
            background-color: #fff !important;
            border: 1px solid #E5E5E5;
            color: #000 !important;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            font-family: 'Univers LT Std', sans-serif;
            font-weight: 400;
            cursor: pointer;
            transition: background-color 0.3s ease;
            height: 44px;
            margin-top: 20px !important;
        }

        .buy-button:hover {
            background-color: #F3F4F4 !important;
            border-color: #F3F4F4 !important;
            color: #000 !important;
        }

        hr {
            width: 100%;
            margin-bottom: 2px;
        }

        .slider-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.8);
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 1;
            border-radius: 5px;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        /* Responsive styling */
        @media (max-width: 1024px) {
            .card {
                width: 28%;
            }
        }

        @media (max-width: 768px) {
            .card {
                width: 45%;
                flex: 0 0 50%;
            }
        }

        @media (max-width: 480px) {

            /* For mobile, show 1 card */
            .card {
                width: 90%;
                flex: 0 0 100%;
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            /* Move elements slightly downwards */
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
            /* Bring elements to their original position */
        }
    </style>
</head>

<body>
    <main class="featured-main animate-on-scroll">
        <section class="featured-products animate-on-scroll">
            <h2 class="featured-title animate-on-scroll">Our Exclusive Collection</h2>
            <div class="slider">
                <button class="slider-button prev animate-on-scroll" aria-label="Previous Slide" onclick="moveSlide(-1)">&#10094;</button>
                <div class="slider-container animate-on-scroll">
                    <div class="product-grid">
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/2d6f15c3-5f2a-49ab-16b7-23602a3d8700/w=400x400" alt="Audemars Piguet Royal Oak Selfwinding 34mm">
                            <hr>
                            <h3 class="featured-text">Audemars Piguet Royal Oak Selfwinding 34mm</h3>
                            <p class="featured-price" data-price-in-usd="57154">$ 57,154</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/0739c076-75f1-4bc8-8a66-ce6268fa5a00/w=400x400" alt="Jacob &amp; Co. High Complication Masterpieces Casino Tourbillon Baguette Diamonds Limited Edition 44mm">
                            <hr>
                            <h3 class="featured-text">Jacob &amp; Co. High Complication Masterpieces Casino Tourbillon Baguette Diamonds Limited Edition 44mm</h3>
                            <p class="featured-price">ON REQUEST</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/a3111a85-9142-446a-d24c-da44dce4f400/w=400x400" alt="Richard Mille RM67-02 Automatic Winding Extra-Thin &amp;quot;Mutaz Essa Barshim&amp;&quot;">
                            <hr>
                            <h3 class="featured-text">Richard Mille RM67-02 Automatic Winding Extra-Thin &amp;quot;Mutaz Essa Barshim&amp;&quot;</h3>
                            <p class="featured-price" data-price-in-usd="324507">$ 324,507</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/15af5265-b853-493d-c020-60b2b499de00/w=400x400" alt="Vacheron Constantin Overseas Self-winding 34.5mm">
                            <hr>
                            <h3 class="featured-text">Vacheron Constantin Overseas Self-winding 34.5mm</h3>
                            <p class="featured-price" data-price-in-usd="26836">$ 26,836</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/74a8797a-33b7-4c8b-7368-2a90ca9d8b00/w=400x400" alt="Audemars Piguet Royal Oak Selfwinding Chronograph &amp;&quot;50th Anniversary&amp;&quot; 41mm">
                            <hr>
                            <h3 class="featured-text">Audemars Piguet Royal Oak Selfwinding Chronograph &amp;&quot;50th Anniversary&amp;&quot; 41mm</h3>
                            <p class="featured-price" data-price-in-usd="77365">$ 77,365</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/51185459-938c-4a5a-69fe-da97fcf26500/w=400x400" alt="Audemars Piguet Royal Oak 33mm">
                            <hr>
                            <h3 class=" featured-text">Audemars Piguet Royal Oak 33mm"</h3>
                            <p class="featured-price" data-price-in-usd="33574">$ 33,574</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/32e13bec-79ff-4ab3-ceb5-0a6ed4bae200/w=400x400" alt="Audemars Piguet Royal Oak Double Balance Wheel Openworked 41mm">
                            <hr>
                            <h3 class="featured-text">Audemars Piguet Royal Oak Double Balance Wheel Openworked 41mm</h3>
                            <p class="featured-price show-from-text" data-price-in-usd="257135">FROM $ 257,135</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/e9f16ed5-771f-495b-be31-5dc1493e0600/w=400x400" alt="Patek Philippe Nautilus 40mm">
                            <hr>
                            <h3 class="featured-text">Patek Philippe Nautilus 40mm</h3>
                            <p class="featured-price" data-price-in-usd="112174">$ 112,174</p>
                            <button class="buy-button">View Details</button>
                        </div>
                        <!--  -->
                        <div class="card">
                            <img loading="lazy" class="featured-card" src="https://imagedelivery.net/lyg2LuGO05OELPt1DKJTnw/d58061b2-44dc-488c-1687-66e7fa753600/w=400x400" alt="Richard Mille RM11-03 Automatic Flyback Chronograph McLaren Limited Edition"">
                            <hr>
                            <h3 class=" featured-text">Richard Mille RM11-03 Automatic Flyback Chronograph McLaren Limited Edition"</h3>
                            <p class="featured-price" data-price-in-usd="364930">$ 364,930</p>
                            <button class="buy-button">View Details</button>
                        </div>
                    </div>
                </div>
                <button class="slider-button next animate-on-scroll" aria-label="Next Slide" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </section>
    </main>

    <script>
        // Function to initialize sliders
        function initializeSlider(sliderContainer) {
            let currentIndex = 0;
            const cards = sliderContainer.querySelectorAll('.card');
            let slidesToShow = 3; // Default for large screens

            // Function to handle mouse enter
            function handleMouseEnter() {
                cards.forEach((card) => {
                    if (card !== this) {
                        card.classList.add('scale-down'); // Add scale-down class to other cards
                        card.classList.add('reduce-opacity'); // Add reduce-opacity class to other cards
                    }
                });
            }

            // Function to handle mouse leave
            function handleMouseLeave() {
                cards.forEach((card) => {
                    card.classList.remove('scale-down'); // Remove scale-down class
                    card.classList.remove('reduce-opacity'); // Remove reduce-opacity class
                });
            }

            // Attach event listeners to each card
            cards.forEach((card) => {
                card.addEventListener('mouseenter', handleMouseEnter);
                card.addEventListener('mouseleave', handleMouseLeave);
            });

            // Adjust number of slides based on window width
            function updateSlidesToShow() {
                if (window.innerWidth <= 480) {
                    slidesToShow = 1; // For mobile
                } else if (window.innerWidth < 768) {
                    slidesToShow = 2; // For tablets
                } else if (window.innerWidth <= 1024) {
                    slidesToShow = 3; // For smaller laptops
                } else {
                    slidesToShow = 3; // For larger screens
                }
                showSlides();
            }

            // Move the slides
            function moveSlide(step) {
                currentIndex += step;

                // Wrapping logic
                if (currentIndex < 0) {
                    currentIndex = cards.length - slidesToShow; // Jump from the first slide to the last set of slides
                } else if (currentIndex > cards.length - slidesToShow) {
                    currentIndex = 0; // Jump from the last set of slides to the first slide
                }

                showSlides();
            }

            // Display the slides by translating the grid
            function showSlides() {
                const grid = sliderContainer.querySelector('.product-grid');
                const cardWidth = cards[0].offsetWidth; // Get the width of the first card
                const totalWidth = (cardWidth + 9.5) * currentIndex; // 9px gap between cards
                grid.style.transform = `translateX(-${totalWidth}px)`; // Translate the grid
            }

            // Expose moveSlide function to the global scope for button clicks
            sliderContainer.querySelector('.prev').onclick = () => moveSlide(-1);
            sliderContainer.querySelector('.next').onclick = () => moveSlide(1);

            // Initial setup
            updateSlidesToShow();
            window.addEventListener('resize', updateSlidesToShow);
        }

        // Initialize sliders
        document.querySelectorAll('.slider').forEach(initializeSlider);
    </script>
</body>

</html>