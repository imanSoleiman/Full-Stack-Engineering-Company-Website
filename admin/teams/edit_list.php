<?php 
include "../../config.php";
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Get list ID and slide ID safely
$id = $_GET['id'] ?? null;
$slide_id = $_GET['slide_id'] ?? null;

if (!$id || !$slide_id) {
    die("Missing required parameters.");
}

// Fetch the list item
$list = $conn->query("SELECT * FROM professional_lists WHERE id=$id")->fetch_assoc();
if (!$list) die("List item not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = $_POST['heading'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE professional_lists SET heading=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $heading, $description, $id);
    $stmt->execute();

    // Redirect back to the slide edit page
    header("Location: edit_slide.php?id=$slide_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit List Item</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6f8;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    h1 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 30px;
        font-size: 28px;
    }

    form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    form input[type="text"],
    form textarea {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    form input[type="text"]:focus,
    form textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.2);
    }

    form textarea {
        resize: vertical;
        min-height: 100px;
    }

    button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
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

    .back-link {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: #007bff;
        font-weight: 500;
        transition: 0.3s;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .container {
            margin: 20px;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1><i class="fa-solid fa-list"></i> Edit List Item</h1>
    <form method="post">
        <label for="heading">Heading:</label>
        <input type="text" id="heading" name="heading" value="<?= htmlspecialchars($list['heading']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($list['description']) ?></textarea>

        <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>

    <p><a class="back-link" href="edit_slide.php?id=<?= $slide_id ?>"><i class="fa-solid fa-arrow-left"></i> Back to Slide</a></p>
</div>

</body>
</html>
