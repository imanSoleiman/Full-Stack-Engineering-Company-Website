<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';
require_once __DIR__ . '/../includes/image_upload.php';

$id = $_POST['id'];
$header = $conn->real_escape_string($_POST['header']);
$description = $conn->real_escape_string($_POST['description']);

// Handle image upload
if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
    $imageName = spectrum_store_image(
        $_FILES['image'],
        'services',
        __DIR__ . '/../../assets/service_page_uploads/'
    );
    $conn->query("UPDATE cadd_section SET header='$header', description='$description', image='$imageName' WHERE id=$id");
} else {
    $conn->query("UPDATE cadd_section SET header='$header', description='$description' WHERE id=$id");
}

header("Location: admin_cadd.php");
exit();
