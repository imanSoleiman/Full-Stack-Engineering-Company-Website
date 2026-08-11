<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$action = $_POST['action'];

if($action == 'add'){
    $text = $conn->real_escape_string($_POST['text']);
    $section_id = $_POST['section_id'];
    $conn->query("INSERT INTO cadd_columns (cadd_section_id, text) VALUES ('$section_id', '$text')");
    header("Location: admin_cadd.php");
    exit();
}

if($action == 'update'){
    $id = $_POST['id'];
    $text = $conn->real_escape_string($_POST['text']);
    $conn->query("UPDATE cadd_columns SET text='$text' WHERE id=$id");
    header("Location: admin_cadd.php");
    exit();
}

if($action == 'delete'){
    $id = $_POST['id'];
    $conn->query("DELETE FROM cadd_columns WHERE id=$id");
    header("Location: admin_cadd.php");
    exit();
}
