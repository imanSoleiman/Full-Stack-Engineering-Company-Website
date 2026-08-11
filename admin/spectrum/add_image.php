<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

if(!isset($_GET['category_id'])) { header("Location: index.php"); exit; }
$category_id = $_GET['category_id'];

// Handle form
if(isset($_POST['submit'])){
    $header = $_POST['header'];
    $description = $_POST['description'];
    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $uploadDir = "../../assets/spectrum/";

    move_uploaded_file($tmpName, $uploadDir.$imageName);

    $stmt = $conn->prepare("INSERT INTO spectrum_images (category_id, image_name, header, description) VALUES (?,?,?,?)");
    $stmt->bind_param("isss", $category_id, $imageName, $header, $description);
    $stmt->execute();

    header("Location: manage_images.php?category_id=".$category_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Image</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 40px;
    color: #333;
}
h1 {
    text-align: center;
    color: #1E3A8A;
    margin-bottom: 30px;
}
form {
    background: #fff;
    max-width: 600px;
    margin: auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #1E3A8A;
}
input[type="text"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
}
textarea {
    resize: vertical;
    min-height: 100px;
}
button {
    padding: 12px 20px;
    background: #1E90FF;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    font-size: 1rem;
}
button:hover {
    background: #0d6efd;
}
.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 16px;
    background: #6c757d;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: 0.3s;
}
.back-btn:hover {
    background: #5a6268;
}
</style>
</head>
<body>

<h1><i class="fa fa-plus-circle"></i> Add Image for Category ID <?= $category_id ?></h1>
<a href="manage_images.php?category_id=<?= $category_id ?>" class="back-btn"><i class="fa fa-arrow-left"></i> Back to Images</a>

<form method="POST" enctype="multipart/form-data">
    <label>Header:</label>
    <input type="text" name="header" required>

    <label>Description:</label>
    <textarea name="description" required></textarea>

    <label>Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" name="submit"><i class="fa fa-save"></i> Add Image</button>
</form>

</body>
</html>
