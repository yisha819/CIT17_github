<?php
require 'config.php';
session_start();

if (!isset($_GET['id'])) {
    die("Missing ID");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM staff WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php#staff");
} else {
    echo "Error: " . $stmt->error;
}
?>
