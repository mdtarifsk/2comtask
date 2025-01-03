<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['user_role'];
$userId = $_SESSION['user_id'];

// Fetch leads
$query = $role === 'Admin' ? "SELECT * FROM leads" : "SELECT * FROM leads WHERE assigned_to='$userId'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<h1>Welcome to the Dashboard</h1>
<a href="logout.php">Logout</a>

<h2>Leads</h2>
<h3><a href="import.php">Bluk Import Lead</a></h3>
<h3><a href="export.php">Export or Download Lead</a></h3>
<h3><a href="lead_history.php">Cheak Here Lead History</a></h3>
<table border="1">
    <thead>
        <tr>
        <h3><a href="add_lead.php">ADD Lead</a></h3>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Actions</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                    <a href="delete_lead.php?id=<?= $row['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
