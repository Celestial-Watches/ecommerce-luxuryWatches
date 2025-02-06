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
    <title>Admin Panel - User Activity Logs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1,
        h2 {
            font-size: 2rem;
            color: #343a40;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .btn {
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .table-responsive {
            margin-top: 30px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: center;
            padding: 14px 16px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
            color: #495057;
        }

        table td {
            color: #6c757d;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        .chart-container {
            margin-top: 50px;
            margin-bottom: 30px;
        }

        #activityChart {
            max-width: 100%;
            height: 400px;
            border-radius: 10px;
        }

        .export-btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .export-btn-container a {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .export-btn-container a:hover {
            background-color:rgb(80, 230, 0);
        }

        .paginate_button {
            padding: 10px 20px;
            border: none;
            background-color: rgb(0, 0, 0);
            color: white;
            display: inline-block;
            font-weight: 600;
            transition: background-color 0.3s ease;
            width: max-content;
        }

        .page-link {
            color: white;
            text-decoration: none;
        }

        .pagination {
            gap: 5px;
            display: inline-flex;
        }
    </style>


</head>

<body>
    <div class="container">
        <h1>User Activity Logs</h1>

        <!-- Export Button Container -->
        <div class="export-btn-container">
            <a href="export_csv_logs.php" id="exportBtn" class="btn btn-success btn-outline-success btn-lg">Export CSV</a>
        </div>

        <!-- Activity Logs Table -->
        <div class="table-responsive">
            <table id="activityLogsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Activity Type</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Activity Statistics Chart -->
        <div class="chart-container">
            <h2>User Activity Statistics</h2>
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <!-- Panel Navigation Scripts -->
    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>

    <!-- jQuery, Bootstrap Bundle, DataTables, Chart.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Main JS for AJAX calls -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#activityLogsTable').DataTable({
                "ajax": "ajax_get_activity_logs.php",
                "columns": [{
                        "data": "username"
                    },
                    {
                        "data": "activity_type"
                    },
                    {
                        "data": "description"
                    },
                    {
                        "data": "ip_address"
                    },
                    {
                        "data": "user_agent"
                    },
                    {
                        "data": "timestamp"
                    }
                ],
                "order": [
                    [5, "desc"]
                ],
                responsive: true
            });

            // Load Chart Data via AJAX and render using Chart.js
            $.ajax({
                url: 'ajax_get_activity_chart_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var ctx = document.getElementById('activityChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Activities',
                                data: response.data,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                fill: true,
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    beginAtZero: true
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>