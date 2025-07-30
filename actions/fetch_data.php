<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM records";
$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $status = strtolower($row['status']);


        $status_raw = $row['status'];
        $badge_class = match ($status) {
            'pending-priority' => 'bg-danger text-white',
            'pending-common' => 'bg-warning text-dark',
            'completed-priority' => 'bg-success text-white',
            'completed-common' => 'bg-success text-white',
            'take note' => 'bg-primary text-white',
            'cancelled', 'canceled' => 'bg-secondary text-white',
            default => 'bg-secondary text-white'
        };

        $row['status_raw'] = $status_raw;

        $row['status'] = '
     
        <div class="dropdown">
         <span class="d-none">' . htmlspecialchars($status) . '</span>
        <button class="btn btn-sm dropdown-toggle ' . $badge_class . '" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            ' . htmlspecialchars($row['status']) . '
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Pending-Priority\')">Pending-Priority</a></li>
            <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Pending-Common\')">Pending-Common</a></li>
            <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Take Note\')">Take Note</a></li>
            <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Completed-Priority\')">Completed-Priority</a></li>
            <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Completed-Common\')">Completed-Common</a></li>
            <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Cancelled\')">Cancelled</a></li>
        </ul>
        </div>';


        $row['actions'] = '
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-secondary action-main-btn" data-id="' . $row['id'] . '" data-action="Update">
                Update
            </button>
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="selectAction(this, \'' . $row['id'] . '\', \'Update\')">Update</a></li>
                <li><a class="dropdown-item text-danger" href="#" onclick="selectAction(this, \'' . $row['id'] . '\', \'Delete\')">Delete</a></li>
            </ul>
        </div>';


        $data[] = $row;
    }

}

echo json_encode(['data' => $data]);
?>