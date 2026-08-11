<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php'; 

$sql = "SELECT sc.id, sc.title, sc.short_desc, sc.show_on_homepage, sc.image, sd.title AS detail_title
        FROM services_cards sc
        JOIN services_details sd ON sc.detail_page_id = sd.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Service Cards Admin</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h1 {
    margin-top: 0;
    color: #333;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover { text-decoration: underline; }
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background: #007bff;
    color: #fff;
    font-weight: normal;
}
tr:hover {
    background: #f1f1f1;
}
img {
    border-radius: 5px;
}
button, .toggle-label {
    cursor: pointer;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    transition: 0.3s;
}
button.edit {
    background: #28a745;
    color: #fff;
}
button.edit:hover { background: #218838; }
button.delete {
    background: #dc3545;
    color: #fff;
}
button.delete:hover { background: #c82333; }

/* Toggle switch */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 25px;
}
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 34px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 19px;
  width: 19px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #007bff;
}
input:checked + .slider:before {
  transform: translateX(25px);
}
</style>
</head>
<body>
<div class="container">
<h1>Service Cards</h1>
<a href="add_service.php" style="display:inline-block;margin-bottom:15px;">+ Add New Service</a>

<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Short Desc</th>
    <th>Card Image</th>
    <th>Detail Title</th>
    <th>Show on Home</th>
    <th>Actions</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= htmlspecialchars($row['short_desc']) ?></td>
    <td>
        <?php if(!empty($row['image'])): ?>
            <img src="../../assets/service_page_uploads/<?= $row['image']; ?>" width="80" alt="<?= htmlspecialchars($row['title']) ?>">
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($row['detail_title']) ?></td>
    <td>
        <form action="toggle_homepage.php" method="post" style="display:inline-block;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <label class="switch">
                <input type="checkbox" name="show_on_homepage" value="1" <?= $row['show_on_homepage'] ? 'checked' : '' ?> onchange="this.form.submit()">
                <span class="slider"></span>
            </label>
        </form>
    </td>
    <td>
        <a href="edit_service.php?id=<?= $row['id'] ?>" class="edit">Edit</a>
        <a href="delete_service.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>
</body>
</html>
