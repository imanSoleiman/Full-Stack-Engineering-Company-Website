<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Delete category and related images (ON DELETE CASCADE)
    $conn->query("DELETE FROM spectrum_categories WHERE id=$id");

    header("Location: index.php");
}
