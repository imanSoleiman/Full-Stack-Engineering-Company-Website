<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';
// Get current values
$result = $conn->query("SELECT * FROM corporate_second_section WHERE id = 1");
$data = $result->fetch_assoc();

$message = '';

// On form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $section_title = $_POST['section_title'];
    $section_subtitle = $_POST['section_subtitle'];
    $section_paragraph = $_POST['section_paragraph'];

    $stmt = $conn->prepare("UPDATE corporate_second_section SET section_title=?, section_subtitle=?, section_paragraph=? WHERE id=1");
    $stmt->bind_param("sss", $section_title, $section_subtitle, $section_paragraph);
    $stmt->execute();

    $message = "✅ Second section updated successfully!";
    $result = $conn->query("SELECT * FROM corporate_second_section WHERE id = 1");
    $data = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Social Responsibility Section</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 700px;
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
    form input[type="text"], form textarea {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }
    form input[type="text"]:focus, form textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.2);
    }
    form textarea {
        resize: vertical;
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
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Social Responsibility Section</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Section Title:</label>
        <input type="text" name="section_title" value="<?= htmlspecialchars($data['section_title']) ?>" required>

        <label>Section Subtitle:</label>
        <input type="text" name="section_subtitle" value="<?= htmlspecialchars($data['section_subtitle']) ?>" required>

        <label>Paragraph:</label>
        <textarea name="section_paragraph" rows="5" required><?= htmlspecialchars($data['section_paragraph']) ?></textarea>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
