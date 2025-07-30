<?php
require '../connection.php';

$q = $_GET['q'] ?? '';

if (empty($q)) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT ref_no, particulars, sender FROM backlog WHERE ref_no LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("s", $q);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
