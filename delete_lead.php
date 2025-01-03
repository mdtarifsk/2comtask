<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $leadId = $conn->real_escape_string($_GET['id']);

    // Check if lead exists
    $checkLead = $conn->query("SELECT id FROM leads WHERE id = '$leadId'");
    if ($checkLead->num_rows === 0) {
        echo "Lead not found.";
    } else {
        // Delete the lead
        $query = "DELETE FROM leads WHERE id = '$leadId'";
        if ($conn->query($query)) {
            echo "Lead deleted successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    echo "Invalid request.";
}
?>
