<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: manage_news.php");
    exit;
}

$id = intval($_GET['id']); 

$years = $conn->query("SELECT * FROM news_years ORDER BY year DESC");
$categories = $conn->query("SELECT * FROM news_categories ORDER BY name ASC");

$stmt = $conn->prepare("SELECT * FROM news_cards WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();

if(!$news){
    echo "News not found!";
    exit;
}

if(isset($_POST['submit'])){
    $year_id = intval($_POST['year_id']);
    $category_id = intval($_POST['category_id']); 
    $title = trim($_POST['title']);
    $day = intval($_POST['day']);
    $month = intval($_POST['month']);

    $stmtYear = $conn->prepare("SELECT year FROM news_years WHERE id = ?");
    $stmtYear->bind_param("i", $year_id);
    $stmtYear->execute();
    $resultYear = $stmtYear->get_result();
    if($row = $resultYear->fetch_assoc()){
        $year = $row['year'];
    } else {
        echo "Invalid year selected!";
        exit;
    }

    $stmtCat = $conn->prepare("SELECT id FROM news_categories WHERE id = ?");
    $stmtCat->bind_param("i", $category_id);
    $stmtCat->execute();
    $resultCat = $stmtCat->get_result();
    if($resultCat->num_rows === 0){
        echo "Invalid category selected!";
        exit;
    }

    $image_name = $news['image'];

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
        $image_name = time() . "_" . basename($_FILES['image']['name']); 
        $target_dir = "../../assets/news/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("UPDATE news_cards SET year_id=?, category_id=?, title=?, date_day=?, date_month=?, date_year=?, image=? WHERE id=?");
    $stmt->bind_param("iisiiisi", $year_id, $category_id, $title, $day, $month, $year, $image_name, $id);
    if($stmt->execute()){
        header("Location: manage_news.php");
        exit;
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit News</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7f8;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }
    .form-container {
        background: #fff;
        padding: 30px 40px;
        margin: 50px 0;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 600px;
    }
    .form-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #555;
    }
    input[type="text"],
    input[type="number"],
    select,
    input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 16px;
        transition: border 0.3s;
    }
    input[type="text"]:focus,
    input[type="number"]:focus,
    select:focus,
    input[type="file"]:focus {
        border-color: #007bff;
        outline: none;
    }
    p {
        font-size: 14px;
        color: #777;
        margin-bottom: 10px;
    }
    button {
        width: 100%;
        padding: 14px;
        background: #007bff;
        color: #fff;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }
    button:hover {
        background: #0056b3;
    }
    @media(max-width: 600px){
        .form-container {
            padding: 20px;
            margin: 20px;
        }
    }
</style>
</head>
<body>
<div class="form-container">
    <h2>Edit News</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Select Year:</label>
        <select name="year_id" required>
            <option value="">Select Year</option>
            <?php while($row = $years->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= ($row['id']==$news['year_id'])?'selected':'' ?>>
                    <?= $row['year'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Select Category:</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php
            $categories->data_seek(0);
            while($c = $categories->fetch_assoc()):
            ?>
                <option value="<?= $c['id'] ?>" <?= ($c['id']==$news['category_id'])?'selected':'' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>News Title:</label>
        <input type="text" name="title" placeholder="News Title" value="<?= htmlspecialchars($news['title']) ?>" required>

        <label>Day:</label>
        <input type="number" name="day" placeholder="Day" min="1" max="31" value="<?= $news['date_day'] ?>" required>

        <label>Month:</label>
        <select name="month" required>
            <?php 
            $months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
            foreach($months as $index => $m){
                $val = $index+1;
                $selected = ($val == $news['date_month']) ? 'selected' : '';
                echo "<option value='$val' $selected>$m</option>";
            }
            ?>
        </select>

        <p>Current Image: <?= htmlspecialchars($news['image']) ?></p>
        <label>Upload New Image (optional):</label>
        <input type="file" name="image">

        <button type="submit" name="submit">Update News</button>
    </form>
</div>
</body>
</html>
