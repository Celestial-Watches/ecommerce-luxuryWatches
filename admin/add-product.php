<?php
define('ALLOW_ACCESS', true);
include 'panel.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../app/controllers/login.php"); 
    exit();
  }
  

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    // Redirect to login if not authenticated
    header("Location: ../app/controllers/login.php");
    exit();
}

define('BASE_PATH', __DIR__ . '/..');
require_once BASE_PATH . '/app/config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pcategory = trim($_POST['product-category']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $year = trim($_POST['year']);
    $ref_code = trim($_POST['ref_code']);
    $button_name = trim($_POST['button_name']);
    $icon = trim($_POST['icon']);
    $image_url = trim($_POST['image_url']);
    $upload_image = $_FILES['upload_image'];
    $brand = trim($_POST['brand']);
    $tags = trim($_POST['tags']);
    $stock = trim($_POST['stock']);
    $description = trim($_POST['description']);

    // Basic validation
    if (empty($pcategory) || empty($name) || empty($price) || empty($year) || empty($ref_code) || empty($brand) || empty($tags) || empty($stock) || empty($description)) {
        $error = "All fields are required.";
    } else {
        // Handle image upload or URL
        if (isset($_POST['image_option']) && $_POST['image_option'] == 'url') {
            // Use the image URL
            $image = filter_var($image_url, FILTER_VALIDATE_URL) ? $image_url : '';
        } else {
            // Upload the image
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($upload_image["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check file size and type
            if ($upload_image["size"] > 500000) {
                $error = "Sorry, your file is too large.";
            } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($upload_image["tmp_name"], $target_file)) {
                    $image = $target_file;
                } else {
                    $error = "Sorry, there was an error uploading your file.";
                }
            }
        }

        // If no error, insert the product into the database
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO products (product_category,name, brand, price, year, ref_code, tags, stock, description, button_name, icon, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $pcategory, $name, $brand, $price, $year, $ref_code, $tags, $stock, $description, $button_name, $icon, $image);
            if ($stmt->execute()) {
                $success = "Product added successfully!";
            } else {
                $error = "Error adding product.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../src/assets/css/panel.css">
</head>

<body>



    <div class="container mt-5">
        <h2 class="text-center" style="color: black;">Add New Product</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label style="color: black;" for="name">Product Category</label>
                <input type="text" name="product-category" id="product-category" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="name">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="brand">Brand</label>
                <input type="text" name="brand" id="brand" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="year">Year</label>
                <input type="text" name="year" id="year" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="ref_code">Reference Code</label>
                <input type="text" name="ref_code" id="ref_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="tags">Tags (comma separated)</label>
                <input type="text" name="tags" id="tags" class="form-control" placeholder="e.g., luxury, limited edition" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="stock">Stock Quantity</label>
                <input type="number" name="stock" id="stock" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label style="color: black;" for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label style="color: black;" style="color: black;" for="button_name">Button Name</label>
                <select name="button_name" id="button_name" class="form-control" required>
                    <option value="" disabled selected>Select a button name</option>
                    <option value="Add to cart">Add to cart</option>
                    <option value="Send Message">Send Message</option>
                </select>
            </div>
            <div class="form-group">
                <label style="color: black;" style="color: black;" for="icon">Icon Class</label>
                <select name="icon" id="icon" class="form-control" required>
                    <option value="" disabled selected>Select an icon class</option>
                    <option value="ri-shopping-cart-2-line">ri-shopping-cart-2-line</option>
                    <option value="ri-message-2-line">ri-message-2-line</option>
                </select>
            </div>
            <div class="form-group">
                <label style="color: black;" for="image_option">Select Image Type:</label>
                <div>
                    <input type="radio" name="image_option" value="url" id="url_option" onchange="toggleImageFields()" required>
                    <label style="color: black;" for="url_option">Image URL</label>
                    <input type="radio" name="image_option" value="upload" id="upload_option" onchange="toggleImageFields()" required>
                    <label style="color: black;" for="upload_option">Upload Image</label>
                </div>
            </div>
            <div id="url_field" class="form-group" style="display:none;">
                <label style="color: black;" for="image_url">Image URL</label>
                <input type="url" name="image_url" id="image_url" class="form-control" placeholder="Enter image URL">
            </div>
            <div id="upload_field" class="form-group" style="display:none;">
                <label style="color: black;" for="upload_image">Upload Image</label>
                <input type="file" name="upload_image" id="upload_image" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>

    </div>

    <script>
        function toggleImageFields() {
            const urlField = document.getElementById('url_field');
            const uploadField = document.getElementById('upload_field');
            const urlOption = document.getElementById('url_option');
            if (urlOption.checked) {
                urlField.style.display = 'block';
                uploadField.style.display = 'none';
            } else {
                urlField.style.display = 'none';
                uploadField.style.display = 'block';
            }
        }
    </script>
    <script type="text/javascript" src="../src/assets/js/panelNav.js" defer></script>
    <script type="text/javascript" src="../src/assets/js/navigation.js"></script>
</body>

</html>

<?php
$conn->close();
?>