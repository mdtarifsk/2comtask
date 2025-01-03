<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = implode(',', array_map('intval', $_POST['ids']));
    $query = "DELETE FROM leads WHERE id IN ($ids)";
    $conn->query($query);
}

header('Location: dashboard.php');
exit;
?>
