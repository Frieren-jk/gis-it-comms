<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="css/login.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="left-panel"></div>

    <div class="right-panel">
      <div class="content-wrapper">
        <div class="logo-container">
          <img src="img/logo.png" alt="Logo" class="logo-img">
          <div class="logo-label">GIS-IT COMMS</div>
        </div>

        <div class="login-container">
          <h2>Login</h2>
          <form id="loginForm">
            <input type="text" placeholder="Username" name="username" />
            <input type="password" placeholder="Password" name="password" />

           

            <button type="submit">Login</button>
           
          </form>
        </div>
      </div>
    </div>
  </div>

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