<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Internal Communication System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <div class="logo-container">
            <img src="img/logo.png" alt="Logo" class="logo-img">
            <div class="logo-label">GIS-IT COMMS</div>
        </div>



    </div>

    <!-- Main content -->
    <div class="main-content">
        <h2 class="mb-4">Internal and External Communication Data</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEntryModal">
                Add Entry
            </button>

        </div>
        <table id="dataTable" class="table table-striped table-bordered">
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
            <div class="modal-dialog modal-xl">
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
                                    <input type="text" class="form-control" name="id">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Particulars</label>
                                    <input type="text" class="form-control" name="particulars">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sender</label>
                                    <input type="text" class="form-control" name="sender">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date Received</label>
                                    <input type="date" class="form-control" name="date_received">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Remarks / Instruction</label>
                                    <input type="text" class="form-control" name="remarks">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Assign To</label>
                                    <input type="text" class="form-control" name="assign_to">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date Assign</label>
                                    <input type="date" class="form-control" name="date_assign">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Action Taken</label>
                                    <input type="text" class="form-control" name="action_taken">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <input type="text" class="form-control" name="status">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">FILE TO</label>
                                    <input type="text" class="form-control" name="file_to">
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
        </div>

    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script src="js/script.js"></script>

</body>

</html>