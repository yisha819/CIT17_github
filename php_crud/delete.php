<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Application</title>

    <link rel="stylesheet" href="css/style.css"> 
    
    </head>
<body>


<?php
include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id=$id");
header("Location: index.php");
exit;
?>
</body>
<html>