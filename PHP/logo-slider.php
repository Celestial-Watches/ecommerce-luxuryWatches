<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
            height: 50px;
            margin: 0 40px;
        }
    </style>
</head>

<body>
    <div class="logos">
        <div class="logos-slide">
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