<?php
include '../../config.php';

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch available years
$years = $conn->query("SELECT * FROM news_years ORDER BY year DESC");

// Fetch available categories
$categories = $conn->query("SELECT * FROM news_categories ORDER BY name ASC");
$preselect_category = $_GET['category_id'] ?? null;

if(isset($_POST['submit'])){
    $year_id = intval($_POST['year_id']);
    $category_id = intval($_POST['category_id']);
    $title = trim($_POST['title']);
    $day = intval($_POST['day']);
    $month = intval($_POST['month']);

    // Validate year
    $stmtYear = $conn->prepare("SELECT year FROM news_years WHERE id = ?");
    $stmtYear->bind_param("i", $year_id);
    $stmtYear->execute();
    $resultYear = $stmtYear->get_result();
    if($resultYear->num_rows === 0){ echo "<p style='color:red;'>Invalid year selected!</p>"; exit; }
    $yearRow = $resultYear->fetch_assoc();
    $year = $yearRow['year'];

    // Validate category
    $stmtCat = $conn->prepare("SELECT id FROM news_categories WHERE id = ?");
    $stmtCat->bind_param("i", $category_id);
    $stmtCat->execute();
    $resultCat = $stmtCat->get_result();
    if($resultCat->num_rows === 0){ echo "<p style='color:red;'>Invalid category selected!</p>"; exit; }

    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $image_name = time() . "_" . basename($_FILES['image']['name']); 
        $target_dir = "../../assets/news/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . $image_name;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)){
            $stmt = $conn->prepare("INSERT INTO news_cards (year_id, category_id, title, date_day, date_month, date_year, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisiiis", $year_id, $category_id, $title, $day, $month, $year, $image_name);
            if($stmt->execute()){
                echo "<p style='color:green;'>News added successfully!</p>";
            } else {
                echo "<p style='color:red;'>Database insert failed: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Failed to upload image!</p>";
        }
    } else {
        echo "<p style='color:red;'>No image selected or upload error!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add News</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 40px;
    color: #333;
}
h1 {
    text-align: center;
    color: #1E3A8A;
    margin-bottom: 30px;
}
form {
    background: #fff;
    max-width: 700px;
    margin: auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #1E3A8A;
}
input[type="text"],
input[type="number"],
select,
input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
}
select {
    cursor: pointer;
}
button {
    padding: 12px 25px;
    background: #1E90FF;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    font-size: 1rem;
}
button:hover {
    background: #0d6efd;
}
</style>
</head>
<body>

<h1><i class="fa fa-newspaper"></i> Add News</h1>

<form method="POST" enctype="multipart/form-data">
    <label>Select Year:</label>
    <select name="year_id" required>
        <option value="">Select Year</option>
        <?php while($row = $years->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['year'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Select Category:</label>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php while($c = $categories->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>" <?= ($c['id'] == $preselect_category) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>News Title:</label>
    <input type="text" name="title" placeholder="News Title" required>

    <label>Day:</label>
    <input type="number" name="day" placeholder="Day" min="1" max="31" required>

    <label>Month:</label>
    <select name="month" required>
        <option value="">Select Month</option>
        <?php 
        $months = ["January","February","March","April","May","June",
                   "July","August","September","October","November","December"];
        foreach($months as $index => $m){
            $val = $index+1;
            echo "<option value='$val'>$m</option>";
        }
        ?>
    </select>

    <label>Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" name="submit"><i class="fa fa-plus"></i> Add News</button>
</form>

</body>
</html>
