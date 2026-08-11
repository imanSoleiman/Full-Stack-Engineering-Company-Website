<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

$category_id = $_GET['category_id'];
$category = $conn->query("SELECT * FROM gulf_spectrum_categories WHERE id=$category_id")->fetch_assoc();

$images = $conn->query("SELECT * FROM gulf_spectrum_images WHERE category_id=$category_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Images - <?= htmlspecialchars($category['name']) ?></title>
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
a.add-btn, a.back-btn {
    display: inline-block;
    background: #1E90FF;
    color: #fff;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 20px;
    margin-right: 10px;
    transition: 0.3s;
}
a.add-btn:hover, a.back-btn:hover {
    background: #0d6efd;
}
.table-container {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
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
tr:nth-child(even) {
    background: #f9fcff;
}
tr:hover {
    background: #f0f8ff;
}
td.description {
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
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
    display: inline-block;
    transition: 0.3s;
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
</style>
</head>
<body>

<h1><i class="fa fa-images"></i> Images in <?= htmlspecialchars($category['name']) ?></h1>
<a href="index.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back to Categories</a>
<a href="add_image.php?category_id=<?= $category_id ?>" class="add-btn"><i class="fa fa-plus"></i> Add New Image</a>

<div class="table-container">
<table>
    <tr>
        <th>ID</th>
        <th>Header</th>
        <th>Description</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $images->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['header']) ?></td>
        <td class="description" title="<?= htmlspecialchars($row['description']) ?>">
            <?= htmlspecialchars($row['description']) ?>
        </td>
        <td><img src="../../assets/gulfspectrum/<?= htmlspecialchars($row['image_name']) ?>" class="preview"></td>
        <td class="actions">
            <a href="edit_image.php?id=<?= $row['id'] ?>" class="edit"><i class="fa fa-edit"></i> Edit</a>
            <a href="delete_image.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Delete this image?')"><i class="fa fa-trash"></i> Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

</body>
</html>
