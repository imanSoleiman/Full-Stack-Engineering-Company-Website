<?php include "../../config.php"; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];

    // Handle image upload
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $target = "../../assets/teams/" . $fileName;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target);

    $stmt = $conn->prepare("INSERT INTO professional_slides (title, image) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $fileName);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Slide</title>
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
    }

    form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    form input[type="text"],
    form input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    form input[type="text"]:focus,
    form input[type="file"]:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.2);
    }

    button {
        display: block;
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

    @media (max-width: 480px) {
        .container {
            padding: 20px;
            margin: 20px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1><i class="fa-solid fa-image"></i> Add Slide</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Slide Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter slide title" required>

        <label for="image">Slide Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit"><i class="fa-solid fa-plus"></i> Add Slide</button>
    </form>
</div>

</body>
</html>
