<?php 
include "../../config.php";
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
$id = $_GET['id'];
$slide = $conn->query("SELECT * FROM professional_slides WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];

    if (!empty($_FILES["image"]["name"])) {
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $target = "../../assets/teams/" . $fileName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);

        $stmt = $conn->prepare("UPDATE professional_slides SET title=?, image=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $fileName, $id);
    } else {
        $stmt = $conn->prepare("UPDATE professional_slides SET title=? WHERE id=?");
        $stmt->bind_param("si", $title, $id);
    }
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Slide</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
/* Body & Typography */
body {
    font-family: 'Inter', sans-serif;
    background: #f4f6f8;
    color: #333;
    padding: 20px;
}
h1, h2 { color: #007bff; }
h1 { text-align: center; margin-bottom: 30px; }
h2 { margin-top: 40px; margin-bottom: 15px; }

/* Form Styling */
form {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    max-width: 600px;
    margin: auto;
}
form input[type="text"], form input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
}
form button {
    padding: 12px 22px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 1rem;
    transition: 0.3s;
}
form button:hover { background: #0056b3; }

img.slide-preview {
    max-width: 200px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
}

/* List Section Styling */
.list-section {
    max-width: 800px;
    margin: 40px auto;
}
.list-section a.add-btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 18px;
    background: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s;
}
.list-section a.add-btn:hover { background: #218838; }

/* List Items */
ul.list-items {
    list-style: none;
    padding: 0;
    margin: 0;
}
ul.list-items li {
    background: #fff;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
ul.list-items li div.actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 5px;
}
ul.list-items li div.actions a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: 0.3s;
    color: #fff;
}
ul.list-items li div.actions a.edit {
    background-color: #007bff;
}
ul.list-items li div.actions a.delete {
    background-color: #dc3545;
}
ul.list-items li div.actions a.edit:hover { background-color: #0056b3; }
ul.list-items li div.actions a.delete:hover { background-color: #a71d2a; }

/* Responsive */
@media(max-width:600px){
    ul.list-items li { flex-direction: column; align-items: flex-start; }
    ul.list-items li div.actions { margin-top: 12px; }
}
</style>

</head>
<body>

<h1>Edit Slide</h1>

<form method="post" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($slide['title']) ?>" required>

    <label>Image:</label>
    <img src="../../assets/teams/<?= htmlspecialchars($slide['image']) ?>" alt="Slide Image" class="slide-preview">
    <input type="file" name="image" accept="image/*">

    <button type="submit"><i class="fa fa-save"></i> Save Changes</button>
</form>

<div class="list-section">
<h2>Manage List Items</h2>
<a href="add_list.php?slide_id=<?= $id ?>" class="add-btn"><i class="fa fa-plus"></i> Add List Item</a>

<ul class="list-items">
<?php
$lists = $conn->query("SELECT * FROM professional_lists WHERE slide_id=$id");
while ($l = $lists->fetch_assoc()):
?>
<li>
    <div>
        <strong><?= htmlspecialchars($l['heading']) ?>:</strong> <?= htmlspecialchars($l['description']) ?>
    </div>
    <div class="actions">
        <a href="edit_list.php?id=<?= $l['id'] ?>&slide_id=<?= $id ?>" class="edit"><i class="fa fa-edit"></i> Edit</a>
        <a href="delete_list.php?id=<?= $l['id'] ?>&slide_id=<?= $id ?>" class="delete" onclick="return confirm('Delete this item?')"><i class="fa fa-trash"></i> Delete</a>
    </div>
</li>
<?php endwhile; ?>
</ul>
</div>

</body>
</html>
