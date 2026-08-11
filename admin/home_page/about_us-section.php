<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php'; // DB connection

$success_message = "";

// ================== ABOUT SECTION ==================
$about_section = [];
$result = $conn->query("SELECT * FROM about_section LIMIT 1");
if($row = $result->fetch_assoc()){
    $about_section = $row;
}

if(isset($_POST['save_about'])){
    $subheading = $_POST['subheading'];
    $heading = $_POST['heading'];
    $description = $_POST['description'];
    $intro_text = $_POST['intro_text'];

    if(isset($about_section['id'])){
        $id = $about_section['id'];
        $stmt = $conn->prepare("UPDATE about_section SET subheading=?, heading=?, description=?, intro_text=? WHERE id=?");
        $stmt->bind_param("ssssi", $subheading, $heading, $description, $intro_text, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO about_section (subheading, heading, description, intro_text) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $subheading, $heading, $description, $intro_text);
        $stmt->execute();
    }
    $success_message = "About Section saved successfully!";
}

// ================== LEFT CARD ==================
if(isset($_POST['save_left'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $text = $_POST['text'];
    $overlay_text = $_POST['overlay_text'];

    if(isset($_FILES['image1']) && $_FILES['image1']['name'] != ""){
        $image_name1 = time().'_'.$_FILES['image1']['name'];
        move_uploaded_file($_FILES['image1']['tmp_name'], '../../assets/home/'.$image_name1);
        $conn->query("UPDATE left_cards SET image1='$image_name1' WHERE id=1");
    }

    if(isset($_FILES['image2']) && $_FILES['image2']['name'] != ""){
        $image_name2 = time().'_'.$_FILES['image2']['name'];
        move_uploaded_file($_FILES['image2']['tmp_name'], '../../assets/home/'.$image_name2);
        $conn->query("UPDATE left_cards SET image2='$image_name2' WHERE id=1");
    }

    $stmt = $conn->prepare("UPDATE left_cards SET title=?, description=?, text=?, overlay_text=? WHERE id=1");
    $stmt->bind_param("ssss", $title, $description, $text, $overlay_text);
    $stmt->execute();

    $success_message = "Left Card updated successfully!";
}
$left_card = $conn->query("SELECT * FROM left_cards WHERE id=1")->fetch_assoc();

// ================== RIGHT CARDS ==================
if(isset($_POST['add_card'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO right_cards (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);
    $stmt->execute();
    $success_message = "Right Card added successfully!";
}
if(isset($_POST['edit_card'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $stmt = $conn->prepare("UPDATE right_cards SET title=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $description, $id);
    $stmt->execute();
    $success_message = "Right Card updated successfully!";
}
if(isset($_GET['delete_card'])){
    $id = $_GET['delete_card'];
    $conn->query("DELETE FROM right_cards WHERE id=$id");
    $success_message = "Right Card deleted successfully!";
}
$right_cards = $conn->query("SELECT * FROM right_cards");

// ================== RIGHT POINTS ==================
if(isset($_POST['add_point'])){
    $text = $_POST['text'];
    $stmt = $conn->prepare("INSERT INTO right_points (text) VALUES (?)");
    $stmt->bind_param("s", $text);
    $stmt->execute();
    $success_message = "Right Point added successfully!";
}
if(isset($_POST['edit_point'])){
    $id = $_POST['id'];
    $text = $_POST['text'];
    $stmt = $conn->prepare("UPDATE right_points SET text=? WHERE id=?");
    $stmt->bind_param("si", $text, $id);
    $stmt->execute();
    $success_message = "Right Point updated successfully!";
}
if(isset($_GET['delete_point'])){
    $id = $_GET['delete_point'];
    $conn->query("DELETE FROM right_points WHERE id=$id");
    $success_message = "Right Point deleted successfully!";
}
$right_points = $conn->query("SELECT * FROM right_points");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - About Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; background: #f4f6f8; }
        .container { max-width: 1000px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        h1 { text-align: center; margin-bottom: 30px; }
        h2 { margin-top: 40px; color: #333; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0 15px; border-radius: 5px; border: 1px solid #ccc; font-size: 15px; }
        button { padding: 10px 20px; background: #007BFF; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: 500; }
        button:hover { background: #0056b3; }
        label { font-weight: 500; margin-bottom: 5px; display: block; }
        .message { padding: 12px 20px; background: #28a745; color: #fff; border-radius: 5px; margin-bottom: 20px; animation: fadeOut 3s forwards; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; padding: 10px; }
        th { background: #f1f1f1; }
        a { text-decoration: none; color: #dc3545; margin-left: 10px; }
        a:hover { text-decoration: underline; }
        img { margin-top: 10px; border-radius: 5px; }
        .preview { text-align: center; margin-bottom: 30px; }
        .preview img { max-width: 100%; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        @keyframes fadeOut { 0% { opacity:1; } 80% { opacity:1; } 100% { opacity:0; display:none; } }
    </style>
</head>
<body>

<div class="container">

    <!-- Website Screenshot Preview -->
    <div class="preview">
        <img src="../../images/home_description.jpg" alt="About Section Preview">
    </div>

   <h1>About Us section</h1>
<p class="subtitle">This section manages the About Us content displayed on your homepage, including headings, descriptions, images, and highlights that represent your company’s identity and values.</p>
    <?php if($success_message): ?>
        <div class="message"><?= $success_message ?></div>
    <?php endif; ?>

    <!-- ABOUT SECTION -->
    <h2>About Section</h2>
    <form method="POST">
        <label>Subheading</label>
        <input type="text" name="subheading" value="<?= htmlspecialchars($about_section['subheading'] ?? '') ?>" required>

        <label>Heading</label>
        <input type="text" name="heading" value="<?= htmlspecialchars($about_section['heading'] ?? '') ?>" required>

        <label>Description</label>
        <textarea name="description" rows="5" required><?= htmlspecialchars($about_section['description'] ?? '') ?></textarea>

        <label>Intro Text</label>
        <textarea name="intro_text" rows="3"><?= htmlspecialchars($about_section['intro_text'] ?? '') ?></textarea>

        <button type="submit" name="save_about">Save Changes</button>
    </form>

    <!-- LEFT CARD -->
    <h2>Edit Left Card</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($left_card['title']) ?>">

        <label>Description</label>
        <input type="text" name="description" value="<?= htmlspecialchars($left_card['description']) ?>">

        <label>Overlay Text</label>
        <input type="text" name="overlay_text" value="<?= htmlspecialchars($left_card['overlay_text'] ?? '') ?>">

        <label>Image 1</label><br>
        <?php if($left_card['image1']): ?>
            <img src="../../assets/home/<?= $left_card['image1'] ?>" width="100"><br>
        <?php endif; ?>
        <input type="file" name="image1"><br><br>

        <label>Image 2</label><br>
        <?php if($left_card['image2']): ?>
            <img src="../../assets/home/<?= $left_card['image2'] ?>" width="100"><br>
        <?php endif; ?>
        <input type="file" name="image2"><br><br>

        <button name="save_left">Save Changes</button>
    </form>

    <!-- RIGHT CARDS -->
    <h2>Right Cards</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Title e.g. 50+" required>
        <input type="text" name="description" placeholder="Description e.g. Projects Completed" required>
        <button name="add_card">Add</button>
    </form>

    <table>
        <tr><th>Title</th><th>Description</th><th>Action</th></tr>
        <?php while($row = $right_cards->fetch_assoc()): ?>
            <tr>
            <form method="POST">
                <td><input type="text" name="title" value="<?= $row['title'] ?>"></td>
                <td><input type="text" name="description" value="<?= $row['description'] ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button name="edit_card">Save</button>
                    <a href="?delete_card=<?= $row['id'] ?>" onclick="return confirm('Delete this card?')">Delete</a>
                </td>
            </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- RIGHT POINTS -->
    <h2>Right Points</h2>
    <form method="POST">
        <input type="text" name="text" placeholder="Add new point">
        <button name="add_point">Add</button>
    </form>

    <table>
        <tr><th>Text</th><th>Action</th></tr>
        <?php while($row = $right_points->fetch_assoc()): ?>
            <tr>
            <form method="POST">
                <td><input type="text" name="text" value="<?= $row['text'] ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button name="edit_point">Save</button>
                    <a href="?delete_point=<?= $row['id'] ?>" onclick="return confirm('Delete this point?')">Delete</a>
                </td>
            </form>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
