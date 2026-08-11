<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch current data
$data = $conn->query("SELECT * FROM team_intro WHERE id=1")->fetch_assoc();
$headings = json_decode($data['headings'], true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headings = $_POST['headings'] ?? [];
    $paragraph = $_POST['paragraph'];
    $imagePath = $data['image_path'];

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../../assets/teams/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $imagePath = "assets/teams/" . $fileName;
    }

    $headingsJson = json_encode($headings);

    $stmt = $conn->prepare("UPDATE team_intro SET headings=?, paragraph=?, image_path=? WHERE id=1");
    $stmt->bind_param("sss", $headingsJson, $paragraph, $imagePath);
    $stmt->execute();

    header("Location: edit_team.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Team Intro</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        background: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 25px;
        font-size: 28px;
    }

    h3 {
        color: #333;
        margin-bottom: 15px;
    }

    form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }

    input[type="text"], textarea, input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    input[type="text"]:focus, textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.2);
    }

    textarea { resize: vertical; min-height: 100px; }

    .heading-wrapper {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: center;
    }

    .heading-wrapper input {
        flex: 1;
    }

    .heading-wrapper button {
        background: #ff4d4f;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }

    .heading-wrapper button:hover {
        background: #d9363e;
    }

    .add-btn {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
        margin-bottom: 20px;
    }

    .add-btn:hover {
        background: #0056b3;
    }

    .submit-btn {
        background: #10b981;
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
        width: 100%;
    }

    .submit-btn:hover {
        background: #059669;
    }

    .success-msg {
        background: #d1fae5;
        color: #065f46;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #10b981;
    }

    .current-image img {
        margin-top: 10px;
        border-radius: 8px;
        max-width: 200px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    @media (max-width: 600px) {
        .container { padding: 20px; }
    }
</style>
<script>
function addHeadingField() {
    let container = document.getElementById("headings-container");
    let wrapper = document.createElement("div");
    wrapper.className = "heading-wrapper";

    let input = document.createElement("input");
    input.type = "text";
    input.name = "headings[]";
    input.placeholder = "Enter heading";

    let delBtn = document.createElement("button");
    delBtn.type = "button";
    delBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
    delBtn.onclick = function() { removeHeading(delBtn); };

    wrapper.appendChild(input);
    wrapper.appendChild(delBtn);
    container.appendChild(wrapper);
}

function removeHeading(btn) {
    if(confirm("Are you sure you want to delete this heading?")) {
        btn.parentElement.remove();
    }
}
</script>
</head>
<body>

<div class="container">
    <h2><i class="fa-solid fa-users"></i> Edit Team Intro</h2>

    <?php if(isset($_GET['success'])): ?>
        <div class="success-msg"><i class="fa-solid fa-circle-check"></i> Updated successfully!</div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <h3>Headings</h3>
        <div id="headings-container">
            <?php foreach($headings as $h): ?>
            <div class="heading-wrapper">
                <input type="text" name="headings[]" value="<?= htmlspecialchars($h) ?>">
                <button type="button" onclick="removeHeading(this)"><i class="fa-solid fa-trash"></i></button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add-btn" onclick="addHeadingField()"><i class="fa-solid fa-plus"></i> Add Heading</button>

        <label>Paragraph:</label>
        <textarea name="paragraph"><?= htmlspecialchars($data['paragraph']) ?></textarea>

        <label>Background Image:</label>
        <input type="file" name="image">
        <?php if($data['image_path']): ?>
        <div class="current-image">
            <small>Current Image:</small><br>
            <img src="../../<?= $data['image_path'] ?>" alt="Current Image">
        </div>
        <?php endif; ?>

        <button type="submit" class="submit-btn"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>
</div>

</body>
</html>
