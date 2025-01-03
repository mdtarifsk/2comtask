<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit;
}

$query = "SELECT lh.*, l.name AS lead_name, u.username AS updated_by_user 
          FROM lead_history lh
          JOIN leads l ON lh.lead_id = l.id
          JOIN users u ON lh.updated_by = u.id
          ORDER BY lh.update_time DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lead History</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h4><a href="dashboard.php">Go to Dashboard</a></h4>
<h1>Lead History</h1>
<table border="1">
    <thead>
        <tr>
            <th>Lead Name</th>
            <th>Status From</th>
            <th>Status To</th>
            <th>Updated By</th>
            <th>Update Time</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['lead_name'] ?></td>
                <td><?= $row['status_from'] ?></td>
                <td><?= $row['status_to'] ?></td>
                <td><?= $row['updated_by_user'] ?></td>
                <td><?= $row['update_time'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
