<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = 1; // only one section
$section = $conn->query("SELECT * FROM middle_east_section WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $header = $_POST['header'];
    $content = $_POST['content'];
    $image_filename = $section['image_path']; // store only filename

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../../assets/teams/"; 
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image_filename = $fileName; 
        }
    }

    $stmt = $conn->prepare("UPDATE middle_east_section SET header=?, content=?, image_path=? WHERE id=?");
    $stmt->bind_param("sssi", $header, $content, $image_filename, $id);
    $stmt->execute();

    header("Location: middle_east.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Middle East Section</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 700px;
        margin: 40px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    h1 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 30px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"], textarea {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
    }

    input[type="file"] {
        margin-bottom: 15px;
    }

    img.preview {
        display: block;
        max-width: 100%;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    button {
        background-color: #2563eb;
        color: #fff;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background-color: #1d4ed8;
    }

    @media (max-width: 500px) {
        .container {
            padding: 20px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1><i class="fa-solid fa-pen-to-square"></i> Edit Middle East Section</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="header">Header</label>
        <input type="text" id="header" name="header" value="<?= htmlspecialchars($section['header']) ?>" required>

        <label for="content">Content</label>
        <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($section['content']) ?></textarea>

        <label for="image">Section Image</label>
        <input type="file" id="image" name="image">
        <?php if ($section['image_path']) {
            $imagePath = "../../assets/teams/" . $section['image_path'];
        ?>
            <img src="<?= $imagePath ?>" alt="Current Image" class="preview">
        <?php } ?>

        <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>
</div>

</body>
</html>
