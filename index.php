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
        <h2 class="mb-4">Internal and External Communication Data</h2>
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
                    <th>Reference #</th>
                    <th>Particulars</th>
                    <th>Sender</th>
                    <th>Date Received</th>
                    <th>Remarks / Instruction</th>
                    <th>Assign To</th>
                    <th>Date Assign</th>
                    <th>Action Taken</th>
                    <th>Status</th>
                    <th>File To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>


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
                                    <select class="form-select" name="status" required id="status">
                                        <option value="">Select status</option>

                                        <option value="Pending-Priority">Pending-Priority</option>
                                        <option value="Pending-Common">Pending-Common</option>
                                        <option value="In Progress">In Progress</option>
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