<?php
session_start();
require 'config.php';

// Block access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ===== HANDLE CUSTOMER FORM SUBMISSION =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['full_name'], $_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt = $conn->prepare("INSERT INTO customer_profiles (user_id, full_name, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $full_name, $phone, $address);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php#customers");
    exit;
}

// ===== FETCH TABLE DATA =====
$bookings = $conn->query("
    SELECT 
        b.id, 
        b.user_id,
        b.customer_name, 
        DATE_FORMAT(b.booking_date, '%Y-%m-%d') AS booking_date,
        TIME_FORMAT(b.booking_time, '%H:%i:%s') AS booking_time,
        s.name AS service_name, 
        s.price,
        b.status
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    ORDER BY b.booking_date DESC, b.booking_time DESC
");

$services = $conn->query("SELECT * FROM services ORDER BY name ASC");
$users = $conn->query("SELECT id, username, role FROM users ORDER BY username ASC");
$customers = $conn->query("SELECT * FROM customer_profiles ORDER BY id DESC");
$payment_methods = $conn->query("SELECT * FROM payment_methods ORDER BY id DESC");

$payments = $conn->query("
    SELECT 
        p.*, 
        b.customer_name, 
        b.user_id AS customer_user_id,
        pm.method_name
    FROM payments p
    LEFT JOIN bookings b ON p.booking_id = b.id
    LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id
    ORDER BY p.id DESC
");

$reviews = $conn->query("
    SELECT 
        r.*, 
        u.username, 
        r.user_id AS customer_user_id,
        b.customer_name
    FROM service_reviews r
    JOIN users u ON r.user_id = u.id
    JOIN bookings b ON r.booking_id = b.id
    ORDER BY r.id DESC
");



$staff = $conn->query("
    SELECT staff.*, services.name AS service_name
    FROM staff
    LEFT JOIN services ON staff.service_id = services.id
    ORDER BY staff.id ASC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: linear-gradient(90deg, #4a90e2, #357ABD); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar .navbar-brand, .navbar a { font-weight: 600; }
        .nav-tabs .nav-link { border: none; color: #4a4a4a; font-weight: 500; margin-right: 5px; border-radius: 0.5rem 0.5rem 0 0; transition: all 0.3s ease; }
        .nav-tabs .nav-link.active { background: #357ABD; color: #fff; }
        .card { border-radius: 0.75rem; box-shadow: 0 8px 20px rgba(0,0,0,0.08); border: none; }
        .table thead { background: #357ABD; color: #fff; }
        .table-hover tbody tr:hover { background: rgba(53,122,189,0.1); }
        .btn-primary { background: #4a90e2; border: none; transition: all 0.3s ease; }
        .btn-primary:hover { background: #357ABD; }
        .btn-warning { background: #f5a623; border: none; }
        .btn-warning:hover { background: #d48806; }
        .btn-danger { background: #e94b35; border: none; }
        .btn-danger:hover { background: #c0392b; }
        .table td, .table th { vertical-align: middle; }
        .tab-content { margin-top: 20px; }
        .card-body { padding: 1rem; }
        .d-flex .btn-sm { margin-left: 5px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark sticky-top">
    <div class="container d-flex justify-content-between">
        <span class="navbar-brand">Admin Dashboard</span>
        <span class="text-white">
            Logged in as <strong><?= $_SESSION['username'] ?></strong>
            | <a href="logout.php" class="text-white text-decoration-underline">Logout</a>
        </span>
    </div>
</nav>

<div class="container mt-4">
    <!-- Tabs -->
    <ul class="nav nav-tabs sticky-top bg-light" id="adminTabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="bookings-tab" data-bs-toggle="tab" href="#bookings">Bookings</a></li>
        <li class="nav-item"><a class="nav-link" id="services-tab" data-bs-toggle="tab" href="#services">Services</a></li>
        <li class="nav-item"><a class="nav-link" id="users-tab" data-bs-toggle="tab" href="#users">Users</a></li>
        <li class="nav-item"><a class="nav-link" id="customers-tab" data-bs-toggle="tab" href="#customers">Customers</a></li>
        <li class="nav-item"><a class="nav-link" id="payment-methods-tab" data-bs-toggle="tab" href="#payment_methods">Payment Methods</a></li>
        <li class="nav-item"><a class="nav-link" id="payments-tab" data-bs-toggle="tab" href="#payments">Payments</a></li>
        <li class="nav-item"><a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" id="staff-tab" data-bs-toggle="tab" href="#staff">Staff</a></li>
    </ul>

    <div class="tab-content">

    <!-- ================= BOOKINGS ================= -->
    <div class="tab-pane fade show active" id="bookings">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>All Bookings</h4>
            <a href="booking_add.php" class="btn btn-primary btn-sm">Add Booking</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr><th>User ID</th><th>Customer</th><th>Service</th><th>Price</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                            <td>₱<?= number_format($row['price'],2) ?></td>
                            <td><?= $row['booking_date'] ?></td>
                            <td><?= $row['booking_time'] ?: '00:00:00' ?></td>
                            <td><span class="badge bg-<?= $row['status']=='confirmed'?'success':($row['status']=='pending'?'warning':'secondary')?>"><?= ucfirst($row['status']) ?></span></td>
                            <td>
                                <a href="booking_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="booking_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this booking?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= SERVICES ================= -->
    <div class="tab-pane fade" id="services">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Services</h4>
            <a href="service_add.php" class="btn btn-primary btn-sm">Add Service</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead><tr><th>#</th><th>Name</th><th>Price</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php while ($row = $services->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>₱<?= number_format($row['price'],2) ?></td>
                            <td>
                                <a href="service_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="service_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this service?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= USERS ================= -->
    <div class="tab-pane fade" id="users">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Users</h4>
            <a href="user_add.php" class="btn btn-primary btn-sm">Add User</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= ucfirst($row['role']) ?></td>
                            <td>
                                <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="user_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= CUSTOMER PROFILES ================= -->
    <div class="tab-pane fade" id="customers">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Customer Profiles</h4>
            <a href="customer_add.php" class="btn btn-primary btn-sm">Add Customer</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr><th>#</th><th>User ID</th><th>Full Name</th><th>Phone</th><th>Address</th><th>Created At</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $customers->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a href="customer_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="customer_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= PAYMENT METHODS ================= -->
    <div class="tab-pane fade" id="payment_methods">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Payment Methods</h4>
            <a href="paymentMethods_add.php" class="btn btn-primary btn-sm">Add Method</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead><tr><th>#</th><th>Method Name</th><th>Details</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php while ($row = $payment_methods->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['method_name']) ?></td>
                            <td><?= htmlspecialchars($row['details']) ?></td>
                            <td>
                                <a href="paymentMethods_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="paymentMethods_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this method?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= PAYMENTS ================= -->
    <div class="tab-pane fade" id="payments">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Payments</h4>
            <a href="payments_add.php" class="btn btn-primary btn-sm">Add Payment</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr><th>User ID</th><th>Booking</th><th>Payment Method</th><th>Amount Paid</th><th>Status</th><th>Paid At</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $payments->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['customer_user_id'] ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['method_name']) ?></td>
                            <td>₱<?= number_format($row['amount_paid'],2) ?></td>
                            <td><?= $row['payment_status'] ?></td>
                            <td><?= $row['paid_at'] ?></td>
                            <td>
                                <a href="payments_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="payments_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= REVIEWS ================= -->
    <div class="tab-pane fade" id="reviews">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Service Reviews</h4>
            <a href="reviews_add.php" class="btn btn-primary btn-sm">Add Review</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr><th>User ID</th><th>Username</th><th>Customer Name</th><th>Rating</th><th>Comment</th><th>Created At</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $reviews->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['customer_user_id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['customer_name'] ?></td>
                            <td><?= $row['rating'] ?></td>
                            <td><?= htmlspecialchars($row['comment']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a href="reviews_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="reviews_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this review?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= STAFF ================= -->
    <div class="tab-pane fade" id="staff">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Staff</h4>
            <a href="staff_add.php" class="btn btn-primary btn-sm">Add Staff</a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Service</th> <!-- NEW -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $staff->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['service_name'] ?? "None") ?></td> <!-- NEW -->
                            <td>
                                <a href="staff_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="staff_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var hash = window.location.hash;
    if (hash) {
        var triggerEl = document.querySelector(`#adminTabs a[href="${hash}"]`);
        if (triggerEl) new bootstrap.Tab(triggerEl).show();
    }
    var tabLinks = document.querySelectorAll('#adminTabs a[data-bs-toggle="tab"]');
    tabLinks.forEach(function(link) {
        link.addEventListener('shown.bs.tab', function(e) {
            history.replaceState(null, null, e.target.getAttribute('href'));
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
