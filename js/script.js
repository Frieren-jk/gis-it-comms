
let table;
let backlogTable
let actionsVisible = true;

$(document).ready(function () {

  const statusMap = {
    "pending_priority": "Pending - Priority",
    "pending_common": "Pending - Common",
    "in_progress": "In Progress",
    "take_note": "Take Note",
    "completed_priority": "Completed - Priority",
    "completed_common": "Completed - Common",
    "cancelled": "Cancelled"
  };


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
      {
        data: "status",
        render: function (data, type, row) {

          if (type === 'filter' || type === 'sort') {
            return row.status_raw;
          }

          return statusMap[data] || data;
        }
      },
      { data: "file_to" },
      { data: "actions" }
    ]


  });

  backlogTable = $('#backlogTable').DataTable({
    ajax: "actions/fetch_backlog.php",
    columns: [
      { data: "action_id" },
      { data: "ref_no" },
      { data: "particulars" },
      { data: "sender" },

      {
        data: "action",
        render: function (data, type, row) {
          return `<button class="btn btn-sm btn-primary">View</button>`;
        }
      }
    ]
  });



  $('#addEntryForm').on('submit', function (e) {
    e.preventDefault();

    const mode = $(this).data('mode') || 'add'; // default to add
    const updateId = $(this).data('update-id');

    const id = $('#refInput').val().trim();
    if (!id) {
      Swal.fire({
        icon: 'warning',
        title: 'Missing Reference No.',
        toast: true,
        position: 'top-end',
        iconColor: 'white',
        customClass: { popup: 'colored-toast' },
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
      });
      return;
    }

    const formData = $(this).serialize() + (mode === 'update' ? `&id=${updateId}` : '');

    const url = mode === 'update' ? 'actions/update_entry.php' : 'actions/add_entry.php';
    const successMessage = mode === 'update' ? 'Entry updated successfully!' : 'Entry added successfully!';

    $.ajax({
      url,
      method: 'POST',
      data: formData,
      success: function () {
        Swal.fire({
          icon: 'success',
          title: successMessage,
          toast: true,
          position: 'top-end',
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false,
          iconColor: 'white',
          customClass: { popup: 'colored-toast' }
        });

        $('#addEntryModal').modal('hide');
        $('#addEntryForm')[0].reset();
        table.ajax.reload();

        // Reset mode
        $('#addEntryForm').removeData('mode').removeData('update-id');
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
          timerProgressBar: true,
          customClass: { popup: 'colored-toast' }
        });
      }
    });
  });



  $('#toggleActionsBtn').on('click', function () {
    actionsVisible = !actionsVisible;
    
    table.column(10).visible(actionsVisible, false);
    backlogTable.column(4).visible(actionsVisible, false);

    setTimeout(() => {
      table.columns.adjust().draw(false);
      backlogTable.columns.adjust().draw(false);

    }, 100);


    $(this).text(actionsVisible ? 'Hide Actions' : 'Show Actions');
  });


  const $refInput = $('#refInput');
  const $dropdown = $('#refDropdown');

  $refInput.on('input', function () {
    const query = $(this).val();

    if (query.length < 2) {
      $dropdown.hide();
      return;
    }

    $.ajax({
      url: 'actions/search_backlogs.php',
      method: 'GET',
      data: { q: query },
      success: function (response) {
        const results = JSON.parse(response);
        $dropdown.empty();

        if (results.length === 0) {
          $dropdown.hide();
          return;
        }

        results.forEach(item => {
          const button = $(`
            <button type="button" class="dropdown-item"
              data-ref_no="${item.ref_no}"
              data-particulars="${item.particulars}"
              data-sender="${item.sender}">
              ${item.ref_no} - ${item.particulars} - ${item.sender}
            </button>
          `);

          button.on('click', function () {
            const refNo = $(this).data('ref_no');
            const particulars = $(this).data('particulars');
            const sender = $(this).data('sender');

            console.log({ refNo, particulars, sender });

            $refInput.val(refNo);
            $('#particularsInput').val(particulars);
            $('#senderInput').val(sender);

            $dropdown.hide();
          });

          $dropdown.append(button);
        });

        $dropdown.show();
      },
      error: function (xhr, status, err) {
        console.error('AJAX Error:', err);
      }
    });
  });

  // Hide dropdown if clicked outside
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#refInput, #refDropdown').length) {
      $dropdown.hide();
    }
  });


  //end 
});


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


  mainBtn.text(action);
  mainBtn.data('action', action);
}


$(document).on('click', '.action-main-btn', function () {
  const id = $(this).data('id');
  const action = $(this).data('action');

  if (action === 'Update') {
    // Get row data from DataTable
    const rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();

    // Fill modal fields
    $('#addEntryModalLabel').text('Update Entry');
    $('#addEntryForm button[type="submit"]').text('Update Entry');
    $('#refInput').val(rowData.id);
    $('#particularsInput').val(rowData.particulars);
    $('#senderInput').val(rowData.sender);
    $('#dateReceived').val(rowData.date_received);
    $('#remarks').val(rowData.remarks);
    $('#assignToInput').val(rowData.assign_to);
    $('#dateAssign').val(rowData.date_assign);
    $('#actionTaken').val(rowData.action_taken);
    $('#status').val(rowData.status);
    $('#fileToInput').val(rowData.file_to);

    // Store mode info (e.g., update vs add)
    $('#addEntryForm').data('mode', 'update');
    $('#addEntryForm').data('update-id', rowData.id);

    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('addEntryModal'));
    modal.show();
  }

  else if (action === 'Delete') {
    Swal.fire({
      title: 'Are you sure?',
      text: 'This will delete entry with ID: ' + id,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        $.post('actions/delete_entry.php', { id }, function (res) {
          $('#dataTable').DataTable().ajax.reload(null, false);
          Swal.fire({
            title: 'Deleted!',
            text: 'Entry has been deleted.',
            icon: 'success',
            toast: true,
            position: 'top-end',
            timer: 2000,
            customClass: {
              popup: 'colored-toast'
            },
            showConfirmButton: false,
            timerProgressBar: true
          });
        }).fail(() => {
          Swal.fire({
            title: 'Error!',
            text: 'Failed to delete entry.',
            icon: 'error',
            toast: true,
            position: 'top-end',
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true
          });
        });
      }
    });
  }
});


document.addEventListener('DOMContentLoaded', function () {
  const links = document.querySelectorAll('.nav-link');
  const currentPath = window.location.pathname.split("/").pop();

  links.forEach(link => {
    const linkPath = link.getAttribute('href');
    if (linkPath === currentPath) {
      link.classList.add('active');
    }
  });
});


$('#addEntryModal').on('hidden.bs.modal', function () {
  $('#refInput').val('');
  $('#particularsInput').val('');
  $('#senderInput').val('');
  $('#refDropdown').hide();
  $('#status').val('');
  $('#remarks').val('');
  $('#actionTaken').val('');
  $('#assignToInput').val('');
  $('#dateReceived').val('');
  $('#dateAssign').val('');
  $('#fileToInput').val('');
});

/* Circles */
