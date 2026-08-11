<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

// Fetch all categories
$categories = $conn->query("SELECT * FROM spectrum_categories ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Spectrum Categories</title>
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
a.add-btn {
    display: inline-block;
    background: #1E90FF;
    color: #fff;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 20px;
    transition: 0.3s;
}
a.add-btn:hover {
    background: #0d6efd;
}
.table-container {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid #eee;
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
.actions a {
    margin-right: 8px;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
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
.actions a.manage {
    background: #28a745;
    color: #fff;
}
.actions a:hover {
    opacity: 0.85;
}
@media(max-width:768px){
    th, td { font-size: 14px; }
    .actions a { margin-bottom: 5px; display: inline-block; }
}
</style>
</head>
<body>

<h1><i class="fa fa-layer-group"></i> Spectrum Categories</h1>
<a href="add_category.php" class="add-btn"><i class="fa fa-plus"></i> Add New Category</a>

<div class="table-container">
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php while($cat = $categories->fetch_assoc()): ?>
    <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= htmlspecialchars($cat['name']) ?></td>
        <td class="actions">
            <a href="edit_category.php?id=<?= $cat['id'] ?>" class="edit"><i class="fa fa-edit"></i> Edit</a>
            <a href="delete_category.php?id=<?= $cat['id'] ?>" class="delete" onclick="return confirm('Delete this category?')"><i class="fa fa-trash"></i> Delete</a>
            <a href="manage_images.php?category_id=<?= $cat['id'] ?>" class="manage"><i class="fa fa-image"></i> Manage Images</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

</body>
</html>
