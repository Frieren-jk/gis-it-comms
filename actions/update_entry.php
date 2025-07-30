<?php
require '../connection.php'; // adjust path as needed

if (!isset($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing ID']);
    exit;
}

$id = $_POST['id']; // No need to cast to int since it's a string

// Get other fields
$particulars    = $_POST['particulars'] ?? '';
$sender         = $_POST['sender'] ?? '';
$date_received  = $_POST['date_received'] ?? null;
$remarks        = $_POST['remarks'] ?? '';
$assign_to      = $_POST['assign_to'] ?? '';
$date_assign    = $_POST['date_assign'] ?? null;
$action_taken   = $_POST['action_taken'] ?? '';
$status         = $_POST['status'] ?? '';
$file_to        = $_POST['file_to'] ?? '';

// Prepare update query
$stmt = $conn->prepare("
    UPDATE records 
    SET 
        particulars = ?, 
        sender = ?, 
        date_received = ?, 
        remarks = ?, 
        assign_to = ?, 
        date_assign = ?, 
        action_taken = ?, 
        status = ?, 
        file_to = ?
    WHERE id = ?
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// All 10 parameters are strings
$stmt->bind_param(
    "ssssssssss",
    $particulars,
    $sender,
    $date_received,
    $remarks,
    $assign_to,
    $date_assign,
    $action_taken,
    $status,
    $file_to,
    $id
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
