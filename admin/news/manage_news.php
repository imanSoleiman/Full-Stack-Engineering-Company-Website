<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Delete news
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM news_cards WHERE id=$id");
    header("Location: index.php");
    exit;
}

// Fetch all news with category
$result = $conn->query("
    SELECT n.id, n.title, n.date_day, n.date_month, n.date_year, n.image, y.year, c.name AS category_name
    FROM news_cards n 
    JOIN news_years y ON n.year_id = y.id 
    LEFT JOIN news_categories c ON n.category_id = c.id
    ORDER BY n.date_year DESC, n.date_month DESC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage News</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #eee; }
    a.button { padding: 5px 10px; background: #007bff; color: #fff; text-decoration: none; border-radius: 5px; }
    a.button.red { background: #dc3545; }
  </style>
</head>
<body>

<h1>Manage News</h1>
<a href="add_news.php" class="button">+ Add News</a>
<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Date</th>
    <th>Year</th>
    <th>Category</th> <!-- New column -->
    <th>Image</th>
    <th>Actions</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= $row['date_day'] . "-" . $row['date_month'] . "-" . $row['date_year'] ?></td>
    <td><?= $row['year'] ?></td>
    <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td> <!-- Display category -->
    <td><img src="../../assets/news/<?= $row['image'] ?>" width="100"></td>
    <td>
        <a href="edit_news.php?id=<?= $row['id'] ?>" class="button">Edit</a>
        <a href="?delete=<?= $row['id'] ?>" class="button red" onclick="return confirm('Are you sure?')">Delete</a>
        <a href="admin-details.php?id=<?= $row['id'] ?>" class="button">Details</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
