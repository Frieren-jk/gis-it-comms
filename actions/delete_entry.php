<?php
require '../connection.php'; // or adjust based on your file structure

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // sanitize input

    $stmt = $conn->prepare("DELETE FROM records WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500); // internal server error
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400); // bad request
    echo json_encode(['success' => false, 'error' => 'ID not provided']);
}
