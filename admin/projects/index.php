<?php
include '../../config.php';

// ========================
// Handle Category Add/Delete
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    header('Location: index.php');
    exit;
}

if (isset($_GET['delete_category'])) {
    $id = intval($_GET['delete_category']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    header('Location: index.php');
    exit;
}

// ========================
// Fetch Categories & Projects
// ========================
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

// Fetch projects
$projects_query = "SELECT projects.*, categories.name AS category_name 
                   FROM projects 
                   LEFT JOIN categories ON projects.category_id = categories.id";
if ($category_id > 0) {
    $projects_query .= " WHERE category_id = $category_id";
}
$result = $conn->query($projects_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 20px;
}
h1, h2, h3 { color: #333; }
a.button {
    background-color: #007bff;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    margin-bottom: 20px;
    display: inline-block;
    transition: background 0.2s;
}
a.button:hover { background-color: #0056b3; }
select, input[type="text"], button {
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    margin-bottom: 15px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 40px;
}
th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th { background-color: #007bff; color: white; }
td img { border-radius: 6px; max-width: 100px; }
td a { color: #007bff; text-decoration: none; margin-right: 10px; }
td a:hover { text-decoration: underline; }
input[type="checkbox"] { transform: scale(1.2); margin-right: 5px; cursor: pointer; }
.category-list li { margin-bottom: 8px; }
</style>
</head>
<body>

<h1>Projects</h1>
<a href="create.php" class="button">➕ Add New Project</a>

<h3>Filter by Category</h3>
<form method="get">
    <select name="category_id" onchange="this.form.submit()">
        <option value="0">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<table>
<tr>
    <th>Project Name</th>
    <th>Category</th>
    <th>Image</th>
    <th>Show on Homepage</th>
    <th>location</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>

    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['category_name']) ?></td>
    <td><img src="../../assets/projects_uploads/<?= htmlspecialchars($row['image_path']); ?>" alt="Project Image"></td>
    <td>
        <form action="toggle_home.php" method="post">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <label>
                <input type="checkbox" name="show_on_home" value="1" <?= $row['show_on_home'] ? 'checked' : '' ?> onchange="this.form.submit()">
                Show on Home
            </label>
        </form>
    </td>  
      <td>
    <?php if (!empty($row['location_url'])): ?>
        <a href="<?= htmlspecialchars($row['location_url']) ?>" target="_blank" title="View Location">
            📍
        </a>
    <?php endif; ?>
    <?= htmlspecialchars($row['location']) ?>
</td>

    <td>
        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
        <a href="edit_modal.php?id=<?= $row['id'] ?>">Edit Popup</a>
        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this project?')">Delete</a>
    </td>


</tr>
<?php endwhile; ?>
</table>

<hr>

<h2>Manage Categories</h2>

<!-- Add Category -->
<form method="POST">
    <input type="text" name="name" placeholder="New Category" required>
    <button type="submit" name="add_category">Add</button>
</form>

<ul class="category-list">
<?php foreach ($categories as $cat): ?>
    <li>
        <?= htmlspecialchars($cat['name']) ?>
        <a href="?delete_category=<?= $cat['id'] ?>" onclick="return confirm('Delete this category?')">Delete</a>
    </li>
<?php endforeach; ?>
</ul>

</body>
</html>
