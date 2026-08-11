<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$project = $conn->query("SELECT * FROM projects WHERE id = $id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Project</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 20px;
}
.container {
    max-width: 700px;
    margin: 40px auto;
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}
form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
}
form input[type="text"],
form input[type="url"],
form select,
form input[type="file"] {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s;
}
form input[type="text"]:focus,
form input[type="url"]:focus,
form select:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0,123,255,0.2);
}
form select {
    cursor: pointer;
}
img.current-image {
    display: block;
    margin-bottom: 20px;
    border-radius: 8px;
    max-width: 150px;
    border: 1px solid #ddd;
}
button {
    display: inline-block;
    padding: 12px 25px;
    background-color: #007bff;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background-color: #0056b3;
}
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }
}
</style>
</head>
<body>

<div class="container">
    <h2>Edit Project</h2>
    <form action="save_project.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $project['id'] ?>">

        <label for="name">Project Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($project['name']) ?>" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($project['location']) ?>" required>

        <label for="location_url">Location URL (optional):</label>
        <input type="url" id="location_url" name="location_url" value="<?= htmlspecialchars($project['location_url'] ?? '') ?>" placeholder="https://maps.google.com/...">

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $project['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Current Image:</label>
        <img src="../../assets/projects_uploads/<?= htmlspecialchars($project['image_path']) ?>" alt="Project Image" class="current-image">

        <label for="image">Change Image:</label>
        <input type="file" id="image" name="image">

        <button type="submit" name="action" value="update">Update Project</button>
    </form>
</div>

</body>
</html>
