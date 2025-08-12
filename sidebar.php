<!-- sidebar.php -->

<?php
$is_guest = isset($_SESSION['guest']) && $_SESSION['guest'] === true;
$is_logged_in = isset($_SESSION['user_id']);
?>

<?php
require_once 'connection.php'; // adjust path as needed

// Initialize counts
$total = 0;
$completed = 0;
$priority = 0;
$common = 0;

// Query total number of records
$sql = "SELECT status, COUNT(*) as count FROM communication GROUP BY status";
$result = $conn->query($sql);

// Initialize status-specific counts
$status_counts = [
    'Pending-Priority' => 0,
    'Pending-Common' => 0,
    'In Progress' => 0,
    'Take Note' => 0,
    'Completed-Priority' => 0,
    'Completed-Common' => 0,
    'Cancelled' => 0,
];

// Populate status_counts from DB
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['status'];
        $count = (int) $row['count'];
        if (isset($status_counts[$status])) {
            $status_counts[$status] = $count;
        }
    }
}

// Compute final values
$total = array_sum($status_counts);
$completed = $status_counts['Completed-Priority'] + $status_counts['Completed-Common'];
$priority = $status_counts['Pending-Priority'];
$common = $status_counts['Pending-Common'];

// Avoid division by zero
function get_percent($part, $total)
{
    return $total > 0 ? round(($part / $total) * 100) : 0;
}

$complete_percent = get_percent($completed, $total);
$priority_percent = get_percent($priority, $total);
$common_percent = get_percent($common, $total);
?>

<div class="sidebar d-flex flex-column">
    <div class="logo-container" id="logo-container">
        <img src="img/logo.png" alt="Logo" class="logo-img" id="logo-img">
        <div class="logo-label">GIS-IT COMMS</div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-4">
        <?php if (!$is_guest): ?>
            <a href="index.php" class="nav-link">Communications</a>
            <a href="records.php" class="nav-link">Records</a>
        <?php endif; ?>
        <?php if ($is_guest): ?>
            <a href="actions/end_guest_session.php" class="logout-link" id="logout-btn" data-guest="true">End Guest
                Session</a>
        <?php elseif ($is_logged_in): ?>
            <a href="actions/logout.php" class="logout-link" id="logout-btn" data-guest="false">Logout</a>
        <?php else: ?>
            <a href="login.php" class="login-link">Login</a>
        <?php endif; ?>
    </nav>

    <hr style="border: none; height: 2px; background-color: #ccc;">
    <?php if (!$is_guest): ?>
        <!-- Circles -->
        <div id="sidebar-comm-stats">
            <div class="stat-box">
                <div class="label">TOTAL</div>
                <div class="value-box" id="total-comms"><?php echo $total; ?></div>
            </div>

            <div class="stat-box">
                <div class="label">COMPLETE</div>
                <div class="value-box">
                    <div class="circle-box complete" id="complete-circle"
                        style="background-image: conic-gradient(#28a745 <?php echo $complete_percent; ?>%, #444 <?php echo $complete_percent; ?>%);">
                        <div
                            style="position: absolute; width: 80%; height: 80%; background-color: #222; border-radius: 50%; z-index: 1;">
                        </div>
                        <div class="circle-value" id="complete-val"><?php echo $completed; ?></div>
                        <div class="circle-ratio" id="complete-ratio"><?php echo "$completed/$total"; ?></div>
                    </div>
                </div>
            </div>

            <div class="stat-box">
                <div class="label">PENDING</div>
                <div class="value-box">
                    <div class="sub-label">Priority</div>
                    <!-- PRIORITY CIRCLE -->
                    <div class="circle-box pending priority" id="priority-circle"
                        style="background-image: conic-gradient(#dc3545 <?php echo $priority_percent; ?>%, #444 <?php echo $priority_percent; ?>%);">
                        <div
                            style="position: absolute; width: 80%; height: 80%; background-color: #222; border-radius: 50%; z-index: 1;">
                        </div>
                        <div class="circle-value" id="priority-val"><?php echo $priority; ?></div>
                        <div class="circle-ratio" id="priority-ratio"><?php echo "$priority/$total"; ?></div>
                    </div>

                    <div class="sub-label">Common</div>
                    <!-- COMMON CIRCLE -->
                    <div class="circle-box pending common" id="common-circle"
                        style="background-image: conic-gradient(#ffc107 <?php echo $common_percent; ?>%, #444 <?php echo $common_percent; ?>%);">
                        <div
                            style="position: absolute; width: 80%; height: 80%; background-color: #222; border-radius: 50%; z-index: 1;">
                        </div>
                        <div class="circle-value" id="common-val"><?php echo $common; ?></div>
                        <div class="circle-ratio" id="common-ratio"><?php echo "$common/$total"; ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>



</div>