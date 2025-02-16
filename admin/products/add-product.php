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

require '../../app/config/conn.php';

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
    $material = trim($_POST['material']);
    $glass = trim($_POST['glass']);
    $dial_numerals = trim($_POST['dial_numerals']);
    $movement = trim($_POST['movement']);
    $water_resistance = trim($_POST['water_resistance']);
    $clasp_type = trim($_POST['clasp_type']);
    $power_reserve = trim($_POST['power_reserve']);
    $bracelet_color = trim($_POST['bracelet_color']);
    $gender = trim($_POST['gender']);
    $case_diameter = trim($_POST['case_diameter']);
    $case_material = trim($_POST['case_material']);
    $clasp_material = trim($_POST['clasp_material']);
    $bezel_material = trim($_POST['bezel_material']);
    $bracelet_material = trim($_POST['bracelet_material']);
    $functions = trim($_POST['functions']);

    // validation
    if (empty($pcategory) || empty($name) || empty($price) || empty($year) || empty($ref_code) || empty($brand) || empty($tags) || empty($stock) || empty($description) || empty($material) || empty($glass) || empty($dial_numerals) || empty($movement) || empty($water_resistance) || empty($clasp_type) || empty($power_reserve) || empty($bracelet_color) || empty($gender) || empty($case_diameter) || empty($case_material) || empty($clasp_material) || empty($bezel_material) || empty($bracelet_material) || empty($functions)) {
        $error = "All fields are required.";
    } else {
        // Handle image upload or URL
        if (isset($_POST['image_option']) && $_POST['image_option'] == 'url') {
            // Use the image URL
            $image = filter_var($image_url, FILTER_VALIDATE_URL) ? $image_url : '';
        } else {
            // Upload the image
            $target_dir = "../uploads/";
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
                $product_id = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO product_details (product_id, material, glass, dial_numerals, movement, water_resistance, clasp_type, power_reserve, bracelet_color, gender, case_diameter, case_material, clasp_material, bezel_material, bracelet_material, functions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssssss", $product_id, $material, $glass, $dial_numerals, $movement, $water_resistance, $clasp_type, $power_reserve, $bracelet_color, $gender, $case_diameter, $case_material, $clasp_material, $bezel_material, $bracelet_material, $functions);
                if ($stmt->execute()) {
                    // Redirect to the same page after successful insert
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $error = "Error adding product details.";
                }
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <style>
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #000;
            border-bottom: 1px solid #dee2e6;
        }

        h5{
            color: #000;
        }
        .form-label {
            font-weight: bold;
            color: #000;
        }

        .preview-label {
            font-size: 0.9rem;
            color: #000;
            margin-bottom: 0.5rem;
        }

        label{
            color: #000;
        }
        .image-preview {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 5px;
            background: #fff;
        }

        @media (min-width: 768px) {
            .two-col {
                display: flex;
                gap: 1rem;
            }

            .two-col>div {
                flex: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="card">
            <div class="card-header text-center">
                <h3>Add New Product</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <!-- Basic Information Section -->
                    <h5 class="mb-3">Basic Information</h5>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="product-category" class="form-label">Product Category</label>
                            <input type="text" name="product-category" id="product-category" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" name="brand" id="brand" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" name="price" id="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" name="year" id="year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="ref_code" class="form-label">Reference Code</label>
                            <input type="text" name="ref_code" id="ref_code" class="form-control" required>
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="tags" class="form-label">Tags (comma separated)</label>
                            <input type="text" name="tags" id="tags" class="form-control" placeholder="e.g., luxury, limited edition" required>
                        </div>
                        <div class="form-group">
                            <label for="stock" class="form-label">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <!-- Action Buttons -->
                    <div class="two-col">
                        <div class="form-group">
                            <label for="button_name" class="form-label">Button Name</label>
                            <select name="button_name" id="button_name" class="form-control" required>
                                <option value="" disabled selected>Select a button name</option>
                                <option value="Add to cart">Add to cart</option>
                                <option value="Send Message">Send Message</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="form-label">Icon Class</label>
                            <select name="icon" id="icon" class="form-control" required>
                                <option value="" disabled selected>Select an icon class</option>
                                <option value="ri-shopping-cart-2-line">ri-shopping-cart-2-line</option>
                                <option value="ri-message-2-line">ri-message-2-line</option>
                            </select>
                        </div>
                    </div>

                    <!-- Image Options Section -->
                    <h5 class="mt-4 mb-3">Image Options</h5>
                    <div class="form-group">
                        <label class="form-label">Select Image Type:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="image_option" value="url" id="url_option" onchange="toggleImageFields()" required>
                            <label class="form-check-label" for="url_option">Image URL</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="image_option" value="upload" id="upload_option" onchange="toggleImageFields()" required>
                            <label class="form-check-label" for="upload_option">Upload Image</label>
                        </div>
                    </div>
                    <div id="url_field" class="form-group" style="display:none;">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input type="url" name="image_url" id="image_url" class="form-control" placeholder="Enter image URL">
                        <div class="mt-2">
                            <span class="preview-label">URL Image Preview</span>
                            <img id="urlPreview" class="image-preview" alt="URL image preview">
                        </div>
                    </div>
                    <div id="upload_field" class="form-group" style="display:none;">
                        <label for="upload_image" class="form-label">Upload Image</label>
                        <input type="file" name="upload_image" id="upload_image" class="form-control-file">
                        <div class="mt-2">
                            <span class="preview-label">Uploaded Image Preview</span>
                            <img id="uploadPreview" class="image-preview" alt="Upload preview">
                        </div>
                    </div>

                    <!-- Product Details Section -->
                    <h5 class="mt-4 mb-3">Product Details</h5>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="glass" class="form-label">Glass</label>
                            <input type="text" name="glass" id="glass" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="dial_numerals" class="form-label">Dial Numerals</label>
                            <input type="text" name="dial_numerals" id="dial_numerals" class="form-control" required>
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="movement" class="form-label">Movement</label>
                            <input type="text" name="movement" id="movement" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="water_resistance" class="form-label">Water Resistance</label>
                            <input type="text" name="water_resistance" id="water_resistance" class="form-control" required>
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="clasp_type" class="form-label">Clasp Type</label>
                            <input type="text" name="clasp_type" id="clasp_type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="power_reserve" class="form-label">Power Reserve</label>
                            <input type="text" name="power_reserve" id="power_reserve" class="form-control" required>
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="bracelet_color" class="form-label">Bracelet Color</label>
                            <input type="text" name="bracelet_color" id="bracelet_color" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="form-label">Gender</label>
                            <input type="text" name="gender" id="gender" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="case_diameter" class="form-label">Case Diameter</label>
                        <input type="text" name="case_diameter" id="case_diameter" class="form-control" required>
                    </div>

                    <!-- Materials Section -->
                    <h5 class="mt-4 mb-3">Materials</h5>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="case_material" class="form-label">Case Material</label>
                            <input type="text" name="case_material" id="case_material" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="clasp_material" class="form-label">Clasp Material</label>
                            <input type="text" name="clasp_material" id="clasp_material" class="form-control" required>
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="form-group">
                            <label for="bezel_material" class="form-label">Bezel Material</label>
                            <input type="text" name="bezel_material" id="bezel_material" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="bracelet_material" class="form-label">Bracelet Material</label>
                            <input type="text" name="bracelet_material" id="bracelet_material" class="form-control" required>
                        </div>
                    </div>

                    <!-- Functions Section -->
                    <div class="form-group">
                        <label for="functions" class="form-label">Functions</label>
                        <textarea name="functions" id="functions" class="form-control" rows="4" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function toggleImageFields() {
            const urlField = document.getElementById('url_field');
            const uploadField = document.getElementById('upload_field');
            const urlOption = document.getElementById('url_option');

            if (urlOption.checked) {
                urlField.style.display = 'block';
                uploadField.style.display = 'none';
                clearPreview('uploadPreview');
            } else {
                urlField.style.display = 'none';
                uploadField.style.display = 'block';
                clearPreview('urlPreview');
            }
        }

        // URL image preview
        document.getElementById('image_url').addEventListener('input', function(e) {
            const preview = document.getElementById('urlPreview');
            if (this.value) {
                preview.style.display = 'block';
                preview.src = this.value;
            } else {
                preview.style.display = 'none';
            }
        });

        // Uploaded image preview
        document.getElementById('upload_image').addEventListener('change', function(e) {
            const preview = document.getElementById('uploadPreview');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.display = 'block';
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        function clearPreview(elementId) {
            const preview = document.getElementById(elementId);
            preview.style.display = 'none';
            preview.src = '';
        }
    </script>
    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>
</body>

</html>
<?php
ob_end_flush();
$conn->close();
?>