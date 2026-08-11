<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

if(isset($_GET['id']) && isset($_GET['category_id'])){
    $id = $_GET['id'];
    $category_id = $_GET['category_id'];

    // Delete image file
    $img = $conn->query("SELECT * FROM spectrum_images WHERE id=$id")->fetch_assoc();
    $uploadDir = "../../assets/spectrum/";
    if(file_exists($uploadDir.$img['image_name'])) unlink($uploadDir.$img['image_name']);

    // Delete DB record
    $conn->query("DELETE FROM spectrum_images WHERE id=$id");

    header("Location: manage_images.php?category_id=".$category_id);
}
