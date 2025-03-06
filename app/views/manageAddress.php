<?php
session_start();
require '../../vendor/autoload.php';
include '../config/conn.php';

// Check if user is logged in
$isUserLoggedIn = isset($_SESSION["user"]) && isset($_SESSION["user_id"]);
$userId = $_SESSION['user_id'] ?? null;

$userAddresses = [];
if ($isUserLoggedIn) {
    $userIdEscaped = mysqli_real_escape_string($conn, $userId);
    $query = "SELECT * FROM address WHERE user_id = '$userIdEscaped'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $userAddresses[] = $row;
        }
    }
}

// Handle form submissions
$successMessage = '';
$errorMessage   = '';

if ($isUserLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    // Gather POST data
    $action    = $_POST['action'] ?? '';
    $label     = mysqli_real_escape_string($conn, $_POST['label']     ?? '');
    $name      = mysqli_real_escape_string($conn, $_POST['name']      ?? '');
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']     ?? '');
    $address1  = mysqli_real_escape_string($conn, $_POST['address1']  ?? '');
    $address2  = mysqli_real_escape_string($conn, $_POST['address2']  ?? '');
    $city      = mysqli_real_escape_string($conn, $_POST['city']      ?? '');
    $state     = mysqli_real_escape_string($conn, $_POST['state']     ?? '');
    $zip       = mysqli_real_escape_string($conn, $_POST['zip']       ?? '');
    $country   = mysqli_real_escape_string($conn, $_POST['country']   ?? '');
    $isDefault = isset($_POST['is_default']) ? 1 : 0;

    if ($action === 'add') {
        // Add new address
        $query = "
            INSERT INTO addresses
            (user_id, label, name, phone, address1, address2, city, state, zip, country, is_default)
            VALUES
            ('$userId', '$label', '$name', '$phone', '$address1', '$address2', '$city', '$state', '$zip', '$country', '$isDefault')
        ";
        if (mysqli_query($conn, $query)) {
            $successMessage = "New address has been added.";
        } else {
            $errorMessage = "Failed to add address: " . mysqli_error($conn);
        }
    } elseif ($action === 'delete') {
        // Delete address
        $addressId = mysqli_real_escape_string($conn, $_POST['address_id'] ?? '');
        $query = "DELETE FROM addresses WHERE id='$addressId' AND user_id='$userId'";
        if (mysqli_query($conn, $query)) {
            $successMessage = "Address has been deleted.";
        } else {
            $errorMessage = "Failed to delete address: " . mysqli_error($conn);
        }
    } elseif ($action === 'update') {
        // Update address
        $addressId = mysqli_real_escape_string($conn, $_POST['address_id'] ?? '');
        $query = "
            UPDATE addresses
            SET label    = '$label',
                name     = '$name',
                phone    = '$phone',
                address1 = '$address1',
                address2 = '$address2',
                city     = '$city',
                state    = '$state',
                zip      = '$zip',
                country  = '$country',
                is_default = '$isDefault'
            WHERE id='$addressId' AND user_id='$userId'
        ";
        if (mysqli_query($conn, $query)) {
            $successMessage = "Address has been updated.";
        } else {
            $errorMessage = "Failed to update address: " . mysqli_error($conn);
        }
    }
}

