<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_no = $_POST['ref_no'];
    $particulars = $_POST['particulars'];
    $sender = $_POST['sender'];

    $stmt = $conn->prepare("
        UPDATE records
        SET particulars = ?, sender = ?
        WHERE ref_no = ?
    ");
    $stmt->bind_param("sss", $particulars, $sender, $ref_no);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update record.']);
    }

    $stmt->close();
}
$conn->close();
?>
