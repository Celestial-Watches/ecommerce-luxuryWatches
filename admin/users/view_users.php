<?php
session_start();
session_regenerate_id(true);
ob_start();

define('ALLOW_ACCESS', true);
include '../panel.php';
require '../../app/config/conn.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - View Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            margin-top: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color:rgb(0, 0, 0);
        }

        .export-btn-container {
            margin-bottom: 20px;
            text-align: right;
        }

        .card {
            margin-bottom: 30px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
            border: none;
        }

        .card-header {
            background-color:rgb(0, 0, 0);
            color: #fff;
            font-weight: 600;
        }

        .chart-container {
            padding: 20px;
        }

        label{color: black;}
        .dataTables_info{color: black;}
    </style>
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Users Management</h1>
            <p class="lead text-dark">Overview of registered users and analytics</p>
        </div>

        <!-- Export Button in a card -->
        <div class="card">
            <div class="card-header">
                Export Options
            </div>
            <div class="card-body export-btn-container">
                <button id="exportBtn" class="btn btn-outline-success btn-lg">
                    <i class="fas fa-file-export"></i> Export CSV
                </button>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="card">
            <div class="card-header">
                Users List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table data is loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Graphical Analytics Card -->
        <div class="card">
            <div class="card-header">
                User Registrations Per Month
            </div>
            <div class="card-body chart-container">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Panel Navigation Scripts -->
    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>

    <!-- jQuery, Bootstrap Bundle, DataTables, Chart.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FontAwesome for icon usage -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Main JS for AJAX calls -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable with AJAX loading
            var table = $('#usersTable').DataTable({
                "ajax": "ajax_get_users.php",
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "username"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "phone"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "role"
                    }
                ],
                "order": [
                    [0, "desc"]
                ],
                responsive: true
            });

            // Load Chart Data via AJAX and render using Chart.js
            $.ajax({
                url: 'ajax_get_chart_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var ctx = document.getElementById('userChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.labels, // e.g., ["2025-01", "2025-02", ...]
                            datasets: [{
                                label: 'Registrations',
                                data: response.data, // counts per month
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Chart AJAX error:", error);
                }
            });

            // Export CSV button – redirects to export_users.php
            $('#exportBtn').click(function() {
                window.location.href = 'export_users.php';
            });
        });
    </script>
</body>

</html>
<?php
ob_end_flush();
$conn->close();
?>