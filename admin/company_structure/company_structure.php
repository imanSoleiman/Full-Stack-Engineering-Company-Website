<?php
require_once __DIR__ . '/../session.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/image_upload.php';

$uploadFolder = __DIR__ . '/../../assets/structure/';

// Fetch existing data
$result = mysqli_query($conn, "SELECT * FROM company_structure WHERE id=1");
$company = mysqli_fetch_assoc($result);

if(isset($_POST['update'])) {
    $heading = $_POST['heading'];
    $description = $_POST['description'];

    // Handle image upload
    if($_FILES['background_image']['name']) {
        $imageName = spectrum_store_image(
            $_FILES['background_image'],
            'structure',
            $uploadFolder
        );
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
        <img src="<?= htmlspecialchars(spectrum_admin_image_src($company['background_image'], '../../assets/structure/')) ?>" width="200" alt="Current Image" style="margin-top:10px;">
    <?php endif; ?>
    <br><br>

    <button type="submit" name="update">Update</button>
</form>
