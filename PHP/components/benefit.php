<?php if (!defined('ALLOW_ACCESS')) {
    header("Location: ../../index.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="../../src/assets/js/scroll-animation.js" async></script>
    <style>
        /* General Section Styling */
        .available-payments {
            padding: 40px 0;
            background-color: #f8f8f8;
            text-align: center;
        }

        .futures_inner {
            max-width: 1070px;
            width: 100%;
            flex: 0 0 auto;
            flex-wrap: wrap;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 48px;
            border-bottom: 2px solid #F3F4F4;
        }

        .benefit-item {
            font-size: 21px;
            font-family: 'Open Sans';
            line-height: 24px;
            font-weight: 400;
            margin-bottom: 25px;
        }

        .benefit-item img {
            margin-bottom: 10px;
        }

        .benefit-item div {
            margin-top: 14px;
            max-width: 100%;
            color: #000;
        }

        /* Payment Methods Section */
        .payments-box {
            margin-top: 30px;
            padding-top: 19px;
        }

        .payments-box h4 {
            font-size: 11px;
            line-height: 13px;
            font-family: 'Univers-LT-Std-55-Roman', sans-serif;
            margin-bottom: 20px;
            font-weight: 400;
            text-align: center;
        }

        /* benefit-Logos Container */
        .benefit-logos {
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .benefit-logo-box {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .benefit-logos img {
            height: auto;
        }


        /* Responsive Design */

        @media (min-width: 768px) {
            .benefit-logos {
                flex-direction: row;
                justify-content: center;
                gap: 15px;
            }

            

            .card-payment {
                display: flex;
                gap: 15px;
            }

            .card-payment img {
                margin-bottom: 0;
            }

            .benefit-logo-box {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }

            .benefit-logo-box img {
                margin-right: 15px;
            }


        }


        @media (max-width: 768px) {
            .benefit-logos {
                flex-direction: row;
                flex-wrap: nowrap;
                justify-content: center;
                gap: 15px;
            }

            .card-payment {
                display: flex;
                gap: 15px;
            }

            .card-payment img {
                margin-bottom: 0;
            }

            .futures_inner{
                justify-content: center;
                padding-bottom: 0;
                flex-direction: column;
            }

            .benefit-logo-box {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .benefit-logo-box img {
                margin-bottom: 10px;
            }

        }
    </style>
</head>

<body>
    <section class="available-payments text-center animate-on-scroll" style="margin-bottom: 30px !important;">
        <div class="futures d-flex justify-content-center " style="display:flex; justify-content:center;">
            <div class="futures_inner">
                <div class="benefit-item">
                    <img alt="track" width="31" height="30" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/home-page/track.png" loading="lazy">
                    <div>Secured and<br>tracked delivery</div>
                </div>

                <div class="benefit-item">
                    <img alt="user" width="28" height="31" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/home-page/user.svg" loading="lazy">
                    <div>Passionate experts<br>at your service</div>
                </div>

                <div class="benefit-item">
                    <img alt="support" width="28" height="29" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/home-page/support.svg" loading="lazy">
                    <div>Help and<br>customer service 24/7</div>
                </div>

                <div class="benefit-item">
                    <img alt="guard" width="31" height="32" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/home-page/guard.svg" loading="lazy">
                    <div>All your payments<br>secured</div>
                </div>
            </div>
        </div>

        <div class="payments-box animate-on-scroll">
            <h4 class="text-uppercase animate-on-scroll" style="text-transform: uppercase;">
                We accept all these payment methods.ALL YoUR DATA ARE SECURED.
            </h4>
            <div class="d-flex benefit-logos justify-content-center" style="justify-content: center;">
                <div class="benefit-logo-box d-flex animate-on-scroll" style="display: flex;">
                    <div class="card-payment">
                        <div class="visa ">
                            <img alt="visa" width="56" height="18" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/visa.png?v=1">
                        </div>
                        <div class="master">
                            <img alt="master" width="31" height="24" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/master.svg">
                        </div>
                        <div class="amex">
                            <img alt="amex" width="30" height="27" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/amex.svg">
                        </div>
                        <div class="paypal">
                            <img alt="paypal" width="95" height="23" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/paypal.svg">
                        </div>
                    </div>
                    <div class="checkout">
                        <img alt="checkout" width="150" height="22" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/checkout.svg">
                    </div>
                    <div class="ssl">
                        <img alt="ssl" width="105" height="22" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/ssl.svg">
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>