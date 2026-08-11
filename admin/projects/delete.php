<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$project = $conn->query("SELECT image_path FROM projects WHERE id = $id")->fetch_assoc();
if ($project) {
    unlink("../../assets/uploads/" . $project['image_path']);
    $conn->query("DELETE FROM projects WHERE id = $id");
}
header("Location: index.php");
exit;
