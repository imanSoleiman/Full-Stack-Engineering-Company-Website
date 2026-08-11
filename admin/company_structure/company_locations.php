<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';

$uploadFolder = '../../assets/structure/';

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $loc_res = mysqli_query($conn, "SELECT image_path FROM locations WHERE id = $delete_id");
    $loc = mysqli_fetch_assoc($loc_res);
    if ($loc && !empty($loc['image_path'])) unlink($uploadFolder . basename($loc['image_path']));

    $popup_res = mysqli_query($conn, "SELECT background_image_path FROM location_details WHERE location_id = $delete_id");
    $popup = mysqli_fetch_assoc($popup_res);
    if ($popup && !empty($popup['background_image_path'])) unlink($uploadFolder . basename($popup['background_image_path']));

    mysqli_query($conn, "DELETE FROM departments WHERE location_id = $delete_id");
    mysqli_query($conn, "DELETE FROM location_details WHERE location_id = $delete_id");
    mysqli_query($conn, "DELETE FROM locations WHERE id = $delete_id");

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Toggle homepage visibility
if (isset($_GET['toggle_home'])) {
    $id = intval($_GET['toggle_home']);
    mysqli_query($conn, "UPDATE locations SET show_on_home = IF(show_on_home=1, 0, 1) WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Company structure update
$structure_result = $conn->query("SELECT * FROM company_structure WHERE id = 1");
$structure_data = $structure_result->fetch_assoc();

if (isset($_POST['update_structure'])) {
    $heading = $_POST['heading'];
    $description = $_POST['description'];

    if ($_FILES['background_image']['name']) {
        $imageName = time() . '_' . $_FILES['background_image']['name'];
        move_uploaded_file($_FILES['background_image']['tmp_name'], $uploadFolder . $imageName);
    } else {
        $imageName = $structure_data['background_image'];
    }

    $stmt = $conn->prepare("UPDATE company_structure SET heading=?, description=?, background_image=? WHERE id=1");
    $stmt->bind_param("sss", $heading, $description, $imageName);
    $stmt->execute();

    $structure_data['heading'] = $heading;
    $structure_data['description'] = $description;
    $structure_data['background_image'] = $imageName;
    echo "<p class='success'>Company structure updated successfully!</p>";
}

// Fetch locations
$locations_result = mysqli_query($conn, "SELECT l.*, d.background_image_path 
                    FROM locations l 
                    LEFT JOIN location_details d ON l.id = d.location_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel - Company Structure & Locations</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h2 {
    margin-top: 0;
    color: #333;
}
form {
    margin-bottom: 40px;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}
input[type="text"], textarea, input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
textarea {
    resize: vertical;
    height: 100px;
}
button {
    padding: 10px 20px;
    background: #007bff;
    border: none;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
}
button:hover {
    background: #0056b3;
}
.success {
    color: green;
    margin-bottom: 15px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table th, table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}
table th {
    background: #007bff;
    color: #fff;
}
table tr:nth-child(even) {
    background: #f9f9f9;
}
table tr:hover {
    background: #f1f1f1;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
img {
    border-radius: 5px;
}
.actions a {
    margin: 0 5px;
}
</style>
</head>
<body>
<div class="container">
    <h2>Company Structure</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Heading:</label>
        <input type="text" name="heading" value="<?= htmlspecialchars($structure_data['heading']) ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($structure_data['description']) ?></textarea>

        <label>Background Image:</label>
        <?php if (!empty($structure_data['background_image'])): ?>
            <img src="<?= $uploadFolder . htmlspecialchars($structure_data['background_image']) ?>" width="200" alt="Background"><br><br>
        <?php endif; ?>
        <input type="file" name="background_image">

        <button type="submit" name="update_structure">Update Company Structure</button>
    </form>

    <h2>Locations Management</h2>
    <a href="add_location.php">+ Add New Location</a>
    <table>
        <tr>
            <th>Image</th>
            <th>City</th>
            <th>Country</th>
            <th>Popup Image</th>
            <th>Show on Home</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($locations_result)) { ?>
        <tr>
            <td>
                <?php if (!empty($row['image_path'])): ?>
                    <img src="<?= $uploadFolder . htmlspecialchars(basename($row['image_path'])) ?>" width="80" alt="<?= htmlspecialchars($row['city']) ?>">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['city']) ?></td>
            <td><?= htmlspecialchars($row['country']) ?></td>
            <td>
                <?php if (!empty($row['background_image_path'])): ?>
                    <img src="<?= $uploadFolder . htmlspecialchars(basename($row['background_image_path'])) ?>" width="80" alt="Popup">
                <?php endif; ?>
            </td>
            <td>
                <a href="?toggle_home=<?= $row['id'] ?>">
                    <?= $row['show_on_home'] ? '✅ Yes' : '❌ No' ?>
                </a>
            </td>
            <td class="actions">
                <a href="edit_location.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this location and all related data?')">Delete</a> |
                <a href="manage_departments.php?id=<?= $row['id'] ?>">Departments</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
