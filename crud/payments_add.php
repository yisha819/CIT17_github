<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Fetch bookings with service prices
$bookings_result = $conn->query("
    SELECT b.id, b.customer_name, s.name AS service_name, s.price 
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    ORDER BY b.id DESC
");

$bookings = [];
while ($row = $bookings_result->fetch_assoc()) {
    $bookings[] = $row;
}

// Fetch payment methods
$payment_methods = $conn->query("SELECT * FROM payment_methods");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $payment_method_id = $_POST['payment_method_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_status = $_POST['payment_status'];

    $stmt = $conn->prepare("INSERT INTO payments (booking_id, payment_method_id, amount_paid, payment_status, paid_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iids", $booking_id, $payment_method_id, $amount_paid, $payment_status);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php#payments");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Add Payment</h2>

<form method="post">
    <label>Booking:</label>
    <select name="booking_id" id="booking_id" class="form-control mb-3" required onchange="updateAmount()">
        <option value="">Select Booking</option>
        <?php foreach ($bookings as $b): ?>
            <option value="<?= $b['id'] ?>" data-price="<?= $b['price'] ?>">
                #<?= $b['id'] ?> - <?= htmlspecialchars($b['customer_name']) ?> - <?= htmlspecialchars($b['service_name']) ?> (₱<?= number_format($b['price'], 2) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Payment Method:</label>
    <select name="payment_method_id" class="form-control mb-3" required>
        <option value="">Select Payment Method</option>
        <?php while ($pm = $payment_methods->fetch_assoc()): ?>
            <option value="<?= $pm['id'] ?>"><?= htmlspecialchars($pm['method_name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Amount Paid:</label>
    <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control mb-3" required>

    <label>Payment Status:</label>
    <select name="payment_status" class="form-control mb-3">
        <option value="pending">Pending</option>
        <option value="paid">Paid</option>
        <option value="refunded">Refunded</option>
    </select>

    <button class="btn btn-success">Save</button>
    <a href="admin.php#payments" class="btn btn-secondary">Cancel</a>
</form>

<script>
function updateAmount() {
    var select = document.getElementById('booking_id');
    var selectedOption = select.options[select.selectedIndex];
    var price = selectedOption.getAttribute('data-price');
    
    if (price) {
        document.getElementById('amount_paid').value = price;
    }
}
</script>

</body>
</html>