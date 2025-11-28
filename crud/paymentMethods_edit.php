<?php
require 'config.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the ID of the payment method to edit
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) die("Invalid ID");

// Fetch the payment method
$stmt = $conn->prepare("SELECT * FROM payment_methods WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$method = $result->fetch_assoc();
$stmt->close();

if (!$method) die("Payment method not found");

// Handle form submission
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method_name = trim($_POST['method_name']);
    $description = trim($_POST['description']);

    if (empty($method_name)) {
        $error = "Please select a payment method.";
    } else {
        $stmt = $conn->prepare("UPDATE payment_methods SET method_name=?, description=? WHERE id=?");
        $stmt->bind_param("ssi", $method_name, $description, $id);

        if ($stmt->execute()) {
            header("Location: admin.php#paymentMethods");
            exit;
        } else {
            $error = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Payment Method</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2 class="mb-4">Edit Payment Method</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">

    <div class="mb-3">
        <label class="form-label">Method Name</label>
        <select name="method_name" class="form-control" required>
            <option value="">Select Payment Method</option>
            <option value="Cash" <?= $method['method_name'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
            <option value="GCash" <?= $method['method_name'] === 'GCash' ? 'selected' : '' ?>>GCash</option>
            <option value="PayPal" <?= $method['method_name'] === 'PayPal' ? 'selected' : '' ?>>PayPal</option>
            <option value="Bank Transfer" <?= $method['method_name'] === 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($method['description']) ?></textarea>
    </div>

    <button class="btn btn-warning">Update</button>
    <a href="admin.php#paymentMethods" class="btn btn-secondary">Cancel</a>

</form>
</body>
</html>
