$(document).ready(function () {
  // Initialize DataTable with AJAX source
  const table = $('#dataTable').DataTable({
    ajax: "actions/fetch_data.php",
    columns: [
      { data: "id" },
      { data: "particulars" },
      { data: "sender" },
      { data: "date_received" },
      { data: "remarks" },
      { data: "assign_to" },
      { data: "date_assign" },
      { data: "action_taken" },
      { data: "status" },
      { data: "file_to" },
      { data: "actions" }  // for dropdown buttons
    ]
  });

  // Handle form submission for adding entry
  $('#addEntryForm').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      url: 'add_entry.php',
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        $('#addEntryModal').modal('hide');
        $('#addEntryForm')[0].reset();

        // Reload DataTable to show new row
        table.ajax.reload();
        alert("Entry added!");
      },
      error: function () {
        alert("Failed to add entry.");
      }
    });
  });
});
