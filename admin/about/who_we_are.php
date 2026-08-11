<?php
session_start();
include("../../config.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}


// Fetch section text
$section = $conn->query("SELECT * FROM who_we_are_section LIMIT 1")->fetch_assoc();

// Fetch images
$imagesResult = $conn->query("SELECT * FROM who_we_are_images ORDER BY position ASC");
$images = [];
while($img = $imagesResult->fetch_assoc()) {
    $images[$img['position']] = $img['image_name'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Section text
    $title = $_POST['section_title'];
    $subtitle = $_POST['section_subtitle'];
    $leftText = $_POST['left_text'];

    if ($section) {
        $stmt = $conn->prepare("UPDATE who_we_are_section SET section_title=?, section_subtitle=?, left_text=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $subtitle, $leftText, $section['id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO who_we_are_section (section_title, section_subtitle, left_text) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $subtitle, $leftText);
        $stmt->execute();
    }

    // Handle images
    foreach([1,2] as $pos) {
        if (!empty($_FILES["image$pos"]["name"])) {
            $imageName = time() . "_" . basename($_FILES["image$pos"]["name"]);
            $target = "../../assets/about/" . $imageName;
            move_uploaded_file($_FILES["image$pos"]["tmp_name"], $target);

            // Delete old image if exists
            if (isset($images[$pos]) && file_exists("../../assets/about/".$images[$pos])) {
                unlink("../../assets/about/".$images[$pos]);
            }

            // Update or insert
            if (isset($images[$pos])) {
                $stmt = $conn->prepare("UPDATE who_we_are_images SET image_name=? WHERE position=?");
                $stmt->bind_param("si", $imageName, $pos);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("INSERT INTO who_we_are_images (image_name, position) VALUES (?, ?)");
                $stmt->bind_param("si", $imageName, $pos);
                $stmt->execute();
            }
        }
    }

    header("Location: who_we_are.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Who We Are Section</title>
<style>
    /* Reset some default styles */
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', Roboto, Arial, sans-serif; background: #f5f6fa; padding: 30px; color: #333; }

    h2 { text-align: center; margin-bottom: 25px; color: #222; }

    form {
        max-width: 800px;
        margin: auto;
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
        color: #555;
    }

    input[type="text"], textarea, input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: all 0.2s;
    }

    input[type="text"]:focus, textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
    }

    textarea { min-height: 120px; resize: vertical; }

    .image-preview {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 8px;
    }

    .image-preview img {
        width: 120px;
        height: auto;
        border-radius: 8px;
        border: 1px solid #ccc;
        object-fit: cover;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    button {
        padding: 12px 18px;
        background-color: #007bff;
        color: #fff;
        font-size: 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        align-self: flex-start;
    }

    button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    p.success {
        background: #e6ffed;
        border: 1px solid #8cd19d;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #2d7a35;
        text-align: center;
    }

    @media(max-width: 600px) {
        .image-preview { flex-direction: column; }
        .image-preview img { width: 100%; max-width: 200px; }
    }
</style>
</head>
<body>

<h2>Manage Who We Are Section</h2>

<?php if(isset($_GET['success'])): ?>
    <p class="success">✅ Section updated successfully!</p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Section Title:</label>
    <input type="text" name="section_title" value="<?= htmlspecialchars($section['section_title'] ?? '') ?>" required>

    <label>Section Subtitle:</label>
    <textarea name="section_subtitle" required><?= htmlspecialchars($section['section_subtitle'] ?? '') ?></textarea>

    <label>Left Text (image left):</label>
    <textarea name="left_text"><?= htmlspecialchars($section['left_text'] ?? '') ?></textarea>

    <?php foreach([1,2] as $pos): ?>
        <label>Image <?= $pos ?>:</label>
        <div class="image-preview">
            <?php if(isset($images[$pos])): ?>
                <img src="../../assets/about/<?= htmlspecialchars($images[$pos]) ?>" alt="Image <?= $pos ?>">
            <?php endif; ?>
            <input type="file" name="image<?= $pos ?>">
        </div>
    <?php endforeach; ?>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
