<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Required ID"); // stop the script and show message
}

$id = $_GET['id'];

// Get images and detail id
$sql = "SELECT sc.image AS card_image, sd.image AS detail_image, sd.id AS detail_id
        FROM services_cards sc
        JOIN services_details sd ON sc.detail_page_id = sd.id
        WHERE sc.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

// Check if the ID exists in the database
if (!$row) {
    die("Service ID not found"); // stop if ID is invalid
}

// Delete images
if (file_exists('../../uploads/services/' . $row['card_image'])) {
    unlink('../../uploads/services/' . $row['card_image']);
}
if (file_exists('../../uploads/services/details/' . $row['detail_image'])) {
    unlink('../../uploads/services/details/' . $row['detail_image']);
}

// Delete detail page first
$conn->query("DELETE FROM services_details WHERE id = ".$row['detail_id']);

// Delete card
$conn->query("DELETE FROM services_cards WHERE id = ".$id);

header("Location: services_list.php");
exit;
