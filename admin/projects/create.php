<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<h2 style="text-align:center; margin-bottom: 20px; color: #e22b2b;">Add New Project</h2>

<form action="save_project.php" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">

  <div style="margin-bottom: 15px;">
    <label for="name" style="display:block; font-weight:600; margin-bottom:5px;">Project Name <span style="color:red;">*</span></label>
    <input type="text" id="name" name="name" placeholder="Enter project name" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size: 0.95rem;">
  </div>

  <div style="margin-bottom: 15px;">
    <label for="location" style="display:block; font-weight:600; margin-bottom:5px;">Location <span style="color:red;">*</span></label>
    <input type="text" id="location" name="location" placeholder="Enter location" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size: 0.95rem;">
  </div>

  <div style="margin-bottom: 15px;">
    <label for="location_url" style="display:block; font-weight:600; margin-bottom:5px;">Location URL (Optional)</label>
    <input type="url" id="location_url" name="location_url" placeholder="https://maps.google.com/..." style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size: 0.95rem;">
    <small style="color:#888;">you can leave it empty</small>
  </div>

  <?php if (count($categories) > 0): ?>
    <div style="margin-bottom: 15px;">
      <label for="category_id" style="display:block; font-weight:600; margin-bottom:5px;">Category <span style="color:red;">*</span></label>
      <select id="category_id" name="category_id" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size: 0.95rem;">
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php else: ?>
    <p style="color:red; text-align:center;">⚠️ You need to <a href="categories.php" style="color:#e22b2b; text-decoration:none;">add at least one category</a> before creating projects.</p>
  <?php endif; ?>

  <div style="margin-bottom: 20px;">
    <label for="image" style="display:block; font-weight:600; margin-bottom:5px;">Project Image <span style="color:red;">*</span></label>
    <input type="file" id="image" name="image" required style="width:100%; padding:6px; border-radius:6px; border:1px solid #ccc;">
  </div>

  <button type="submit" name="action" value="create" style="width:100%; padding:12px; background-color:#e22b2b; color:#fff; font-weight:600; border:none; border-radius:8px; cursor:pointer; font-size:1rem; transition: background 0.3s;">
    Save Project
  </button>

</form>

<style>
  form input:focus,
  form select:focus {
    outline: none;
    border-color: #e22b2b;
    box-shadow: 0 0 5px rgba(226, 43, 43, 0.4);
  }

  form button:hover {
    background-color: #c71f1f;
  }
</style>