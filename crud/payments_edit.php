<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get payment ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) die("Invalid ID");

// Fetch payment record
$stmt = $conn->prepare("SELECT * FROM payments WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();
$stmt->close();

if (!$payment) die("Payment not found");

// Fetch dropdowns
$bookings = $conn->query("
    SELECT b.id, b.customer_name, s.name AS service_name, b.booking_date, b.booking_time
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    ORDER BY b.booking_date ASC, b.booking_time ASC
");

$methods = $conn->query("SELECT id, method_name FROM payment_methods ORDER BY method_name ASC");
$users = $conn->query("SELECT id, username, role FROM users ORDER BY username ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $method_id = $_POST['payment_method_id'] ?: null;
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount_paid'];
    $status = $_POST['payment_status'];

    $stmt = $conn->prepare("
        UPDATE payments
        SET booking_id=?, payment_method_id=?, user_id=?, amount_paid=?, payment_status=?
        WHERE id=?
    ");
    $stmt->bind_param("iiidsi", $booking_id, $method_id, $user_id, $amount, $status, $id);

    if ($stmt->execute()) {
        header("Location: admin.php#payments");
        exit;
    } else {
        $error = "Database error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Edit Payment</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">

    <div class="mb-3">
        <label>Booking</label>
        <select name="booking_id" class="form-control" required>
            <option value="">Select Booking</option>
            <?php while ($b = $bookings->fetch_assoc()): ?>
                <option value="<?= $b['id'] ?>" <?= $b['id'] == $payment['booking_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['customer_name'] . " – " . $b['service_name'] . " (" . $b['booking_date'] . " " . $b['booking_time'] . ")") ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Payment Method</label>
        <select name="payment_method_id" class="form-control">
            <option value="">Select Method</option>
            <?php while ($m = $methods->fetch_assoc()): ?>
                <option value="<?= $m['id'] ?>" <?= $m['id'] == $payment['payment_method_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['method_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Assigned Staff</label>
        <select name="user_id" class="form-control" required>
            <option value="">Select Staff</option>
            <?php while ($u = $users->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>" <?= $u['id'] == $payment['user_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['username'] . " – " . $u['role']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Amount Paid</label>
        <input type="number" step="0.01" name="amount_paid" class="form-control" value="<?= $payment['amount_paid'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="payment_status" class="form-control">
            <option value="pending" <?= $payment['payment_status']=='pending' ? 'selected' : '' ?>>Pending</option>
            <option value="paid" <?= $payment['payment_status']=='paid' ? 'selected' : '' ?>>Paid</option>
            <option value="refunded" <?= $payment['payment_status']=='refunded' ? 'selected' : '' ?>>Refunded</option>
        </select>
    </div>

    <button class="btn btn-warning">Update</button>
    <a href="admin.php#payments" class="btn btn-secondary">Cancel</a>

</form>
</body>
</html>
