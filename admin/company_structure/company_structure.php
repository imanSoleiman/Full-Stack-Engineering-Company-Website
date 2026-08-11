<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';

$uploadFolder = '../../assets/structure/';

// Fetch existing data
$result = mysqli_query($conn, "SELECT * FROM company_structure WHERE id=1");
$company = mysqli_fetch_assoc($result);

if(isset($_POST['update'])) {
    $heading = $_POST['heading'];
    $description = $_POST['description'];

    // Handle image upload
    if($_FILES['background_image']['name']) {
        $imageName = time() . '_' . $_FILES['background_image']['name'];
        $targetPath = $uploadFolder . $imageName;
        move_uploaded_file($_FILES['background_image']['tmp_name'], $targetPath);
    } else {
        $imageName = $company['background_image'];
    }

    // Update database
    $stmt = $conn->prepare("UPDATE company_structure SET heading=?, description=?, background_image=? WHERE id=1");
    $stmt->bind_param("sss", $heading, $description, $imageName);
    $stmt->execute();

    echo "<p style='color:green;'>Updated successfully!</p>";

    // Refresh data
    $result = mysqli_query($conn, "SELECT * FROM company_structure WHERE id=1");
    $company = mysqli_fetch_assoc($result);
}
?>

<h2>Edit Company Structure</h2>

<form method="post" enctype="multipart/form-data">
    <label>Heading:</label><br>
    <input type="text" name="heading" value="<?= htmlspecialchars($company['heading']) ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" required><?= htmlspecialchars($company['description']) ?></textarea><br><br>

    <label>Background Image:</label><br>
    <input type="file" name="background_image"><br>
    <?php if(!empty($company['background_image'])): ?>
        <img src="<?= $uploadFolder . htmlspecialchars($company['background_image']) ?>" width="200" alt="Current Image" style="margin-top:10px;">
    <?php endif; ?>
    <br><br>

    <button type="submit" name="update">Update</button>
</form>
