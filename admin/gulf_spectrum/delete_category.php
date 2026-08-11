<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');
$id = $_GET['id'];

// Delete category (images will also be deleted due to FK cascade)
$conn->query("DELETE FROM gulf_spectrum_categories WHERE id=$id");
header("Location: index.php");
