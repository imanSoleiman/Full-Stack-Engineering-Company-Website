<?php include "../../config.php";
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
 ?>
<?php
$id = $_GET['id'];
$conn->query("DELETE FROM professional_slides WHERE id=$id");
header("Location: index.php");
exit;
?>
