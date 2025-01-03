<?php
require 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];

    // Check file type
    $validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'];
    if (!in_array($fileType, $validTypes)) {
        echo "Invalid file type. Please upload an Excel (.xlsx, .xls) or CSV file.";
        exit;
    }

    // Parse the file
    $spreadsheet = null;
    if ($fileType === 'text/csv') {
        $reader = IOFactory::createReader('Csv');
        $reader->setDelimiter(',');
        $spreadsheet = $reader->load($file);
    } else {
        $spreadsheet = IOFactory::load($file);
    }

    $sheetData = $spreadsheet->getActiveSheet()->toArray();
    $successCount = 0;
    $failCount = 0;
    $errors = [];

    foreach ($sheetData as $index => $row) {
        if ($index === 0) continue; // Skip header row

        $name = isset($row[0]) ? $conn->real_escape_string(trim($row[0])) : null;
        $email = isset($row[1]) ? $conn->real_escape_string(trim($row[1])) : null;
        $phone = isset($row[2]) ? $conn->real_escape_string(trim($row[2])) : null;
        $status = isset($row[3]) ? $conn->real_escape_string(trim($row[3])) : null;

        // Validate fields
        if (empty($name) || empty($email) || empty($phone) || empty($status)) {
            $failCount++;
            $errors[] = "Row " . ($index + 1) . ": Missing required fields.";
            continue;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $failCount++;
            $errors[] = "Row " . ($index + 1) . ": Invalid email format.";
            continue;
        }

        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            $failCount++;
            $errors[] = "Row " . ($index + 1) . ": Invalid phone number.";
            continue;
        }

        // Insert into database
        $query = "INSERT INTO leads (name, email, phone, status, date_added, last_updated) 
                  VALUES ('$name', '$email', '$phone', '$status', NOW(), NOW())";

        if ($conn->query($query)) {
            $successCount++;
        } else {
            $failCount++;
            $errors[] = "Row " . ($index + 1) . ": " . $conn->error;
        }
    }

    // Display summary
    echo "<h3>Import Summary</h3>";
    echo "Successfully Imported: $successCount<br>";
    echo "Failed Records: $failCount<br>";
    if (!empty($errors)) {
        echo "<h4>Errors:</h4><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import Leads</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h4><a href="dashboard.php">Go to Dashboard</a></h4>
<h1>Import Leads</h1>
<form method="POST" enctype="multipart/form-data">
    <label for="file">Choose an Excel or CSV file:</label>
    <input type="file" name="file" id="file" accept=".xlsx, .xls, .csv" required>
    <br>
    <button type="submit">Import</button>
</form>
</body>
</html>
