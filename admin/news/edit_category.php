<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM news_categories WHERE id = $id");
$cat = $result->fetch_assoc();

if(isset($_POST['submit'])){
    $name = trim($_POST['name']);
    $stmt = $conn->prepare("UPDATE news_categories SET name=? WHERE id=?");
    $stmt->bind_param("si", $name,$id);
    if($stmt->execute()){
        header("Location: manage_categories.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Category</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7f8;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 500px;
        margin: 60px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-size: 26px;
        font-weight: 600;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
    }

    input[type="text"] {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
        transition: all 0.3s;
    }

    input[type="text"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.5);
        outline: none;
    }

    button {
        padding: 12px 20px;
        border: none;
        background: #007bff;
        color: white;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    button:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }

    .back-link {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: #007bff;
        transition: all 0.3s;
    }

    .back-link:hover {
        text-decoration: underline;
        color: #0056b3;
    }

    @media(max-width: 600px){
        .container {
            margin: 30px 20px;
            padding: 25px;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h1>Edit Category</h1>
    <form method="POST">
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
        <button type="submit" name="submit">Update</button>
    </form>
    <a href="manage_categories.php" class="back-link">&larr; Back to Categories</a>
</div>
</body>
</html>
