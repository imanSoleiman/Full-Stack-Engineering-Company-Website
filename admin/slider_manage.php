<?php
session_start();
include('../config.php');

// Only logged-in admins can access
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$mysqli = $conn;

$errors = [];
$success = '';

$uploadDir = __DIR__ . '/../assets/home';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ADD SLIDE
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $title = trim($_POST['title'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($title === '') $errors[] = 'Title is required';
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) $errors[] = 'Image is required';

        if (!$errors) {
            $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['image']['tmp_name']);
            if (!in_array($mime, $allowed, true)) $errors[] = 'Invalid image type. Allowed: JPG, PNG, WEBP, GIF.';
            if ($_FILES['image']['size'] > 5*1024*1024) $errors[] = 'Image too large (max 5MB).';
        }

        if (!$errors) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $basename = bin2hex(random_bytes(8)) . '.' . strtolower($ext);
            $destPath = $uploadDir . '/' . $basename;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
                $errors[] = 'Failed to save image.';
            } else {
                $stmt = $mysqli->prepare("INSERT INTO slider_items (title, description, image_path, sort_order, is_active) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('sssii', $title, $desc, $basename, $sortOrder, $isActive);
                if ($stmt->execute()) $success = 'Slide added successfully.';
                else $errors[] = 'Insert failed: ' . $stmt->error;
                $stmt->close();
            }
        }
    }

    // TOGGLE SLIDE ACTIVE
    if (isset($_POST['action']) && $_POST['action'] === 'toggle' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $stmt = $mysqli->prepare("UPDATE slider_items SET is_active = 1 - is_active WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) $success = 'Toggled slide.';
        else $errors[] = 'Toggle failed: ' . $stmt->error;
        $stmt->close();
    }

    // TOGGLE SIDE SLIDES
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_side_slides') {
        $stmt = $mysqli->prepare("UPDATE slider_settings SET show_side_slides = 1 - show_side_slides WHERE id = 1");
        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    // DELETE SLIDE
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $stmt = $mysqli->prepare("SELECT image_path FROM slider_items WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $file = $uploadDir . '/' . $row['image_path'];
            if (is_file($file)) @unlink($file);
        }
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE FROM slider_items WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) $success = 'Slide deleted.';
        else $errors[] = 'Delete failed: ' . $stmt->error;
        $stmt->close();
    }

    // EDIT SLIDE
    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $title = trim($_POST['title'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $params = [$title, $desc, $sortOrder, $isActive, $id];
        $types = 'ssiii';
        $sql = "UPDATE slider_items SET title=?, description=?, sort_order=?, is_active=? WHERE id=?";

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $basename = bin2hex(random_bytes(8)) . '.' . strtolower($ext);
            $destPath = $uploadDir . '/' . $basename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
                $stmtImg = $mysqli->prepare("SELECT image_path FROM slider_items WHERE id=?");
                $stmtImg->bind_param('i', $id);
                $stmtImg->execute();
                $resImg = $stmtImg->get_result();
                if ($rowImg = $resImg->fetch_assoc()) {
                    $oldFile = $uploadDir . '/' . $rowImg['image_path'];
                    if (is_file($oldFile)) @unlink($oldFile);
                }
                $stmtImg->close();

                $sql = "UPDATE slider_items SET title=?, description=?, sort_order=?, is_active=?, image_path=? WHERE id=?";
                $params = [$title, $desc, $sortOrder, $isActive, $basename, $id];
                $types = 'ssiisi';
            }
        }

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) $success = 'Slide updated successfully.';
        else $errors[] = 'Update failed: ' . $stmt->error;
        $stmt->close();
    }
}

// FETCH SLIDES
$slides = [];
$res = $mysqli->query("SELECT * FROM slider_items ORDER BY sort_order ASC, id ASC");
if ($res) $slides = $res->fetch_all(MYSQLI_ASSOC);

