<?php
session_start();
session_regenerate_id(true);
ob_start();
define('ALLOW_ACCESS', true);
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
  header("Location: ../../app/controllers/login.php");
  exit();
}
require '../../app/config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Process form submission: update the offer record
  $offerId            = $_POST['offer_id'];
  $discountCode       = trim($_POST['discount_code']);
  $discountName       = trim($_POST['discount_name']);
  $discountPercentage = trim($_POST['discount_percentage']);
  $activeStatus       = isset($_POST['active_status']) ? intval($_POST['active_status']) : 0;
  $paymentMethods     = trim($_POST['payment_methods']);
  $description        = trim($_POST['description']);
  $startDate          = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
  $endDate            = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

  $stmt = $conn->prepare("UPDATE discount_name 
                           SET discount_code = ?, discount_name = ?, discount_percentage = ?, active_status = ?, applicable_payment_methods = ?, description = ?, start_date = ?, end_date = ?, updated_at = NOW()
                           WHERE id = ?");
  // Types: s = string, d = double, i = integer.
  $stmt->bind_param("ssdissssi", $discountCode, $discountName, $discountPercentage, $activeStatus, $paymentMethods, $description, $startDate, $endDate, $offerId);
  $stmt->execute();
  $stmt->close();

  header("Location: admin_offers.php");
  exit();
} else {
  // GET request: retrieve the offer details to display in the edit form.
  if (isset($_GET['id'])) {
    $offerId = intval($_GET['id']);
    if ($offerId > 0) {
      $stmt = $conn->prepare("SELECT * FROM discount_name WHERE id = ?");
      $stmt->bind_param("i", $offerId);
      $stmt->execute();
      $result = $stmt->get_result();
      $offer = $result->fetch_assoc();
      if (!$offer) {
        echo "Offer not found.";
        exit;
      }
      $stmt->close();
    } else {
      echo "Invalid offer ID.";
      exit;
    }
  } else {
    echo "No offer ID specified.";
    exit;
  }
}
include '../panel.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Edit Offer</title>
  <link rel="stylesheet" href="/src/assets/css/panel.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    ul {
      padding-left: 0 !important;
    }
  </style>
  <style>
    .offer-form-container {
      padding: 25px;
      background-color: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .offer-form-container h1.page-title {
      font-size: 2rem;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .offer-card-body form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .offer-card-body form .full-width {
      grid-column: span 2;
    }

    label.form-label::after {
      content: ' *';
      color: red;
      font-weight: normal;
    }

    .form-label.optional::after {
      content: '';
    }

    .action-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 25px;
      grid-column: span 2;
    }

    @media (max-width: 768px) {
      .offer-card-body form {
        grid-template-columns: 1fr;
      }

      .action-buttons {
        justify-content: center;
      }
    }
  </style>

  <div class="offer-form-container">
    <h1 class="page-title"><i class="bi bi-pencil-square"></i> Edit Offer</h1>
    <form action="edit_offer.php" method="post">
      <input type="hidden" name="offer_id" value="<?php echo htmlspecialchars($offer['id']); ?>">

      <div>
        <label for="discount_code" class="form-label">Discount Code</label>
        <input type="text" name="discount_code" id="discount_code" class="form-control" value="<?php echo htmlspecialchars($offer['discount_code']); ?>" required>
      </div>

      <div>
        <label for="discount_name" class="form-label">Offer Name</label>
        <input type="text" name="discount_name" id="discount_name" class="form-control" value="<?php echo htmlspecialchars($offer['discount_name']); ?>" required>
      </div>

      <div>
        <label for="discount_percentage" class="form-label">Discount (%)</label>
        <input type="number" step="0.01" name="discount_percentage" id="discount_percentage" class="form-control" value="<?php echo htmlspecialchars($offer['discount_percentage']); ?>" required>
      </div>

      <div>
        <label for="active_status" class="form-label optional">Status</label>
        <select name="active_status" id="active_status" class="form-select">
          <option value="1" <?php if ($offer['active_status'] == 1) echo 'selected'; ?>>Active</option>
          <option value="0" <?php if ($offer['active_status'] == 0) echo 'selected'; ?>>Inactive</option>
        </select>
      </div>

      <div class="full-width">
        <label for="payment_methods" class="form-label">Applicable Payment Methods</label>
        <input type="text" name="payment_methods" id="payment_methods" class="form-control" value="<?php echo htmlspecialchars($offer['applicable_payment_methods']); ?>" placeholder="e.g., credit_card,debit_card" required>
      </div>

      <div class="full-width">
        <label for="description" class="form-label optional">Description</label>
        <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($offer['description']); ?></textarea>
      </div>

      <div>
        <label for="start_date" class="form-label optional">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo htmlspecialchars($offer['start_date']); ?>">
      </div>

      <div>
        <label for="end_date" class="form-label optional">End Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo htmlspecialchars($offer['end_date']); ?>">
      </div>

      <div class="action-buttons">
        <a href="admin_offers.php" class="btn btn-outline-danger">
          <i class="bi bi-x-circle"></i> Cancel
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save2"></i> Save Changes
        </button>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="/src/assets/js/panelNav.js" async></script>
  <script type="text/javascript" src="/src/assets/js/navigation.js" async></script>
  </body>

</html>
<?php ob_end_flush(); ?>