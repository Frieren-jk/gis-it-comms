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
        $row['actions'] = '<div class="dropdown">
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
