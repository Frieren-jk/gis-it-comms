<?php
require '../connection.php';

if (isset($_POST['id'])) {
    $ref_no = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM records WHERE ref_no = ?");
    $stmt->bind_param("s", $ref_no);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete record.']);
    }

    $stmt->close();
}
$conn->close();
?>
