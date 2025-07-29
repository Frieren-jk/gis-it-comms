<?php
include '../connection.php';

$id = $_POST['id']?? '';
$particulars = $_POST['particulars']?? '';
$sender = $_POST['sender']?? '';
$date_received = $_POST['date_received']?? '';
$remarks = $_POST['remarks']?? '';
$assign_to = $_POST['assign_to']?? '';
$date_assign = $_POST['date_assign']?? '';
$action_taken = $_POST['action_taken']?? '';
$status = $_POST['status']?? '';
$file_to = $_POST['file_to']?? '';

$sql = "INSERT INTO comms_records (id, particulars, sender, date_received, remarks, assign_to, date_assign, action_taken, status, file_to) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssss", $id, $particulars, $sender, $date_received, $remarks, $assign_to, $date_assign, $action_taken, $status, $file_to);


if ($stmt->execute()) {
  echo "success";
} else {
  http_response_code(500);
  echo "error";
}

$stmt->close();
$conn->close();
?>
