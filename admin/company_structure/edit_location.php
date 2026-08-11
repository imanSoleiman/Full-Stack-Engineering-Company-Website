<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';

if (!isset($_GET['id'])) {
    die("Location ID is missing.");
}

$location_id = intval($_GET['id']);
$uploadFolder = "../../assets/structure/";
$message = '';

// Fetch location info
$loc_res = mysqli_query($conn, "SELECT * FROM locations WHERE id = $location_id");
$location = mysqli_fetch_assoc($loc_res);

// Fetch popup info
$popup_res = mysqli_query($conn, "SELECT * FROM location_details WHERE location_id = $location_id");
$popup = mysqli_fetch_assoc($popup_res);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);

    $image_path_sql = '';
    if (!empty($_FILES['image']['name'])) {
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = $uploadFolder . $image_name;
        if (!is_dir($uploadFolder)) mkdir($uploadFolder, 0777, true);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_path_sql = ", image_path = '$image_name'";
            if (!empty($location['image_path']) && file_exists($uploadFolder . $location['image_path'])) {
                unlink($uploadFolder . $location['image_path']);
            }
        }
    }

    $update_loc = "UPDATE locations 
                   SET city = '$city', country = '$country' $image_path_sql 
                   WHERE id = $location_id";
    mysqli_query($conn, $update_loc);

    if (!empty($_FILES['popup_bg']['name'])) {
        $bg_name = uniqid() . '_' . basename($_FILES['popup_bg']['name']);
        $bg_target = $uploadFolder . $bg_name;
        if (!is_dir($uploadFolder)) mkdir($uploadFolder, 0777, true);
        if (move_uploaded_file($_FILES['popup_bg']['tmp_name'], $bg_target)) {
            if (!empty($popup['background_image_path']) && file_exists($uploadFolder . $popup['background_image_path'])) {
                unlink($uploadFolder . $popup['background_image_path']);
            }
            $update_popup = "UPDATE location_details 
                             SET background_image_path = '$bg_name' 
                             WHERE location_id = $location_id";
            mysqli_query($conn, $update_popup);
        }
    }

    $message = "✅ Location updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Location</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
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
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        form input[type="text"], 
        form input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        form input[type="file"] {
            padding: 5px;
        }
        form img {
            display: block;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        button {
            display: block;
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
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
        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
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
    <h2>Edit Location</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>City:</label>
        <input type="text" name="city" value="<?= htmlspecialchars($location['city']) ?>" required>

        <label>Country:</label>
        <input type="text" name="country" value="<?= htmlspecialchars($location['country']) ?>" required>

        <label>Current Location Image:</label>
        <?php if (!empty($location['image_path'])): ?>
            <img src="<?= $uploadFolder . htmlspecialchars($location['image_path']) ?>" width="150">
        <?php endif; ?>
        <input type="file" name="image" accept="image/*">

        <label>Current Popup Background Image:</label>
        <?php if (!empty($popup['background_image_path'])): ?>
            <img src="<?= $uploadFolder . htmlspecialchars($popup['background_image_path']) ?>" width="150">
        <?php endif; ?>
        <input type="file" name="popup_bg" accept="image/*">

        <button type="submit">Update Location</button>
    </form>
</div>

</body>
</html>
