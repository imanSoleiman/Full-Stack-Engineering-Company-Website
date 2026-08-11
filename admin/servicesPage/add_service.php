<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $short_desc = $_POST['short_desc'];
    $detail_title = $_POST['detail_title'];
    $description = $_POST['description'];
    $section_title = $_POST['section_title'];
    $list_items = $_POST['list_items'];

    $uploadDir = '../../assets/service_page_uploads/';
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0755, true);
    }

    $allowed_extensions = ['jpg','jpeg','webp','png'];

    function validateImage($file_name, $allowed_extensions){
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        return in_array($ext, $allowed_extensions);
    }

    $card_image = $_FILES['card_image']['name'];
    $card_tmp = $_FILES['card_image']['tmp_name'];
    if(!validateImage($card_image, $allowed_extensions)){
        die("Card image must be JPG, JPEG, PNG, or WEBP.");
    }
    move_uploaded_file($card_tmp, $uploadDir . $card_image);

    $detail_image = $_FILES['detail_image']['name'];
    $detail_tmp = $_FILES['detail_image']['tmp_name'];
    if(!validateImage($detail_image, $allowed_extensions)){
        die("Detail image must be JPG, JPEG, PNG, or WEBP.");
    }
    move_uploaded_file($detail_tmp, $uploadDir . $detail_image);

    $stmt = $conn->prepare("INSERT INTO services_details (title, description, image, section_title, list_items) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $detail_title, $description, $detail_image, $section_title, $list_items);
    $stmt->execute();
    $detail_id = $stmt->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO services_cards (title, short_desc, image, detail_page_id) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("sssi", $title, $short_desc, $card_image, $detail_id);
    $stmt2->execute();

    header("Location: services_list.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add New Service</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0 20px 50px;
    color: #333;
}
h1 {
    text-align: center;
    color: #007bff;
    margin: 40px 0 20px;
}
form {
    max-width: 800px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
form h3 {
    color: #007bff;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 5px;
}
form label {
    font-weight: 600;
    display: block;
    margin-top: 15px;
}
form input[type="text"],
form input[type="file"],
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
form input[type="submit"] {
    margin-top: 25px;
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
form input[type="submit"]:hover {
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

<a href="services_list.php" class="back-link"><i class="fa fa-arrow-left"></i> Back to Services</a>
<h1>Add New Service</h1>

<form method="post" enctype="multipart/form-data">
    <h3>Card Info</h3>
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Short Description</label>
    <textarea name="short_desc" required></textarea>

    <label>Card Image</label>
    <input type="file" name="card_image" accept="image/*" required>

    <h3>Detail Page Info</h3>
    <label>Detail Title</label>
    <input type="text" name="detail_title" required>

    <label>Description</label>
    <textarea name="description" required></textarea>

    <label>Detail Image</label>
    <input type="file" name="detail_image" accept="image/*" required>

    <label>Section Title</label>
    <input type="text" name="section_title" required>

    <label>List Items (separate items with | )</label>
    <textarea name="list_items" placeholder="Item1|Item2|Item3" required></textarea>

    <input type="submit" name="submit" value="Add Service">
</form>

</body>
</html>
