<?php
$uploadDir = __DIR__ . '/shared_data/';
$file = 'output.sam';
$filepath = $uploadDir . $file;

if (!file_exists($filepath)) {
    http_response_code(404);
    echo "File not found.";
    exit;
}

// Set headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

flush(); // Flush system output buffer
readfile($filepath);
exit;
?>
