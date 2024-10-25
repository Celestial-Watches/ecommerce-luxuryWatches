<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Layout</title>
    <script src="../../src/assets/js/scroll-animation.js"></script>
    <style>
        .article-container {
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            padding: 20px;
            max-width: 1400px;
            margin: auto;
        }

        .feature-section {
            display: flex;
            flex-direction: column;
            background-color: white;
            margin-bottom: 20px;
        }

        .feature-img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }

        .feature-content {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .spotlight {
            font-family: brother-1816, sans-serif;
            font-size: 10px;
            font-weight: 400;
            line-height: 15px;
            letter-spacing: 0.5px;
            text-align: center;
            display: inline-block;
            text-transform: uppercase;
            padding: 6px 20px;
            background-color: #9D0000;
            color: #FFFFFF;
            margin-bottom: 16px;
        }

        .feature-content .spotlight-head {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .feature-content .spotlight-text {
            max-width: 100%;
            font-family: "brother-1816", sans-serif;
            font-style: normal;
            font-size: 12px;
            line-height: 18px;
            letter-spacing: 0.1px;
            color: #646364;
            margin: 0px;
        }

        .right-section {
            display: flex;
            flex-direction: column;
        }

        .right-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
        }

        .right-item {
            background-color: white;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .right-img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            cursor: pointer;
            aspect-ratio: 7/6;
        }

        .right-text {
            padding: 10px 0;
        }

        .right-text .category {
            color: #cc0000;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .right-text .spotlight-large-header,
        .spotlight-head {
            font-family: "mrs-eaves-xl-serif-narrow", serif;
            font-style: normal;
            font-weight: 400;
            font-size: 18px;
            line-height: 24px;
            margin-bottom: 16px;
            letter-spacing: 0.1px;
            color: #000000;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
            -webkit-line-clamp: 2;
            cursor: pointer;
        }

        .right-text .spotlight-text {
            font-size: 14px;
            color: gray;
        }

        .meta-info,
        .spotlight-text,
        .category {
            font-family: "mrs-eaves-xl-serif-narrow", serif;
            font-style: italic;
            font-weight: 400;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.1px;
            color: #646364;
        }

        .article-title {
            font-size: 14px;
            line-height: 24px;
            color: #000000;
            font-weight: 500;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin: 10px;
            padding: 20px;
            margin-left: 20px;
            display: flex;
            cursor: pointer;
        }

        .article-body {
            display: flex;
            flex-direction: row;
        }

        .feature-content-container {
            flex: 2.27;
            margin-right: 20px;
        }

        .right-section {
            flex: 1.38;
        }

        @media (max-width: 768px) {
            .article-body {
                flex-direction: column;
            }

            .feature-content-container {
                margin-right: 0;
            }

            .right-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="articleBody">
    <div class="topic-name">
        <h2 class="article-title">Trending Articles <svg style="
    margin-left: 10px;
" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
        </h2>
    </div>
    <div class="article-container animate-on-scroll">
        <div class="article-body animate-on-scroll">
            <div class="feature-content-container animate-on-scroll">
                <div class="feature-section">
                    <img loading="lazy" class="feature-img"
                        src="https://www.favreleuba.com/blog/wp-content/uploads/2024/09/Favre-Leuba-Chief-chronograph.jpg"
                        alt="Feature Watch">
                </div>
                <div class="feature-content animate-on-scroll">
                    <div class="spotlight animate-on-scroll">SPOTLIGHT</div>
                    <h1 class="spotlight-head animate-on-scroll" style="font-size: 40px; line-height: 1;">Favre Leuba's Chief Collection: A 287-Year-Old Phoenix Rises</h1>
                    <p class="spotlight-text animate-on-scroll">
                        In the quiet Swiss town of Grenchen, a 287-year-old phoenix is rising from the ashes. Favre Leuba, once the titan of Swiss watchmaking, are stirring back to life with a collection that’s equal parts homage and revolution. The Favre Leuba Chief
                    </p> <br>
                    <div class="meta-info animate-on-scroll">
                        <span>August 31, 2024</span>
                    </div>
                </div>
            </div>
            <div class="right-section animate-on-scroll">
                <div class="right-grid animate-on-scroll">
                    <div class="right-item animate-on-scroll">
                        <img loading="lazy" class="right-img animate-on-scroll"
                            src="https://cdn1.ethoswatches.com/media/catalog/product/h/-/h-moser-cie-pioneer-6804-0400-multiple-4.jpg"
                            alt="Watch">
                        <div class="right-text animate-on-scroll">
                            <span class="category animate-on-scroll">ROUND-UP</span>
                            <h3 class="spotlight-large-header animate-on-scroll">Evolution Of Fluid Timekeeping: Presenting The Finest H.
                                Moser &amp; Cie. Streamliner Timepieces</h3>
                            <p class="spotlight-text animate-on-scroll">August 19, 2024</p>
                        </div>
                    </div>
                    <div class="right-item animate-on-scroll">
                        <img loading="lazy" class="right-img animate-on-scroll"
                            src="../../src/assets/image/Carl-F-Bucherer-Heritage-Chronometer-Celebration-Feat.jpg"
                            alt="Watch">
                        <div class="right-text animate-on-scroll">
                            <span class="category animate-on-scroll">REVIEW</span>
                            <h3 class="spotlight-large-header animate-on-scroll">Simply Stunning: Introducing The Heritage Chronometer
                                Celebration timepieces By Carl F. Bucherer</h3>
                            <p class="spotlight-text animate-on-scroll">August 23, 2024</p>
                        </div>
                    </div>
                    <div class="right-item animate-on-scroll">
                        <img loading="lazy" class="right-img animate-on-scroll"
                            src="../../src/assets/image/wempe-signature-collection-girard-perregaux-laureato-42mm-16.jpg"
                            alt="Watch">
                        <div class="right-text animate-on-scroll">
                            <span class="category animate-on-scroll">REVIEW</span>
                            <h3 class="spotlight-large-header animate-on-scroll">The Legacy Lives On: Presenting The Girard-Perregaux
                                Laureato 38mm Copper Diamond Bezel</h3>
                            <p class="spotlight-text animate-on-scroll">August 23, 2024</p>
                        </div>
                    </div>
                    <div class="right-item animate-on-scroll">
                        <img loading="lazy" class="right-img animate-on-scroll"
                            src="../../src/assets/image/Ressence-Type-3-Featured.jpeg"
                            alt="Watch">
                        <div class="right-text animate-on-scroll">
                            <span class="category animate-on-scroll">REVIEW</span>
                            <h3 class="spotlight-large-header animate-on-scroll">A Well-Oiled Machine: Presenting The Ressence Type 3
                                Collection</h3>
                            <p class="spotlight-text animate-on-scroll">August 23, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>