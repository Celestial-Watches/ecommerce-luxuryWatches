<?php
// analytics.php
session_start();
require_once '/MarwadiUniversity/Semester5/PROJECT/app/config/conn.php'; 

// --- Fetch All Analytics Data ---
$result = $conn->query("SELECT * FROM exchange_analytics ORDER BY created_at ASC");
if (!$result) {
    die("Error fetching analytics data: " . $conn->error);
}
$analytics = $result->fetch_all(MYSQLI_ASSOC);

// --- Calculate Key Metrics ---
$totalRecords = count($analytics);
$sumMetrics = 0;
foreach ($analytics as $row) {
    $sumMetrics += $row['metrics'];
}
$avgMetricOverall = $totalRecords > 0 ? $sumMetrics / $totalRecords : 0;

// --- Fetch Aggregated Data for Pie Chart ---
// Group by exchange_id to show distribution of records per exchange_id
$aggResult = $conn->query("SELECT exchange_id, COUNT(*) AS total FROM exchange_analytics GROUP BY exchange_id");
if (!$aggResult) {
    die("Error fetching aggregated data: " . $conn->error);
}
$aggData = $aggResult->fetch_all(MYSQLI_ASSOC);

// Prepare arrays for the pie chart
$pieLabels = [];
$pieTotals = [];
foreach ($aggData as $row) {
    $pieLabels[] = $row['exchange_id'];
    $pieTotals[] = (int)$row['total'];
}

// Prepare data for the line chart (trend over time)
// Using created_at as label and metrics as data point
$lineLabels = [];
$lineData = [];
foreach ($analytics as $row) {
    // Format date as needed (e.g., 'Y-m-d H:i')
    $lineLabels[] = date('Y-m-d H:i', strtotime($row['created_at']));
    $lineData[] = (float)$row['metrics'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Exchange Analytics</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container-fluid mt-5">
    <h2>Exchange Analytics</h2>
    
    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Records</h5>
                    <p class="display-4"><?= $totalRecords ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Average Metric</h5>
                    <p class="display-4"><?= number_format($avgMetricOverall, 2) ?></p>
                </div>
            </div>
        </div>
        <!-- Add more metric cards if needed -->
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-6">
            <canvas id="pieChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <!-- Data Export -->
    <div class="mt-4">
        <a href="export.php?type=exchange_analytics" class="btn btn-dark">Export to CSV</a>
    </div>
</div>

<script>
// Prepare Pie Chart Data
const pieLabels = <?= json_encode($pieLabels); ?>;
const pieData = <?= json_encode($pieTotals); ?>;

// Create the Pie Chart (Distribution by exchange_id)
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: pieLabels,
        datasets: [{
            data: pieData,
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)'
            ]
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Prepare Line Chart Data (Trend Over Time)
const lineLabels = <?= json_encode($lineLabels); ?>;
const lineData = <?= json_encode($lineData); ?>;

// Create the Line Chart
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: lineLabels,
        datasets: [{
            label: 'Metric Trend',
            data: lineData,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        scales: {
            x: {
                title: { display: true, text: 'Date' }
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Metric' }
            }
        }
    }
});
</script>
</body>
</html>
