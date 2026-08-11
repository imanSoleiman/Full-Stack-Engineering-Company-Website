<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php'); // your database connection

// Fetch existing data
$result = $conn->query("SELECT * FROM company_structure WHERE id = 1");
$data = $result->fetch_assoc();

if(isset($_POST['update'])) {
    $heading = $_POST['heading'];
    $description = $_POST['description'];

    // Handle image upload
    if($_FILES['background_image']['name']) {
        $imageName = time() . '_' . $_FILES['background_image']['name'];
        $targetDir = "../../assets/structure/" . $imageName; // <-- new path
        move_uploaded_file($_FILES['background_image']['tmp_name'], $targetDir);
    } else {
        $imageName = $data['background_image']; // keep old image
    }

    // Update database
    $stmt = $conn->prepare("UPDATE company_structure SET heading=?, description=?, background_image=? WHERE id=1");
    $stmt->bind_param("sss", $heading, $description, $imageName);
    $stmt->execute();
    echo "Updated successfully!";
}
?>

<form method="post" enctype="multipart/form-data">
    <label>Heading:</label>
    <input type="text" name="heading" value="<?= $data['heading'] ?>" required><br><br>

    <label>Description:</label>
    <textarea name="description" required><?= $data['description'] ?></textarea><br><br>

    <label>Background Image:</label>
    <input type="file" name="background_image"><br><br>

    <button type="submit" name="update">Update</button>
</form>
