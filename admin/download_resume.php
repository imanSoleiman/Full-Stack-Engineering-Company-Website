<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['file'])) {
    die("No file specified.");
}

$file = basename($_GET['file']); // sanitize input
$filepath = 'cv/' . $file; // relative to this PHP file inside admin/

if(!file_exists($filepath)){
    die("File not found.");
}

// Optional: allow only specific extensions
$allowed_ext = ['pdf','doc','docx','txt'];
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
if(!in_array($ext, $allowed_ext)){
    die("Invalid file type.");
}

// Force download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;
?>
