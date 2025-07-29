<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Internal Communication System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <div class="logo-container">
            <img src="img/logo.png" alt="Logo" class="logo-img">
        </div>
        <nav class="nav flex-column p-2">
            <a class="nav-link" href="#">Sample</a>
            <a class="nav-link" href="#">Sample </a>
            <a class="nav-link" href="#">Sample </a>
            <a class="nav-link" href="#">Sample</a>
        </nav>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h2 class="mb-4">Table</h2>
        <table id="messagesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td contenteditable="true">1</td>
                    <td contenteditable="true">Ana</td>
                    <td contenteditable="true">Hello team, update complete!</td>
                    <td contenteditable="true">2025-07-29 14:00</td>
                </tr>
                <tr>
                    <td contenteditable="true">2</td>
                    <td contenteditable="true">Juan</td>
                    <td contenteditable="true">Checking system status.</td>
                    <td contenteditable="true">2025-07-29 14:05</td>

                    <!-- Add more rows dynamically later -->
            </tbody>
        </table>
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