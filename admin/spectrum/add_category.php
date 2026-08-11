<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO spectrum_categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Spectrum Category</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 30px;
    color: #333;
}
h1 {
    text-align: center;
    color: #1E3A8A;
    margin-bottom: 30px;
}
form {
    background: #fff;
    max-width: 500px;
    margin: auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}
input[type="text"] {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
    font-size: 1rem;
}
button {
    display: inline-block;
    padding: 12px 20px;
    background: #1E90FF;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 1rem;
    transition: 0.3s;
}
button:hover {
    background: #0d6efd;
}
.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    color: #1E90FF;
    text-decoration: none;
    font-weight: 600;
}
.back-btn:hover {
    text-decoration: underline;
}
@media(max-width: 600px){
    form { padding: 20px; }
}
</style>
</head>
<body>

<h1><i class="fa fa-plus-circle"></i> Add Spectrum Category</h1>
<a href="index.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back to Categories</a>

<form method="POST">
    <label>Category Name:</label>
    <input type="text" name="name" placeholder="Enter category name" required>
    <button type="submit" name="submit"><i class="fa fa-save"></i> Add Category</button>
</form>

</body>
</html>
