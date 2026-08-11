<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
// Fetch existing data
$result = $conn->query("SELECT * FROM news_intro WHERE id=1");
$row = $result->fetch_assoc();

// Handle form submission
if(isset($_POST['update'])){
    $line1 = $_POST['header_line1'];
    $line2 = $_POST['header_line2'];
    $line3 = $_POST['header_line3'];
    $line4 = $_POST['header_line4'];
    $paragraph = $_POST['paragraph'];

    // Handle background image upload
    $bg_image = $row['background_image']; // default
    if(isset($_FILES['background_image']) && $_FILES['background_image']['name'] != ""){
        $ext = pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION);
        $new_name = 'news_bg_' . time() . '.' . $ext;
        $upload_path = '../../assets/news/' . $new_name;
        if(move_uploaded_file($_FILES['background_image']['tmp_name'], $upload_path)){
            $bg_image = $new_name;
        }
    }

    $stmt = $conn->prepare("UPDATE news_intro SET header_line1=?, header_line2=?, header_line3=?, header_line4=?, paragraph=?, background_image=? WHERE id=1");
    $stmt->bind_param("ssssss", $line1, $line2, $line3, $line4, $paragraph, $bg_image);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color:green;'>Content updated successfully!</p>";

    // Refresh $row to reflect changes
    $result = $conn->query("SELECT * FROM news_intro WHERE id=1");
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit News Intro</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 40px;
    color: #333;
}
h1 {
    text-align: center;
    color: #1E3A8A;
    margin-bottom: 30px;
}
form {
    background: #fff;
    max-width: 700px;
    margin: auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #1E3A8A;
}
input[type="text"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
}
textarea {
    resize: vertical;
    min-height: 100px;
}
img.preview {
    max-width: 300px;
    border-radius: 6px;
    border: 1px solid #ddd;
    margin-top: 10px;
}
button {
    padding: 12px 25px;
    background: #1E90FF;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    font-size: 1rem;
}
button:hover {
    background: #0d6efd;
}
</style>
</head>
<body>

<h1><i class="fa fa-newspaper"></i> Edit News Intro</h1>

<form method="post" enctype="multipart/form-data">
    <label>Header Line 1:</label>
    <input type="text" name="header_line1" value="<?= htmlspecialchars($row['header_line1']) ?>">

    <label>Header Line 2:</label>
    <input type="text" name="header_line2" value="<?= htmlspecialchars($row['header_line2']) ?>">

    <label>Header Line 3:</label>
    <input type="text" name="header_line3" value="<?= htmlspecialchars($row['header_line3']) ?>">

    <label>Header Line 4:</label>
    <input type="text" name="header_line4" value="<?= htmlspecialchars($row['header_line4']) ?>">

    <label>Paragraph:</label>
    <textarea name="paragraph"><?= htmlspecialchars($row['paragraph']) ?></textarea>

    <label>Background Image:</label>
    <input type="file" name="background_image" accept="image/*">
    <small>Current Image:</small><br>
    <img src="../../assets/news/<?= htmlspecialchars($row['background_image']) ?>" class="preview"><br><br>

    <button type="submit" name="update"><i class="fa fa-save"></i> Update Content</button>
</form>

</body>
</html>
