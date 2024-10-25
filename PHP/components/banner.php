<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches</title>
    <script src="../../src/assets/js/scroll-animation.js"></script>
    <style>
        .banner {
            font-family: Arial, sans-serif !important;
            background-color: #f7f7f7;
            flex-direction: column;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 100%; 
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bannerOverlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            font-family: 'univers_55regular';
        }

        .logos:before {
            left: 0;
            background: linear-gradient(to left, rgba(255, 255, 255, 0), white);
        }

        .logos:after {
            right: 0;
            background: linear-gradient(to right, rgba(255, 255, 255, 0), white);
        }

        .bannerOverlay h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-family: 'icomoon';
        }

        .bannerOverlay p {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .cta-button {
            padding: 10px 20px;
            background-color: #000;
            color: #ffff;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        .cta-button:hover {
            transform: scale(1.05);
            border-color: #fff9;
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


        @media (max-width: 600px) {
            .bannerOverlay h1 {
                font-size: 1.8rem;
            }

            .bannerOverlay p {
                font-size: 1rem;
            }

            .video-container {
                width: 95%;
            }
        }
    </style>
</head>

<body>
    <section class="banner ">
        <div class="video-container">
            <video id="bannerVideo" autoplay muted loop>
                <source src="../../src/assets/image/video/Untitled video - Made with Clipchamp.mp4" type="video/mp4" loading="lazy">
            </video>
            <div class="bannerOverlay">
                <h1 class="animate-on-scroll">Welcome to Celestial Watches</h1>
                <p class="animate-on-scroll">Exclusivity in Every Tick</p>
                <a href="#shop" class="cta-button">Shop Now</a>
            </div>
        </div>
    </section>
</body>

</html>
