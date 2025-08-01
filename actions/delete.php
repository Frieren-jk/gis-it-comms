<?php
require_once '../connection.php';

// Record Count for preview (AJAX)
if (isset($_POST['preview'])) {
    $month = $_POST['month'];
    $year = intval($_POST['year']);
    $count = 0;

    if ($month === "all" || $month === "" || !isset($month)) {
        // Whole year
        $start = "$year-01-01 00:00:00";
        $end = ($year + 1) . "-01-01 00:00:00";
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM records WHERE created_at >= ? AND created_at < ?");
        $stmt->bind_param("ss", $start, $end);
    } else {
        // Specific month
        $month = intval($month);
        $start = sprintf("%04d-%02d-01 00:00:00", $year, $month);
        $endMonth = $month + 1;
        $endYear = $year;
        if ($endMonth > 12) {
            $endMonth = 1;
            $endYear++;
        }
        $end = sprintf("%04d-%02d-01 00:00:00", $endYear, $endMonth);
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM records WHERE created_at >= ? AND created_at < ?");
        $stmt->bind_param("ss", $start, $end);
    }
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    echo json_encode($count);
    exit;
}

// Export to CSV
if (isset($_GET['export']) && isset($_GET['year'])) {
    $month = isset($_GET['month']) ? $_GET['month'] : "";
    $year = intval($_GET['year']);

    $filename = "records_export_{$year}";
    if ($month !== "" && $month !== "all") {
        $filename .= "_{$month}";
    }
    $filename .= ".csv";

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen("php://output", "w");
    fputcsv($output, ['ref_no', 'particulars', 'sender', 'created_at']);

    if ($month === "all" || $month === "" || !isset($month)) {
        // Whole year
        $start = "$year-01-01 00:00:00";
        $end = ($year + 1) . "-01-01 00:00:00";
        $stmt = $conn->prepare("SELECT ref_no, particulars, sender, created_at FROM records WHERE created_at >= ? AND created_at < ?");
        $stmt->bind_param("ss", $start, $end);
    } else {
        // Specific month
        $month = intval($month);
        $start = sprintf("%04d-%02d-01 00:00:00", $year, $month);
        $endMonth = $month + 1;
        $endYear = $year;
        if ($endMonth > 12) {
            $endMonth = 1;
            $endYear++;
        }
        $end = sprintf("%04d-%02d-01 00:00:00", $endYear, $endMonth);
        $stmt = $conn->prepare("SELECT ref_no, particulars, sender, created_at FROM records WHERE created_at >= ? AND created_at < ?");
        $stmt->bind_param("ss", $start, $end);
    }
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        fputcsv($output, array_values($row));
    }

    fclose($output);
    exit;
}

// Delete after export
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    $month = $_POST['month'];
    $year = intval($_POST['year']);

    if ($month === "all" || $month === "" || !isset($month)) {
        // Whole year
        $start = "$year-01-01 00:00:00";
        $end = ($year + 1) . "-01-01 00:00:00";
        $stmt = $conn->prepare("DELETE FROM records WHERE created_at >= ? AND created_at < ?");
        $stmt->bind_param("ss", $start, $end);
    } else {
        // Specific month
        $month = intval($month);
        $start = sprintf("%04d-%02d-01 00:00:00", $year, $month);
        $endMonth = $month + 1;
        $endYear = $year;
        if ($endMonth > 12) {
            $endMonth = 1;
            $endYear++;
        }
        $end = sprintf("%04d-%02d-01 00:00:00", $endYear, $endMonth);
        $stmt = $conn->prepare("DELETE FROM records WHERE created_at >= ? AND created_at < ?");
        $stmt->bind_param("ss", $start, $end);
    }

    $result = $stmt->execute();
    if (!$result) {
        die("Delete failed: " . $stmt->error);
    }

    header("Location: records.php?deleted=1");
    exit;
}
?>

<form method="POST" id="deleteForm">
    <select name="month" id="deleteMonth">
        <option value="">-- Month --</option>
        <option value="all">All Months</option>
        <!-- Add month options here -->
    </select>
    <select name="year" id="deleteYear">
        <!-- Add year options here -->
    </select>
    <input type="hidden" name="confirm_delete" value="1">
    <!-- Add submit button and any other fields as needed -->
</form>
