<?php
session_start();
define('ALLOW_ACCESS', true);
include '../../PHP/components/navbar.php';


require_once '../config/conn.php';

$limit = 9; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);


$count_sql = "SELECT COUNT(*) AS total FROM blogs";
$count_result = $conn->query($count_sql);
$total_blogs = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_blogs / $limit);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Grid</title>
    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- ============= JS =============  -->
    <script src="/src/assets/js/navigation.js" async></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="/src/assets/css/deskView.css" />
    <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/src/assets/css/google-header.css">

    <!-- ============= FONTS=============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 10px 125px;
        }

        /* Card Styles */
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .card-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-content {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-description {
            font-size: 14px;
            color: #555;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .read-more{
            color: #000;
            text-decoration: none;
            font-weight: 400;
            transition: color 0.2s;
            border: 1px solid #ddd;
            width: 30%;
            text-align: center;
            padding: 5px;
        }

        .card-author {
            font-size: 15px;
            color: #000;
            margin: 0;
            font-weight: 600;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .card-date {
            font-size: 12px;
            color: #888;
            margin: 0;
        }

        .card-share {
            background: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            color: #333;
            transition: background-color 0.2s, color 0.2s;
        }

        .card-share:hover {
            background-color: #333;
            color: #fff;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 8px;
            padding: 10px;
        }

        .pagination a {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s, color 0.2s;
        }

        .pagination a.active {
            background-color: #333;
            color: #fff;
        }

        .pagination a:hover {
            background-color: #555;
            color: #fff;
        }


        /* Responsive Design */

        /* Medium screens (Tablets) */
        @media screen and (max-width: 1199px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr);
                padding: 10px 50px;
            }

            .card-image {
                height: 150px;
            }

            .card-title {
                font-size: 16px;
            }

            .card-description {
                font-size: 13px;
            }
        }

        /* Small screens (Mobiles) */
        @media screen and (max-width: 767px) {
            .card-grid {
                grid-template-columns: 1fr;
                padding: 10px 20px;
            }

            .card-image {
                height: 120px;
            }

            .card-title {
                font-size: 14px;
            }

            .card-description {
                font-size: 12px;
            }

            .card-share {
                font-size: 12px;
                padding: 6px 10px;
            }
        }
    </style>
</head>

<body> 
    <div class="card-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="card">
                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo $row['title']; ?></h3>
                    <p class="card-description">
                        <?php echo substr($row['description'], 0, 100); ?>...
                    </p>
                    <p class="card-author">By <?php echo $row['author']; ?></p>
                    <div class="card-footer">
                        <p class="card-date"><?php echo date('F d, Y', strtotime($row['created_at'])); ?></p>
                        <a href="<?php echo $row['external_url']; ?>" target="_blank" class="read-more">Read More</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php } ?>
    </div>

    <?php include '../../PHP/components/footer.php' ?>

    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <script src="/src/assets/js/cookie-monitor.js" async></script>
</body>

</html>
