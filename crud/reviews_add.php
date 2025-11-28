<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch bookings for dropdown
$bookings = $conn->query("SELECT id, customer_name FROM bookings ORDER BY id ASC");


// Fetch users for dropdown
$users = $conn->query("SELECT id, username FROM users ORDER BY username ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Validate values
    if (!$booking_id || !$user_id || !$rating) {
        die("Please fill in all required fields.");
    }

    $stmt = $conn->prepare("INSERT INTO service_reviews (booking_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $booking_id, $user_id, $rating, $comment);

    if ($stmt->execute()) {
        header("Location: admin.php#reviews");
        exit;
    } else {
        die("Error inserting review: " . $stmt->error);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Review</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Add Review</h2>
<form method="POST">

    <div class="mb-3">
        <label>Booking</label>
        <select name="booking_id" class="form-control" required>
            <option value="">Select Booking</option>
            <?php while ($b = $bookings->fetch_assoc()): ?>
            <option value="<?= $b['id'] ?>">
                Booking #<?= $b['id'] ?> - <?= htmlspecialchars($b['customer_name']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>


    </div>

    <div class="mb-3">
        <label>User</label>
        <select name="user_id" class="form-control" required>
            <option value="">Select User</option>
            <?php while ($u = $users->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Rating</label>
        <input type="number" name="rating" min="1" max="5" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Comment</label>
        <textarea name="comment" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Save</button>
    <a href="admin.php#reviews" class="btn btn-secondary">Cancel</a>

</form>
</body>
</html>
