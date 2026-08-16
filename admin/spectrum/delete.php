<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');
require_once __DIR__ . '/../includes/image_upload.php';

if(isset($_GET['id']) && isset($_GET['category_id'])){
    $id = $_GET['id'];
    $category_id = $_GET['category_id'];

    // Delete image file
    $img = $conn->query("SELECT * FROM spectrum_images WHERE id=$id")->fetch_assoc();
    spectrum_delete_image($img['image_name'], __DIR__ . '/../../assets/spectrum/');

    // Delete DB record
    $conn->query("DELETE FROM spectrum_images WHERE id=$id");

    header("Location: manage_images.php?category_id=".$category_id);
}
