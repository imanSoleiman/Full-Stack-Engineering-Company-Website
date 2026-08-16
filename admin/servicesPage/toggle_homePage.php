<?php
require_once __DIR__ . '/../session.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}


$id = $_POST['id'];
$show_on_homepage = isset($_POST['show_on_homepage']) ? 1 : 0;

$sql = "UPDATE services_cards SET show_on_homepage = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $show_on_homepage, $id);
$stmt->execute();

header("Location: services_list.php");
exit;
?>
