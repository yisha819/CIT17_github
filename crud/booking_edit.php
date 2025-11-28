<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = $_GET['id'];

// Fetch booking
$stmt = $conn->prepare("
    SELECT * FROM bookings WHERE id=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found!");
}

// Fetch users
$users_result = $conn->query("SELECT id, username FROM users");
$users = [];
while ($row = $users_result->fetch_assoc()) {
    $users[] = $row;
}

// Fetch services
$services_result = $conn->query("SELECT id, name FROM services");
$services = [];
while ($row = $services_result->fetch_assoc()) {
    $services[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST['user_id'];
    $service_id = $_POST['service_id'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE bookings 
        SET user_id=?, service_id=?, booking_date=?, booking_time=?, status=? 
        WHERE id=?
    ");

    $stmt->bind_param("iisssi", $user_id, $service_id, $booking_date, $booking_time, $status, $id);
    $stmt->execute();

    header("Location: admin.php#bookings");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Edit Booking</h2>

<form method="post">

    <label>User:</label>
    <select name="user_id" class="form-control mb-3" required>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>" 
                <?= $u['id'] == $booking['user_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['username']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Service:</label>
    <select name="service_id" class="form-control mb-3" required>
        <?php foreach ($services as $s): ?>
            <option value="<?= $s['id'] ?>" 
                <?= $s['id'] == $booking['service_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Date:</label>
    <input type="date" 
           name="booking_date" 
           value="<?= $booking['booking_date'] ?>" 
           class="form-control mb-3" required>

    <label>Time:</label>
    <input type="time" 
           name="booking_time" 
           value="<?= $booking['booking_time'] ?>" 
           class="form-control mb-3" required>

    <label>Status:</label>
    <select name="status" class="form-control mb-3">
        <option <?= $booking['status']=='Pending'?'selected':'' ?>>Pending</option>
        <option <?= $booking['status']=='Confirmed'?'selected':'' ?>>Confirmed</option>
        <option <?= $booking['status']=='Completed'?'selected':'' ?>>Completed</option>
        <option <?= $booking['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
    </select>

    <button class="btn btn-warning">Update</button>
    <a href="admin.php#bookings" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>