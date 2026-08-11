<?php
include('../config.php');

// Make sure row with id=1 exists
$conn->query("INSERT IGNORE INTO contact_info (id, address, image_path) VALUES (1, '', '')");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];

    // Handle image upload
    $fileName = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../assets/contact/"; // save in assets/contact
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES["image"]["name"]); // unique name
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    }

    // Update DB (save only filename, not full path)
    if ($fileName) {
        $stmt = $conn->prepare("UPDATE contact_info SET address=?, image_path=? WHERE id=1");
        $stmt->bind_param("ss", $address, $fileName);
    } else {
        $stmt = $conn->prepare("UPDATE contact_info SET address=? WHERE id=1");
        $stmt->bind_param("s", $address);
    }

    $stmt->execute();
    echo "Updated successfully!";
}

// Fetch existing data
$query = $conn->query("SELECT * FROM contact_info WHERE id=1");
$contact = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Contact Info</title>
</head>
<body>
  <h2>Edit Contact Info</h2>
  <form method="POST" enctype="multipart/form-data">
    <label>Address:</label><br>
    <input type="text" name="address" value="<?php echo htmlspecialchars($contact['address'] ?? ''); ?>"><br><br>

    <label>Change Image:</label><br>
    <input type="file" name="image"><br>
    <?php if (!empty($contact['image_path'])): ?>
      <img src="../assets/contact/<?php echo htmlspecialchars($contact['image_path']); ?>" 
           alt="Current Image" width="200">
    <?php endif; ?>
    <br><br>

    <button type="submit">Save Changes</button>
  </form>
</body>
</html>
