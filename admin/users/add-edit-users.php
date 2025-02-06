<?php
// add_edit_user.php
session_start();
session_regenerate_id(true);
ob_start();

define('ALLOW_ACCESS', true);
include '../panel.php';
require '../../app/config/conn.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Add/Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <style>
        body {
            background: #ffffff;
            color: #000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            box-shadow: none;
        }

        .card-header {
            background-color: #ffffff;
            color: #000;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
        }

        .page-header {
            margin-top: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #000;
        }

        .export-btn-container {
            margin-bottom: 20px;
            text-align: right;
        }

        .form-required:after {
            content: "*";
            color: red;
            margin-left: 2px;
        }

        .alert-container {
            margin-top: 15px;
        }

        .avatar-preview {
            width: 100px;
            height: 100px;
            border: 2px solid #ddd;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="page-header text-center mb-5">
            <h1>Add / Edit User</h1>
            <p class="lead">Manage user accounts with ease</p>
        </div>

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs" id="userTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="form-tab" data-toggle="tab" href="#formTab" role="tab" aria-controls="formTab" aria-selected="true">Add/Edit User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="list-tab" data-toggle="tab" href="#listTab" role="tab" aria-controls="listTab" aria-selected="false">User List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="analytics-tab" data-toggle="tab" href="#analyticsTab" role="tab" aria-controls="analyticsTab" aria-selected="false">Analytics</a>
            </li>
        </ul>

        <div class="tab-content" id="userTabsContent">
            <!-- Add/Edit Form Tab -->
            <div class="tab-pane fade show active" id="formTab" role="tabpanel" aria-labelledby="form-tab">
                <div class="card mt-3">
                    <div class="card-header">
                        <span id="formTitle">Add New User</span>
                    </div>
                    <div class="card-body">
                        <!-- Alert for messages -->
                        <div id="formAlert" class="alert-container"></div>
                        <form id="userForm" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" id="user_id" value="<?= isset($user) ? $user['id'] : ''; ?>">
                           
                            <div class="form-group">
                                <label class="form-required">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-required">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-required">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                            </div>
                            <div class="form-group" id="passwordFields">
                                <label class="form-required">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <small class="form-text text-muted">Leave blank if not changing password (for edit mode).</small>
                            </div>
                            <div class="form-group">
                                <label class="form-required">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="">Select Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg" id="formSubmitBtn"><?= isset($user) ? "Update User" : "Add User" ?></button>
                            <button type="button" class="btn btn-secondary btn-lg" id="formResetBtn">Reset</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- User List Tab -->
            <div class="tab-pane fade" id="listTab" role="tabpanel" aria-labelledby="list-tab">
                <div class="card mt-3">
                    <div class="card-header">
                        User List
                        <button id="exportListBtn" class="btn btn-outline-secondary btn-sm float-right">
                            <i class="fas fa-file-export"></i> Export CSV
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="userListTable" class="table table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- User list is loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div class="tab-pane fade" id="analyticsTab" role="tabpanel" aria-labelledby="analytics-tab">
                <div class="card mt-3">
                    <div class="card-header">
                        User Role Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="roleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap Bundle, DataTables, and Chart.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable for user list
            var userListTable = $('#userListTable').DataTable({
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
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-sm btn-info editUserBtn" data-id="' + row.id + '"><i class="fas fa-edit"></i> Edit</button>';
                        },
                        "orderable": false
                    }
                ],
                responsive: true,
                order: [
                    [0, "desc"]
                ]
            });

            // Load analytics: Role distribution chart
            $.ajax({
                url: 'ajax_get_role_data.php', // Endpoint must return JSON {labels: [...], data: [...]}
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var ctx = document.getElementById('roleChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                data: response.data,
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 99, 132, 0.7)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            });

            // Export CSV from User List
            $('#exportListBtn').click(function() {
                window.location.href = 'export_users.php';
            });

            // Handle Edit button click from user list
            $('#userListTable tbody').on('click', '.editUserBtn', function() {
                var userId = $(this).data('id');
                // Load user data via AJAX
                $.ajax({
                    url: 'ajax_get_single_user.php',
                    method: 'GET',
                    data: {
                        id: userId
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Populate the form with user data
                        $('#user_id').val(response.id);
                        $('#username').val(response.username);
                        $('#email').val(response.email);
                        $('#phone').val(response.phone);
                        $('#role').val(response.role);
                        $('#status').val(response.status);
                        // Remove required attribute from password field when editing
                        $('#password').prop('required', false);
                        $('#formTitle').text("Edit User");
                        $('#formSubmitBtn').text("Update User");
                        // Switch to the Add/Edit tab
                        $('#form-tab').tab('show');
                    }
                });
            });

            // Handle form submission via AJAX for Add/Edit
            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'user_action.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#formAlert').html('<div class="alert alert-success">' + response + '</div>');
                        // Reset form after submission
                        $('#userForm')[0].reset();
                        $('#user_id').val('');
                        $('#password').prop('required', true);
                        $('#formTitle').text("Add New User");
                        $('#formSubmitBtn').text("Add User");
                        // Reload the user list table and analytics
                        userListTable.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        $('#formAlert').html('<div class="alert alert-danger">An error occurred: ' + error + '</div>');
                    }
                });
            });

            // Reset form button functionality
            $('#formResetBtn').click(function() {
                $('#userForm')[0].reset();
                $('#user_id').val('');
                $('#password').prop('required', true);
                $('#formTitle').text("Add New User");
                $('#formSubmitBtn').text("Add User");
                $('#formAlert').html('');
            });

            // Preview image for avatar file input
            $("#avatar").change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#avatarPreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>

    <!-- Panel Navigation Scripts -->
    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>
</body>

</html>
<?php
ob_end_flush();
$conn->close();
?>