<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch customer
$customer = $conn->query("SELECT * FROM customer_profiles WHERE id=$id")->fetch_assoc();
if (!$customer) die("Customer not found!");

// Fetch users with friendly display names
$users_result = $conn->query("SELECT id, CONCAT(username,' – ', role) AS display_name FROM users ORDER BY username ASC");
$users = [];
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
        "UPDATE customer_profiles SET user_id=?, full_name=?, phone=?, address=? WHERE id=?"
    );
    $stmt->bind_param("isssi", $user_id, $full_name, $phone, $address, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to customers tab
    header("Location: admin.php#customers");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Edit Customer Profile</h2>

<form method="post">
    <label>Assigned Staff:</label>
    <select name="user_id" class="form-control mb-3" required>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>" <?= $u['id'] == $customer['user_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['display_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Full Name:</label>
    <input type="text" name="full_name" class="form-control mb-3" value="<?= htmlspecialchars($customer['full_name']) ?>" required>

    <label>Phone:</label>
    <input type="text" name="phone" class="form-control mb-3" value="<?= htmlspecialchars($customer['phone']) ?>">

    <label>Address:</label>
    <textarea name="address" class="form-control mb-3"><?= htmlspecialchars($customer['address']) ?></textarea>

    <button class="btn btn-warning">Update</button>
    <a href="admin.php#customers" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
