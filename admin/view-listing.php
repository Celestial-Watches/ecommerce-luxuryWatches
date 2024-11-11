<?php
session_start();
session_regenerate_id(true);

define('ALLOW_ACCESS', true);
include 'panel.php'; 
require_once '../app/config/conn.php';


if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../app/controllers/login.php"); 
    exit();
  }
  

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    header("Location: /app/controllers/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../src/assets/css/panel.css">

    <title>Product List</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container{
            overflow-x: auto;
            max-width: 95%;
        }

        .table {
            margin: 20px;
            border-radius: 0.5rem;
            overflow: hidden;
            border-collapse: collapse;
        }

        .table thead th {
            background-color: #000000;
            color: white;
        }

        .table td {
            padding: 0.5rem;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            gap: 8px;
            justify-content: center;
        }

        .page-item {
            display: inline-block;
        }

        .page-link {
            color: #ffff;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background-color: #000000;
            transition: background-color 0.3s, color 0.3s;
        }

        @media (max-width: 801px) {
            .page-link{
                margin-bottom: 50%;
            }
        }

        .page-link:hover {
            background-color: #f1f1f1;
        }

        .page-item.active .page-link {
            background-color: #000000;
            color: white;
            border-color: #000000;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        /* Custom styles for the modern search bar */
        .search-wrapper {
            position: relative;
            max-width: 100%;
            width: 400px;
            margin: 20px auto;
        }

        .modern-search {
            width: 100%;
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid transparent;
            border-radius: 50px;
            background-color: #f5f5f5;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            outline: none;
        }

        .modern-search::placeholder {
            color: #999;
            transition: all 0.3s ease;
        }

        .modern-search:focus {
            background-color: #fff;
            border-color: #000000;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .modern-search:focus::placeholder {
            opacity: 0;
            transform: translateY(-10px);
        }
    </style>
</head>

<body>

    

    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color:#000000;">Product List</h2>

        <!-- Search Bar -->
        <div class="mb-3 search-wrapper">
            <input type="text" id="searchInput" class="modern-search" placeholder="Search for products...">
        </div>

        <!-- Product Table -->
        <div id="productTableWrapper">
            <!-- Table will be loaded here dynamically -->
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination" id="paginationWrapper">
                <!-- Pagination links will be loaded here dynamically -->
            </ul>
        </nav>
    </div>

    
    <script>
        $(document).ready(function () {
            // Function to load products with AJAX
            function loadProducts(page = 1, searchTerm = '') {
                $.ajax({
                    url: 'fetch_products.php',
                    type: 'GET',
                    data: {
                        page: page,
                        search: searchTerm
                    },
                    success: function (response) {
                        const data = JSON.parse(response);
                        $('#productTableWrapper').html(data.products); // Load product table
                        $('#paginationWrapper').html(data.pagination); // Load pagination links
                    },
                    error: function () {
                        alert('Error loading data.');
                    }
                });
            }

            // Load initial product list
            loadProducts();

            // Event handler for search input
            $('#searchInput').on('input', function () {
                const searchTerm = $(this).val();
                loadProducts(1, searchTerm); // Load products from page 1 on search
            });

            // Event handler for pagination links
            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                const searchTerm = $('#searchInput').val();
                loadProducts(page, searchTerm); // Load products for the selected page
            });
        });
    </script>
    
    <!-- <script src="../src/assets/js/panelNav.js" defer></script> -->
    <script src="../src/assets/js/navigation.js"></script>
</body>

</html>
