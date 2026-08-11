<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php'; // your database connection

$message = "";

// Update section if form submitted
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $header = $_POST['header'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE sections SET header=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $header, $content, $id);
    $stmt->execute();
    $stmt->close();

    $message = "✅ Section updated successfully!";
}

// Fetch all sections
$result = $conn->query("SELECT * FROM sections");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Sections</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
    }
    .container {
        max-width: 900px;
        margin: auto;
    }
    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }
    .message {
        background-color: #e6ffed;
        border: 1px solid #2a7a2a;
        color: #2a7a2a;
        font-weight: bold;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    form {
        background-color: #fff;
        padding: 25px 30px;
        margin-bottom: 20px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #555;
    }
    input[type=text], textarea {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        margin-bottom: 20px;
        transition: 0.3s;
    }
    input[type=text]:focus, textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.2);
    }
    textarea {
        resize: vertical;
    }
    button {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
        font-size: 16px;
        padding: 12px 25px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    hr {
        border: 0;
        border-top: 1px solid #e0e0e0;
        margin: 30px 0;
    }
    @media (max-width: 768px) {
        form {
            padding: 20px;
        }
        button {
            width: 100%;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h1>Edit Sections</h1>

    <?php if($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <?php while($row = $result->fetch_assoc()): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        
        <label>Header:</label>
        <input type="text" name="header" value="<?= htmlspecialchars($row['header']) ?>" required>
        
        <label>Content:</label>
        <textarea name="content" rows="6" required><?= htmlspecialchars($row['content']) ?></textarea>
        
        <button type="submit" name="update">Update Section</button>
    </form>
    <hr>
    <?php endwhile; ?>
</div>
</body>
</html>
