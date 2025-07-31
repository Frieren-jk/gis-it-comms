<?php

require '../connection.php';

try {
    $ref_no = $_POST['ref_no'];
    $particulars = $_POST['particulars'];
    $sender = $_POST['sender'];

    $stmt = $conn->prepare("INSERT INTO records (ref_no, particulars, sender) VALUES (?, ?, ?)");

    $stmt->bind_param("sss", $ref_no, $particulars, $sender);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        if ($conn->errno === 1062) {
            echo json_encode([
                "success" => false,
                "error" => "Duplicate reference number",
                "code" => 1062
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => $stmt->error,
                "code" => $conn->errno
            ]);
        }
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(["success" => false, "exception" => $e->getMessage()]);
}
?>
