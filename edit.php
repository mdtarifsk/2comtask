<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM leads WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "Lead not found.";
    exit;
}

$lead = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $status = $conn->real_escape_string($_POST['status']);

    $updateQuery = "UPDATE leads SET 
        name = '$name', 
        email = '$email', 
        phone = '$phone', 
        status = '$status', 
        last_updated = NOW() 
        WHERE id = $id";

    if ($conn->query($updateQuery)) {
        // Log lead history
        $historyQuery = "INSERT INTO lead_history (lead_id, status_from, status_to, updated_by) 
                         VALUES ($id, '{$lead['status']}', '$status', {$_SESSION['user_id']})";
        $conn->query($historyQuery);

        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error updating lead.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lead</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
<h4><a href="dashboard.php">Go to Dashboard</a></h4>
<h1>Edit Lead</h1>
<form method="POST" action="">
    <input type="text" name="name" value="<?= $lead['name'] ?>" required>
    <br>
    <input type="email" name="email" value="<?= $lead['email'] ?>" required>
    <br>
    <input type="text" name="phone" value="<?= $lead['phone'] ?>" required>
    <br>
    <select name="status" required>
        <option value="New" <?= $lead['status'] === 'New' ? 'selected' : '' ?>>New</option>
        <option value="In Progress" <?= $lead['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
        <option value="Closed" <?= $lead['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
    </select>
    <br>
    <button type="submit">Update Lead</button>
</form>
</body>
</html>
