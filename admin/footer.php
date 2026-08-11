<?php
include '../config.php';

// Fetch footer content
$result = $conn->query("SELECT * FROM footer_content WHERE id=1");
$row = $result->fetch_assoc();

// If no row exists, create a default one
if (!$row) {
    $conn->query("INSERT INTO footer_content (logo, description, slider_text) 
                  VALUES ('default.png', 'Default footer description', 'contact us')");
    $result = $conn->query("SELECT * FROM footer_content WHERE id=1");
    $row = $result->fetch_assoc();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Handle logo upload safely
    if(isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK){
        $originalName = basename($_FILES['logo']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $logoName = 'logo_' . time() . '.' . $extension;

        $uploadDir = '../assets/home/';
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0755, true);
        }

        if(!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $logoName)){
            echo "<p style='color:red;'>Failed to upload logo.</p>";
            $logoName = $row['logo'];
        }
    } else {
        $logoName = $row['logo'];
    }

    $description = $_POST['description'];
    $slider_text = $_POST['slider_text'];

    $stmt = $conn->prepare("UPDATE footer_content SET logo=?, description=?, slider_text=? WHERE id=1");
    $stmt->bind_param("sss", $logoName, $description, $slider_text);
    $stmt->execute();
    $stmt->close();

    $result = $conn->query("SELECT * FROM footer_content WHERE id=1");
    $row = $result->fetch_assoc();

    echo "<p style='color:green;'>Footer updated successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Footer Admin Panel</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 750px;
    margin: 60px auto;
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #1f2937;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #4b5563;
}

input[type="text"], textarea {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 15px;
    transition: all 0.3s;
}
input[type="text"]:focus, textarea:focus {
    border-color: #3b82f6;
    outline: none;
}

button {
    padding: 12px 25px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    background: linear-gradient(135deg,#4f46e5,#3b82f6);
    color: #fff;
    transition: all 0.3s;
}
button:hover {
    opacity: 0.9;
}

.success {
    background: #d1fae5;
    color: #065f46;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #10b981;
    text-align: center;
}

.file-upload {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
    margin-top: 10px;
}
.file-upload input[type=file] {
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    cursor: pointer;
}
.file-upload-label {
    display: block;
    padding: 12px;
    background: #f3f4f6;
    border: 1px dashed #9ca3af;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}
.file-upload-label:hover {
    background: #e5e7eb;
}

.current-logo {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
}
.current-logo img {
    max-height: 60px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
}
.current-logo span {
    font-weight: 500;
    color: #374151;
}
</style>
<script>
function previewLogo(input) {
    const preview = document.getElementById('logo-preview');
    if(input.files && input.files[0]){
        const reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</head>
<body>

<div class="container">
    <h2>Footer Admin Panel</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="success">✅ Footer updated successfully!</div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <!-- Current Logo -->
        <label>Current Logo:</label>
        <div class="current-logo">
            <img id="logo-preview" src="../assets/home/<?= htmlspecialchars($row['logo']) ?>" alt="Current Logo">
            <span><?= htmlspecialchars($row['logo']) ?></span>
        </div>

        <!-- Upload New Logo -->
        <label>Upload New Logo:</label>
        <div class="file-upload">
            <label class="file-upload-label">Choose New Logo</label>
            <input type="file" name="logo" accept="image/*" onchange="previewLogo(this)">
        </div>

        <label>Description:</label>
        <textarea name="description" rows="5"><?= htmlspecialchars($row['description']) ?></textarea>

        <label>Slider Text (single word/phrase):</label>
        <input type="text" name="slider_text" value="<?= htmlspecialchars($row['slider_text']) ?>">

        <button type="submit">Update Footer</button>
    </form>
</div>

</body>
</html>
