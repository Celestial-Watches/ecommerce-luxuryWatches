<?php if (!defined('ALLOW_ACCESS')) {
    header("Location: ../../index.php");
    exit();
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="../../src/assets/js/scroll-animation.js" async></script>
    <style>
        @keyframes slide {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }

        .logos {
            overflow: hidden;
            padding: 60px 0;
            background: #fff;
            white-space: nowrap;
            position: relative;
        }

        .brand-slider {
            font-family: Times New Roman;
            font-size: 22px;
            color: #808080;
            text-transform: uppercase;
        }

        .logos:before,
        .logos:after {
            position: absolute;
            top: 0;
            width: 250px;
            height: 100%;
            content: "";
            z-index: 2;
        }

        .logos:hover .logos-slide {
            animation-play-state: paused;
        }

        .logos-slide {

            display: inline-block;
            animation: 35s slide infinite linear;
        }

        .logos-slide .brand-slider {
            height: 25px;
            margin: 0 40px;
        }

        .logg-button:hover {
            transform: scale(1.05);
            border-color: #fff9;
        }

        .logg-button:hover .icon {
            transform: translate(4px);
        }

        .logg-button:hover::before {
            animation: shine 1.5s ease-out infinite;
        }

        .logg-button::before {
            content: "";
            position: absolute;
            width: 100px;
            height: 100%;
            background-image: linear-gradient(120deg,
                    rgba(255, 255, 255, 0) 30%,
                    rgba(255, 255, 255, 0.8),
                    rgba(255, 255, 255, 0) 70%);
            top: 0;
            left: -100px;
            opacity: 0.6;
        }

        @keyframes shine {
            0% {
                left: -100px;
            }

            60% {
                left: 100%;
            }

            to {
                left: 100%;
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
    <div class="logos animate-on-scroll">
        <div class="logos-slide animate-on-scroll">
            <span class="brand-slider"> Patek Philippe </span>
            <span class="brand-slider"> Richard Mille </span>
            <span class="brand-slider"> Audemars Piguet </span>
            <span class="brand-slider"> Vacheron Constantin </span>
            <span class="brand-slider"> Jaeger-LeCoultre </span>
            <span class="brand-slider"> IWC Schaffhausen </span>
            <span class="brand-slider"> Breguet </span>
            <span class="brand-slider"> Cartier </span>
            <span class="brand-slider"> Blancpain </span>
            <span class="brand-slider"> Hublot </span>
        </div>
    </div>

    <script>
        var copy = document.querySelector(".logos-slide").cloneNode(true);
        document.querySelector(".logos").appendChild(copy);
    </script>
</body>

</html>