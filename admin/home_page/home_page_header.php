<?php
include('../../config.php'); // connection
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle individual section updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
    $section = $_POST['section'];
    if ($section === 'structure') {
        $header = $_POST['header'];
        $desc   = $_POST['description'];
        $stmt = $conn->prepare("UPDATE homepage_content SET header=?, description=? WHERE section=?");
        $stmt->bind_param("sss", $header, $desc, $section);
    } else {
        $header = $_POST['header'];
        $stmt = $conn->prepare("UPDATE homepage_content SET header=? WHERE section=?");
        $stmt->bind_param("ss", $header, $section);
    }
    $stmt->execute();
    $stmt->close();
    $successMsg = "✅ Updated Successfully!";
}

$result = $conn->query("SELECT * FROM homepage_content");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Homepage Content Admin</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #1f2937;
    margin-bottom: 30px;
}

.card {
    background: #fff;
    padding: 25px 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.card h3 {
    margin-top: 0;
    color: #4b5563;
    margin-bottom: 15px;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #374151;
}

input[type="text"], textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    margin-bottom: 15px;
    font-size: 14px;
    transition: all 0.3s;
}
input[type="text"]:focus, textarea:focus {
    border-color: #3b82f6;
    outline: none;
}

button {
    padding: 10px 20px;
    font-size: 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    background: linear-gradient(135deg,#4f46e5,#3b82f6);
    color: #fff;
    transition: all 0.3s;
}
button:hover {
    opacity: 0.9;
}

.success {
    background: #d1fae5;
    color: #065f46;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 1px solid #10b981;
    text-align: center;
}
</style>
</head>
<body>

<div class="container">
    <h2>Homepage Content Admin</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <h3><?= ucfirst($row['section']) ?></h3>

            <form method="POST">
                <input type="hidden" name="section" value="<?= $row['section'] ?>">

                <?php if ($row['section'] === 'structure'): ?>
                    <label>Header</label>
                    <input type="text" name="header" value="<?= htmlspecialchars($row['header']) ?>">

                    <label>Description</label>
                    <textarea name="description" rows="5"><?= htmlspecialchars($row['description']) ?></textarea>
                <?php else: ?>
                    <label>Header</label>
                    <input type="text" name="header" value="<?= htmlspecialchars($row['header']) ?>">
                <?php endif; ?>

                <button type="submit">Save Changes</button>

                <?php if (isset($successMsg)) echo "<div class='success'>{$successMsg}</div>"; ?>
            </form>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
