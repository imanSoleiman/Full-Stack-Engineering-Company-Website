<?php 
include "../../config.php"; 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Get slide ID safely
$slide_id = $_GET['slide_id'] ?? null;
if (!$slide_id) {
    die("Slide ID is missing!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = $_POST['heading'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO professional_lists (slide_id, heading, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $slide_id, $heading, $description);
    $stmt->execute();

    header("Location: edit_slide.php?id=$slide_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add List Item</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 20px;
    color: #333;
}
h1 {
    text-align: center;
    color: #007bff;
    margin-bottom: 30px;
}
form {
    max-width: 600px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
form label {
    font-weight: 600;
    display: block;
    margin-top: 15px;
}
form input[type="text"],
form textarea {
    width: 100%;
    padding: 10px 12px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
    box-sizing: border-box;
}
form textarea {
    resize: vertical;
    min-height: 80px;
}
form button {
    margin-top: 20px;
    padding: 12px 25px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}
form button:hover {
    opacity: 0.9;
}
.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #007bff;
    font-weight: 600;
    text-decoration: none;
}
.back-link i {
    margin-right: 5px;
}
@media(max-width:600px){
    form {
        padding: 20px;
    }
}
</style>
</head>
<body>

<a href="edit_slide.php?id=<?= $slide_id ?>" class="back-link"><i class="fa fa-arrow-left"></i> Back to Slide</a>
<h1>Add List Item</h1>

<form method="post">
    <label>Heading</label>
    <input type="text" name="heading" placeholder="Enter heading" required>

    <label>Description</label>
    <textarea name="description" placeholder="Enter description" required></textarea>

    <button type="submit"><i class="fa fa-plus"></i> Add Item</button>
</form>

</body>
</html>
