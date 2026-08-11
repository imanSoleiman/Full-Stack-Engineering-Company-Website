<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';
$result = $conn->query("SELECT * FROM corporate_first_section WHERE id = 1");
$data = $result->fetch_assoc();

$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $section_header = $_POST['section_header'];
    $paragraph1 = $_POST['paragraph1'];
    $paragraph2 = $_POST['paragraph2'];
    $card1_title = $_POST['card1_title'];
    $card1_text = $_POST['card1_text'];
    $card2_title = $_POST['card2_title'];
    $card2_text = $_POST['card2_text'];

    $image_path = $data['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../assets/corporate/";
        $new_image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_image_name;

        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if (!empty($image_path) && file_exists($image_path)) unlink($image_path);
            $image_path = $target_file;
        }
    }

    $stmt = $conn->prepare("UPDATE corporate_first_section SET section_header=?, paragraph1=?, paragraph2=?, image_path=?, card1_title=?, card1_text=?, card2_title=?, card2_text=? WHERE id=1");
    $stmt->bind_param("ssssssss", $section_header, $paragraph1, $paragraph2, $image_path, $card1_title, $card1_text, $card2_title, $card2_text);
    $stmt->execute();

    $message = "✅ Section updated successfully!";
    $result = $conn->query("SELECT * FROM corporate_first_section WHERE id = 1");
    $data = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Corporate First Section</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }
    .message {
        text-align: center;
        color: green;
        font-weight: bold;
        margin-bottom: 20px;
    }
    form label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #555;
    }
    form input[type="text"], 
    form textarea, 
    form input[type="file"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }
    form textarea {
        resize: vertical;
    }
    img.preview {
        display: block;
        max-width: 200px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
    button {
        display: block;
        width: 100%;
        padding: 14px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    .card {
        background-color: #f9fafb;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Edit First Section</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Section Header:</label>
        <input type="text" name="section_header" value="<?= htmlspecialchars($data['section_header']) ?>" required>

        <label>Paragraph 1:</label>
        <textarea name="paragraph1" rows="4" required><?= htmlspecialchars($data['paragraph1']) ?></textarea>

        <label>Paragraph 2:</label>
        <textarea name="paragraph2" rows="4"><?= htmlspecialchars($data['paragraph2']) ?></textarea>

        <!-- Card 1 -->
        <div class="card">
            <label>Card 1 Title:</label>
            <input type="text" name="card1_title" value="<?= htmlspecialchars($data['card1_title']) ?>">

            <label>Card 1 Text:</label>
            <textarea name="card1_text" rows="3"><?= htmlspecialchars($data['card1_text']) ?></textarea>
        </div>

        <!-- Card 2 -->
        <div class="card">
            <label>Card 2 Title:</label>
            <input type="text" name="card2_title" value="<?= htmlspecialchars($data['card2_title']) ?>">

            <label>Card 2 Text:</label>
            <textarea name="card2_text" rows="3"><?= htmlspecialchars($data['card2_text']) ?></textarea>
        </div>

        <label>Change Image:</label>
        <input type="file" name="image">
        <?php if (!empty($data['image_path'])): ?>
            <img src="<?= htmlspecialchars($data['image_path']) ?>" class="preview">
        <?php endif; ?>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
