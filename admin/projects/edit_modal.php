<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch project and popup data
$query = "
  SELECT p.*, pp.popup_description, pp.popup_key_features
  FROM projects p
  LEFT JOIN project_popups pp ON pp.project_id = p.id
  WHERE p.id = $id
";
$project = $conn->query($query)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $key_features_lines = array_filter(array_map('trim', explode("\n", $_POST['key_features'])));
    $key_features_json = json_encode($key_features_lines);

    $popupCheck = $conn->query("SELECT project_id FROM project_popups WHERE project_id = $id")->fetch_assoc();

    if ($popupCheck) {
        $stmt = $conn->prepare("UPDATE project_popups SET popup_description=?, popup_key_features=? WHERE project_id=?");
        $stmt->bind_param("ssi", $description, $key_features_json, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO project_popups (project_id, popup_description, popup_key_features) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $description, $key_features_json);
        $stmt->execute();
    }

    echo "<p style='color:green; text-align:center;'>✅ Project popup updated successfully!</p>";
}

// Convert JSON back to multiline text
$key_features_text = '';
if (!empty($project['popup_key_features'])) {
    $features_array = json_decode($project['popup_key_features'], true);
    if (is_array($features_array)) $key_features_text = implode("\n", $features_array);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Project Popup Data</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 20px;
}
.container {
    max-width: 700px;
    margin: 40px auto;
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}
p.project-info {
    font-weight: 600;
    color: #555;
    margin-bottom: 15px;
}
form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
}
form textarea {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    resize: vertical;
    transition: 0.3s;
}
form textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0,123,255,0.2);
}
button {
    display: inline-block;
    padding: 12px 25px;
    background-color: #007bff;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background-color: #0056b3;
}
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }
}
</style>
</head>
<body>

<div class="container">
    <h2>Edit Project Popup Data</h2>

    <p class="project-info"><strong>Name:</strong> <?= htmlspecialchars($project['name']) ?></p>
    <p class="project-info"><strong>Location:</strong> <?= htmlspecialchars($project['location']) ?></p>

    <form method="POST">
        <label>Description:</label>
        <textarea name="description" rows="5"><?= htmlspecialchars($project['popup_description']) ?></textarea>

        <label>Key Features (one per line):</label>
        <textarea name="key_features" rows="5"><?= htmlspecialchars($key_features_text) ?></textarea>

        <button type="submit">Update Project Popup</button>
    </form>
</div>

</body>
</html>
