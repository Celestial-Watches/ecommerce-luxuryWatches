<?php
session_start();
require '../../app/config/conn.php';

// --- Function Definitions ---
function calculateBaseValue($watchData) {
    // For demonstration, assign a base value based on the model.
    $baseValues = [
        'Rolex' => 10000,
        'Omega' => 8000,
        'Seiko' => 3000,
    ];
    return $baseValues[$watchData['model']] ?? 5000;
}

function applyConditionRules($value, $condition) {
    $rules = [
        'new'     => 1.0,
        'used'    => 0.8,
        'damaged' => 0.6
    ];
    return $value * ($rules[$condition] ?? 0.7);
}

// --- Handle AJAX Requests ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Handle CSV Upload (Market Data)
    if (isset($_FILES['market_data']) && $_FILES['market_data']['error'] === UPLOAD_ERR_OK) {
        $csvFile = fopen($_FILES['market_data']['tmp_name'], 'r');
        // Assuming CSV columns: model, condition, base_value, adjustment
        while (($data = fgetcsv($csvFile)) !== false) {
            if (count($data) >= 4) {
                $model         = $data[0];
                $condition     = $data[1];
                $baseValueCSV  = floatval($data[2]);
                $adjustmentCSV = floatval($data[3]);
                $adjustedValue = $baseValueCSV + $adjustmentCSV;
                $stmt = $conn->prepare("INSERT INTO valuations (model, val_condition, base_value, adjusted_value) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssdd", $model, $condition, $baseValueCSV, $adjustedValue);
                $stmt->execute();
            }
        }
        fclose($csvFile);
        echo json_encode(['status' => 'success', 'message' => 'Market data CSV uploaded and processed.']);
        exit();
    }

    // Handle Manual Valuation Form
    if (isset($_POST['model']) && isset($_POST['condition'])) {
        $watchData = [
            'model'     => $_POST['model'],
            'condition' => $_POST['condition'],
        ];
        $baseValue     = calculateBaseValue($watchData);
        $adjustedValue = applyConditionRules($baseValue, $watchData['condition']);
        
        $stmt = $conn->prepare("INSERT INTO valuations (model, val_condition, base_value, adjusted_value) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $watchData['model'], $watchData['condition'], $baseValue, $adjustedValue);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => "Valuation for {$watchData['model']} added successfully with value: $adjustedValue"]);
        exit();
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit();
}

// --- Fetch Historical Data ---
$result = $conn->query("SELECT * FROM valuations ORDER BY created_at DESC");
$valuationHistory = $result->fetch_all(MYSQLI_ASSOC);

$chartLabels = array_map(function($row) {
    return date('Y-m-d H:i', strtotime($row['created_at']));
}, $valuationHistory);
$chartData = array_map(function($row) {
    return $row['adjusted_value'];
}, $valuationHistory);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Watch Pricing & Valuation Tool</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid mt-5">
    <h2>Watch Pricing & Valuation Tool</h2>
    
    <div id="formMsg" class="alert" style="display: none;"></div>
    <div id="csvMsg" class="alert" style="display: none;"></div>
    
    <div class="row">
        <!-- Input Form -->
        <div class="col-md-4">
            <form id="valuationForm">
                <div class="mb-3">
                    <label class="form-label">Watch Model</label>
                    <input type="text" name="model" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Condition</label>
                    <select name="condition" class="form-select">
                        <option value="new">New</option>
                        <option value="used">Used</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-dark">Calculate Value</button>
            </form>
        </div>

        <!-- Valuation Chart -->
        <div class="col-md-8">
            <canvas id="valuationChart"></canvas>
        </div>
    </div>

    <!-- CSV Upload Form -->
    <div class="mt-4">
        <form id="csvUploadForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Upload Market Data CSV</label>
                <input type="file" name="market_data" accept=".csv" class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary">Upload Market Data</button>
        </form>
    </div>

    <!-- Valuation History Table -->
    <hr>
    <h3>Historical Valuations</h3>
    <table class="table table-bordered" id="valuationHistoryTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Model</th>
          <th>Condition</th>
          <th>Base Value</th>
          <th>Adjusted Value</th>
          <th>Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($valuationHistory as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['model']) ?></td>
          <td><?= htmlspecialchars($row['val_condition']) ?></td>
          <td><?= htmlspecialchars($row['base_value']) ?></td>
          <td><?= htmlspecialchars($row['adjusted_value']) ?></td>
          <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</div>

<script>
// Initialize the Chart.js line chart for valuation history
const ctx = document.getElementById('valuationChart').getContext('2d');
const valuationChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chartLabels); ?>,
        datasets: [{
            label: 'Valuation History',
            data: <?= json_encode($chartData); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            fill: true
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Handle form submission for manual valuation
$('#valuationForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.status === 'success') {
                $('#formMsg').removeClass('alert-danger').addClass('alert-success').text(response.message).show();
                updateValuationHistory();
            } else {
                $('#formMsg').removeClass('alert-success').addClass('alert-danger').text(response.message).show();
            }
        },
        error: function() {
            $('#formMsg').removeClass('alert-success').addClass('alert-danger').text('An error occurred. Please try again.').show();
        }
    });
});

// Handle CSV upload form submission
$('#csvUploadForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
        url: '',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.status === 'success') {
                $('#csvMsg').removeClass('alert-danger').addClass('alert-info').text(response.message).show();
                updateValuationHistory();
            } else {
                $('#csvMsg').removeClass('alert-info').addClass('alert-danger').text(response.message).show();
            }
        },
        error: function() {
            $('#csvMsg').removeClass('alert-info').addClass('alert-danger').text('An error occurred. Please try again.').show();
        }
    });
});

// Function to update valuation history table and chart
function updateValuationHistory() {
    $.ajax({
        url: 'fetch_valuation_history.php',
        type: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                // Update table
                const tableBody = $('#valuationHistoryTable tbody');
                tableBody.empty();
                response.data.forEach(row => {
                    tableBody.append(`
                        <tr>
                            <td>${row.id}</td>
                            <td>${row.model}</td>
                            <td>${row.val_condition}</td>
                            <td>${row.base_value}</td>
                            <td>${row.adjusted_value}</td>
                            <td>${row.created_at}</td>
                        </tr>
                    `);
                });

                // Update chart
                valuationChart.data.labels = response.chartLabels;
                valuationChart.data.datasets[0].data = response.chartData;
                valuationChart.update();
            }
        },
        error: function() {
            console.error('Failed to fetch valuation history.');
        }
    });
}
</script>
</body>
</html>