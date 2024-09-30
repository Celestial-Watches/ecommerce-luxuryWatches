<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches</title>
    <style>
       

        /* Banner Styles */
        .banner {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Light background for contrast */
            flex-direction: column; /* Use flex to stack elements vertically */
            height: 72vh; /* Full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1; /* This allows the banner to take up remaining space */
        }

        .video-container {
            position: relative;
            width: 100%; /* Full width of the banner */
            height: calc(100vh - 220px); /* Remaining height after navbar */
            overflow: hidden; /* Hide overflow */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Optional shadow for a luxurious feel */
        }

        .video-container video {
            width: 100%;
            height: 100%; /* Full height of the container */
            object-fit: cover; /* Cover the area */
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
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay */
            color: white; /* Text color */
            text-align: center;
        }

        .bannerOverlay h1 {
            font-size: 2.5rem; /* Main title size */
            margin-bottom: 10px;
        }

        .bannerOverlay p {
            font-size: 1.2rem; /* Subtitle size */
            margin-bottom: 20px;
        }

        .cta-button {
            padding: 10px 20px;
            background-color: gold; /* Button color */
            color: black; /* Text color */
            text-decoration: none;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #ffd700; /* Lighter gold on hover */
        }

        /* Responsive Adjustments */
        @media (max-width: 600px) {
            .bannerOverlay h1 {
                font-size: 1.8rem; /* Smaller title on mobile */
            }
            .bannerOverlay p {
                font-size: 1rem; /* Smaller subtitle on mobile */
            }
            .video-container {
                width: 95%; /* Wider on mobile */
            }
        }
    </style>
</head>
<body>
  
    <section class="banner">
        <div class="video-container">
            <video id="bannerVideo" autoplay muted loop>
                <source src="/image/video/Untitled video - Made with Clipchamp.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="bannerOverlay">
                <h1>Welcome to Celestial Watches</h1>
                <p>Exclusivity in Every Tick</p>
                <a href="#shop" class="cta-button">Shop Now</a>
            </div>
        </div>
    </section>
</body>
</html>
