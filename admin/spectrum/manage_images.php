<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

if(!isset($_GET['category_id'])) { header("Location: index.php"); exit; }
$category_id = $_GET['category_id'];

// Fetch category
$cat = $conn->query("SELECT * FROM spectrum_categories WHERE id=$category_id")->fetch_assoc();

// Fetch images
$images = $conn->query("SELECT * FROM spectrum_images WHERE category_id=$category_id ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Images for <?= htmlspecialchars($cat['name']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    text-align: center;
}
a.btn {
    display: inline-block;
    margin-bottom: 20px;
    margin-right: 10px;
    padding: 10px 16px;
    background: #1E90FF;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: 0.3s;
}
a.btn:hover {
    background: #0d6efd;
}
.table-container {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}
th {
    background: #1E90FF;
    color: #fff;
    text-transform: uppercase;
    font-size: 14px;
}
tr:hover {
    background: #f0f8ff;
}
img.preview {
    max-width: 100px;
    border-radius: 6px;
    border: 1px solid #ddd;
}
.actions a {
    margin-right: 8px;
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
}
.actions a.edit {
    background: #1E90FF;
    color: #fff;
}
.actions a.delete {
    background: #dc3545;
    color: #fff;
}
.actions a:hover {
    opacity: 0.85;
}
@media(max-width: 600px){
    th, td { padding: 10px; font-size: 14px; }
    a.btn { padding: 8px 12px; font-size: 14px; }
}
</style>
</head>
<body>

<h1><i class="fa fa-images"></i> Images in <?= htmlspecialchars($cat['name']) ?></h1>
<a href="add_image.php?category_id=<?= $category_id ?>" class="btn"><i class="fa fa-plus"></i> Add New Image</a>
<a href="index.php" class="btn"><i class="fa fa-arrow-left"></i> Back to Categories</a>

<div class="table-container">
<table>
    <tr>
        <th>ID</th>
        <th>Header</th>
        <th>Description</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php while($img = $images->fetch_assoc()): ?>
    <tr>
        <td><?= $img['id'] ?></td>
        <td><?= htmlspecialchars($img['header']) ?></td>
        <td><?= htmlspecialchars($img['description']) ?></td>
        <td><img src="../../assets/spectrum/<?= htmlspecialchars($img['image_name']) ?>" class="preview"></td>
        <td class="actions">
            <a href="edit_image.php?id=<?= $img['id'] ?>" class="edit"><i class="fa fa-edit"></i> Edit</a>
            <a href="delete_image.php?id=<?= $img['id'] ?>&category_id=<?= $category_id ?>" class="delete" onclick="return confirm('Delete this image?')"><i class="fa fa-trash"></i> Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

</body>
</html>
