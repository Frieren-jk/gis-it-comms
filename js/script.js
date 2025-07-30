
let table; 
let actionsVisible = true;

$(document).ready(function () {

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
         
          return data;
        }
      },
      { data: "file_to" },
      { data: "actions" }  
    ]
    
    
  });


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
      return; 
    }


    // lagay dito ng swal para sa dropdown status


    const formData = $(this).serialize(); 

    $.ajax({
      url: 'actions/add_entry.php',
      method: 'POST',
      data: formData,
      success: function (response) {

        Swal.fire({
          icon: 'success', 
          title: 'Entry added successfully!',
          toast: true,
          position: 'top-end',
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false,
          iconColor: 'white', 
          customClass: {
            popup: 'colored-toast'
          }
        });

        $('#addEntryModal').modal('hide'); 
        $('#addEntryForm')[0].reset();   
        table.ajax.reload();            
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



  $('#toggleActionsBtn').on('click', function () {
    actionsVisible = !actionsVisible;

  
    table.column(10).visible(actionsVisible, false);

   
    setTimeout(() => {
      table.columns.adjust().draw(false);
    }, 100); 


    $(this).text(actionsVisible ? 'Hide Actions' : 'Show Actions');
  });
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
    Swal.fire({
      title: 'Update Entry',
      text: 'Updating ID: ' + id,
      icon: 'info',
      toast: true,
      position: 'top-end',
      timer: 2000,
      showConfirmButton: false,
      timerProgressBar: true
    });
    // You can put your update logic here or redirect to update modal
    // e.g. open update modal: $('#updateModal').modal('show');
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
