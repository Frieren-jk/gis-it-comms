<?php
session_start();

// Prevent caching so back button doesn't show protected pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$is_guest = isset($_SESSION['guest']) && $_SESSION['guest'] === true;
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_guest && !$is_logged_in) {
    // Not guest, not logged in — redirect to login
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Records - GIS-IT COMMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main content -->
    <div class="main-content">
        <h2 class="mb-4">Records</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBacklogModal">
                    Add Entry
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                    Export
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete Records
                </button>
            </div>
            <button id="toggleActionsBtn" class="btn btn-secondary">
                Show Actions
            </button>
        </div>

        <!-- Modal: Add Entry -->
    <div class="modal fade" id="addBacklogModal" tabindex="-1" aria-labelledby="addBacklogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form id="addRecordForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBacklogModalLabel">Add New Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Reference No.</label>
                                <input type="text" class="form-control" name="ref_no" id="refRecordInput">
                            </div>
                            <div class="col-md-4">  
                                <label class="form-label">Particulars</label>
                                <input type="text" class="form-control" name="particulars" id="particularsRecordInput">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sender</label>
                                <input type="text" class="form-control" name="sender" id="senderRecordInput">
                            </div>
        
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- Modal: Delete Records -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" id="deleteForm" action="actions/delete.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Delete Records</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Select Month</label>
                                <select name="month" id="deleteMonth" class="form-select" required>
                                    <option value="">-- Month --</option>
                                    <option value="all">All Months</option>
                                    <?php
                                    for ($m = 1; $m <= 12; $m++) {
                                        echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Select Year</label>
                                <select name="year" id="deleteYear" class="form-select" required>
                                    <option value="">-- Year --</option>
                                    <?php
                                    for ($y = 2023; $y <= date('Y'); $y++) {
                                        echo "<option value='$y'>$y</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="confirm_delete" value="1">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="exportDeleteBtn">Export & Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Export Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form id="exportForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exportModalLabel">Export Records</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Select Month</label>
                                <select name="month" id="exportMonth" class="form-select" required>
                                    <option value="">-- Month --</option>
                                    <option value="all">All Months</option>
                                    <?php
                                    for ($m = 1; $m <= 12; $m++) {
                                        echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Select Year</label>
                                <select name="year" id="exportYear" class="form-select" required>
                                    <option value="">-- Year --</option>
                                    <?php
                                    for ($y = 2023; $y <= date('Y'); $y++) {
                                        echo "<option value='$y'>$y</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Export CSV</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <table id="backlogTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ReferenceNo.</th>
                    <th>Particulars</th>
                    <th>Sender</th>
                    <th>CreatedAt</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
             
            </tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>
</body>
</html>