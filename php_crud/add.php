<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Add New User</h2>
    <form method="POST">
        Name: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Phone: <input type="text" name="phone" required><br><br>
        <input type="submit" name="save" value="Save">
    </form>
</body>
</html>

<?php
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO users (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>