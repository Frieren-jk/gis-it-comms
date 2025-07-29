$(document).ready(function () {
  $('#messagesTable').DataTable({
    responsive: true,
    paging: true,
    searching: true,
    ordering: true
  });
});


function submitEntry() {
  const form = document.getElementById("entryForm");
  const values = Array.from(form.elements)
    .filter(el => el.tagName === "INPUT")
    .map(el => el.value);

  const table = document.getElementById("commTable").getElementsByTagName('tbody')[0];
  const row = table.insertRow();

  values.forEach(value => {
    const cell = row.insertCell();
    cell.contentEditable = "true";
    cell.innerText = value;
  });

  // Fill remaining cells if less than 10
  for (let i = values.length; i < 10; i++) {
    const cell = row.insertCell();
    cell.contentEditable = "true";
    cell.innerText = "";
  }

  // Close modal and reset form
  const modal = bootstrap.Modal.getInstance(document.getElementById("addEntryModal"));
  modal.hide();
  form.reset();
}

