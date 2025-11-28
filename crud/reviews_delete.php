<?php
require 'config.php';
session_start();

// Block access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if ID is provided via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Missing or invalid review ID");
}

$id = (int)$_GET['id'];

// Delete the review
$stmt = $conn->prepare("DELETE FROM service_reviews WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php#reviews"); // redirect back
    exit;
} else {
    die("Error deleting review: " . $stmt->error);
}
