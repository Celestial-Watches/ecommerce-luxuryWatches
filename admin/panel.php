<?php
if (!defined('ALLOW_ACCESS')) {
  header("Location: ../index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Celestial Watches - Admin Panel</title>
  <link rel="stylesheet" href="../src/assets/css/panel.css">
  <script type="text/javascript" src="../src/assets/js/panelNav.js" async></script>
  <script type="text/javascript" src="../src/assets/js/navigation.js" async></script>
</head>

<body>
  <nav id="sidebar">
    <ul>
      <li>
        <span class="logo" style="cursor:pointer;">Celestial Watches</span>
        <button onclick=toggleSidebar() id="toggle-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z" />
          </svg>
        </button>
      </li>

      <!-- Dashboard Overview -->

      <li class="active">
        <a href="/admin/dashboard.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z" />
          </svg>
          <span class="noShow">Dashboard Overview</span>
        </a>
      </li>

      <!-- User Management -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd" />
          </svg>

          <span class="noShow">User Management</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">View Users</a></li>
            <li><a href="#">Add/Edit User</a></li>
            <li><a href="#">User Roles & Permissions</a></li>
            <li><a href="#">User Activity Logs</a></li>
            <li><a href="#">Support Queries</a></li>
          </div>
        </ul>
      </li>

      <!-- Product Listing -->
      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M7 4h10l1 1h3v2h-2.2l-1 5H6.9L5 6H2V4h2l1 2h8l1-2H7zM7 20c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm10 0c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM6 8h12l1 5H5l1-5zm11 9h-2v2h2v-2z" />
          </svg>
          <span class="noShow">Product Listing</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="/admin/view-listing.php">View Listings</a></li>
            <li><a href="/admin/add-product.php">Add/Edit Listing</a></li>
            <li><a href="#">Watch Categories</a></li>
            <li><a href="#">Rental/Lease Watches</a></li>
            <li><a href="#">NFT Customization Requests</a></li>
          </div>
        </ul>
      </li>

      <!-- Orders & Payments -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M3 3h18v18H3V3zm0 2v14h18V5H3zM7 10h10v2H7v-2z" />
          </svg>
          <span class="noShow">Orders & Payments</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">View Orders</a></li>
            <li><a href="#">Payment History</a></li>
            <li><a href="#">Auction Management</a></li>
            <li><a href="#">Refund/Cancellation Requests</a></li>
          </div>
        </ul>
      </li>

      <!-- Watch Authentication Service -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M12 0C5.373 0 0 5.373 0 12c0 6.627 5.373 12 12 12 6.627 0 12-5.373 12-12C24 5.373 18.627 0 12 0zm0 22C6.478 22 2 17.522 2 12S6.478 2 12 2s10 4.478 10 10-4.478 10-10 10zm1-15l-2 4h-3l4 4 6-10h-3z" />
          </svg>
          <span class="noShow">Watch Authentication</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Authentication Requests</a></li>
            <li><a href="#">Issue Certificates</a></li>
            <li><a href="#">Suspicious Listings</a></li>
          </div>
        </ul>
      </li>

      <!-- Time Capsule Service -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" fill="#e8eaed">
            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm-1-12h2v6h-2zm0 8h2v2h-2z" />
          </svg>
          <span class="noShow">Time Capsule Service</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Scheduled Deliveries</a></li>
            <li><a href="#">Manage Messages</a></li>
            <li><a href="#">Delivery Adjustments</a></li>
          </div>
        </ul>
      </li>

      <!-- Wear & Resell Program -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" fill="#e8eaed">
            <path d="M12 2a10 10 0 00-10 10c0 5.523 4.477 10 10 10s10-4.477 10-10S17.523 2 12 2zm1 16h-2v-2h2v2zm0-4h-2V7h2v7z" />
          </svg>
          <span class="noShow">Wear & Resell Program</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Manage Participations</a></li>
            <li><a href="#">Track Resell Conditions</a></li>
            <li><a href="#">Set Resell Prices</a></li>
          </div>
        </ul>
      </li>

      <!-- Watch Care Plan -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M12 2C6.478 2 2 6.478 2 12s4.478 10 10 10 10-4.478 10-10S17.522 2 12 2zm-1 17h-2v-2h2v2zm0-4h-2V7h2v8z" />
          </svg>
          <span class="noShow">Watch Care Plan</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Service Requests</a></li>
            <li><a href="#">Service History</a></li>
            <li><a href="#">Service Reminders</a></li>
          </div>
        </ul>
      </li>

      <!-- Events & Promotions -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22c-5.528 0-10-4.472-10-10S6.472 2 12 2s10 4.472 10 10-4.472 10-10 10zm-1-15h2v6h-2zm0 8h2v2h-2z" />
          </svg>
          <span class="noShow">Events & Promotions</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Manage Auctions</a></li>
            <li><a href="#">Online Events</a></li>
            <li><a href="#">Coupons & Discounts</a></li>
          </div>
        </ul>
      </li>

      <!-- Reports & Insights -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M3 3h18v18H3V3zm1 1v16h16V4H4zm5 2h2v8H9V6zm3 0h2v5h-2V6zM6 11h2v3H6v-3zM3 20h18v2H3v-2z" />
          </svg>
          <span class="noShow">Reports & Insights</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Sales Reports</a></li>
            <li><a href="#">Watch Market Value Trends</a></li>
            <li><a href="#">User Engagement Reports</a></li>
          </div>
        </ul>
      </li>

      <!-- NFT Management -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm2 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V5h4v2z" />
          </svg>
          <span class="noShow">NFT Management</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Mint NFTs</a></li>
            <li><a href="#">Transfer NFT Ownership</a></li>
            <li><a href="#">NFT Collection Insights</a></li>
          </div>
        </ul>
      </li>

      <!-- Settings & Security -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" fill="#e8eaed">
            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm1 20h-2v-2h2v2zm0-4h-2v-6h2v6z" />
          </svg>
          <span class="noShow">Settings & Security</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">General Settings</a></li>
            <li><a href="#">Admin Roles & Permissions</a></li>
            <li><a href="#">System Logs</a></li>
            <li><a href="#">Privacy & Security (GPC)</a></li>
          </div>
        </ul>
      </li>

      <!-- Support & Feedback -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M12 0C5.373 0 0 5.373 0 12c0 6.627 5.373 12 12 12 6.627 0 12-5.373 12-12S18.627 0 12 0zm-1 17h-2v-2h2v2zm0-4h-2V7h2v6z" />
          </svg>
          <span class="noShow">Support & Feedback</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Customer Support Tickets</a></li>
            <li><a href="#">Feedback Monitoring</a></li>
          </div>
        </ul>
      </li>

      <!-- Log Management -->

      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#e8eaed">
            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-1 17h-2v-2h2v2zm0-4h-2V7h2v6z" />
          </svg>
          <span class="noShow">Log Management</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
          </svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">System Activity Logs</a></li>
            <li><a href="#">Admin Actions</a></li>
          </div>
        </ul>
      </li>

      <li>
        <a href="../app/controllers/logout.php">
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" transform="scale(-1, 1)">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2" />
          </svg>

          <span class="noShow">Logout</span>
        </a>
      </li>

    </ul>
  </nav>
</body>
</html>