<?php
require_once __DIR__ . '/../session.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM middle_east_countries WHERE id=$id");

header("Location: middle_east.php");
exit;
?>
