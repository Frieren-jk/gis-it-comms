$(document).ready(function (){
$('#loginForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: 'actions/process-login.php',
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        if (response.status === 'success') {
          window.location.href = 'index.php';
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: response.message || 'Invalid credentials.'
          });
        }
      },
      error: function (xhr) {
        let message = 'Could not contact server or received invalid response.';
        try {
          const res = JSON.parse(xhr.responseText);
          if (res.message) message = res.message;
        } catch (e) {
          console.warn('Invalid JSON:', xhr.responseText);
        }

        Swal.fire({
          icon: 'error',
          title: 'Login Error', 
          text: message
        });
      }
    });
  })});