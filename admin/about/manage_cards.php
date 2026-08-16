<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../includes/image_upload.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}





$result = $conn->query("SELECT * FROM our_story_cards ORDER BY id ASC");
$counter = 1;
?>
<a href="add_card.php">+ Add New Card</a>
<table border="1">
<tr><th>#</th><th>Image</th><th>Title</th><th>Description</th><th>Actions</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $counter++ ?></td>
  <td><img src="<?= htmlspecialchars(spectrum_admin_image_src($row['image_path'], '../../assets/about/')) ?>" width="80"></td>
  <td><?= $row['title'] ?></td>
  <td><?= $row['description'] ?></td>
  <td>
    <a href="edit_card.php?id=<?= $row['id'] ?>">Edit</a> |
    <a href="delete_card.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this card?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
