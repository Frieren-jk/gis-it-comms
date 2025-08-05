 document.querySelectorAll('.dashboard-link').forEach(link => {
    link.addEventListener('click', function () {
      const url = this.getAttribute('data-url');
      document.getElementById('goToDashboard').href = url;
    });
  });