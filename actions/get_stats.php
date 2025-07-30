<?php
require_once '../connection.php';

$status_counts = [
    'Pending-Priority' => 0,
    'Pending-Common' => 0,
    'In Progress' => 0,
    'Take Note' => 0,
    'Completed-Priority' => 0,
    'Completed-Common' => 0,
    'Cancelled' => 0,
];

$sql = "SELECT status, COUNT(*) as count FROM communication GROUP BY status";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['status'];
        if (isset($status_counts[$status])) {
            $status_counts[$status] = (int)$row['count'];
        }
    }
}

$total = array_sum($status_counts);
$completed = $status_counts['Completed-Priority'] + $status_counts['Completed-Common'];
$priority = $status_counts['Pending-Priority'];
$common = $status_counts['Pending-Common'];

function get_percent($part, $total) {
    return $total > 0 ? round(($part / $total) * 100) : 0;
}

echo json_encode([
    'total' => $total,
    'completed' => $completed,
    'complete_percent' => get_percent($completed, $total),
    'priority' => $priority,
    'priority_percent' => get_percent($priority, $total),
    'common' => $common,
    'common_percent' => get_percent($common, $total)
]);
