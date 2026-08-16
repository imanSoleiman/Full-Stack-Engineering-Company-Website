<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/includes/image_upload.php';

// Make sure row with id=1 exists
$conn->query("INSERT IGNORE INTO contact_info (id, address, image_path) VALUES (1, '', '')");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];

    // Handle image upload
    $fileName = null;
    if (!empty($_FILES['image']['name'])) {
        try {
            $fileName = spectrum_store_image(
                $_FILES['image'],
                'contact',
                __DIR__ . '/../assets/contact/'
            );
        } catch (Throwable $e) {
            die("Image upload failed: " . htmlspecialchars($e->getMessage()));
        }
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
      <img src="<?php echo htmlspecialchars(spectrum_admin_image_src($contact['image_path'], '../assets/contact/')); ?>" 
           alt="Current Image" width="200">
    <?php endif; ?>
    <br><br>

    <button type="submit">Save Changes</button>
  </form>
</body>
</html>
