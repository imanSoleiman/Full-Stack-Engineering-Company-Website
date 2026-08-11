<?php
session_start();
include("../../config.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}


// Fetch current vision section (only 1 row)
$vision = $conn->query("SELECT * FROM vision_section LIMIT 1")->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $heading = trim($_POST['heading']);
    $subheading = trim($_POST['subheading']);

    if ($vision) {
        $stmt = $conn->prepare("UPDATE vision_section SET heading=?, subheading=? WHERE id=?");
        $stmt->bind_param("ssi", $heading, $subheading, $vision['id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO vision_section (heading, subheading) VALUES (?, ?)");
        $stmt->bind_param("ss", $heading, $subheading);
        $stmt->execute();
    }

    header("Location: manage_vision.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Vision Section</title>
<style>
body { font-family: Arial; padding: 20px; }
form { max-width: 600px; margin: auto; display: flex; flex-direction: column; gap: 10px; }
input, textarea { padding: 8px; font-size: 14px; }
textarea { height: 80px; resize: vertical; }
button { padding: 10px; background: #007bff; color: #fff; border: none; cursor: pointer; }
button:hover { background: #0056b3; }
.msg { color: green; font-weight: bold; margin-bottom: 10px; }
</style>
</head>
<body>

<h2>Manage Vision Section</h2>

<?php if(isset($_GET['success'])): ?>
<p class="msg">✅ Vision section updated successfully!</p>
<?php endif; ?>

<form method="post">
    <label>Main Heading:</label>
    <input type="text" name="heading" value="<?= htmlspecialchars($vision['heading'] ?? '') ?>" required>

    <label>Subheading:</label>
    <textarea name="subheading" required><?= htmlspecialchars($vision['subheading'] ?? '') ?></textarea>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
