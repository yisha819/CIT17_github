<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP CRUD Example</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>User List</h2>
    <a href="add.php">Add New User</a>
    <br><br>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Action</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM users");
        while($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['phone']; ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
                <a href="delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Delete this record?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>