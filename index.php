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
    <title>Internal Communication System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>



<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>




    <!-- Main content -->
    <div class="main-content">
        <div id="hover-preview" class="mb-4" style="display: none;">
            <div class="card">

                <div class="card-body text-center">
                    <img id="preview-img" src="" alt="Preview" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>

        
        <div id="secret-popup"
            style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999; background-color: rgba(0,0,0,0.9);">

            <button id="close-popup"
                style="position: absolute; top: 20px; right: 30px; background: none; border: none; color: #fff; font-size: 30px; cursor: pointer; z-index: 10000;">✕</button>

            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                <img src="img/ojt.png" alt="Secret Image"
                    style="max-width: 90vw; max-height: 90vh; object-fit: contain; border-radius: 10px;">
            </div>
        </div>



        <?php if (!$is_guest): ?>
            <h2 class="mb-4">Communication Data</h2>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEntryModal">
                    Add Entry
                </button>
                <button id="toggleActionsBtn" class="btn btn-secondary">
                    Show Actions
                </button>

            </div>
            <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ReferenceNo.</th>
                        <th>Particulars</th>
                        <th>Sender</th>
                        <th>DateReceived</th>
                        <th>Remarks</th>
                        <th>AssignTo</th>
                        <th>DateAssign</th>
                        <th>ActionTaken</th>
                        <th>Status</th>
                        <th>File-To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>




            <!-- Modal -->
            <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="previewModalLabel">Dashboard Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>This will open the Quezon City UGIS Dashboard in a new tab.</p>
                            <a href="#" target="_blank" class="btn btn-primary" id="goToDashboard">Go to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>




            <div class="modal fade" id="addEntryModal" tabindex="-1" aria-labelledby="addEntryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEntryModalLabel">Add New Entry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form id="addEntryForm">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="form-label">Reference No.</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" name="id" id="refInput"
                                                autocomplete="off">
                                            <div id="refDropdown" class="dropdown-menu show"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Particulars</label>
                                        <input type="text" class="form-control" name="particulars" id="particularsInput">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Sender</label>
                                        <input type="text" class="form-control" name="sender" id="senderInput">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date Received</label>
                                        <input type="date" class="form-control" name="date_received" id="dateReceived">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Remarks / Instruction</label>
                                        <input type="text" class="form-control" name="remarks" id="remarks">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Assign To</label>
                                        <input type="text" class="form-control" name="assign_to" id="assignToInput">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date Assign</label>
                                        <input type="date" class="form-control" name="date_assign" id="dateAssign">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Action Taken</label>
                                        <input type="text" class="form-control" name="action_taken" id="actionTaken">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="">Select status</option>

                                            <option value="Pending-Priority">Pending-Priority</option>
                                            <option value="Pending-Common">Pending-Common</option>
                                            <option value="Take Note">Take Note</option>

                                            <option value="Completed-Priority">Completed-Priority</option>
                                            <option value="Completed-Common">Completed-Common</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>

                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">FILE TO</label>
                                        <input type="text" class="form-control" name="file_to" id="fileToInput">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>


                                    <button type="submit" class="btn btn-primary">Add Entry</button>

                                    <button type="button" class="btn btn-success d-none" id="updateEntryBtn">Update
                                        Entry</button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
        <?php endif; ?>


    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="js/script.js"></script>


</body>

</html>