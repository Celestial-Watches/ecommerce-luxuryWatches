<?php
session_start();
session_regenerate_id(true);

ob_start();

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

require_once '../app/config/conn.php';
require_once 'simple_html_dom.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['blog_inserted'])) {
    $url = $_POST['url'];
    $author = $_POST['author'];

    // Scrape the URL to fetch the blog details
    $html = file_get_html($url);

    if ($html === false) {
        echo "Error fetching the URL.";
        exit();
    }

    // Extract necessary data from the HTML
    $title = $html->find('title', 0)->plaintext;
    $description = $html->find('meta[name=description]', 0)->content;
    $image_url = $html->find('meta[property="og:image"]', 0)->content;

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO blogs (title, description, author, image_url, external_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $author, $image_url, $url);

    if ($stmt->execute()) {
        // Set session variable to prevent re-insertion on refresh
        $_SESSION['blog_inserted'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../src/assets/css/panel.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 100%;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            box-sizing: border-box;
            transition: transform 0.3s ease-in-out;
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            box-sizing: border-box;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #28a745;
            background-color: #fff;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #aaa;
        }

        .form-group button {
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #218838;
        }

        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px;
            }

            h2 {
                font-size: 20px;
            }

            .form-group input,
            .form-group textarea {
                padding: 12px;
                font-size: 14px;
            }

            .form-group button {
                padding: 14px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 18px;
            }

            .form-container {
                padding: 20px;
            }

            .form-group input,
            .form-group textarea {
                padding: 10px;
                font-size: 12px;
            }

            .form-group button {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div id="alert-box" class="alert">
        New blog inserted successfully!
    </div>

    <div class="form-container">
        <h2>Insert Blog from URL</h2>
        <form action="" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="url">Blog URL</label>
                <input type="url" id="url" name="url" required placeholder="Enter the URL of the blog to scrape">
            </div>

            <div class="form-group">
                <label for="author">Author Name</label>
                <input type="text" id="author" name="author" required placeholder="Enter author's name">
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn">Insert Blog</button>
            </div>

        </form>
    </div>

    <script type="text/javascript" src="../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../src/assets/js/navigation.js" async></script>

    <script>
        <?php if (isset($_SESSION['blog_inserted']) && $_SESSION['blog_inserted'] === true): ?>
            document.getElementById('alert-box').style.display = 'block';
            setTimeout(function() {
                document.getElementById('alert-box').style.display = 'none';
            }, 5000);

            <?php unset($_SESSION['blog_inserted']); ?>
        <?php endif; ?>
    </script>

</body>

</html>