<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

// First, delete all news related to this category
$conn->query("DELETE FROM news_cards WHERE category_id = $id");

// Then, delete the category itself
$conn->query("DELETE FROM news_categories WHERE id = $id");

header("Location: manage_categories.php");
exit;
