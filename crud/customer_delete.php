<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM customer_profiles WHERE id=$id");

header("Location: admin.php");
exit;
?>