// FETCH SIDE SLIDE SETTING
$setting = $mysqli->query("SELECT show_side_slides FROM slider_settings WHERE id = 1")->fetch_assoc();
$currentSetting = $setting ? (int)$setting['show_side_slides'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Slider Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family:Segoe UI,Roboto,sans-serif; margin:0; background:#f5f5f5;}
.container{max-width:1200px; margin:auto; padding:20px;}
h1,h2{color:#333;}
.alert{padding:12px 15px; border-radius:6px; margin-bottom:20px;}
.alert.err{background:#ffecec; border:1px solid #e99; color:#900;}
.alert.ok{background:#ecffec; border:1px solid #9e9; color:#060;}
form{background:#fff; border-radius:8px;}
form label{display:block; margin-bottom:8px; font-weight:600;}
form input[type=text], form textarea, form input[type=number], form input[type=file]{width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:4px;}
form input[type=checkbox]{margin-right:6px;}
form button{padding:10px 15px; border:none; border-radius:4px; background:#007bff; color:#fff; cursor:pointer;}
form button:hover{background:#0056b3;}
table{width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05);}
table th, table td{padding:12px; text-align:left; border-bottom:1px solid #eee;}
table th{background:#f0f0f0;}
table tr:hover{background:#fafafa;}
img{max-height:60px; border-radius:4px;}
.actions button{margin-right:5px; background:#28a745;}
.actions button.delete{background:#dc3545;}
.actions button:hover{opacity:0.9;}
.edit-form{background:#f9f9f9; padding:15px; border-radius:6px; margin-top:10px; display:none;}

/* Carousel preview */
.carousel-preview{display:flex; overflow:hidden; margin-top:15px; border:1px solid #ccc; border-radius:6px; padding:10px; background:#fff;}
.carousel-preview.with-sides .carousel-item{transform:scale(0.8); opacity:0.5;}
.carousel-preview .carousel-item{flex:0 0 auto; width:120px; margin-right:10px; transition:all 0.3s ease; text-align:center;}
.carousel-preview img{max-width:100%; border-radius:4px; display:block; margin-bottom:5px;}
.slides-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.slide-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.slide-image img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.slide-info {
    padding: 15px;
}
.slide-info h3 {
    margin: 0 0 8px;
    font-size: 18px;
    color: #1f2937;
}
.slide-info p {
    font-size: 14px;
    margin: 4px 0;
    color: #4b5563;
}.slide-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
    align-items: center; /* Align buttons vertically */
}

.slide-actions form {
    display: inline-flex; /* make buttons align like normal buttons */
}

.slide-actions button {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    line-height: 1.2; /* make line height consistent */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.slide-actions .toggle { background: #16a34a; color: #fff; }
.slide-actions .toggle:hover { background: #15803d; }
.slide-actions .edit { background: #2563eb; color: #fff; }
.slide-actions .edit:hover { background: #1d4ed8; }
.slide-actions .delete { background: #dc2626; color: #fff; }
.slide-actions .delete:hover { background: #b91c1c; }

.edit-form {
    margin-top: 10px;
    padding: 10px;
    background: #f3f4f6;
    border-radius: 6px;
    display: none;
}

</style>
</head>
<body>
<div class="container">
<h1>Slider Admin</h1>

<?php if ($errors): ?>
<div class="alert err"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php elseif ($success): ?>
<div class="alert ok"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<h2>Global Setting</h2>
<table>
<tr>
    <th>Show Side Slides</th>
    <td id="sideSlidesStatus"><?= $currentSetting ? 'Enabled' : 'Disabled' ?></td>
    <td>
        <button id="toggleSideSlides">
            <?= $currentSetting ? 'Disable' : 'Enable' ?>
        </button>
    </td>
</tr>
</table>

<h2>Carousel Preview</h2>
<div class="carousel-preview <?php if($currentSetting) echo 'with-sides'; ?>">
    <?php foreach ($slides as $s): ?>
        <?php if ($s['is_active']): ?>
        <div class="carousel-item">
            <img src="../assets/home/<?= htmlspecialchars($s['image_path']) ?>" alt="">
            <p><?= htmlspecialchars($s['title']) ?></p>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<h2>Add New Slide</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <label>Title<input type="text" name="title" required></label>
    <label>Description<textarea name="description" rows="3"></textarea></label>
    <label>Image<input type="file" name="image" accept="image/*" required></label>
    <label>Sort Order<input type="number" name="sort_order" value="0"></label>
    <label><input type="checkbox" name="is_active" checked> Active</label>
    <button type="submit">Save Slide</button>
</form>
<h2>Existing Slides</h2>
<div class="slides-container">
<?php foreach ($slides as $s): ?>
<div class="slide-card">
    <div class="slide-image">
        <?php if ($s['image_path']): ?>
        <img src="../assets/home/<?= htmlspecialchars($s['image_path']) ?>" alt="<?= htmlspecialchars($s['title']) ?>">
        <?php endif; ?>
    </div>
    <div class="slide-info">
        <h3><?= htmlspecialchars($s['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars($s['description'])) ?></p>
        <p><strong>Image:</strong> <?= htmlspecialchars($s['image_path']) ?></p>
        <p><strong>Order:</strong> <?= (int)$s['sort_order'] ?> | <strong>Active:</strong> <?= $s['is_active'] ? 'Yes' : 'No' ?></p>
        <div class="slide-actions">
            <form method="post">
                <input type="hidden" name="action" value="toggle">
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <button type="submit" class="toggle"><?= $s['is_active'] ? 'Deactivate' : 'Activate' ?></button>
            </form>
            <button type="button" class="edit" onclick="toggleEdit('edit-<?= $s['id'] ?>')">Edit</button>
            <form method="post" onsubmit="return confirm('Delete this slide?');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <button type="submit" class="delete">Delete</button>
            </form>
        </div>

        <!-- Edit Form -->
        <div id="edit-<?= $s['id'] ?>" class="edit-form">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <label>Title<input type="text" name="title" value="<?= htmlspecialchars($s['title']) ?>" required></label>
                <label>Description<textarea name="description" rows="3"><?= htmlspecialchars($s['description']) ?></textarea></label>
                <label>Sort Order<input type="number" name="sort_order" value="<?= (int)$s['sort_order'] ?>"></label>
                <label><input type="checkbox" name="is_active" <?= $s['is_active'] ? 'checked' : '' ?>> Active</label>
                <label>Replace Image<input type="file" name="image" accept="image/*"></label>
                <button type="submit" class="edit">Save Changes</button>
                <button type="button" class="cancel" onclick="toggleEdit('edit-<?= $s['id'] ?>')">Cancel</button>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<script>
function toggleEdit(id){
    const el = document.getElementById(id);
    el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}

document.getElementById('toggleSideSlides').addEventListener('click', function() {
    const btn = this;
    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=toggle_side_slides'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Toggle button text
            btn.textContent = btn.textContent === 'Enable' ? 'Disable' : 'Enable';

            // Update status
            const statusCell = document.getElementById('sideSlidesStatus');
            statusCell.textContent = statusCell.textContent === 'Enabled' ? 'Disabled' : 'Enabled';

            // Toggle carousel preview
            const preview = document.querySelector('.carousel-preview');
            preview.classList.toggle('with-sides');

            alert('Side slides setting updated!');
        } else {
            alert('Failed to update setting.');
        }
    })
    .catch(err => console.error(err));
});
</script>
</body>
</html>
