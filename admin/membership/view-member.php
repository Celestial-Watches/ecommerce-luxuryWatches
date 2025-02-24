<?php
session_start();
session_regenerate_id(true);
ob_start();

function renderTableRows($applications)
{
    $html = '';
    while ($app = $applications->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($app['full_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($app['email']) . '</td>';
        $html .= '<td>' . date('M j, Y', strtotime($app['created_at'])) . '</td>';
        $html .= '<td class="status-' . $app['status'] . '">' . ucfirst($app['status']) . '</td>';
        $html .= '<td><a href="admin_view_application.php?id=' . $app['id'] . '">View</a>';
        if ($app['status'] === 'pending') {
            $html .= ' | <a href="admin_action.php?action=approve&id=' . $app['id'] . '">Approve</a>';
            $html .= ' | <a href="admin_action.php?action=reject&id=' . $app['id'] . '">Reject</a>';
        }
        if ($app['status'] === 'approved') {
            $html .= ' | <a href="admin_action.php?action=terminate&id=' . $app['id'] . '">Terminate</a>';
        }
        $html .= '</td>';
        $html .= '</tr>';
    }
    return $html;
}

function generatePaginationLinks($totalPages, $currentPage)
{
    $html = '';
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i === $currentPage) {
            $html .= '<strong>' . $i . '</strong>';
        } else {
            $html .= '<a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a>';
        }
    }
    return $html;
}

// Check admin authentication and handle unauthorized access
if (!isset($_SESSION['authenticated']) || !isset($_SESSION['admin']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
        header('HTTP/1.1 403 Forbidden');
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Access denied']);
        exit();
    } else {
        header("Location: ../../app/controllers/login.php");
        exit();
    }
}

// Handle AJAX requests
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    $search = "%{$searchQuery}%";

    require_once '../../app/config/conn.php';

    // Fetch applications
    $stmt = $conn->prepare("SELECT * FROM membership_applications 
                            WHERE full_name LIKE ? OR email LIKE ?
                            ORDER BY created_at DESC 
                            LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $search, $search, $perPage, $offset);
    $stmt->execute();
    $applications = $stmt->get_result();

    // Calculate pagination data
    $stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM membership_applications 
                                 WHERE full_name LIKE ? OR email LIKE ?");
    $likes = "%{$searchQuery}%";
    $stmtCount->bind_param("ss", $likes, $likes);
    $stmtCount->execute();
    $total = $stmtCount->get_result()->fetch_assoc()['total'];
    $pages = ceil($total / $perPage);

    // Generate rows and pagination
    $rowsHtml = renderTableRows($applications);
    $paginationHtml = generatePaginationLinks($pages, $page);

    header('Content-Type: application/json');
    echo json_encode(['rows' => $rowsHtml, 'pagination' => $paginationHtml]);
    exit();
}

// Proceed with non-AJAX request handling
define('ALLOW_ACCESS', true);
include '../panel.php';

require_once '../../app/config/conn.php';

// Prepare main query and generate initial HTML
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$search = "%{$searchQuery}%";

$stmt = $conn->prepare("SELECT * FROM membership_applications 
                        WHERE full_name LIKE ? OR email LIKE ?
                        ORDER BY created_at DESC 
                        LIMIT ? OFFSET ?");
$stmt->bind_param("ssii", $search, $search, $perPage, $offset);
$stmt->execute();
$applications = $stmt->get_result();

$stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM membership_applications 
                             WHERE full_name LIKE ? OR email LIKE ?");
$likes = "%{$searchQuery}%";
$stmtCount->bind_param("ss", $likes, $likes);
$stmtCount->execute();
$total = $stmtCount->get_result()->fetch_assoc()['total'];
$pages = ceil($total / $perPage);
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Membership Applications</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <style>
        /* Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 25px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .dashboard-container h1 {
            font-size: 2.25rem;
            font-weight: 600;
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Search Box */
        .search-box {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-box input[type="text"] {
            width: 70%;
            padding: 12px 15px;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }

        .search-box input[type="text"]:focus {
            outline: none;
            border-color: #80bdff;
        }

        .search-box button {
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            background-color: rgb(0, 0, 0);
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }

        .search-box button:hover {
            background-color: rgb(85, 89, 94);
            color: #000;
        }

        /* Table Styling */
        .application-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        .application-table thead {
            background-color: #343a40;
            color: #fff;
        }

        .application-table th,
        .application-table td {
            padding: 15px 20px;
            border: 1px solid #dee2e6;
            text-align: left;
            font-size: 0.95rem;
        }

        .application-table td {
            color: #000;
        }

        .application-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .application-table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Status Colors */
        .status-pending {
            color: #ffc107 !important;
            font-weight: bold;
        }

        .status-approved {
            color: #28a745 !important;
            font-weight: bold;
        }

        .status-rejected {
            color: #dc3545 !important;
            font-weight: bold;
        }

        /* Actions Links */
        .application-table a {
            color: rgb(11, 11, 11);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .application-table a:hover {
            color: rgb(41, 43, 45);
        }

        /* Pagination */
        .pagination {
            margin-top: 30px;
            text-align: center;
        }

        .pagination a,
        .pagination strong {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 1rem;
            color: rgb(0, 0, 0);
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .pagination strong {
            background-color: rgb(0, 0, 0);
            color: #fff;
        }

        .pagination a:hover {
            background-color: #e2e6ea;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 15px;
                padding: 15px;
            }

            .search-box input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }

            .search-box button {
                width: 100%;
                margin-left: 0;
            }

            .application-table th,
            .application-table td {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            .pagination a,
            .pagination strong {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <h1>Membership Applications</h1>
        <div class="search-box">
            <input type="text" id="search" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars($searchQuery) ?>">
            <button id="search-button">Search</button>
        </div>
        <table class="application-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?= renderTableRows($applications) ?>
            </tbody>
        </table>
        <div class="pagination" id="pagination-container">
            <?= generatePaginationLinks($pages, $page) ?>
        </div>
    </div>
    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>
    <script>
        $(document).ready(function() {
            function fetchApplications(page = 1) {
                var searchQuery = $('#search').val();
                $.ajax({
                    url: '',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        search: searchQuery,
                        ajax: 1,
                        page: page
                    },
                    success: function(response) {
                        $('#table-body').html(response.rows);
                        $('#pagination-container').html(response.pagination);
                    }
                });
            }
            $('#search-button').on('click', function(e) {
                e.preventDefault();
                fetchApplications(1);
            });
            let typingTimer;
            let doneTypingInterval = 500;
            $('#search').on('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    fetchApplications(1);
                }, doneTypingInterval);
            }).on('keydown', function() {
                clearTimeout(typingTimer);
            });
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                fetchApplications(page);
            });
        });
    </script>
</body>

</html>
<?php
ob_end_flush();
?>