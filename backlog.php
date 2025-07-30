<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Backlogs - GIS-IT COMMS</title>
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
        <h2 class="mb-4">Backlogs</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBacklogModal">
                Add Entry
            </button>
            <button id="toggleActionsBtn" class="btn btn-secondary">
                Show Actions
            </button>
        </div>

        <table id="backlogTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Action ID</th>
                    <th>Reference #</th>
                    <th>Particulars</th>
                    <th>Sender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
             
            </tbody>
        </table>
    </div>

    <!-- Modal: Add Entry -->
    <div class="modal fade" id="addBacklogModal" tabindex="-1" aria-labelledby="addBacklogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form id="addBacklogForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBacklogModalLabel">Add New Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Reference No.</label>
                                <input type="text" class="form-control" name="id" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Particulars</label>
                                <input type="text" class="form-control" name="particulars" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sender</label>
                                <input type="text" class="form-control" name="sender">
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert (again for safety if reused in script.js) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script src="js/script.js"></script>

</body>

</html>