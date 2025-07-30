<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connection.php';

try {
    $id = $_POST['id'];
    $particulars = $_POST['particulars'];
    $sender = $_POST['sender'];
    $date_received = $_POST['date_received'];
    $remarks = $_POST['remarks'];
    $assign_to = $_POST['assign_to'];
    $date_assign = $_POST['date_assign'];
    $action_taken = $_POST['action_taken'];
    $status = $_POST['status'];
    $file_to = $_POST['file_to'];

    $stmt = $conn->prepare("INSERT INTO records
        (id, particulars, sender, date_received, remarks, assign_to, date_assign, action_taken, status, file_to) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssss", $id, $particulars, $sender, $date_received, $remarks, $assign_to, $date_assign, $action_taken, $status, $file_to);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(["success" => false, "exception" => $e->getMessage()]);
}
?>
