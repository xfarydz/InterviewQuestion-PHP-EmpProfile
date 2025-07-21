<?php
// Enable error reporting for troubleshooting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = 'employees.csv';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['row'])) {
    $row_to_delete = (int)$_GET['row'];
    
    if (!file_exists($file)) {
        header('Location: view_employees.php');
        exit;
    }

    // Read all data
    $employees = [];
    $fp = fopen($file, 'r');
    while (($row = fgetcsv($fp)) !== false) {
        $employees[] = $row;
    }
    fclose($fp);

    // Check if row exists (accounting for header)
    if ($row_to_delete < 1 || $row_to_delete >= count($employees)) {
        header('Location: view_employees.php');
        exit;
    }

    // Remove the specified row (add 1 to skip header)
    array_splice($employees, $row_to_delete, 1);

    // Rewrite the file
    $fp = fopen($file, 'w');
    foreach ($employees as $employee) {
        fputcsv($fp, $employee);
    }
    fclose($fp);

    // Redirect with success message
    header('Location: view_employees.php?deleted=1');
    exit;
} else {
    // Invalid request
    header('Location: view_employees.php');
    exit;
}
?>
