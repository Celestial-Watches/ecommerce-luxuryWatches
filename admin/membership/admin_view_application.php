<?php
session_start();
session_regenerate_id(true);
ob_start();

define('ALLOW_ACCESS', true);
include '../panel.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

require_once '../../app/config/conn.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $conn->prepare("SELECT * FROM membership_application WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$application = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Application Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            width: -webkit-fill-available;
            border: none;
        }

        .card-header {
            background-color: #000;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 1.75rem;
            font-weight: 600;
        }

        .card-body {
            padding: 30px;
            background-color: #fff;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-static {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            color: #555;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            color: #000;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid #000;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            Application #<?= $application['id'] ?>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Full Name:</label>
                <p class="form-control-static"><?= htmlspecialchars($application['full_name']) ?></p>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <p class="form-control-static"><?= htmlspecialchars($application['email']) ?></p>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <p class="form-control-static"><?= htmlspecialchars($application['phone']) ?></p>
            </div>
            <div class="form-group">
                <label>Referral Code:</label>
                <p class="form-control-static"><?= $application['referral_code'] ? htmlspecialchars($application['referral_code']) : 'None' ?></p>
            </div>
            <div class="form-group">
                <label>Background:</label>
                <p class="form-control-static"><?= nl2br(htmlspecialchars($application['background'])) ?></p>
            </div>
            <div class="form-group">
                <label>Motivation:</label>
                <p class="form-control-static"><?= nl2br(htmlspecialchars($application['motivation'])) ?></p>
            </div>
            <a href="view-member.php" class="btn-back">&laquo; Back to List</a>
        </div>
    </div>
    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>
</body>

</html>
<?php
ob_end_flush();
?>