// Generate new CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Addresses - Celestial Watches</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
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
            background-color: #F7F9FB;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            cursor: pointer;
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
        }

        /* MAIN CONTENT CONTAINER */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            min-height: 100vh;
            margin-left: 280px;
            /* accommodate nav-bar width */
            padding: 2rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .alert-error {
            background-color: #FEE2E2;
            color: #B91C1C;
        }

        /* PAGE TITLE */
        h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* ADD NEW ADDRESS SECTION */
        .add-address-section {
            margin-bottom: 2rem;
        }

        .add-address-btn {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: background 0.3s;
            cursor: pointer;
        }

        .add-address-btn:hover {
            background-color: #333;
        }

        .add-address-form {
            display: none;
            /* toggled */
            background: #fff;
            border-radius: 6px;
            padding: 1.5rem;
            margin-top: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .add-address-form h2 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="checkbox"] {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .default-checkbox {
            display: inline-flex;
            align-items: center;
            margin-top: 0.5rem;
        }

        .default-checkbox input {
            width: auto;
            margin-right: 0.4rem;
        }

        .submit-btn {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background-color: #333;
        }

        /* ADDRESS LIST */
        .address-list {
            display: grid;
            grid-template-columns: repeat(minmax(260px, 1fr));
            gap: 1rem;
        }

        .address-card {
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            position: relative;
        }

        .address-card h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .address-card p {
            font-size: 0.9rem;
            color: #555;
            line-height: 1.4;
            margin-bottom: 1rem;
        }

        .default-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background-color: #000;
            color: #fff;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .button-row {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .delete-btn,
        .edit-btn {
            background: #E5E7EB;
            color: #333;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            transition: background 0.3s;
            cursor: pointer;
        }

        .delete-btn:hover,
        .edit-btn:hover {
            background: #ccc;
        }

        /* EDIT FORM (inline) */
        .edit-form {
            display: none;
            margin-top: 1rem;
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 6px;
        }

        .edit-form h4 {
            margin-bottom: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
        }

        .edit-form .form-group input[type="text"],
        .edit-form .form-group input[type="checkbox"] {
            background: #fff;
        }

        .update-btn {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            transition: background 0.3s;
        }

        .update-btn:hover {
            background-color: #333;
        }

        /* Responsive for nav-bar on small screens */
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

            .container {
                margin-left: 0;
                padding-top: 4rem;
            }
        }

        @media (max-width: 600px) {
            .address-list {
                grid-template-columns: 1fr;
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
            <li><a href="user-dashboard.php">Account</a></li>
            <li><a href="#">NFT Customisation</a></li>
            <li><a href="selling.php">Sell & Exchange</a></li>
            <li><a href="#">Auction Participation</a></li>
            <li><a href="transaction.php">Transaction History</a></li>
            <li><a href="manageAddress.php" class="active">Manage Address</a></li>
            <li><a href="#">Payment Methods</a></li>
        </ul>

        <h3>System</h3>
        <ul class="nav-menu">
            <li><a href="#">Notifications</a></li>
            <li><a href="#">Preferences</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container">
        <!-- Alert Messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= $errorMessage ?></div>
        <?php endif; ?>

        <h1>Manage Addresses</h1>

        <?php if ($isUserLoggedIn): ?>
            <!-- Add Address Section -->
            <div class="add-address-section">
                <button class="add-address-btn" onclick="toggleAddForm()">
                    <i class="ri-add-line" style="margin-right:4px;"></i> Add New Address
                </button>

                <!-- Hidden Add Address Form -->
                <div class="add-address-form" id="addAddressForm">
                    <h2>Add New Address</h2>
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="action" value="add">

                        <div class="form-group">
                            <label for="label">Address Label (e.g. Home, Office)</label>
                            <input type="text" id="label" name="label" placeholder="Home" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Recipient Name</label>
                            <input type="text" id="name" name="name" placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" placeholder="123-456-7890" required>
                        </div>

                        <div class="form-group">
                            <label for="address1">Address Line 1</label>
                            <input type="text" id="address1" name="address1" placeholder="123 Main St" required>
                        </div>

                        <div class="form-group">
                            <label for="address2">Address Line 2</label>
                            <input type="text" id="address2" name="address2" placeholder="Apt, Suite, etc.">
                        </div>

                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" placeholder="New York" required>
                        </div>

                        <div class="form-group">
                            <label for="state">State/Province</label>
                            <input type="text" id="state" name="state" placeholder="NY" required>
                        </div>

                        <div class="form-group">
                            <label for="zip">ZIP/Postal Code</label>
                            <input type="text" id="zip" name="zip" placeholder="10001" required>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" placeholder="USA" required>
                        </div>

                        <div class="form-group default-checkbox">
                            <input style="width: auto !important;" type="checkbox" id="editIsDefault" name="is_default"> 
                            <label for="editIsDefault" style="width: auto !important; margin:0; font-size:0.85rem;">Set as Default Address</label>
                        </div>

                        <button style="display: block;" type="submit" class="submit-btn">Add Address</button>
                    </form>
                </div>
            </div>

            <!-- Address List -->
            <?php if (empty($userAddresses)): ?>
                <p>No addresses found. Please add a new address.</p>
            <?php else: ?>
                <div class="address-list">
                    <?php foreach ($userAddresses as $addr): ?>
                        <div class="address-card">
                            <?php if ($addr['is_default']): ?>
                                <div class="default-badge">Default</div>
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($addr['label']) ?></h3>
                            <p>
                                <?= htmlspecialchars($addr['name']) ?><br>
                                <?= htmlspecialchars($addr['address1']) ?>
                                <?php if (!empty($addr['address2'])): ?>
                                    , <?= htmlspecialchars($addr['address2']) ?>
                                <?php endif; ?>
                                <br>
                                <?= htmlspecialchars($addr['city']) ?>,
                                <?= htmlspecialchars($addr['state']) ?>
                                <?= htmlspecialchars($addr['zip']) ?><br>
                                <?= htmlspecialchars($addr['country']) ?><br>
                                Phone: <?= htmlspecialchars($addr['phone']) ?>
                            </p>

                            <div class="button-row">
                                <!-- Edit Button -->
                                <button type="button" class="edit-btn"
                                    onclick="toggleEditForm(<?= $addr['id'] ?>)">
                                    Edit
                                </button>

                                <!-- Delete Form -->
                                <form method="POST" style="margin-top:0.5rem;">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </div>

                            <!-- Inline Edit Form (hidden by default) -->
                            <div class="edit-form" id="editForm-<?= $addr['id'] ?>">
                                <h4>Edit Address</h4>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">

                                    <div class="form-group">
                                        <label>Address Label</label>
                                        <input type="text" name="label"
                                            value="<?= htmlspecialchars($addr['label']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Recipient Name</label>
                                        <input type="text" name="name"
                                            value="<?= htmlspecialchars($addr['name']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone"
                                            value="<?= htmlspecialchars($addr['phone']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Address Line 1</label>
                                        <input type="text" name="address1"
                                            value="<?= htmlspecialchars($addr['address1']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Address Line 2</label>
                                        <input type="text" name="address2"
                                            value="<?= htmlspecialchars($addr['address2']) ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" name="city"
                                            value="<?= htmlspecialchars($addr['city']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>State/Province</label>
                                        <input type="text" name="state"
                                            value="<?= htmlspecialchars($addr['state']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>ZIP/Postal Code</label>
                                        <input type="text" name="zip"
                                            value="<?= htmlspecialchars($addr['zip']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" name="country"
                                            value="<?= htmlspecialchars($addr['country']) ?>" required>
                                    </div>

                                    <div class="form-group default-checkbox">
                                        <input style="width: auto !important;" type="checkbox" name="is_default"
                                            <?= $addr['is_default'] ? 'checked' : '' ?>>
                                        <label style="width: auto !important; margin:0; font-size:0.85rem;">
                                            Set as Default Address
                                        </label>
                                    </div>

                                    <button style="display: block;" type="submit" class="update-btn">Update Address</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>Please <a href="../../app/controllers/login.php" style="color: #000; text-decoration: underline;">log in</a> to manage your addresses.</p>
        <?php endif; ?>
    </div>

    <script>
        // Toggle Nav Bar on mobile
        function toggleNavBar() {
            var navBar = document.getElementById('navBar');
            navBar.classList.toggle('active');
        }

        // Show/hide the "Add New Address" form
        function toggleAddForm() {
            var form = document.getElementById('addAddressForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        // Toggle each address's edit form
        function toggleEditForm(addressId) {
            var formId = 'editForm-' + addressId;
            var form = document.getElementById(formId);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>

</html>