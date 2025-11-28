<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid review ID.");
}
$id = (int)$_GET['id'];

// Fetch review
$stmt = $conn->prepare("SELECT * FROM service_reviews WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();
if (!$review) die("Review not found");

// Fetch bookings and users
$bookings = $conn->query("SELECT id, customer_name FROM bookings ORDER BY booking_date DESC");
$users = $conn->query("SELECT id, username FROM users ORDER BY username ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'] ?? null;
    $user_id = $_POST['user_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? '';

    if (!$booking_id || !$user_id || !$rating) {
        die("Please fill in all required fields.");
    }

    $stmt = $conn->prepare("UPDATE service_reviews SET booking_id=?, user_id=?, rating=?, comment=? WHERE id=?");
    $stmt->bind_param("iiisi", $booking_id, $user_id, $rating, $comment, $id);
    if ($stmt->execute()) {
        header("Location: admin.php#reviews");
        exit;
    } else {
        die("Error updating review: " . $stmt->error);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Edit Review</h2>
<form method="POST">

    <div class="mb-3">
        <label>Booking</label>
        <select name="booking_id" class="form-control" required>
            <option value="">Select Booking</option>
            <?php
            $bookings->data_seek(0); // Reset pointer
            while ($b = $bookings->fetch_assoc()):
            ?>
            <option value="<?= $b['id'] ?>" <?= $b['id'] == $review['booking_id'] ? 'selected' : '' ?>>
                Booking #<?= $b['id'] ?> - <?= htmlspecialchars($b['customer_name']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>User</label>
        <select name="user_id" class="form-control" required>
            <option value="">Select User</option>
            <?php
            $users->data_seek(0); // Reset pointer
            while ($u = $users->fetch_assoc()):
            ?>
            <option value="<?= $u['id'] ?>" <?= $u['id'] == $review['user_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['username']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Rating</label>
        <input type="number" name="rating" min="1" max="5" class="form-control" value="<?= $review['rating'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Comment</label>
        <textarea name="comment" class="form-control"><?= htmlspecialchars($review['comment']) ?></textarea>
    </div>

    <button class="btn btn-warning">Update</button>
    <a href="admin.php#reviews" class="btn btn-secondary">Cancel</a>

</form>
</body>
</html>
