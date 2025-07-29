<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection.php'; // fix path if needed

header('Content-Type: application/json');

$sql = "SELECT * FROM comms_records"; // Replace with actual table name
$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add colored status badge dropdown
        $status = strtolower($row['status']);

        $badge_class = match ($status) {
            'pending' => 'bg-warning text-dark',
            'completed' => 'bg-success text-white',
            'in progress' => 'bg-primary text-white',
            'cancelled', 'canceled' => 'bg-danger text-white',
            default => 'bg-secondary text-white'
        };

        $row['status'] = '
<div class="dropdown">
  <button class="btn btn-sm dropdown-toggle ' . $badge_class . '" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    ' . htmlspecialchars($row['status']) . '
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Pending\')">Pending</a></li>
    <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'In Progress\')">In Progress</a></li>
    <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Completed\')">Completed</a></li>
    <li><a class="dropdown-item text-black" href="#" onclick="updateStatus(\'' . $row['id'] . '\', \'Cancelled\')">Cancelled</a></li>
  </ul>
</div>';


        // Add the existing action buttons
        $row['actions'] = '
    <div class="dropdown">
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Update</a></li>
            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
        </ul>
    </div>';

        $data[] = $row;
    }

}

echo json_encode(['data' => $data]);
?>