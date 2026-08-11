<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['news_id'])) die("News ID missing.");
$news_id = intval($_GET['news_id']);

if(isset($_POST['add_section'])) {
    $heading = $_POST['heading'];
    $content = $_POST['content'];
    $order = intval($_POST['section_order']);

    $stmt = $conn->prepare("INSERT INTO news_sections (news_id, heading, content, section_order) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $news_id, $heading, $content, $order);
    $stmt->execute();

    header("Location: admin-details.php?id=$news_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add News Section</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 700px;
        margin: 50px auto;
        padding: 20px;
    }
    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }
    .form-card {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    label {
        display: block;
        margin-top: 15px;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }
    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 16px;
        transition: border 0.3s;
    }
    input[type="text"]:focus,
    input[type="number"]:focus,
    textarea:focus {
        border-color: #007bff;
        outline: none;
    }
    textarea {
        resize: vertical;
    }
    button {
        margin-top: 20px;
        padding: 12px 25px;
        background: #007bff;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }
    button:hover {
        background: #0056b3;
    }
    @media(max-width: 600px){
        .container {
            padding: 15px;
        }
        button {
            width: 100%;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h1>Add News Section</h1>
    <div class="form-card">
        <form method="post">
            <label>Heading:</label>
            <input type="text" name="heading" placeholder="Enter section heading" required>

            <label>Content:</label>
            <textarea name="content" rows="6" placeholder="Enter section content" required></textarea>

            <label>Order:</label>
            <input type="number" name="section_order" value="1" min="1" required>

            <button type="submit" name="add_section">Add Section</button>
        </form>
    </div>
</div>
</body>
</html>
