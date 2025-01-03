<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $conn->real_escape_string(trim($_POST['name'])) : null;
    $email = isset($_POST['email']) ? $conn->real_escape_string(trim($_POST['email'])) : null;
    $phone = isset($_POST['phone']) ? $conn->real_escape_string(trim($_POST['phone'])) : null;
    $status = isset($_POST['status']) ? $conn->real_escape_string(trim($_POST['status'])) : null;

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($status)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        echo "Invalid phone number.";
    } else {
        // Check for duplicate email
        $checkEmail = $conn->query("SELECT id FROM leads WHERE email = '$email'");
        if ($checkEmail->num_rows > 0) {
            echo "A lead with this email already exists.";
        } else {
            $query = "INSERT INTO leads (name, email, phone, status, date_added, last_updated) 
                      VALUES ('$name', '$email', '$phone', '$status', NOW(), NOW())";
            if ($conn->query($query)) {
                echo "Lead added successfully.";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lead</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h4><a href="dashboard.php">Go to Dashboard</a></h4>
<h1>Add Lead</h1>
<form method="POST" action="add_lead.php">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <br>
    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone" required>
    <br>
    <label for="status">Status:</label>
    <select name="status" id="status" required>
        <option value="New">New</option>
        <option value="In Progress">In Progress</option>
        <option value="Closed">Closed</option>
    </select>
    <br>
    <button type="submit">Add Lead</button>
</form>
</body>
</html>
