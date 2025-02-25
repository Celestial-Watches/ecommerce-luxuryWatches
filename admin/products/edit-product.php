<?php
session_start();
session_regenerate_id(true);
ob_start();

define('ALLOW_ACCESS', true);
include '../panel.php';

if (
  !isset($_SESSION['user']) ||
  !isset($_SESSION['admin']) ||
  !isset($_SESSION['authenticated']) ||
  $_SESSION['authenticated'] !== true ||
  $_SESSION['admin'] !== true
) {
  header("Location: ../../app/controllers/login.php");
  exit();
}

require_once '../../app/config/conn.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
  echo "No product specified.";
  exit();
}

$product_id = intval($_GET['id']);

// Retrieve product from products table
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
  echo "Product not found.";
  exit();
}
$product = $result->fetch_assoc();
$stmt->close();

// Retrieve product_details from product_details table (if exists)
$stmt = $conn->prepare("SELECT * FROM product_details WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result_details = $stmt->get_result();
if ($result_details->num_rows === 1) {
  $details = $result_details->fetch_assoc();
} else {
  // Initialize empty values if no details record exists.
  $details = [
    'brand'             => $product['brand'],
    'model'             => '',
    'reference'         => $product['ref_code'],
    'glass'             => '',
    'dial_numerals'     => '',
    'movement'          => '',
    'water_resistance'  => '',
    'clasp_type'        => '',
    'power_reserve'     => '',
    'bracelet_color'    => '',
    'gender'            => '',
    'case_diameter'     => '',
    'case_material'     => '',
    'clasp_material'    => '',
    'bezel_material'    => '',
    'bracelet_material' => '',
    'functions'         => ''
  ];
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // --- Update products table ---
  $image_url        = trim($_POST['image_url']);
  $name             = trim($_POST['name']);
  $price            = trim($_POST['price']);
  $year             = intval($_POST['year']);
  $ref_code         = trim($_POST['ref_code']);
  $button_name      = trim($_POST['button_name']);
  $icon             = trim($_POST['icon']);
  $brand            = trim($_POST['brand']);
  $description      = trim($_POST['description']);
  $tags             = trim($_POST['tags']);
  $stock            = intval($_POST['stock']);
  $product_category = trim($_POST['product_category']);

  $stmt = $conn->prepare("UPDATE products SET image_url = ?, name = ?, price = ?, year = ?, ref_code = ?, button_name = ?, icon = ?, brand = ?, description = ?, tags = ?, stock = ?, product_category = ? WHERE id = ?");
  $stmt->bind_param("sssissssssssi", $image_url, $name, $price, $year, $ref_code, $button_name, $icon, $brand, $description, $tags, $stock, $product_category, $product_id);
  if ($stmt->execute()) {
    $stmt->close();

    // --- Update product_details table ---
    $details_brand             = $product['brand'];
    $details_model             = trim($_POST['details_model']);
    $details_reference         = $product['ref_code'];
    $details_glass             = trim($_POST['details_glass']);
    $details_dial_numerals     = trim($_POST['details_dial_numerals']);
    $details_movement          = trim($_POST['details_movement']);
    $details_water_resistance  = trim($_POST['details_water_resistance']);
    $details_clasp_type        = trim($_POST['details_clasp_type']);
    $details_power_reserve     = trim($_POST['details_power_reserve']);
    $details_bracelet_color    = trim($_POST['details_bracelet_color']);
    $details_gender            = trim($_POST['details_gender']);
    $details_case_diameter     = trim($_POST['details_case_diameter']);
    $details_case_material     = trim($_POST['details_case_material']);
    $details_clasp_material    = trim($_POST['details_clasp_material']);
    $details_bezel_material    = trim($_POST['details_bezel_material']);
    $details_bracelet_material = trim($_POST['details_bracelet_material']);
    $details_functions         = trim($_POST['details_functions']);

    $stmt_check = $conn->prepare("SELECT id FROM product_details WHERE product_id = ?");
    $stmt_check->bind_param("i", $product_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows === 1) {
      $stmt_update = $conn->prepare("UPDATE product_details SET brand = ?, model = ?, reference = ?, glass = ?, dial_numerals = ?, movement = ?, water_resistance = ?, clasp_type = ?, power_reserve = ?, bracelet_color = ?, gender = ?, case_diameter = ?, case_material = ?, clasp_material = ?, bezel_material = ?, bracelet_material = ?, functions = ? WHERE product_id = ?");
      $stmt_update->bind_param(
        "sssssssssssssssssi",
        $details_brand,
        $details_model,
        $details_reference,
        $details_glass,
        $details_dial_numerals,
        $details_movement,
        $details_water_resistance,
        $details_clasp_type,
        $details_power_reserve,
        $details_bracelet_color,
        $details_gender,
        $details_case_diameter,
        $details_case_material,
        $details_clasp_material,
        $details_bezel_material,
        $details_bracelet_material,
        $details_functions,
        $product_id
      );
      $stmt_update->execute();
      $stmt_update->close();
    } else {
      $stmt_insert = $conn->prepare("INSERT INTO product_details (product_id, brand, model, reference, glass, dial_numerals, movement, water_resistance, clasp_type, power_reserve, bracelet_color, gender, case_diameter, case_material, clasp_material, bezel_material, bracelet_material, functions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt_insert->bind_param(
        "isssssssssssssssss",
        $product_id,
        $details_brand,
        $details_model,
        $details_reference,
        $details_glass,
        $details_dial_numerals,
        $details_movement,
        $details_water_resistance,
        $details_clasp_type,
        $details_power_reserve,
        $details_bracelet_color,
        $details_gender,
        $details_case_diameter,
        $details_case_material,
        $details_clasp_material,
        $details_bezel_material,
        $details_bracelet_material,
        $details_functions
      );
      $stmt_insert->execute();
      $stmt_insert->close();
    }
    $stmt_check->close();

    header("Location: product-list.php?update=success");
    exit();
  } else {
    $error = "Failed to update product. " . $stmt->error;
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Watch</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../src/assets/css/panel.css">
  <style>
    body {
      background-color: #fff;
      color: #000;
    }

    .containerEditProduct {
      max-width: 100%;
      margin: 20px auto;
      padding: 20px;
    }

    .card {
      margin-bottom: 20px;
    }

    .card-header {
      background-color: rgb(0, 0, 0);
      color: #fff;
      font-size: 1.2rem;
    }

    .info-row {
      display: flex;
      flex-wrap: wrap;
    }

    .info-col {
      padding: 10px;
    }

    .info-main {
      flex: 0 0 66.66%;
      max-width: 66.66%;
    }

    .info-aside {
      flex: 0 0 33.33%;
      max-width: 33.33%;
      border-left: 1px solid #ddd;
      padding-left: 20px;
    }

    .image-upload-box {
      border: 2px dashed #ced4da;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      background: #f8f9fa;
    }

    .img-preview {
      width: 100%;
      border: 1px solid #ddd;
      padding: 5px;
      border-radius: 4px;
      background-color: #f8f8f8;
    }

    .img-preview img {
      width: 100%;
      height: auto;
      display: block;
    }

    /* Grid design layout for Watch Details section */
    .watch-details-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    .watch-details-card {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 15px;
    }

    .watch-details-card h6 {
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <div class="containerEditProduct container-fluid">
    <h2 class="text-center mb-4">Edit Watch</h2>
    <?php if (isset($error)) {
      echo '<div class="alert alert-danger">' . $error . '</div>';
    } ?>
    <form method="POST" action="">
      <!-- Product Information Section -->
      <div class="card">
        <div class="card-header">Product Information</div>
        <div class="card-body">
          <div class="info-row">
            <!-- Main Information -->
            <div class="info-col info-main">
              <div class="form-group">
                <label for="image_url">Image URL</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>">
              </div>
              <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="price">Price in dollars ($)</label>
                  <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="year">Year</label>
                  <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($product['year']); ?>" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="ref_code">Reference Code</label>
                  <input type="text" class="form-control" id="ref_code" name="ref_code" value="<?php echo htmlspecialchars($product['ref_code']); ?>" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="button_name">Button Name</label>
                  <input type="text" class="form-control" id="button_name" name="button_name" value="<?php echo htmlspecialchars($product['button_name']); ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="icon">Icon</label>
                  <select class="form-control" id="icon" name="icon">
                    <option value="ri-shopping-cart-2-line" <?php echo ($product['icon'] == 'ri-shopping-cart-2-line') ? 'selected' : ''; ?>>Shopping Cart</option>
                    <option value="ri-message-2-line" <?php echo ($product['icon'] == 'ri-message-2-line') ? 'selected' : ''; ?>>Message</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="brand">Product Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required>
              </div>
              <div class="form-group">
                <label for="description">Product Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
              </div>
              <div class="form-group">
                <label for="tags">Product Tags</label>
                <textarea class="form-control" id="tags" name="tags" rows="2"><?php echo htmlspecialchars($product['tags']); ?></textarea>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="stock">Stock</label>
                  <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="product_category">Product Category</label>
                  <input type="text" class="form-control" id="product_category" name="product_category" value="<?php echo htmlspecialchars($product['product_category']); ?>" required>
                </div>
              </div>
            </div>

            <!-- Aside Column for Image Preview -->
            <aside class="info-col info-aside image-upload-box">
              <h5>Image Preview</h5>
              <div class="img-preview" id="img_preview_container">
                <?php if (!empty($product['image_url'])): ?>
                  <img id="img_preview" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Image Preview">
                <?php else: ?>
                  <img id="img_preview" src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
                <?php endif; ?>
              </div>
            </aside>
          </div>
        </div>
      </div>


      <!-- Product Details Section -->
      <div class="card">
        <div class="card-header">Watch Details</div>
        <div class="card-body">
          <div class="watch-details-grid">
            <!-- Basic Info Card -->
            <div class="watch-details-card">
              <h6>Basic Info</h6>
              <div class="form-group">
                <label>Details Brand</label>
                <input type="text" class="form-control" name="details_brand" value="<?= htmlspecialchars($details['brand']) ?>">
              </div>
              <div class="form-group">
                <label>Model</label>
                <input type="text" class="form-control" name="details_model" value="<?= htmlspecialchars($details['model']) ?>">
              </div>
              <div class="form-group">
                <label>Reference</label>
                <input type="text" class="form-control" name="details_reference" value="<?= htmlspecialchars($details['reference']) ?>">
              </div>
            </div>
            <!-- Design & Movement Card with Aside Layout -->
            <div class="watch-details-card">
              <h6>Design & Movement</h6>
              <div class="d-flex">
                <div class="flex-grow-1 pr-2">
                  <div class="form-group">
                    <label>Glass</label>
                    <input type="text" class="form-control" name="details_glass" value="<?= htmlspecialchars($details['glass']) ?>">
                  </div>
                  <div class="form-group">
                    <label>Dial Numerals</label>
                    <input type="text" class="form-control" name="details_dial_numerals" value="<?= htmlspecialchars($details['dial_numerals']) ?>">
                  </div>
                  <div class="form-group">
                    <label>Movement</label>
                    <input type="text" class="form-control" name="details_movement" value="<?= htmlspecialchars($details['movement']) ?>">
                  </div>
                </div>
                <aside class="border-left pl-2" style="min-width:150px;">
                  <div class="form-group">
                    <label>Water Resistance</label>
                    <input type="text" class="form-control" name="details_water_resistance" value="<?= htmlspecialchars($details['water_resistance']) ?>">
                  </div>
                  <div class="form-group">
                    <label>Clasp Type</label>
                    <input type="text" class="form-control" name="details_clasp_type" value="<?= htmlspecialchars($details['clasp_type']) ?>">
                  </div>
                </aside>
              </div>
            </div>
            <!-- Case & Strap Card -->
            <div class="watch-details-card" style="grid-column: span 2;">
              <h6>Case & Strap</h6>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label>Power Reserve</label>
                  <input type="text" class="form-control" name="details_power_reserve" value="<?= htmlspecialchars($details['power_reserve']) ?>">
                </div>
                <div class="form-group col-md-3">
                  <label>Bracelet Color</label>
                  <input type="text" class="form-control" name="details_bracelet_color" value="<?= htmlspecialchars($details['bracelet_color']) ?>">
                </div>
                <div class="form-group col-md-3">
                  <label>Gender</label>
                  <input type="text" class="form-control" name="details_gender" value="<?= htmlspecialchars($details['gender']) ?>">
                </div>
                <div class="form-group col-md-3">
                  <label>Case Diameter</label>
                  <input type="text" class="form-control" name="details_case_diameter" value="<?= htmlspecialchars($details['case_diameter']) ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Case Material</label>
                  <input type="text" class="form-control" name="details_case_material" value="<?= htmlspecialchars($details['case_material']) ?>">
                </div>
                <div class="form-group col-md-6">
                  <label>Clasp Material</label>
                  <input type="text" class="form-control" name="details_clasp_material" value="<?= htmlspecialchars($details['clasp_material']) ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Bezel Material</label>
                  <input type="text" class="form-control" name="details_bezel_material" value="<?= htmlspecialchars($details['bezel_material']) ?>">
                </div>
                <div class="form-group col-md-6">
                  <label>Bracelet Material</label>
                  <input type="text" class="form-control" name="details_bracelet_material" value="<?= htmlspecialchars($details['bracelet_material']) ?>">
                </div>
              </div>
            </div>
            <!-- Functions Card -->
            <div class="watch-details-card" style="grid-column: span 2;">
              <h6>Functions</h6>
              <div class="form-group">
                <textarea class="form-control" name="details_functions" rows="3"><?= htmlspecialchars($details['functions']) ?></textarea>
              </div>
            </div>
          </div> 
        </div>
      </div>
  </div>
  <div class="text-right mb-2">
    <a href="view-listing.php" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary bg-dark">Update Product</button>
  </div>
  </form>
  </div>

  <!-- JavaScript to update image preview as the URL is edited -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
  <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>
  <script>
    // When the image URL input changes, update the preview
    document.getElementById('image_url').addEventListener('input', function() {
      var url = this.value.trim();
      var imgPreview = document.getElementById('img_preview');
      if (url) {
        imgPreview.src = url;
      } else {
        imgPreview.src = 'https://via.placeholder.com/300x200?text=No+Image';
      }
    });
  </script>
</body>

</html>