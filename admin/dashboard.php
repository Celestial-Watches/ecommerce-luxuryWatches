<?php 
define('ALLOW_ACCESS', true);
include 'panel.php';

require '../app/config/conn.php';



if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../app/controllers/login.php");
    exit();
}

// Fetch the total user count
$sqlCount = "SELECT COUNT(*) as user_count FROM users";
$resultCount = $conn->query($sqlCount);
$userCount = ($resultCount->num_rows > 0) ? $resultCount->fetch_assoc()['user_count'] : 0;

// Fetch user growth data by date
$sqlGrowth = "
    SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count 
    FROM users 
    GROUP BY date 
    ORDER BY date ASC
";

$resultGrowth = $conn->query($sqlGrowth);
$dates = [];
$counts = [];

// To store counts for the last two weeks
$lastWeekCount = 0;
$currentWeekCount = 0;

// Get today's date
$today = new DateTime();

// Check if there are results
if ($resultGrowth->num_rows > 0) {
    while ($row = $resultGrowth->fetch_assoc()) {
        $date = new DateTime($row['date']);
        $dates[] = $row['date'];
        $counts[] = (int)$row['count']; // Convert to integer

        // Check if the date is in the current week or the previous week
        if ($date->format('W') === $today->format('W')) {
            $currentWeekCount += (int)$row['count'];
        } elseif ($date->format('W') === $today->modify('-1 week')->format('W')) {
            $lastWeekCount += (int)$row['count'];
        }
    }
} else {
    $dates = [];
    $counts = [];
}

// Calculate growth percentage
$growthPercentage = 0;
if ($lastWeekCount > 0) {
    $growthPercentage = (($currentWeekCount - $lastWeekCount) / $lastWeekCount) * 100;
}

// Close the database connection
$conn->close();

// var_dump($months);
// var_dump($counts);
// echo "<pre>";
// print_r($dates);
// print_r($counts);
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@latest"></script>
    <script>
        window.addEventListener('pageshow', function(event) {
        // Check if the page is being loaded from the cache
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            // Reload the page
            window.location.reload();
        }
    });

    </script>
    <style>
        .card {
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 2rem;
            font-weight: bold;
        }

        .card-header {
            font-size: 1.5rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .canvas-container {
            position: relative;
            height: 200px;
            /* Fixed height for consistency */
        }

        .canvas-container canvas {
            max-width: 100%;
            height: auto;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Main content -->
            <main role="main" style="text-wrap: nowrap;" class="col-md-9 col-lg-10 px-3">
                <!-- Search Functionality -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Search</div>
                            <div class="card-body">
                                <input type="text" class="form-control" placeholder="Search users, products, or orders">
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row" style="color:black;">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-4">
                            <div class="card-header">Total Users</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $userCount; ?></h5>
                                <div class="canvas-container">
                                    <canvas id="totalUsersChart" width="400" height="200"></canvas>
                                </div>
                                <div class="growth-info">
                                    ⬆<?php echo number_format($growthPercentage, 1); ?>% Last Week <a href="#" class="view-more">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-4">
                            <div class="card-header">Total Sales</div>
                            <div class="card-body">
                                <h5 class="card-title">$2000</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-danger mb-4">
                            <div class="card-header">Pending Orders</div>
                            <div class="card-body">
                                <h5 class="card-title">5</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Growth Chart -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">User Growth Over Time</div>
                            <div class="card-body">
                                <canvas id="userGrowthChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Overview -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Sales Trends</div>
                            <div class="card-body">
                                <canvas id="salesTrendsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Management -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Product Management</div>
                            <div class="card-body">
                                <p>Quick access to <a href="#">add/edit/delete products</a>.</p>
                                <p>Inventory Levels: <strong>Alerts for low stock.</strong></p>
                                <p>Recently Added Products: <strong>Product List Here</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Management -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Order Management</div>
                            <div class="card-body">
                                <p>Summary of recent orders:</p>
                                <ul>
                                    <li>Order 1 - Status: Pending</li>
                                    <li>Order 2 - Status: Completed</li>
                                    <li>Order 3 - Status: Canceled</li>
                                </ul>
                                <p><a href="#">View all orders</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Feedback -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Customer Feedback</div>
                            <div class="card-body">
                                <p>Recent reviews:</p>
                                <ul>
                                    <li>Review 1 - Rating: ⭐⭐⭐⭐⭐</li>
                                    <li>Review 2 - Rating: ⭐⭐⭐⭐</li>
                                    <li>Review 3 - Rating: ⭐⭐⭐</li>
                                </ul>
                                <p><a href="#">Manage feedback</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics & Reports -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Analytics & Reports</div>
                            <div class="card-body">
                                <p>Traffic Statistics: <strong>Details here</strong></p>
                                <p>Performance Reports: <strong>User engagement data</strong></p>
                                <p><a href="#">Export detailed reports</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">System Health</div>
                            <div class="card-body">
                                <p>Server Uptime: <strong>99.9%</strong></p>
                                <p>Error Rates: <strong>Minimal errors logged.</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications & Alerts -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Notifications</div>
                            <div class="card-body">
                                <ul>
                                    <li>New user signup: User123</li>
                                    <li>Low inventory alert: Product XYZ</li>
                                    <li>Update: System maintenance scheduled.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings/Configuration Panel -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Settings</div>
                            <div class="card-body">
                                <p>Configure application settings:</p>
                                <ul>
                                    <li>User roles and permissions</li>
                                    <li>Theme customization options</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Activity Log -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">User Activity Log</div>
                            <div class="card-body">
                                <p>Recent actions taken by users:</p>
                                <ul>
                                    <li>User123 logged in at 10:00 AM</li>
                                    <li>User456 made changes at 10:15 AM</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Include Bootstrap JS and Chart.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <script>
        // Example code to create charts using Chart.js

        window.onload = function() {
            // Total Users Chart (if needed, you can keep it as before)
            const totalUsersCtx = document.getElementById('totalUsersChart').getContext('2d');
            const totalUsersChart = new Chart(totalUsersCtx, {
                type: 'line', // Change this to 'line'
                data: {
                    labels: ['Total Users'], // A single label for the total count
                    datasets: [{
                        label: 'Users',
                        data: [<?php echo $userCount; ?>], // Total user count
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        fill: false, // Set to false to not fill below the line
                        tension: 0.1, // Smoother lines
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'User Count'
                            }
                        }
                    }
                }
            });

            // User Growth Chart
            const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
            const userGrowthChart = new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($dates); ?>, // Dates for growth
                    datasets: [{
                        label: 'Users',
                        data: <?php echo json_encode($counts); ?>, // Counts for growth
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3, // Smoother lines
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'User Count'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            },
                            type: 'category', // Use 'time' for x-axis
                            time: {
                                unit: 'day', // Specify unit for time
                                tooltipFormat: 'MMM D, YYYY', // Format for tooltips
                                displayFormats: {
                                    day: 'MMM D, YYYY' // Format for x-axis labels
                                }
                            }
                        }
                    }
                }
            });


            var ctxSalesTrends = document.getElementById('salesTrendsChart').getContext('2d');
            var salesTrendsChart = new Chart(ctxSalesTrends, {
                type: 'bar',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Sales',
                        data: [500, 1000, 1500, 2000],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>