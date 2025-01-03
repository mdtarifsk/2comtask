// index.php: Main Application (with login check)
<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lead Management System</title>
</head>
<body>

<h1>Lead Management System</h1>
<a href="logout.php">Logout</a>

<h2>Import Leads</h2>
<form method="POST" action="import.php" enctype="multipart/form-data">
    <label for="excel_file">Upload Excel File:</label>
    <input type="file" name="excel_file" accept=".xlsx" required>
    <button type="submit">Import</button>
</form>

<h2>Manage Leads</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Date Added</th>
            <th>Last Updated</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM leads ORDER BY date_added DESC");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['phone']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['date_added']}</td>";
            echo "<td>{$row['last_updated']}</td>";
            echo "<td><a href='edit.php?id={$row['id']}'>Edit</a> | <a href='delete.php?id={$row['id']}'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>