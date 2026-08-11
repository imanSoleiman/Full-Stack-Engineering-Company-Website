<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');
$category_id = $_GET['category_id'];
$category = $conn->query("SELECT * FROM gulf_spectrum_categories WHERE id=$category_id")->fetch_assoc();

if(isset($_POST['submit'])){
    $header = $_POST['header'];
    $description = $_POST['description'];

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $uploadDir = "../../assets/gulfspectrum/";
    move_uploaded_file($tmpName, $uploadDir.$imageName);

    $stmt = $conn->prepare("INSERT INTO gulf_spectrum_images (category_id, image_name, header, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $category_id, $imageName, $header, $description);
    $stmt->execute();

    header("Location: manage_images.php?category_id=".$category_id);
}
?>

<h1>Add Image to Category: <?= $category['name'] ?></h1>
<form method="POST" enctype="multipart/form-data">
    <label>Image Header:</label>
    <input type="text" name="header" required><br>

    <label>Description:</label>
    <textarea name="description" required></textarea><br>

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*" required><br>

    <button type="submit" name="submit">Add Image</button>
</form>
