<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}


// Ensure there is a section
$sectionResult = $conn->query("SELECT * FROM cadd_section LIMIT 1");
if($sectionResult->num_rows > 0){
    $section = $sectionResult->fetch_assoc();
} else {
    $conn->query("INSERT INTO cadd_section (header, description, image) VALUES ('CADD Capabilities','Default description','default.jpg')");
    $section = $conn->query("SELECT * FROM cadd_section LIMIT 1")->fetch_assoc();
}

// Fetch CAD columns
$columns = $conn->query("SELECT * FROM cadd_columns WHERE cadd_section_id={$section['id']}");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage CADD Section</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h2, h3 {
    color: #333;
}
form label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold;
    color: #555;
}
input[type="text"], textarea, input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    box-sizing: border-box;
}
textarea { resize: vertical; height: 100px; }
img {
    border-radius: 5px;
    margin-bottom: 15px;
    display: block;
    max-width: 250px;
    border: 1px solid #ddd;
}
button {
    padding: 8px 15px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
    font-size: 14px;
}
button.save {
    background: #007bff;
    color: #fff;
}
button.save:hover { background: #0056b3; }
button.update {
    background: #28a745;
    color: #fff;
}
button.update:hover { background: #218838; }
button.delete {
    background: #dc3545;
    color: #fff;
}
button.delete:hover { background: #c82333; }

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
th {
    background: #007bff;
    color: #fff;
    font-weight: normal;
}
tr:hover {
    background: #f1f1f1;
}
.section-card {
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    margin-bottom: 30px;
}
.add-column-form input[type="text"] {
    width: auto;
    display: inline-block;
    margin-right: 10px;
}
.add-column-form button {
    display: inline-block;
}
</style>
</head>
<body>
<div class="container">

<h2>Edit CADD Section</h2>

<div class="section-card">
<form action="save_cadd.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $section['id'] ?>">

    <label>Header:</label>
    <input type="text" name="header" value="<?= htmlspecialchars($section['header']) ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?= htmlspecialchars($section['description']) ?></textarea>

    <label>Current Image:</label>
    <img src="../../assets/service_page_uploads/<?= $section['image'] ?>" alt="Section Image">

    <label>Change Image:</label>
    <input type="file" name="image">

    <button type="submit" class="save">Save Section</button>
</form>
</div>

<h2>Manage CAD Columns</h2>
<table>
<tr>
    <th>Column Text</th>
    <th>Actions</th>
</tr>
<?php while($col = $columns->fetch_assoc()): ?>
<tr>
    <form action="cadd_column.php" method="POST" enctype="multipart/form-data">
        <td>
            <input type="text" name="text" value="<?= htmlspecialchars($col['text']) ?>" required>
            <input type="hidden" name="id" value="<?= $col['id'] ?>">
        </td>
        <td>
            <button type="submit" name="action" value="update" class="update">Update</button>
            <button type="submit" name="action" value="delete" class="delete" onclick="return confirm('Are you sure?')">Delete</button>
        </td>
    </form>
</tr>
<?php endwhile; ?>
</table>

<h3>Add New CAD Column</h3>
<form action="cadd_column.php" method="POST" class="add-column-form">
    <input type="hidden" name="section_id" value="<?= $section['id'] ?>">
    <input type="text" name="text" placeholder="Column text" required>
    <button type="submit" name="action" value="add" class="save">Add Column</button>
</form>

</div>
</body>
</html>
