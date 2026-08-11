<?php
include '../../config.php';  // adjust path if needed
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if(isset($_POST['id'])) {
    $id = intval($_POST['id']);
    // If checkbox is checked, value is 1, otherwise 0
    $show_on_home = isset($_POST['show_on_home']) ? 1 : 0;

    $sql = "UPDATE projects SET show_on_home = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $show_on_home, $id);
    $stmt->execute();
}

// Redirect back to the admin table page
header("Location: index.php");
exit;
?>
