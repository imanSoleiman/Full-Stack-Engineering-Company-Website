<?php
include("../../config.php");
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}


// ================== HANDLE OUR STORY SECTION ==================
$section = $conn->query("SELECT * FROM our_story_section LIMIT 1")->fetch_assoc();
$section_success = "";

if (isset($_POST['save_section'])) {
    $title = $_POST['section_title'];
    $subtitle = $_POST['section_subtitle'];
    $paragraph = $_POST['section_paragraph'];

    if (!$section) {
        $stmt = $conn->prepare("INSERT INTO our_story_section (section_title, section_subtitle, section_paragraph) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $subtitle, $paragraph);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE our_story_section SET section_title=?, section_subtitle=?, section_paragraph=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $subtitle, $paragraph, $section['id']);
        $stmt->execute();
    }
    $section_success = "Section updated successfully!";
    $section = $conn->query("SELECT * FROM our_story_section LIMIT 1")->fetch_assoc(); // refresh
}

// ================== HANDLE ADD CARD ==================
$success_message = "";
if(isset($_POST['add_card'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $target = "../../assets/about/" . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);

        $stmt = $conn->prepare("INSERT INTO our_story_cards (title, description, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $imageName);
        $stmt->execute();

        $success_message = "Card added successfully!";
    }
}

// ================== HANDLE DELETE CARD ==================
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $card = $conn->query("SELECT image_path FROM our_story_cards WHERE id=$id")->fetch_assoc();
    if($card && file_exists("../../assets/about/".$card['image_path'])){
        unlink("../../assets/about/".$card['image_path']);
    }
    $conn->query("DELETE FROM our_story_cards WHERE id=$id");
    $success_message = "Card deleted successfully!";
}

// ================== HANDLE EDIT CARD ==================
$edit_card = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $edit_card = $conn->query("SELECT * FROM our_story_cards WHERE id=$id")->fetch_assoc();
}

if(isset($_POST['edit_card'])){
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if(!empty($_FILES['image']['name'])){
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $target = "../../assets/about/".$imageName;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            $old = $conn->query("SELECT image_path FROM our_story_cards WHERE id=$id")->fetch_assoc();
            if($old && file_exists("../../assets/about/".$old['image_path'])){
                unlink("../../assets/about/".$old['image_path']);
            }
            $stmt = $conn->prepare("UPDATE our_story_cards SET title=?, description=?, image_path=? WHERE id=?");
            $stmt->bind_param("sssi", $title, $description, $imageName, $id);
            $stmt->execute();
        }
    } else {
        $stmt = $conn->prepare("UPDATE our_story_cards SET title=?, description=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $description, $id);
        $stmt->execute();
    }
    $success_message = "Card updated successfully!";
}

// ================== FETCH ALL CARDS ==================
$result = $conn->query("SELECT * FROM our_story_cards ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Our Story</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Roboto', sans-serif; background: #f4f6f8; margin: 0; padding: 20px; }
.container { max-width: 1000px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
h1 { text-align: center; margin-bottom: 30px; }
form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 30px; }
input, textarea { padding: 10px; font-size: 14px; border-radius: 5px; border: 1px solid #ccc; }
textarea { resize: vertical; height: 100px; }
button { padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #0056b3; }
table { width: 100%; border-collapse: collapse; }
table, th, td { border: 1px solid #ddd; }
th, td { padding: 10px; text-align: left; }
th { background: #f1f1f1; }
a { text-decoration: none; color: #dc3545; margin-left: 10px; }
a:hover { text-decoration: underline; }
img { border-radius: 5px; }
.message { padding: 12px 20px; background: #28a745; color: #fff; border-radius: 5px; margin-bottom: 20px; animation: fadeOut 3s forwards; }
@keyframes fadeOut { 0% { opacity:1; } 80% { opacity:1; } 100% { opacity:0; display:none; } }
</style>
</head>
<body>

<div class="container">
<h1>Manage Our Story</h1>

<!-- SECTION HEADER FORM -->
<?php if($section_success): ?>
<div class="message"><?= $section_success ?></div>
<?php endif; ?>
<h2>Our Story Section Header</h2>
<form method="post">
    <label>Section Title</label>
    <input type="text" name="section_title" value="<?= htmlspecialchars($section['section_title'] ?? '') ?>" required>
    <label>Section Subtitle</label>
    <input type="text" name="section_subtitle" value="<?= htmlspecialchars($section['section_subtitle'] ?? '') ?>" required>
    <label>Section Paragraph</label>
    <textarea name="section_paragraph" required><?= htmlspecialchars($section['section_paragraph'] ?? '') ?></textarea>
    <button type="submit" name="save_section">Save Section</button>
</form>

<hr>

<!-- ADD OR EDIT CARD FORM -->
<?php if($success_message): ?>
<div class="message"><?= $success_message ?></div>
<?php endif; ?>

<?php if($edit_card): ?>
<div id="edit-card-form" style="<?= $edit_card ? 'display:block;' : 'display:none;' ?>">
<h2>Edit Card</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $edit_card['id'] ?>">
    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($edit_card['title']) ?>" required>
    <label>Description</label>
    <textarea name="description" required><?= htmlspecialchars($edit_card['description']) ?></textarea>
    <label>Image</label>
    <?php if(!empty($edit_card['image_path'])): ?>
        <img src="../../assets/about/<?= $edit_card['image_path'] ?>" width="100"><br>
    <?php endif; ?>
    <input type="file" name="image">
    <button type="submit" name="edit_card">Update Card</button>
    <a href="javascript:void(0);" id="cancel-edit" style="margin-top:10px; color:#007bff;">Cancel Edit</a>
</form>
</div>

<!-- ADD CARD FORM -->
<div id="add-card-form" style="<?= $edit_card ? 'display:none;' : 'display:block;' ?>">
<h2>Add New Card</h2>
<form method="post" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" required>
    <label>Description</label>
    <textarea name="description" required></textarea>
    <label>Image</label>
    <input type="file" name="image" required>
    <button type="submit" name="add_card">Add Card</button>
</form>
</div>

<?php else: ?>
<div id="add-card-form" style="<?= $edit_card ? 'display:none;' : 'display:block;' ?>">
<h2>Add New Card</h2>
<form method="post" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" required>
    <label>Description</label>
    <textarea name="description" required></textarea>
    <label>Image</label>
    <input type="file" name="image" required>
    <button type="submit" name="add_card">Add Card</button>
</form>
</div>
<?php endif; ?>

<!-- CARDS LIST -->
<h2>All Cards</h2>
<table>
<tr><th>#</th><th>Image</th><th>Title</th><th>Description</th><th>Actions</th></tr>
<?php $counter = 1; while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $counter++ ?></td>
<td><img src="../../assets/about/<?= $row['image_path'] ?>" width="80"></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['description']) ?></td>
<td>
    <a href="?edit=<?= $row['id'] ?>">Edit</a> |
    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this card?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
<script>
document.getElementById('cancel-edit')?.addEventListener('click', function() {
    // Hide edit form
    document.getElementById('edit-card-form').style.display = 'none';
    // Show add form
    document.getElementById('add-card-form').style.display = 'block';
});
</script>
</body>
</html>
