<?php
session_start();
require 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch staff/users for dropdown with friendly display names
$users = [];
$users_result = $conn->query("SELECT id, CONCAT(username, ' – ', role) AS display_name FROM users ORDER BY username ASC");
while ($row = $users_result->fetch_assoc()) {
    $users[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $conn->prepare(
        "INSERT INTO customer_profiles (user_id, full_name, phone, address) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("isss", $user_id, $full_name, $phone, $address);
    $stmt->execute();
    $stmt->close();

    // Redirect back to admin.php with customers tab active
    header("Location: admin.php#customers");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Add Customer Profile</h2>

<form action="admin.php#customers" method="POST">

    <!-- Assigned Staff Dropdown -->
    <label>Customer:</label>
    <select name="user_id" class="form-control mb-3" required>
        <option value="">Select User</option>
        <?php foreach ($users as $u) { ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['display_name']) ?></option>
        <?php } ?>
    </select>

    <!-- Customer Details -->
    <label>Full Name:</label>
    <input type="text" name="full_name" class="form-control mb-3" required>

    <label>Phone:</label>
    <input type="text" name="phone" class="form-control mb-3">

    <label>Address:</label>
    <textarea name="address" class="form-control mb-3"></textarea>

    <!-- Buttons -->
    <button type="submit" class="btn btn-success">Save</button>
    <a href="admin.php#customers" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
