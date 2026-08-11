<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}


$name = $_POST['name'];
$location = $_POST['location'];
$category_id = $_POST['category_id'];
$location_url = $_POST['location_url'] ?? '';
$action = $_POST['action'];
$image = $_FILES['image']['name'];
$image_path = '';

// Handle image upload
if ($image) {
    $image_path = time() . '_' . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/projects_uploads/" . $image_path);
}

if ($action === 'create') {
    // Insert new project
    $stmt = $conn->prepare("INSERT INTO projects (name, location, location_url, category_id, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $name, $location, $location_url, $category_id, $image_path);
    $stmt->execute();
} elseif ($action === 'update') {
    $id = $_POST['id'];
    if ($image_path) {
        $stmt = $conn->prepare("UPDATE projects SET name=?, location=?, location_url=?, category_id=?, image_path=? WHERE id=?");
        $stmt->bind_param("sssisi", $name, $location, $location_url, $category_id, $image_path, $id);
    } else {
        $stmt = $conn->prepare("UPDATE projects SET name=?, location=?, location_url=?, category_id=? WHERE id=?");
        $stmt->bind_param("sssii", $name, $location, $location_url, $category_id, $id);
    }
    $stmt->execute();
}

header("Location: index.php");
exit;
