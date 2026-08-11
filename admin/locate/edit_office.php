<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$office = $conn->query("SELECT * FROM offices WHERE id=$id")->fetch_assoc();

if(isset($_POST['update_office'])){
    $country = $_POST['country'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Update coordinates if address changed
    $query = urlencode("$address, $city, $country");
    $url = "https://nominatim.openstreetmap.org/search?q=$query&format=json&limit=1";
    $opts = ["http" => ["header" => "User-Agent: SpectrumApp/1.0\r\n"]];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);
    $lat = $data[0]['lat'] ?? null;
    $lng = $data[0]['lon'] ?? null;

    $stmt = $conn->prepare("UPDATE offices SET country=?, city=?, address=?, phone=?, lat=?, lng=? WHERE id=?");
    $stmt->bind_param("sssssdi", $country, $city, $address, $phone, $lat, $lng, $id);
    $stmt->execute();
    header("Location: locate.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Office</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 20px;
    color: #333;
}
.container {
    max-width: 600px;
    margin: 0 auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
h1 {
    text-align: center;
    color: #007bff;
    margin-bottom: 30px;
}
form input {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    box-sizing: border-box;
}
form button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    color: #fff;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}
form button:hover {
    background: #0056b3;
}
.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 15px;
    background: #6c757d;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}
.back-btn:hover {
    background: #5a6268;
}
</style>
</head>
<body>

<div class="container">
    <a href="locate.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back to Locations</a>
    <h1>Edit Office</h1>
    <form method="POST">
        <input type="text" name="country" placeholder="Country" value="<?= htmlspecialchars($office['country']) ?>" required>
        <input type="text" name="city" placeholder="City" value="<?= htmlspecialchars($office['city']) ?>" required>
        <input type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($office['address']) ?>" required>
        <input type="text" name="phone" placeholder="Phone" value="<?= htmlspecialchars($office['phone']) ?>" required>
        <button type="submit" name="update_office"><i class="fa fa-save"></i> Update Office</button>
    </form>
</div>

</body>
</html>
