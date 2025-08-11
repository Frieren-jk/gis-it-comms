let table;
let backlogTable
let actionsVisible = false;
let lastDataHash = '';

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
      { data: "actions", visible: false }
    ]


  });

  backlogTable = $('#backlogTable').DataTable({
    ajax: "actions/fetch_backlog.php",
    columns: [
      { data: "ref_no" },
      { data: "particulars" },
      { data: "sender" },
      { data: "created_at" },
      {
        data: "actions",
        orderable: false,
        searchable: false,
        visible: false
      }
    ]
  });



  // Add communication entry

  $('#addEntryForm').on('submit', function (e) {
    e.preventDefault();

    const mode = $(this).data('mode') || 'add';
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

    const status = $('#status').val();
    if (!status || status.trim() === '') {
      Swal.fire({
        icon: 'warning',
        title: 'Missing Status',
        text: 'Please select a status from the dropdown.',
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
      success: function (response) {
        if (typeof response === 'string') {
          try {
            response = JSON.parse(response);
          } catch (e) {
            console.error('Invalid JSON response', response);
            Swal.fire({
              title: 'Error!',
              text: 'Unexpected response from server.',
              icon: 'error',
              toast: true,
              position: 'top-end',
              timer: 2500,
              showConfirmButton: false,
              timerProgressBar: true,
              customClass: { popup: 'colored-toast' }
            });
            return;
          }
        }

        if (response.success) {
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
          $('#addEntryForm').removeData('mode').removeData('update-id');
        } else {
          let errorMessage = 'Something went wrong ';
          if (response.code === 1062) {
            errorMessage = 'Reference No. already exists!';
            $('#refInput').addClass('is-invalid');
          }

          Swal.fire({
            title: 'Error!',
            text: errorMessage,
            icon: 'error',
            toast: true,
            position: 'top-end',
            timer: 2500,
            showConfirmButton: false,
            timerProgressBar: true,
            customClass: { popup: 'colored-toast' }
          });
        }
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

  //end

  // add records 

  $('#addRecordForm').on('submit', function (e) {
    e.preventDefault();

    const mode = $(this).data('mode') || 'add';
    const updateId = $(this).data('update-id');


    const refNo = $('#refRecordInput').val().trim();
    const particulars = $('#particularsRecordInput').val().trim();
    const sender = $('#senderRecordInput').val().trim();

    if (!refNo) {
      Swal.fire({
        icon: 'warning',
        title: 'Missing Required Fields',
        text: 'Reference No.',
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
    const url = mode === 'update' ? 'actions/update_backlog.php' : 'actions/add_backlog.php';
    const successMessage = mode === 'update' ? 'Record updated successfully!' : 'Record added!';

    $.ajax({
      url,
      method: 'POST',
      data: formData,
      success: function (response) {
        console.log('Raw Response:', response); // add this line
        try {
          response = JSON.parse(response);
        } catch (e) {
          Swal.fire('Error', 'Unexpected server response.', 'error');
          return;
        }
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: successMessage,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: { popup: 'colored-toast' }
          });

          $('#addBacklogModal').modal('hide');
          $('#addRecordForm')[0].reset();
          backlogTable.ajax.reload();
          $('#addRecordForm').removeData('mode').removeData('update-id');
        } else {
          let errorMessage = 'Could not process record.';
          if (response.code === 1062) {
            errorMessage = 'Reference No. already exists!';
            $('#refRecordInput').addClass('is-invalid');
          }

          Swal.fire('Error', response.error || errorMessage, 'error');
        }
      },
      error: function () {
        Swal.fire('Error', 'Request failed.', 'error');
      }
    });
  });

  /* login */
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



  // Dropdown search records
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

  //Update Sidebar Statistics Donout

  function updateSidebarStats() {
    $.ajax({
      url: 'actions/get_stats.php',
      method: 'GET',
      dataType: 'json',
      success: function (data) {
        $('#total-comms').text(data.total);
        $('#complete-val').text(data.completed);
        $('#complete-ratio').text(`${data.completed}/${data.total}`);
        $('#priority-val').text(data.priority);
        $('#priority-ratio').text(`${data.priority}/${data.total}`);
        $('#common-val').text(data.common);
        $('#common-ratio').text(`${data.common}/${data.total}`);

        // Use ID selectors to target specific boxes
        $('#complete-circle').css('background-image', `conic-gradient(#28a745 ${data.complete_percent}%, #444 ${data.complete_percent}%)`);
        $('#priority-circle').css('background-image', `conic-gradient(#dc3545 ${data.priority_percent}%, #444 ${data.priority_percent}%)`);
        $('#common-circle').css('background-image', `conic-gradient(#ffc107 ${data.common_percent}%, #444 ${data.common_percent}%)`);
      },
      error: function (xhr, status, err) {
        console.error("Sidebar update failed:", err);
      }
    });
  }

function updateDataTable(table, url) {
  $.ajax({
    url: url,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
      if (!response.data) return;

      const existingData = table.rows().data().toArray();
      const newData = response.data;

      let changed = false;

      // Loop through new data
      newData.forEach((newRow, index) => {
        const existingRow = existingData.find(r => r.id == newRow.id);
        if (!existingRow || JSON.stringify(existingRow) !== JSON.stringify(newRow)) {
          changed = true;

          // Update row by ID if exists
          const rowIndex = table.rows().eq(0).filter((i) => {
            return table.row(i).data().id == newRow.id;
          });

          if (rowIndex.length > 0) {
            table.row(rowIndex[0]).data(newRow);
          } else {
            table.row.add(newRow); // If new row, add it
          }
        }
      });

      if (changed) {
        table.draw(false);
      }
    },
    error: function (xhr, status, error) {
      console.error(`Failed to fetch ${url}`, error);
    }
  });
}

 
  updateSidebarStats();
  setInterval(updateSidebarStats, 10000);

 setInterval(() => {
  updateDataTable(table, 'actions/fetch_data.php');
 
}, 10000);

  
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
  const splitBtn = group.find('.dropdown-toggle');

  // Change button text and data-action
  mainBtn.text(action);
  mainBtn.data('action', action);

  // Reset button colors
  mainBtn.removeClass('btn-success btn-danger btn-secondary');
  splitBtn.removeClass('btn-success btn-danger btn-secondary');

  // Apply color based on action
  if (action === 'Delete') {
    mainBtn.addClass('btn-danger');
    splitBtn.addClass('btn-danger');
  } else {
    mainBtn.addClass('btn-success');
    splitBtn.addClass('btn-success');
  }
}




$(document).on('click', '.action-main-btn', function () {
  const id = $(this).data('id');

  const action = $(this).data('action');

  const table = $(this).closest('table').attr('id');
  const dt = $('#' + table).DataTable();
  const rowData = dt.row($(this).closest('tr')).data();

  // ---------- UPDATE ----------
  if (action === 'Update') {
    if (table === 'backlogTable') {
      // Fill #addRecordForm fields

      $('#addBacklogModalLabel').text('Update Record');
      $('#addRecordForm button[type="submit"]').text('Update Record');

      $('#refRecordInput').val(rowData.ref_no || '').prop('readonly', true);
      $('#particularsRecordInput').val(rowData.particulars || '');
      $('#senderRecordInput').val(rowData.sender || '');

      $('#addRecordForm').data('mode', 'update');
      $('#addRecordForm').data('update-id', rowData.ref_no);

      const modal = new bootstrap.Modal(document.getElementById('addBacklogModal'));
      modal.show();

    } else {
      // Fill #addEntryForm fields
      $('#addEntryModalLabel').text('Update Entry');
      $('#addEntryForm button[type="submit"]').text('Update Entry');

      $('#refInput').val(rowData.id || '');
      $('#particularsInput').val(rowData.particulars || '');
      $('#senderInput').val(rowData.sender || '');
      $('#dateReceived').val(rowData.date_received || '');
      $('#remarks').val(rowData.remarks || '');
      $('#assignToInput').val(rowData.assign_to || '');
      $('#dateAssign').val(rowData.date_assign || '');
      $('#actionTaken').val(rowData.action_taken || '');
      $('#status').val(rowData.status_raw?.trim() || '');
      $('#fileToInput').val(rowData.file_to || '');

      $('#addEntryForm').data('mode', 'update');
      $('#addEntryForm').data('update-id', rowData.id);

      const modal = new bootstrap.Modal(document.getElementById('addEntryModal'));
      modal.show();
    }
  }

  // ---------- DELETE ----------
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
        const deleteUrl = table === 'backlogTable'
          ? 'actions/delete_backlogs.php'
          : 'actions/delete_entry.php';

        $.post(deleteUrl, { id }, function (res) {
          dt.ajax.reload(null, false);
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





$('#addBacklogModal').on('hidden.bs.modal', function () {
  $('#refRecordInput').val('').prop('readonly', false);
  $('#particularsRecordInput').val('');
  $('#senderRecordInput').val('');
});

// Export & Delete and Export Only modal logic for records.php
$(document).ready(function () {
  // Export & Delete button
  const exportDeleteBtn = document.getElementById('exportDeleteBtn');
  if (exportDeleteBtn) {
    exportDeleteBtn.addEventListener('click', function () {
      const form = document.getElementById('deleteForm');
      const month = document.getElementById('deleteMonth').value;
      const year = document.getElementById('deleteYear').value;

      if (!year) {
        Swal.fire('Missing Fields', 'Please select a year.', 'warning');
        return;
      }

      // First export
      let url = `actions/delete.php?export=1&year=${year}`;
      if (month) url += `&month=${month}`;
      window.open(url, '_blank');

      // Show Swal for export
      Swal.fire({
        icon: 'success',
        title: 'Exported!',
        text: 'Records have been exported as CSV.',
        timer: 2000,
        showConfirmButton: false
      });

      // Then confirm delete
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete these records?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // Submit the form directly
        }
      });
    });
  }

  // Export only (from export modal)
  const exportForm = document.getElementById('exportForm');
  if (exportForm) {
    exportForm.addEventListener('submit', function (e) {
      e.preventDefault();
      let month = document.getElementById('exportMonth').value;
      const year = document.getElementById('exportYear').value;
      if (!year) {
        Swal.fire('Missing Fields', 'Please select a year.', 'warning');
        return;
      }
      let url = `actions/delete.php?export=1&year=${year}`;
      if (month) url += `&month=${month}`;
      window.open(url, '_blank');
      bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
      // Show Swal for export
      Swal.fire({
        icon: 'success',
        title: 'Exported!',
        text: 'Records have been exported as CSV.',
        timer: 2000,
        showConfirmButton: false
      });
    });
  }

  // Show Swal after redirect from delete
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('deleted') === '1') {
    Swal.fire({
      icon: 'success',
      title: 'Exported & Deleted!',
      text: 'Records have been exported and deleted.',
      timer: 2500,
      showConfirmButton: false
    });
    // Remove the query param from URL
    if (window.history.replaceState) {
      const url = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, url);
    }
  }
});

document.getElementById("logout-btn").addEventListener("click", function (e) {
  const isGuest = this.dataset.guest === "true";

  if (isGuest) {
    // Directly logout guest without Swal
    window.location.href = 'actions/end_guest_session.php';
    return;
  }

  e.preventDefault(); // Prevent default link behavior

  Swal.fire({
    title: 'Are you sure?',
    text: "You will be logged out.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, logout',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'actions/logout.php';
    }
  });
});


//preview img guest

document.addEventListener('DOMContentLoaded', function () {
  const previewTriggers = document.querySelectorAll('.preview-trigger');
  const previewBox = document.getElementById('hover-preview');
  const previewImg = document.getElementById('preview-img');

  previewTriggers.forEach(link => {
    link.addEventListener('mouseenter', function () {
      const imgSrc = this.getAttribute('data-img');
      previewImg.src = imgSrc;
      previewBox.style.display = 'block';
    });

    link.addEventListener('mouseleave', function () {
      previewBox.style.display = 'none';
      previewImg.src = '';
    });
  });
});


document.addEventListener("DOMContentLoaded", function () {
  let clickCount = 0;
  const logo = document.getElementById("logo-img");
  const popup = document.getElementById("secret-popup");
  const closeBtn = document.getElementById("close-popup");

  logo.addEventListener("click", () => {
    clickCount++;
    if (clickCount >= 10) {
      popup.style.display = "block";
      clickCount = 0;
    }
  });

  closeBtn.addEventListener("click", () => {
    popup.style.display = "none";
  });
});