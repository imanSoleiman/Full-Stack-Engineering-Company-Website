<?php include "../../config.php"; 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>
<?php
$id = $_GET['id'];
$slide_id = $_GET['slide_id'];
$conn->query("DELETE FROM professional_lists WHERE id=$id");
header("Location: edit_slide.php?id=$slide_id");
exit;
?>
