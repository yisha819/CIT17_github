<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if POST ID exists
if (!isset($_POST['id'])) {
    echo "Missing ID";
    exit;
}

$id = intval($_POST['id']);

$stmt = $conn->prepare("DELETE FROM payments WHERE id=?");
$stmt->bind_param("i", $id);

echo $stmt->execute() ? "deleted" : "Error: " . $stmt->error;
?>
