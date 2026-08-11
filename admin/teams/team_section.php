<?php 
include "../../config.php"; 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch section (if exists)
$section = $conn->query("SELECT * FROM teams_section WHERE id=1")->fetch_assoc();

// Handle section insert/update
if(isset($_POST['save_section'])){
    $heading = $_POST['heading'];
    $paragraph = $_POST['paragraph'];

    $image_sql = "";
    if(isset($_FILES['section_image']) && $_FILES['section_image']['name'] != ""){
        $ext = pathinfo($_FILES['section_image']['name'], PATHINFO_EXTENSION);
        $image_name = time().'_'.rand(1000,9999).'.'.$ext;
        $upload_dir = realpath(__DIR__ . '/../../assets/teams/') . '/';
        move_uploaded_file($_FILES['section_image']['tmp_name'], $upload_dir . $image_name);
        $image_sql = ", image='$image_name'";
    }

    if($section){ 
        $conn->query("UPDATE teams_section SET heading='$heading', paragraph='$paragraph' $image_sql WHERE id=1");
    } else { 
        $conn->query("INSERT INTO teams_section (id, heading, paragraph, image) VALUES (1, '$heading', '$paragraph', '".($image_name ?? '')."')");
    }

    header("Location: team_section.php");
    exit;
}

// Add new focus card
if(isset($_POST['add_focus_card'])){
    $title = $_POST['title'];
    $region = $_POST['region'];
    $conn->query("INSERT INTO teams_focus_cards (title, region) VALUES ('$title', '$region')");
}

// Delete focus card
if(isset($_GET['delete_card'])){
    $id = intval($_GET['delete_card']);
    $conn->query("DELETE FROM teams_focus_cards WHERE id=$id");
}

// Fetch cards
$cards = $conn->query("SELECT * FROM teams_focus_cards ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Team Section</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 50px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    h1, h2 {
        color: #1e3a8a;
        margin-bottom: 20px;
    }

    h1 { text-align: center; }
    h2 { border-bottom: 2px solid #007bff; padding-bottom: 5px; margin-top: 40px; }

    form label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"], textarea, input[type="file"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    input[type="text"]:focus, textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.2);
    }

    textarea { resize: vertical; min-height: 100px; }

    button, .button {
        display: inline-block;
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    button[type="submit"] {
        background-color: #10b981;
        color: #fff;
        font-size: 16px;
        width: 100%;
        margin-top: 10px;
    }

    button[type="submit"]:hover { background-color: #059669; }

    .button {
        background: #007bff;
        color: white;
        text-decoration: none;
        font-size: 14px;
        margin-right: 5px;
    }

    .button.red { background: #dc3545; }

    .button:hover { opacity: 0.85; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    th, td {
        border-bottom: 1px solid #eee;
        text-align: left;
        padding: 12px 15px;
    }

    th { background: #f4f4f4; }

    tr:hover { background: #f9fafb; }

    img {
        border-radius: 8px;
        max-width: 150px;
        border: 1px solid #ddd;
        padding: 2px;
    }

    @media (max-width: 600px) {
        .container { padding: 20px; }
        table, th, td { font-size: 14px; }
    }
</style>
</head>
<body>
<div class="container">
    <h1><i class="fa-solid fa-users"></i> Manage Team Section</h1>

    <h2><?= $section ? 'Edit Section' : 'Add Section' ?></h2>
    <form method="post" enctype="multipart/form-data">
        <label>Heading:</label>
        <input type="text" name="heading" value="<?= htmlspecialchars($section['heading'] ?? '') ?>" required>

        <label>Paragraph:</label>
        <textarea name="paragraph" required><?= htmlspecialchars($section['paragraph'] ?? '') ?></textarea>

        <label>Section Image:</label>
        <input type="file" name="section_image">
        <?php if(!empty($section['image'])): ?>
            <div style="margin-top:10px;">
                <img src="../../assets/teams/<?= $section['image'] ?>" alt="Section Image">
            </div>
        <?php endif; ?>

        <button type="submit" name="save_section"><?= $section ? 'Update Section' : 'Add Section' ?></button>
    </form>

    <h2>Focus Cards</h2>
    <form method="post" style="margin-bottom: 20px;">
        <input type="text" name="title" placeholder="Card Title" required>
        <input type="text" name="region" placeholder="Region" required>
        <button type="submit" name="add_focus_card"><i class="fa-solid fa-plus"></i> Add Focus Card</button>
    </form>

    <table>
        <thead>
            <tr><th>ID</th><th>Title</th><th>Region</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php while($card = $cards->fetch_assoc()): ?>
            <tr>
                <td><?= $card['id'] ?></td>
                <td><?= htmlspecialchars($card['title']) ?></td>
                <td><?= htmlspecialchars($card['region']) ?></td>
                <td>
                    <a href="?delete_card=<?= $card['id'] ?>" class="button red" onclick="return confirm('Delete this card?')"><i class="fa-solid fa-trash"></i> Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
