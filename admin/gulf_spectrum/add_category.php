<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

if(isset($_POST['submit'])){
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO gulf_spectrum_categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    header("Location: index.php");
}
?>

<h1>Add Category</h1>
<form method="POST">
    <label>Category Name:</label>
    <input type="text" name="name" required>
    <button type="submit" name="submit">Add Category</button>
</form>
