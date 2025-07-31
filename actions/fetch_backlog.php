<?php
require '../connection.php'; // or adjust path

$sql = "SELECT  ref_no, particulars, sender, created_at FROM records";  // adjust table/column names
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
   

         $row['actions'] = '
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-secondary action-main-btn" data-id="' . $row['ref_no'] . '" data-action="Update">
                Update
            </button>
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="selectAction(this, \'' . $row['ref_no'] . '\', \'Update\')">Update</a></li>
                <li><a class="dropdown-item text-danger" href="#" onclick="selectAction(this, \'' . $row['ref_no'] . '\', \'Delete\')">Delete</a></li>
            </ul>
        </div>';
    $data[] = $row;
}

echo json_encode(['data' => $data]);
?>