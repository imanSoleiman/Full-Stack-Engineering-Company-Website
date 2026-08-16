<?php
require_once __DIR__ . '/../session.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}


if(isset($_POST['id'])) {
    $id = $_POST['id'];
    $header = $_POST['header_text'];
    $body = $_POST['body_text'];

    $stmt = $conn->prepare("UPDATE sections SET header_text=?, body_text=? WHERE id=?");
    $stmt->bind_param("ssi", $header, $body, $id);
    $stmt->execute();

    header("Location: manage_sections.php");
}
?>
