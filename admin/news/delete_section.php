<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])) die("Section ID missing.");
$id = intval($_GET['id']);

// Get news_id for redirect
$section = $conn->query("SELECT news_id FROM news_sections WHERE id=$id")->fetch_assoc();
$news_id = $section['news_id'] ?? 0;

// Delete section
$conn->query("DELETE FROM news_sections WHERE id=$id");

header("Location: admin-details.php?id=$news_id");
exit;
