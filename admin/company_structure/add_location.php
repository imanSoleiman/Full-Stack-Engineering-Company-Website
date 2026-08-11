<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);

    // Check if location already exists
    $check = mysqli_query($conn, "SELECT id FROM locations WHERE city='$city' AND country='$country'");
    if (mysqli_num_rows($check) > 0) {
        $message = "❌ This location ('$city, $country') was already added before!";
    } else {
        // Single upload folder
        $uploadFolder = "../../assets/structure/";

        // Upload location image
        $image_name = '';
        if (!empty($_FILES['image']['name'])) {
            $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
            $target = $uploadFolder . $image_name;
            if (!is_dir($uploadFolder)) mkdir($uploadFolder, 0777, true);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $message = "❌ Failed to upload location image.";
                $image_name = '';
            }
        }

        // Upload popup background image
        $popup_bg_name = '';
        if (!empty($_FILES['popup_bg']['name'])) {
            $popup_bg_name = uniqid() . '_' . basename($_FILES['popup_bg']['name']);
            $bg_target = $uploadFolder . $popup_bg_name;
            if (!is_dir($uploadFolder)) mkdir($uploadFolder, 0777, true);
            if (!move_uploaded_file($_FILES['popup_bg']['tmp_name'], $bg_target)) {
                $message = "❌ Failed to upload popup background image.";
                $popup_bg_name = '';
            }
        }

        // Insert into database
        $sql = "INSERT INTO locations (city, country, image_path) VALUES ('$city', '$country', '$image_name')";
        if (mysqli_query($conn, $sql)) {
            $location_id = mysqli_insert_id($conn);

            $sql2 = "INSERT INTO location_details (location_id, background_image_path) 
                     VALUES ($location_id, '$popup_bg_name')";
            mysqli_query($conn, $sql2);

            $message = "✅ New location '$city, $country' has been added successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Location</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        form label {
            display: block;
            margin: 12px 0 6px;
            font-weight: 600;
            color: #555;
        }
        form input[type="text"], form input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        form button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #007bff;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        form button:hover {
            background: #0056b3;
        }
        .message {
            margin-bottom: 20px;
            padding: 12px 15px;
            border-radius: 6px;
            font-weight: 600;
        }
        .success { background: #28a745; color: #fff; }
        .error { background: #dc3545; color: #fff; }
        .note {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #555;
            background: #e9f3ff;
            padding: 10px 12px;
            border-left: 4px solid #007bff;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Location</h2>

    <?php if ($message): ?>
        <div class="message <?= strpos($message,'❌') !== false ? 'error' : 'success' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>City:</label>
        <input type="text" name="city" placeholder="Enter city" required>

        <label>Country:</label>
        <input type="text" name="country" placeholder="Enter country" required>

        <label>Location Card Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <label>Popup Background Image:</label>
        <input type="file" name="popup_bg" accept="image/*">

        <div class="note">
            Note: You can use the same image for the detail popup or upload a different one.
        </div>

        <button type="submit">Add Location</button>
    </form>
</div>

</body>
</html>
