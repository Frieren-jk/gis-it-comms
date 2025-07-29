
let table; // Declare globally so all functions can use it
let actionsVisible = true;

$(document).ready(function () {
  // Initialize DataTable with AJAX source
  table = $('#dataTable').DataTable({
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
      { data: "actions" }  // Initially hidden
    ]
  });

  // Form submission for adding entry
  $('#addEntryForm').on('submit', function (e) {
    e.preventDefault();

    const id = $('input[name="id"]').val().trim();
    if (!id) {
      Swal.fire({
        icon: 'warning',
        title: 'Missing Reference No.',
        toast: true,
        position: 'top-end',
        iconColor: 'white',
        customClass: {
          popup: 'colored-toast'
        },
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
      });
      return; // stop submission
    }



    const formData = $(this).serialize(); // this includes the <select> value

    $.ajax({
      url: 'actions/add_entry.php',
      method: 'POST',
      data: formData,
      success: function (response) {
        // show SweetAlert
        Swal.fire({
          icon: 'success', // or 'error', 'warning', etc.
          title: 'Entry added successfully!',
          toast: true,
          position: 'top-end',
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false,
          iconColor: 'white', // ensures icon uses white color
          customClass: {
            popup: 'colored-toast'
          }
        });

        $('#addEntryModal').modal('hide'); // hide modal
        $('#addEntryForm')[0].reset();   // reset form
        table.ajax.reload();             // reload DataTable (if using DataTables)
      },
      error: function () {
        Swal.fire({
          title: 'Error!',
          text: 'Something went wrong.',
          icon: 'error',
          toast: true,
          position: 'top-end',
          timer: 2000,
          showConfirmButton: false,
          timerProgressBar: true
        });
      }
    });
  });


  // Toggle Actions Column
  $('#toggleActionsBtn').on('click', function () {
    actionsVisible = !actionsVisible;

    // Toggle column visibility without breaking layout
    table.column(10).visible(actionsVisible, false);

    // Force recalculation
    setTimeout(() => {
      table.columns.adjust().draw(false);
    }, 100); // slight delay helps with rendering


    $(this).text(actionsVisible ? 'Hide Actions' : 'Show Actions');
  });
});

// Status dropdown update handler
function updateStatus(id, newStatus) {
  $.ajax({
    url: 'actions/update_status.php',
    method: 'POST',
    data: { id: id, status: newStatus },
    success: function () {
      $('#dataTable').DataTable().ajax.reload(null, false);
    },
    error: function () {
      alert("Failed to update status.");
    }
  });
}


function selectAction(el, id, action) {
  const group = $(el).closest('.btn-group');
  const mainBtn = group.find('.action-main-btn');

  // Update button label and store new action in data attribute
  mainBtn.text(action);
  mainBtn.data('action', action); // <-- important
}

// Use `on` to handle dynamically loaded buttons
$(document).on('click', '.action-main-btn', function () {
  const id = $(this).data('id');
  const action = $(this).data('action');

  if (action === 'Update') {
    alert('Updating ID: ' + id);
    // Your update logic here
  } else if (action === 'Delete') {
    if (confirm('Are you sure to delete ID: ' + id + '?')) {
      $.post('actions/delete_entry.php', { id }, function (res) {
        $('#dataTable').DataTable().ajax.reload(null, false);
      });
    }
  }
});
