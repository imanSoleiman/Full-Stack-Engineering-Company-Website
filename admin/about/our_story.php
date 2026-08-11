<?php
session_start();
include("../../config.php");
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}



// Fetch current section row (we assume there's only 1 row, id=1)
$section = $conn->query("SELECT * FROM our_story_section LIMIT 1")->fetch_assoc();

// If form submitted → update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['section_title'];
    $subtitle = $_POST['section_subtitle'];
    $paragraph = $_POST['section_paragraph'];

    // If no row exists, insert one
    if (!$section) {
        $stmt = $conn->prepare("INSERT INTO our_story_section (section_title, section_subtitle, section_paragraph) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $subtitle, $paragraph);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE our_story_section SET section_title=?, section_subtitle=?, section_paragraph=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $subtitle, $paragraph, $section['id']);
        $stmt->execute();
    }
    header("Location: our_story.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Our Story Section</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    form { max-width: 600px; margin: auto; display: flex; flex-direction: column; gap: 10px; }
    input, textarea { padding: 8px; font-size: 14px; }
    textarea { height: 120px; resize: vertical; }
    button { background: #007bff; color: white; border: none; padding: 10px; cursor: pointer; }
    button:hover { background: #0056b3; }
    .msg { color: green; font-weight: bold; margin-bottom: 15px; }
  </style>
</head>
<body>

  <h2>Manage Our Story Section Header</h2>

  <?php if (isset($_GET['success'])): ?>
    <p class="msg">✅ Section updated successfully!</p>
  <?php endif; ?>

  <form method="post">
    <label>Section Title:</label>
    <input type="text" name="section_title" value="<?= htmlspecialchars($section['section_title'] ?? '') ?>" required>

    <label>Section Subtitle:</label>
    <input type="text" name="section_subtitle" value="<?= htmlspecialchars($section['section_subtitle'] ?? '') ?>" required>

    <label>Section Paragraph:</label>
    <textarea name="section_paragraph" required><?= htmlspecialchars($section['section_paragraph'] ?? '') ?></textarea>

    <button type="submit">Save Changes</button>
  </form>

</body>
</html>
