<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $country_name = $_POST['country_name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO middle_east_countries (country_name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $country_name, $description);
    $stmt->execute();

    header("Location: middle_east.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Country</title></head>
<body>
  <h1>Add Country</h1>
  <form method="post">
    <label>Country Name:</label><br>
    <input type="text" name="country_name" required><br><br>

    <label>Description:</label><br>
    <input type="text" name="description" value="Permanent Office • Middle East" required><br><br>

    <button type="submit">Add</button>
  </form>
</body>
</html>
