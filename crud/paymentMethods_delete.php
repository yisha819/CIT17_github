<?php
require 'config.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit;
}

// Get ID from POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo "Missing or invalid ID";
    exit;
}

// Prepare and execute delete
$stmt = $conn->prepare("DELETE FROM payment_methods WHERE id=?");
$stmt->bind_param("i", $id);

echo $stmt->execute() ? "deleted" : "Database error: " . $stmt->error;
$stmt->close();
?>
