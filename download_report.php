<?php
// Ensure file parameter is set
if (!isset($_GET['file']) || empty($_GET['file'])) {
    die("File not specified.");
}

$file = basename($_GET['file']);
$filepath = 'uploads/' . $file;

// Check if the file exists and is a valid file
if (file_exists($filepath) && is_file($filepath)) {
    // Set headers for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    
    // Clear output buffer and read file
    ob_clean();
    flush();
    readfile($filepath);
    exit;
} else {
    die("File not found or is invalid.");
}
?>