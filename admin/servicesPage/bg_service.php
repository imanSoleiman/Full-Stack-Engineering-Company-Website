<?php  
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch current values
$result = $conn->query("SELECT * FROM services_background_image WHERE id=1");
$data = $result->fetch_assoc();

$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $background_image = $data['background_image']; // Keep old image if no new upload

    if (!empty($_FILES['background_image']['name'])) {
        $target_dir = __DIR__ . "/../../assets/service_page_uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

        $fileName = time() . "_" . basename($_FILES["background_image"]["name"]);
        $target_file = $target_dir . $fileName;

        if (move_uploaded_file($_FILES["background_image"]["tmp_name"], $target_file)) {
            $background_image = $fileName;
        } else {
            echo "<p style='color:red;'>Error uploading file.</p>";
        }
    }

    $stmt = $conn->prepare("UPDATE services_background_image SET background_image=?, description=? WHERE id=1");
    $stmt->bind_param("ss", $background_image, $description);
    $stmt->execute();

    $successMsg = "Changes saved successfully!";
    // Refresh data after saving
    $data['background_image'] = $background_image;
    $data['description'] = $description;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Service Background</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h1 {
    margin-top: 0;
    color: #333;
    text-align: center;
}
form label {
    display: block;
    margin: 15px 0 5px;
    font-weight: bold;
    color: #555;
}
input[type="file"], textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    box-sizing: border-box;
}
textarea { resize: vertical; height: 120px; }
img {
    display: block;
    margin: 15px 0;
    max-width: 100%;
    border-radius: 8px;
    border: 1px solid #ddd;
}
button {
    padding: 12px 20px;
    background: #007bff;
    color: #fff;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    width: 100%;
}
button:hover { background: #0056b3; }
.success-msg {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    text-align: center;
}
</style>
</head>
<body>

<div class="container">
    <h1>Edit Service Background</h1>

    <?php if($successMsg): ?>
        <div class="success-msg"><?= $successMsg ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Background Image:</label>
        <input type="file" name="background_image">
        <?php if (!empty($data['background_image'])): ?>
            <img src="../../assets/service_page_uploads/<?php echo $data['background_image']; ?>" alt="Background Image">
        <?php endif; ?>

        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($data['description']) ?></textarea>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
