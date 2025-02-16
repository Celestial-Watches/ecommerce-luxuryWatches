<?php
session_start();
session_regenerate_id(true);
ob_start();

define('ALLOW_ACCESS', true);
include '../panel.php';
require '../../app/config/conn.php';

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
        .containerActivity {
            max-width: 1200px;
            padding: 20px;
            overflow: hidden;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-bottom: 30px;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 20px;
            text-align: center;
        }

        .card-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 500;
            color: #000 !important;
        }

        .card-body {
            padding: 20px;
            color: #000;
        }

        .export-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            border-radius: 25px;
            color: #fff !important;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .export-btn:hover {
            background-color: #218838;
        }

        .table-responsive {
            margin-top: 20px;
        }

        table.dataTable thead {
            background-color: #f8f9fa;
            color: #000;
        }

        table.dataTable th,
        table.dataTable td {
            text-align: center;
            vertical-align: middle;
            padding: 12px 8px;
        }

        .chart-section {
            margin-top: 40px;
            padding: 20px;
        }

        .chart-section h2 {
            text-align: center;
            font-size: 1.5rem;
            color: #000;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        a:hover {
            color: #000;
        }


        li {
            list-style: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #000;
            color: #000 !important;
            border-radius: 5px;
            display: inline-block;
            padding: 8px 16px;
            width: max-content;
            margin: 2px;
            border: none;
            transition: background-color 0.3s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: rgb(254, 255, 253) !important;
            color: #000 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: rgb(255, 255, 255) !important;
            color: #000 !important;
        }

        .dataTables_filter label {
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.5rem;
            position: relative;
            text-align: end;
        }

        .dataTables_filter input[type="search"] {
            width: 100%;
            max-width: 300px;
            padding: 8px 12px;
            padding-left: 40px;
            border: 1px solid #ccc;
            border-radius: 25px;
            background-color: #f9f9f9;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .dataTables_filter input[type="search"]:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 8px rgba(26, 115, 232, 0.3);
            outline: none;
        }

        .dataTables_filter label::before {
            content: "\f002";
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 10px;
            color: #aaa;
            font-size: 1rem;
            pointer-events: none;
            top: 50%;
            transform: translateY(-50%);
        }


        @media (max-width: 768px) {
            .containerActivity {
                margin: 20px auto;
            }
        }
    </style>
</head>

<body>
    <div class="containerActivity">
        <!-- Activity Logs Card -->
        <div class="card">
            <div class="card-header">
                <h1>User Activity Logs</h1>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <a href="export_csv_logs.php" class="export-btn">Export CSV</a>
                </div>
                <div class="table-responsive">
                    <table id="activityLogsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
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
            </div>
        </div>
        <!-- Activity Chart Card -->
        <div class="card chart-section">
            <h2>User Activity Statistics</h2>
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <!-- Panel Navigation Scripts -->
    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- AJAX and Chart.js Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable with custom pagination type
            $('#activityLogsTable').DataTable({
                ajax: "ajax_get_activity_logs.php",
                columns: [{
                        data: "username"
                    },
                    {
                        data: "activity_type"
                    },
                    {
                        data: "description"
                    },
                    {
                        data: "ip_address"
                    },
                    {
                        data: "user_agent"
                    },
                    {
                        data: "timestamp"
                    }
                ],
                order: [
                    [5, "desc"]
                ],
                responsive: true,
                pagingType: "simple_numbers"
            });

            // Fetch Chart Data and Render Chart.js Chart
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