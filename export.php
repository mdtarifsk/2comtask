<?php
require 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $format = $_POST['format'] ?? 'xlsx'; // Default to xlsx
    $statusFilter = $_POST['status'] ?? 'All';

    // Build query with optional filter
    $query = "SELECT * FROM leads";
    if (!empty($statusFilter) && $statusFilter !== 'All') {
        $query .= " WHERE status = '" . $conn->real_escape_string($statusFilter) . "'";
    }

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['ID', 'Name', 'Email', 'Phone', 'Status', 'Date Added', 'Last Updated'];
        $sheet->fromArray($headers, null, 'A1');

        // Add data
        $rowIndex = 2;
        while ($row = $result->fetch_assoc()) {
            $sheet->fromArray([
                $row['id'],
                $row['name'],
                $row['email'],
                $row['phone'],
                $row['status'],
                $row['date_added'],
                $row['last_updated']
            ], null, "A$rowIndex");
            $rowIndex++;
        }

        // Generate file
        $filename = "leads_export_" . date('Y-m-d') . ".$format";
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=$filename");
        header('Cache-Control: max-age=0');

        if ($format === 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else {
            $writer = new Csv($spreadsheet);
        }

        $writer->save('php://output');
        exit;
    } else {
        echo "No leads found for the selected criteria.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Export Leads</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h4><a href="dashboard.php">Go to Dashboard</a></h4>
<h1>Export Leads</h1>
<form method="POST" action="export.php">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status">
        <option value="All">All</option>
        <option value="New">New</option>
        <option value="In Progress">In Progress</option>
        <option value="Closed">Closed</option>
    </select>
    <br>
    <label for="format">Select Format:</label>
    <select name="format" id="format" required>
        <option value="xlsx">Excel (.xlsx)</option>
        <option value="csv">CSV (.csv)</option>
    </select>
    <br>
    <button type="submit">Export</button>
</form>
</body>
</html>
