<?php
session_start();
session_regenerate_id(true);
ob_start();
define('ALLOW_ACCESS', true);
require '../../app/config/conn.php';

if (
    !isset($_SESSION['user'], $_SESSION['admin'], $_SESSION['authenticated']) ||
    $_SESSION['authenticated'] !== true ||
    $_SESSION['admin'] !== true
) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

include '../panel.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Admin - Manage Offers</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../../src/assets/css/panel.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <!-- Custom CSS for Dynamic White Theme with Black Headings and Text -->
  <style>
    :root {
      --primary-bg: #ffffff;
      --secondary-bg: #f9f9f9;
      --card-bg: #ffffff;
      --border-color: #ddd;
      --accent-color: #007bff;
      --text-primary: #000000;
      --text-secondary: #555555;
    }

    body {
      background-color: var(--primary-bg);
      color: var(--text-primary);
      font-family: Arial, sans-serif;
    }

    /* Container for Admin Offer Form */
    .offer-form-container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }

    /* Page Title */
    .page-title {
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
      font-weight: 600;
      color: var(--text-primary);
      border-bottom: 2px solid var(--border-color);
      padding-bottom: 0.75rem;
      display: flex;
      align-items: center;
    }
    
    .page-title i {
      margin-right: 0.75rem;
      color: var(--accent-color);
    }

    /* Card Layout */
    .offer-card {
      background-color: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
      margin-bottom: 2rem;
      overflow: hidden;
    }

    /* Card Header */
    .offer-card-header {
      background-color: var(--secondary-bg);
      border-bottom: 1px solid var(--border-color);
      padding: 1.25rem 1.5rem;
      font-size: 1.25rem;
      font-weight: 500;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .offer-card-header i {
      color: var(--accent-color);
      font-size: 1.4rem;
      margin-right: 0.75rem;
    }

    /* Card Body */
    .offer-card-body {
      padding: 2rem;
    }

    /* Form Grid Layout */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }
    
    .form-grid-full {
      grid-column: span 2;
    }

    /* Form Label */
    .form-label {
      color: var(--text-primary);
      font-weight: 500;
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    /* Form Control */
    .form-control {
      background-color: var(--secondary-bg);
      border: 1px solid var(--border-color);
      border-radius: 0.35rem;
      color: var(--text-primary);
      padding: 0.75rem 1rem;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      background-color: var(--secondary-bg);
      color: var(--text-primary);
      box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
      border-color: var(--accent-color);
    }

    .form-text {
      color: var(--text-secondary);
      font-size: 0.85rem;
      margin-top: 0.35rem;
    }

    /* Button Styles */
    .action-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .btn {
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      border-radius: 0.35rem;
      transition: all 0.2s ease;
    }

    .btn-primary {
      background-color: var(--accent-color);
      border: none;
      color: #ffffff;
    }

    .btn-primary:hover {
      background-color: #0069d9;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,123,255,0.3);
    }

    .btn-outline-secondary {
      background-color: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
    }

    .btn-outline-secondary:hover {
      background-color: rgba(0, 0, 0, 0.05);
      color: var(--text-primary);
    }
    
    /* Help Icon */
    .help-icon {
      color: var(--text-secondary);
      margin-left: 0.5rem;
      font-size: 0.85rem;
      cursor: pointer;
    }
    
    .help-icon:hover {
      color: var(--accent-color);
    }
    
    /* Dynamic Offers Table */
    .offers-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
    }
    
    .offers-table th, .offers-table td {
      border: 1px solid var(--border-color);
      padding: 0.75rem;
      text-align: left;
    }
    
    .offers-table th {
      background-color: var(--secondary-bg);
      color: var(--text-primary);
    }
  </style>
</head>

