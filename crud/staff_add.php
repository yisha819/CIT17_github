<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$services = $conn->query("SELECT id, name FROM services ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $service_id = $_POST['service_id'];

    $stmt = $conn->prepare("
        INSERT INTO staff (name, email, phone, specialization, service_id)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssi", $name, $email, $phone, $specialization, $service_id);

    if ($stmt->execute()) {
        header("Location: admin.php#staff");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Staff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Add Staff</h2>
<form method="POST">

    <div class="mb-3">
        <label>Name</label>
        <input name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input name="phone" class="form-control">
    </div>

    <div class="mb-3">
        <label>Assigned Service</label>
        <select name="service_id" class="form-control">
            <option value="">None</option>
            <?php while ($s = $services->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <button class="btn btn-primary">Save</button>
    <a href="admin.php#staff" class="btn btn-secondary">Cancel</a>

</form>
</body>
</html>
