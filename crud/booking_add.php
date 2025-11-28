<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Fetch users
$users_result = $conn->query("SELECT id, username FROM users");

$users = [];
if ($users_result && $users_result->num_rows > 0) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}


// Fetch services
$services_result = $conn->query("SELECT id, name FROM services");

$services = [];
if ($services_result && $services_result->num_rows > 0) {
    while ($row = $services_result->fetch_assoc()) {
        $services[] = $row;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $service_id = $_POST['service_id'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $status = $_POST['status'];

    // Fetch full name from customer_profiles
    $stmt_name = $conn->prepare("SELECT full_name FROM customer_profiles WHERE user_id = ?");
    $stmt_name->bind_param("i", $user_id);
    $stmt_name->execute();
    $stmt_name->bind_result($full_name);
    $stmt_name->fetch();
    $stmt_name->close();

    // Insert into bookings with full_name and booking_time
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, service_id, customer_name, booking_date, booking_time, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $user_id, $service_id, $full_name, $booking_date, $booking_time, $status);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php#bookings");
    exit;
}

// Fetch users with their full names
$users_result = $conn->query("
    SELECT u.id, u.username, c.full_name 
    FROM users u
    LEFT JOIN customer_profiles c ON u.id = c.user_id
");

$users = [];
if ($users_result && $users_result->num_rows > 0) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Add Booking</h2>

<form method="post">
    <label>User:</label>
    <select name="user_id" class="form-control mb-3" required>
        <option value="">Select User</option>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Service:</label>
    <select name="service_id" class="form-control mb-3" required>
        <option value="">Select Service</option>
        <?php foreach ($services as $s): ?>
            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Date:</label>
    <input type="date" name="booking_date" class="form-control mb-3" required>

    <label>Time:</label>
    <input type="time" name="booking_time" class="form-control mb-3" required>

    <label>Status:</label>
    <select name="status" class="form-control mb-3">
        <option>Pending</option>
        <option>Confirmed</option>
        <option>Completed</option>
        <option>Cancelled</option>
    </select>

    <button class="btn btn-success">Save</button>
    <a href="admin.php#bookings" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>