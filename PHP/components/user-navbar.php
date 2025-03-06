<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Remix Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <style>
        /* RESET & GLOBAL */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F2F3F5;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAV-BAR */
        .nav-bar {
            width: 280px;
            background: #FFFFFF;
            border-right: 1px solid #E0E4E8;
            padding: 1.5rem 1rem;
            transition: transform 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
            z-index: 1000;
        }

        .nav-bar .aligned {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .nav-bar .aligned a {
            font-size: 1.2rem;
            color: #aaa;
            margin-right: 0.5rem;
        }

        .nav-bar h2 {
            font-size: 1.2rem;
            font-weight: 500;
            color: #333;
        }

        .nav-bar h3 {
            font-size: 0.75rem;
            color: #888;
            margin: 2rem 0 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-menu li {
            margin-bottom: 0.5rem;
        }

        .nav-menu li a {
            display: block;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            color: #333;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            background-color: #F0F1F3;
            text-decoration: none;
        }

        /* Toggle Button for Mobile */
        .toggle-nav-bar {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            z-index: 1101;
            /* Above nav-bar */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .toggle-nav-bar {
                display: block;
            }

            .nav-bar {
                transform: translateX(-100%);
            }

            .nav-bar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Toggle Button -->
    <button class="toggle-nav-bar" onclick="toggleNavBar()">Menu</button>

    <!-- NAV-BAR -->
    <div class="nav-bar" id="navBar">
        <div class="aligned">
            <a href="../../index.php"><i class="ri-arrow-left-line"></i></a>
            <h2>Settings</h2>
        </div>

        <!-- Navigation -->
        <ul class="nav-menu">
            <li><a href="#" class="active">Account</a></li>
            <li><a href="#">NFT Customisation</a></li>
            <li><a href="selling.php">Sell & Exchange</a></li>
            <li><a href="#">Auction Participation</a></li>
            <li><a href="transaction.php">Transaction History</a></li>
            <li><a href="manageAddress.php">Manage Address</a></li>
            <li><a href="#">Payment Methods</a></li>
        </ul>

        <!-- Additional system items -->
        <h3>System</h3>
        <ul class="nav-menu">
            <li><a href="my_requests.php">My Stock Requests</a></li>
            <li><a href="notification.php">Notifications</a></li>
            <li><a href="#">Preferences</a></li>
        </ul>
    </div>
    <script>
        function toggleNavBar() {
            var navBar = document.getElementById('navBar');
            navBar.classList.toggle('active');
        }
    </script>
</body>

</html>