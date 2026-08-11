<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$country = $conn->query("SELECT * FROM middle_east_countries WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $country_name = $_POST['country_name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE middle_east_countries SET country_name=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $country_name, $description, $id);
    $stmt->execute();

    header("Location: middle_east.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Country</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 500px;
        margin: 50px auto;
        background: #fff;
        padding: 30px 35px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    h1 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 25px;
        font-size: 24px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        background-color: #2563eb;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background-color: #1d4ed8;
    }

    a.back-link {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: #2563eb;
        font-size: 14px;
    }

    a.back-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 500px) {
        .container {
            padding: 20px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1><i class="fa-solid fa-flag"></i> Edit Country</h1>
    <form method="post">
        <label for="country_name">Country Name</label>
        <input type="text" id="country_name" name="country_name" value="<?= htmlspecialchars($country['country_name']) ?>" required>

        <label for="description">Description</label>
        <input type="text" id="description" name="description" value="<?= htmlspecialchars($country['description']) ?>" required>

        <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>
    <a href="middle_east.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Countries</a>
</div>

</body>
</html>
