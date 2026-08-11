<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php'); // Database connection ($conn)

$message = "";

// =======================
// Handle main header update
// =======================
if(isset($_POST['update_main'])){
    $header = $_POST['main_header'];
    $desc = $_POST['description'];

    $check = $conn->query("SELECT * FROM division_page LIMIT 1");
    if($check->num_rows > 0){
        $stmt = $conn->prepare("UPDATE division_page SET main_header=?, description=? WHERE id=1");
        $stmt->bind_param("ss", $header, $desc);
    } else {
        $stmt = $conn->prepare("INSERT INTO division_page (main_header, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $header, $desc);
    }
    $stmt->execute();
    $stmt->close();
    $message = "✅ Main header updated!";
}

// =======================
// Handle adding a new card
// =======================
if(isset($_POST['add_card'])){
    $title = $_POST['title_front'];
    $header_back = $_POST['header_back'];
    $desc_back = $_POST['description_back'];

    $img_name = "";
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $upload_dir = "../../assets/devision/";
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $img_name = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $img_name);
    }

    $stmt = $conn->prepare("INSERT INTO division_cards (title_front, image_path, header_back, description_back) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $img_name, $header_back, $desc_back);
    $stmt->execute();
    $stmt->close();
    $message = "✅ Card added!";
}

// =======================
// Handle deleting a card
// =======================
if(isset($_GET['delete_card'])){
    $id = $_GET['delete_card'];

    $img = $conn->query("SELECT image_path FROM division_cards WHERE id=$id")->fetch_assoc();
    if(!empty($img['image_path'])){
        $img_file = "../../assets/devision/" . $img['image_path'];
        if(file_exists($img_file)) unlink($img_file);
    }

    $stmt = $conn->prepare("DELETE FROM division_cards WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $message = "❌ Card deleted!";
}

// =======================
// Handle editing a card
// =======================
if(isset($_POST['edit_card'])){
    $id = $_POST['card_id'];
    $title = $_POST['title_front'];
    $header_back = $_POST['header_back'];
    $desc_back = $_POST['description_back'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $upload_dir = "../../assets/devision/";
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $img_name = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $img_name);

        $old_img = $conn->query("SELECT image_path FROM division_cards WHERE id=$id")->fetch_assoc();
        if(!empty($old_img['image_path'])){
            $old_file = "../../assets/devision/" . $old_img['image_path'];
            if(file_exists($old_file)) unlink($old_file);
        }

        $stmt = $conn->prepare("UPDATE division_cards SET title_front=?, image_path=?, header_back=?, description_back=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $img_name, $header_back, $desc_back, $id);
    } else {
        $stmt = $conn->prepare("UPDATE division_cards SET title_front=?, header_back=?, description_back=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $header_back, $desc_back, $id);
    }

    $stmt->execute();
    $stmt->close();
    $message = "✅ Card updated!";
}

// =======================
// Fetch main header
// =======================
$main_result = $conn->query("SELECT * FROM division_page LIMIT 1");
if($main_result && $main_result->num_rows > 0){
    $main = $main_result->fetch_assoc();
    $header_text = $main['main_header'];
    $description_text = $main['description'];
} else {
    $header_text = "";
    $description_text = "";
}

// =======================
// Fetch all cards
// =======================
$cards_result = $conn->query("SELECT * FROM division_cards");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Our Division</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 20px;
}
.container {
    max-width: 1000px;
    margin: auto;
}
h1, h2 {
    text-align: center;
    color: #333;
}
form {
    background-color: #fff;
    padding: 20px 25px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}
input[type=text], textarea, select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}
input[type=text]:focus, textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.2);
}
button {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 20px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}
button:hover {
    background-color: #0056b3;
}
.card-preview {
    display: flex;
    align-items: center;
    background-color: #fff;
    padding: 15px 20px;
    margin-bottom: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    gap: 15px;
}
.card-preview img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.card-info {
    flex-grow: 1;
}
.card-info strong {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
}
.card-info p {
    margin: 2px 0;
    color: #555;
}
.card-actions {
    text-align: right;
}
.card-actions a {
    color: #ff4d4f;
    font-weight: bold;
    text-decoration: none;
}
.card-actions a:hover {
    text-decoration: underline;
}
.edit-form {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    border: 1px dashed #aaa;
    margin-top: 10px;
}
.message {
    padding: 12px 15px;
    background-color: #e6ffed;
    border: 1px solid #2a7a2a;
    color: #2a7a2a;
    font-weight: bold;
    border-radius: 8px;
    margin-bottom: 20px;
}
</style>
</head>
<body>
<div class="container">
<h1>Admin Panel - Our Division</h1>

<?php if($message): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<!-- Edit Main Header -->
<h2>Edit Page Header</h2>
<form method="post">
    <label>Main Header</label>
    <input type="text" name="main_header" value="<?= htmlspecialchars($header_text) ?>" placeholder="Header" required>
    
    <label>Description</label>
    <textarea name="description" placeholder="Description" required><?= htmlspecialchars($description_text) ?></textarea>
    
    <button type="submit" name="update_main">Update Header</button>
</form>

<!-- Add New Card -->
<h2>Add New Card</h2>
<form method="post" enctype="multipart/form-data">
    <label>Front Title</label>
    <input type="text" name="title_front" placeholder="Front Title" required>

    <label>Front Image</label>
    <input type="file" name="image">

    <label>Back Header</label>
    <input type="text" name="header_back" placeholder="Back Header" required>

    <label>Back Description</label>
    <textarea name="description_back" placeholder="Back Description" required></textarea>

    <button type="submit" name="add_card">Add Card</button>
</form>

<!-- Existing Cards -->
<h2>Existing Cards</h2>
<?php if($cards_result && $cards_result->num_rows > 0): ?>
    <?php while($card = $cards_result->fetch_assoc()): ?>
    <div class="card-preview">
        <?php if(!empty($card['image_path'])): ?>
            <img src="../../assets/devision/<?= htmlspecialchars($card['image_path']) ?>" alt="<?= htmlspecialchars($card['title_front']) ?>">
        <?php else: ?>
            <div style="width:100px;height:100px;background:#ccc;display:flex;align-items:center;justify-content:center;">No Image</div>
        <?php endif; ?>
        <div class="card-info">
            <strong><?= htmlspecialchars($card['title_front']) ?></strong>
            <p>Back Header: <?= htmlspecialchars($card['header_back']) ?></p>
            <p>Description: <?= htmlspecialchars($card['description_back']) ?></p>
        </div>
        <div class="card-actions">
            <a href="?delete_card=<?= $card['id'] ?>" onclick="return confirm('Delete this card?')">Delete</a>
        </div>
    </div>

    <!-- Edit form for this card -->
    <form class="edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
        <label>Front Title</label>
        <input type="text" name="title_front" value="<?= htmlspecialchars($card['title_front']) ?>" required>

        <label>Replace Image</label>
        <input type="file" name="image">

        <label>Back Header</label>
        <input type="text" name="header_back" value="<?= htmlspecialchars($card['header_back']) ?>" required>

        <label>Back Description</label>
        <textarea name="description_back" required><?= htmlspecialchars($card['description_back']) ?></textarea>

        <button type="submit" name="edit_card">Update Card</button>
    </form>
    <?php endwhile; ?>
<?php else: ?>
    <p>No cards yet. Add them using the form above.</p>
<?php endif; ?>

</div>
</body>
</html>
