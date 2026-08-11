<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Make sure ID is provided
if(!isset($_GET['id'])) die("Section ID missing.");
$id = intval($_GET['id']);

// Fetch section
$section_query = $conn->prepare("SELECT * FROM news_sections WHERE id=?");
$section_query->bind_param("i", $id);
$section_query->execute();
$result = $section_query->get_result();
$section = $result->fetch_assoc();

if(!$section) die("Section not found.");

$news_id = $section['news_id'];
$success_msg = "";

// Handle form submission
if(isset($_POST['edit_section'])) {
    $heading = $_POST['heading'];
    $content = $_POST['content'];
    $order = intval($_POST['section_order']);

    $stmt = $conn->prepare("UPDATE news_sections SET heading=?, content=?, section_order=? WHERE id=?");
    $stmt->bind_param("ssii", $heading, $content, $order, $id);
    $stmt->execute();

    $success_msg = "Section updated successfully!";
    
    // Refresh section data after update
    $section_query->execute();
    $section = $section_query->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Section</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f7f9fc;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 700px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }
    input[type="text"], input[type="number"], textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        box-sizing: border-box;
        transition: border 0.3s;
    }
    input[type="text"]:focus, input[type="number"]:focus, textarea:focus {
        border-color: #007BFF;
        outline: none;
    }
    button {
        background-color: #007BFF;
        color: #fff;
        border: none;
        padding: 14px 25px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Edit Section</h1>

    <?php if($success_msg): ?>
        <div class="success"><?= $success_msg ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="heading">Heading:</label>
        <input type="text" id="heading" name="heading" value="<?= htmlspecialchars($section['heading']) ?>" required>

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($section['content']) ?></textarea>

        <label for="section_order">Order:</label>
        <input type="number" id="section_order" name="section_order" value="<?= $section['section_order'] ?>" required>

        <button type="submit" name="edit_section">Update Section</button>
    </form>
</div>
</body>
</html>
