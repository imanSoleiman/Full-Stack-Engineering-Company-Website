<?php
include '../../config.php'; 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch current data
$query = $conn->query("SELECT * FROM project_intro WHERE id=1");
$data = $query->fetch_assoc();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $heading1 = $_POST['heading1'];
    $heading2 = $_POST['heading2'];
    $heading3 = $_POST['heading3'];
    $heading4 = $_POST['heading4'];
    $paragraph = $_POST['paragraph'];
    $show_right_image = isset($_POST['show_right_image']) ? 1 : 0;

    // Right Image Upload
    $right_image = $data['right_image'];
    if (!empty($_FILES['right_image']['name'])) {
        $targetDir = "../../assets/projects_uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES['right_image']['name']);
        if (move_uploaded_file($_FILES['right_image']['tmp_name'], $targetDir . $fileName)) {
            $right_image = $fileName;
        } else {
            $message = "❌ Right image upload failed!";
        }
    }

    // Background Image Upload
    $background_image = $data['background_image'];
    if (!empty($_FILES['background_image']['name'])) {
        $targetDir = "../../assets/projects_uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES['background_image']['name']);
        if (move_uploaded_file($_FILES['background_image']['tmp_name'], $targetDir . $fileName)) {
            $background_image = $fileName;
        } else {
            $message = "❌ Background image upload failed!";
        }
    }

    // Update Database
    $stmt = $conn->prepare("UPDATE project_intro 
        SET heading1=?, heading2=?, heading3=?, heading4=?, paragraph=?, right_image=?, show_right_image=?, background_image=? 
        WHERE id=1");
    $stmt->bind_param("ssssssis", $heading1, $heading2, $heading3, $heading4, $paragraph, $right_image, $show_right_image, $background_image);
    $stmt->execute();
    $message = "✅ Section updated successfully!";
    header("Refresh:1"); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Project Intro</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
    }
    .container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #555;
    }
    input[type="text"], textarea, input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
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
    textarea { resize: vertical; }
    button {
        padding: 12px 25px;
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
        font-size: 16px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    .img-preview {
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .message {
        padding: 12px 15px;
        background-color: #e6ffed;
        border: 1px solid #2a7a2a;
        color: #2a7a2a;
        font-weight: bold;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
    @media (max-width:768px){
        .container{padding:20px;}
    }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Project Intro Section</h2>

    <?php if($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Heading 1:</label>
        <input type="text" name="heading1" value="<?= htmlspecialchars($data['heading1']) ?>" required>

        <label>Heading 2:</label>
        <input type="text" name="heading2" value="<?= htmlspecialchars($data['heading2']) ?>" required>

        <label>Heading 3:</label>
        <input type="text" name="heading3" value="<?= htmlspecialchars($data['heading3']) ?>" required>

        <label>Heading 4:</label>
        <input type="text" name="heading4" value="<?= htmlspecialchars($data['heading4']) ?>" required>

        <label>Paragraph:</label>
        <textarea name="paragraph" rows="4" required><?= htmlspecialchars($data['paragraph']) ?></textarea>

        <label>Right Image:</label>
        <input type="file" name="right_image">
        <?php if($data['right_image']): ?>
            <div class="img-preview">
                <img src="../../assets/projects_uploads/<?= htmlspecialchars($data['right_image']) ?>" width="150">
            </div>
        <?php endif; ?>

        <label>Show Right Image:</label>
        <input type="checkbox" name="show_right_image" <?= $data['show_right_image'] ? 'checked' : '' ?>><br><br>

        <label>Background Image:</label>
        <input type="file" name="background_image">
        <?php if($data['background_image']): ?>
            <div class="img-preview">
                <img src="../../assets/projects_uploads/<?= htmlspecialchars($data['background_image']) ?>" width="150">
            </div>
        <?php endif; ?>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
