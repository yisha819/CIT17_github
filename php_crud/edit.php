<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST">
        Name: <input type="text" name="name" value="<?= $row['name']; ?>" required><br><br>
        Email: <input type="email" name="email" value="<?= $row['email']; ?>" required><br><br>
        Phone: <input type="text" name="phone" value="<?= $row['phone']; ?>" required><br><br>
        <input type="submit" name="update" value="Update">
    </form>
</body>
</html>

<?php
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>