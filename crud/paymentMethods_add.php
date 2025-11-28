<?php
require 'config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and sanitize input
    $method_name = trim($_POST['method_name']);
    $description = trim($_POST['description']);

    // Validate input
    if (empty($method_name)) {
        $error = "Please select a payment method.";
    } else {
        // Prepare and execute insert
        $stmt = $conn->prepare("INSERT INTO payment_methods (method_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $method_name, $description);

        if ($stmt->execute()) {
            // Redirect after success
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
    <title>Add Payment Method</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2 class="mb-4">Add Payment Method</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">

    <!-- Payment Method Dropdown -->
    <div class="mb-3">
        <label class="form-label">Method Name</label>
        <select name="method_name" class="form-control" required>
            <option value="">Select Payment Method</option>
            <option value="Cash">Cash</option>
            <option value="GCash">GCash</option>
            <option value="PayPal">PayPal</option>
            <option value="Bank Transfer">Bank Transfer</option>
        </select>
    </div>

    <!-- Description Field -->
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <!-- Buttons -->
    <button class="btn btn-success">Save</button>
    <a href="admin.php#paymentMethods" class="btn btn-secondary">Cancel</a>

</form>

</body>
</html>
