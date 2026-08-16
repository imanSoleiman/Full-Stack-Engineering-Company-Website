<?php
include '../../config.php';
require_once __DIR__ . '/../includes/image_upload.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$project = $conn->query("SELECT image_path FROM projects WHERE id = $id")->fetch_assoc();
if ($project) {
    spectrum_delete_image($project['image_path'], __DIR__ . '/../../assets/projects_uploads/');
    $conn->query("DELETE FROM projects WHERE id = $id");
}
header("Location: index.php");
exit;
