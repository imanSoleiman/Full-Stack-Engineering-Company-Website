<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php'); // Your DB connection

// Get image ID from URL
if(!isset($_GET['id'])){
    header("Location: manage_images.php");
    exit;
}
$id = $_GET['id'];

// Fetch image info
$image = $conn->query("SELECT * FROM gulf_spectrum_images WHERE id=$id")->fetch_assoc();
if(!$image){
    die("Image not found!");
}

// Fetch category info
$category_id = $image['category_id'];
$category = $conn->query("SELECT * FROM gulf_spectrum_categories WHERE id=$category_id")->fetch_assoc();

// Handle form submission
if(isset($_POST['submit'])){
    $header = $_POST['header'];
    $description = $_POST['description'];

    // Check if a new image was uploaded
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $imageName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];
        $uploadDir = "../../assets/gulfspectrum/";

        // Delete old image file
        if(file_exists($uploadDir.$image['image_name'])){
            unlink($uploadDir.$image['image_name']);
        }

        // Move new file
        move_uploaded_file($tmpName, $uploadDir.$imageName);
    } else {
        // Keep old image if no new upload
        $imageName = $image['image_name'];
    }

    // Update database
    $stmt = $conn->prepare("UPDATE gulf_spectrum_images SET header=?, description=?, image_name=? WHERE id=?");
    $stmt->bind_param("sssi", $header, $description, $imageName, $id);
    $stmt->execute();

    header("Location: manage_images.php?category_id=".$category_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Image - <?= htmlspecialchars($category['name']) ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 30px;
    color: #333;
}
h1 {
    color: #1E3A8A;
    margin-bottom: 20px;
}
.form-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    max-width: 600px;
    margin: auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.form-group {
    margin-bottom: 20px;
}
label {
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
}
input[type="text"], textarea, input[type="file"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
}
textarea {
    height: 100px;
    resize: vertical;
}
img.preview {
    margin-top: 10px;
    max-width: 150px;
    border-radius: 6px;
    border: 1px solid #ddd;
}
button {
    background: #1E90FF;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background: #0d6efd;
}
a.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    background: #6c757d;
    color: #fff;
    padding: 10px 16px;
    border-radius: 6px;
    font-weight: 600;
    transition: 0.3s;
}
a.back-btn:hover {
    background: #5a6268;
}
</style>
</head>
<body>

<h1><i class="fa fa-edit"></i> Edit Image for Category: <?= htmlspecialchars($category['name']) ?></h1>
<a href="manage_images.php?category_id=<?= $category_id ?>" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>

<div class="form-card">
<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Image Header:</label>
        <input type="text" name="header" value="<?= htmlspecialchars($image['header']) ?>" required>
    </div>

    <div class="form-group">
        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($image['description']) ?></textarea>
    </div>

    <div class="form-group">
        <label>Replace Image (optional):</label>
        <input type="file" name="image" accept="image/*">
        <small>Current image:</small><br>
        <img src="../../assets/gulfspectrum/<?= htmlspecialchars($image['image_name']) ?>" class="preview">
    </div>

    <button type="submit" name="submit"><i class="fa fa-save"></i> Update Image</button>
</form>
</div>

</body>
</html>
