<?php
session_start();
// Prevent browser from caching login page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

require '../connection.php';

header('Content-Type: application/json');


// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
    exit;
}

// Sanitize and validate input
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter both username and password.'
    ]);
    exit;
}

// Prepare query
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $db_password);
    $stmt->fetch();

    // Replace with password_verify() if using hashed passwords
    if ($password === $db_password) {
        // Clear guest session if it exists
        if (isset($_SESSION['guest']) && $_SESSION['guest'] === true) {
            session_unset(); // Clears all session variables
            session_destroy(); // Destroys the session
            session_start(); // Start a new session after destruction
        }

        // Set login session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;

        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Incorrect password.'
        ]);
    }
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Username does not exist.'
    ]);
}

$stmt->close();
$conn->close();
?>