<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

// Get existing card and detail info
$sql = "SELECT sc.*, sd.title AS detail_title, sd.description, sd.image AS detail_image, sd.id AS detail_id
        FROM services_cards sc
        JOIN services_details sd ON sc.detail_page_id = sd.id
        WHERE sc.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $short_desc = $_POST['short_desc'];
    $detail_title = $_POST['detail_title'];
    $description = $_POST['description'];

    if($_FILES['card_image']['name']){
        $card_image = time() . '_' . $_FILES['card_image']['name'];
        move_uploaded_file($_FILES['card_image']['tmp_name'], '../../assets/service_page_uploads/' . $card_image);
    } else {
        $card_image = $result['image'];
    }

    if($_FILES['detail_image']['name']){
        $detail_image = time() . '_' . $_FILES['detail_image']['name'];
        move_uploaded_file($_FILES['detail_image']['tmp_name'],'../../assets/service_page_uploads/'. $detail_image);
    } else {
        $detail_image = $result['detail_image'];
    }

    $stmt2 = $conn->prepare("UPDATE services_details SET title=?, description=?, image=? WHERE id=?");
    $stmt2->bind_param("sssi", $detail_title, $description, $detail_image, $result['detail_id']);
    $stmt2->execute();

    $stmt3 = $conn->prepare("UPDATE services_cards SET title=?, short_desc=?, image=? WHERE id=?");
    $stmt3->bind_param("sssi", $title, $short_desc, $card_image, $id);
    $stmt3->execute();

    header("Location: services_list.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Service</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h1, h3 {
    color: #333;
}
form label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold;
    color: #555;
}
input[type="text"], textarea, input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    box-sizing: border-box;
}
textarea {
    resize: vertical;
    height: 100px;
}
img {
    border-radius: 5px;
    margin-bottom: 15px;
    display: block;
}
button {
    padding: 10px 20px;
    background: #007bff;
    border: none;
    border-radius: 5px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background: #0056b3;
}
.section {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #ddd;
}
.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #007bff;
    text-decoration: none;
}
.back-link:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="container">
    <a href="services_list.php" class="back-link">← Back to Services List</a>
    <h1>Edit Service</h1>

    <form method="post" enctype="multipart/form-data">
        <div class="section">
            <h3>Card Info</h3>
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($result['title']) ?>" required>

            <label>Short Description</label>
            <textarea name="short_desc" required><?= htmlspecialchars($result['short_desc']) ?></textarea>

            <label>Card Image</label>
            <input type="file" name="card_image">
            <?php if(!empty($result['image'])): ?>
                <img src="../../assets/service_page_uploads/<?= $result['image'] ?>" width="150" alt="Card Image">
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>Detail Page Info</h3>
            <label>Detail Title</label>
            <input type="text" name="detail_title" value="<?= htmlspecialchars($result['detail_title']) ?>" required>

            <label>Description</label>
            <textarea name="description" required><?= htmlspecialchars($result['description']) ?></textarea>

            <label>Detail Image</label>
            <input type="file" name="detail_image">
            <?php if(!empty($result['detail_image'])): ?>
                <img src="../../assets/service_page_uploads/<?= $result['detail_image'] ?>" width="150" alt="Detail Image">
            <?php endif; ?>
        </div>

        <button type="submit" name="submit">Update Service</button>
    </form>
</div>
</body>
</html>
