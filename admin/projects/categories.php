<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    header('Location: categories.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    header('Location: index.php');
    exit;
}

$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<h2>Manage Categories</h2>
<form method="POST">
  <input type="text" name="name" required placeholder="New Category">
  <button type="submit">Add</button>
</form>

<ul>
  <?php foreach ($categories as $cat): ?>
    <li><?= $cat['name'] ?> 
      <a href="?delete=<?= $cat['id'] ?>" onclick="return confirm('Delete this category?')">Delete</a>
    </li>
  <?php endforeach; ?>
</ul>
