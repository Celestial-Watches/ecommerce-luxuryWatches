<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Celestial Watches | Exclusivity in Every Tick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            background-color: #f5f6f8;
            margin: 0;
            padding: 0;
        }

        .settings {
            display: flex;
            width: 100%;
            background: #fff;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .sidebar {
            width: 280px;
            background-color: #f9fafb;
            padding: 24px;
            border-right: 1px solid #e0e4e8;
            transition: transform 0.3s ease;
        }

        .aligned {
            display: flex;
            align-items: center;
        }

        .sidebar h2 {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            /* margin-bottom: 30px; */
            padding-left: 8px;
        }

        .sidebar h3 {
            font-size: 12px;
            color: #8a8f93;
            margin-top: 40px;
            margin-bottom: 12px;
            padding-left: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin-bottom: 12px;
        }

        .menu li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-size: 15px;
        }

        .menu li a .icon {
            margin-right: 14px;
            font-size: 18px;
        }

        .menu li a:hover {
            background-color: #e5e9f0;
        }

        .menu li a.active {
            background-color: #e5e9f0;
            color: #007bff;
            font-weight: bold;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .content h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .content p {
            color: #666;
            margin-bottom: 20px;
        }

        .profile-picture {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .profile-picture button {
            margin-right: 10px;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-top: 15px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .integrated-accounts {
            margin-top: 20px;
        }

        .integrated-accounts h4 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .account {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
        }

        .account button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .luxury-button {
            padding: 12px 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
            background-color: #000000;
            border: 2px solid #fff;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .luxury-button:hover {
            background-color: #fff;
            color: #4c4c4c;
            border-color: #4c4c4c;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }

        .luxury-button:focus {
            border-color: #ffbb00;
            box-shadow: 0 0 0 3px rgba(255, 187, 0, 0.5);
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .settings {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
            }

            .content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {

            /* Make the sidebar a toggle on small screens */
            .sidebar {
                position: fixed;
                left: -280px;
                /* Initially hide sidebar off-screen */
                top: 0;
                height: 100vh;
                width: 280px;
                padding: 20px;
                z-index: 999;
            }

            .settings {
                flex-direction: column;
                padding-top: 60px;
                /* Give space for the toggle button */
            }

            .content {
                padding: 20px;
            }

            .sidebar.open {
                transform: translateX(280px);
                /* Slide the sidebar in */
            }

            .sidebar h2,
            .sidebar h3 {
                text-align: center;
            }

            .sidebar h3 {
                margin-top: 20px;
            }

            /* Button to toggle sidebar visibility */
            .toggle-sidebar {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 4px;
                cursor: pointer;
                z-index: 1000;
            }

            .toggle-sidebar:focus {
                outline: none;
            }

            /* Adjust the button styling for smaller screens */
            .luxury-button {
                font-size: 14px;
                padding: 10px 25px;
            }
        }
    </style>
</head>

<body>
    <div class="settings">
        <div class="sidebar">
            <span class="aligned"><a href="../../index.php" style="text-decoration: none;"><i class="ri-arrow-left-line"></i></a>
                <h2>Settings</h2>
            </span>
            <ul class="menu">
                <li><a href="#" class="active">Account</a></li>
                <li><a href="#"> NFT Customisation</a></li>
                <li><a href="#"> Sell and Exchange</a></li>
                <li><a href="#"> Rental and Lease</a></li>
                <li><a href="#"> Active service management</a></li>
                <li><a href="#"> Auction participation</a></li>
                <li><a href="#"> Transaction History </a></li>
                <li><a href="#"> Manage address </a></li>
                <li><a href="#"> payment methods </a></li>


            </ul>
            <h3>SYSTEM</h3>
            <ul class="menu">
                <li><a href="#"> Notifications</a></li>
                <li><a href="#">Preferences</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>Account</h3>
            <p>Real-time information and activities of your profile.</p>

            <div class="profile-picture">
                <img src="assets/image/user.png" alt="Profile picture">
                <button class="luxury-button">Upload new picture</button>
                <button class="luxury-button">Delete</button>
            </div>

            <form>
                <label>First name</label>
                <input type="text" placeholder="Bryan">

                <label>Last name</label>
                <input type="text" placeholder="Cranston">

                <label>Email</label>
                <input type="email" placeholder="bryan.cranston@mail.com">

                <label>Current password</label>
                <input type="password" placeholder="Current password">

                <label>New password</label>
                <input type="password" placeholder="New password">
        </div>
    </div>
</body>

</html>