<body>
  <div class="offer-form-container">
    <h1 class="page-title">
      <i class="bi bi-tags"></i> Manage Promotional Offers
    </h1>
    
    <!-- Offer Creation Card -->
    <div class="card offer-card">
      <div class="offer-card-header">
        <div>
          <i class="bi bi-plus-circle"></i>
          <span>Create New Offer</span>
        </div>
        <small class="text-secondary">All fields marked with * are required</small>
      </div>
      <div class="card-body offer-card-body">
        <form action="save_offer.php" method="post" class="admin-offer-form">
          <input type="hidden" name="offer_id" value="">

          <div class="form-grid">
            <div class="mb-3">
              <label for="discount_code" class="form-label">
                Discount Code *
                <i class="bi bi-question-circle help-icon" title="Unique code that customers will enter to apply this discount"></i>
              </label>
              <input type="text" id="discount_code" name="discount_code" class="form-control" placeholder="e.g., SUMMER2025" required>
              <div class="form-text">Code must be unique and easy to remember</div>
            </div>

            <div class="mb-3">
              <label for="discount_name" class="form-label">
                Offer Name *
                <i class="bi bi-question-circle help-icon" title="Short descriptive name for this offer"></i>
              </label>
              <input type="text" id="discount_name" name="discount_name" class="form-control" placeholder="e.g., Summer 2025 Special" required>
            </div>

            <div class="mb-3">
              <label for="discount_percentage" class="form-label">
                Discount Percentage *
                <i class="bi bi-question-circle help-icon" title="Amount of discount to apply (in percentage)"></i>
              </label>
              <div class="input-group">
                <input type="number" step="0.01" min="0" max="100" id="discount_percentage" name="discount_percentage" class="form-control" placeholder="e.g., 10.00" required>
                <span class="input-group-text bg-white text-dark border-secondary">%</span>
              </div>
            </div>

            <div class="mb-3">
              <label for="active_status" class="form-label">Status</label>
              <select class="form-control" id="active_status" name="active_status">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>

            <div class="mb-3 form-grid-full">
              <label for="applicable_payment_methods" class="form-label">
                Applicable Payment Methods *
                <i class="bi bi-question-circle help-icon" title="Select which payment methods this offer applies to"></i>
              </label>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="credit_card" id="payment_credit_card" name="payment_methods[]" checked>
                    <label class="form-check-label" for="payment_credit_card">Credit Card</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="debit_card" id="payment_debit_card" name="payment_methods[]">
                    <label class="form-check-label" for="payment_debit_card">Debit Card</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="net_banking" id="payment_net_banking" name="payment_methods[]">
                    <label class="form-check-label" for="payment_net_banking">Net Banking</label>
                  </div>
                </div>
              </div>
              <!-- Hidden field to hold the combined payment methods -->
              <input type="hidden" id="applicable_payment_methods" name="applicable_payment_methods">
            </div>

            <div class="mb-3 form-grid-full">
              <label for="description" class="form-label">Description</label>
              <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter detailed description of the offer"></textarea>
              <div class="form-text">Provide details that will help understand when and how this offer is applicable</div>
            </div>
            
            <div class="mb-3">
              <label for="start_date" class="form-label">Start Date</label>
              <input type="date" id="start_date" name="start_date" class="form-control">
            </div>
            
            <div class="mb-3">
              <label for="end_date" class="form-label">End Date</label>
              <input type="date" id="end_date" name="end_date" class="form-control">
            </div>
          </div>

          <div class="action-buttons mt-4">
            <button type="button" class="btn btn-outline-secondary">
              <i class="bi bi-x-lg"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save"></i> Save Offer
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Dynamic Offers Table -->
    <div id="offersTableContainer" class="card offer-card">
      <div class="offer-card-header">
        <div>
          <i class="bi bi-list-ul"></i>
          <span>Existing Offers</span>
        </div>
      </div>
      <div class="card-body offer-card-body">
        <table class="offers-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Code</th>
              <th>Name</th>
              <th>Discount</th>
              <th>Payment Methods</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="offersTableBody">
            <!-- Dynamic Content via JS -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="/src/assets/js/panelNav.js" async></script>
  <script type="text/javascript" src="/src/assets/js/navigation.js" async></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Convert selected checkboxes to a comma-separated string stored in the hidden field on form submit.
      document.querySelector('.admin-offer-form').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('input[name="payment_methods[]"]:checked');
        const paymentMethods = Array.from(checkboxes).map(cb => cb.value).join(',');
        document.getElementById('applicable_payment_methods').value = paymentMethods;
      });
      
      // Function to load existing offers dynamically
      function loadOffers() {
        fetch('get_offers_admin.php')
          .then(response => response.json())
          .then(data => {
            const offersTableBody = document.getElementById('offersTableBody');
            offersTableBody.innerHTML = '';
            data.forEach(offer => {
              const tr = document.createElement('tr');
              tr.innerHTML = `
                <td>${offer.id}</td>
                <td>${offer.discount_code}</td>
                <td>${offer.discount_name}</td>
                <td>${offer.discount_percentage}%</td>
                <td>${offer.applicable_payment_methods}</td>
                <td>${offer.active_status == 1 ? 'Active' : 'Inactive'}</td>
                <td>
                  <button class="btn btn-outline-secondary btn-sm" onclick="editOffer(${offer.id})">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="deleteOffer(${offer.id})">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              `;
              offersTableBody.appendChild(tr);
            });
          })
          .catch(error => {
            console.error('Error loading offers:', error);
          });
      }
      
      // Initial load of offers
      loadOffers();
      
      // Function to redirect to the edit offer page
      window.editOffer = function(offerId) {
        window.location.href = 'edit_offer.php?id=' + offerId;
      }
      
      // Function to delete an offer
      window.deleteOffer = function(offerId) {
        if(confirm('Are you sure you want to delete this offer?')) {
          fetch('delete_offer.php?id=' + offerId)
            .then(response => response.json())
            .then(result => {
              if(result.success) {
                loadOffers();
              } else {
                alert('Error deleting offer');
              }
            })
            .catch(error => console.error('Error:', error));
        }
      }
    });
  </script>
</body>
</html>
<?php ob_end_flush(); ?>
