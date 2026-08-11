<?php
session_start();
include('../config.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $hours = $_POST['working_hours'] ?? '';

    $updates = ['email' => $email, 'phone' => $phone, 'working_hours' => $hours];
    foreach ($updates as $key => $value) {
        $stmt = $conn->prepare("UPDATE navbar SET setting_value=? WHERE setting_key=?");
        $stmt->bind_param("ss", $value, $key);
        $stmt->execute();
    }

    // Handle social links
    $social_platforms = $_POST['social_platform'] ?? [];
    $social_links = $_POST['social_link'] ?? [];

    $conn->query("DELETE FROM navbar_social");

    $faMap = [
        'facebook' => 'fab fa-facebook-f',
        'instagram' => 'fab fa-instagram',
        'linkedin' => 'fab fa-linkedin-in',
        'twitter' => 'fab fa-twitter'
    ];

    for ($i = 0; $i < count($social_platforms); $i++) {
        $platform = trim($social_platforms[$i]);
        $link = trim($social_links[$i]);
        if ($platform && $link) {
            $iconClass = $faMap[$platform] ?? '';
            if ($iconClass == '') continue;
            $stmt = $conn->prepare("INSERT INTO navbar_social (icon, link) VALUES (?, ?)");
            $stmt->bind_param("ss", $iconClass, $link);
            $stmt->execute();
        }
    }

    header("Location: update_nav.php?success=1");
    exit;
}

// Fetch navbar values
$sql = "SELECT setting_key, setting_value FROM navbar";
$result = $conn->query($sql);
$navbar = [];
while ($row = $result->fetch_assoc()) {
    $navbar[$row['setting_key']] = $row['setting_value'];
}

// Fetch social links
$social_result = $conn->query("SELECT * FROM navbar_social ORDER BY id ASC");
$social_links = [];
while ($row = $social_result->fetch_assoc()) {
    $social_links[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Navbar Info</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 750px;
    margin: 60px auto;
    background: #fff;
    border-radius: 12px;
    padding: 40px 30px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #1f2937;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #4b5563;
}

input, select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 15px;
    transition: all 0.3s;
}
input:focus, select:focus {
    border-color: #3b82f6;
    outline: none;
}

button {
    padding: 12px 25px;
    font-size: 16px;
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
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #10b981;
    text-align: center;
}

.social-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.social-row input {
    flex: 1;
}
.social-row select {
    flex: 1;
}
.social-row button {
    flex: 0 0 auto;
    background: #ef4444;
    color: #fff;
}
.social-row button:hover {
    background: #dc2626;
}

.platform-add {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}
.platform-add select {
    flex: 1;
}
</style>
<script>
let usedPlatforms = new Set();

// Add social row
function addSocialRow(platform='', link='') {
    if (!platform || usedPlatforms.has(platform)) return;
    usedPlatforms.add(platform);
    const container = document.getElementById('social-container');
    const div = document.createElement('div');
    div.className = 'social-row';
    div.innerHTML = `
        <select name="social_platform[]" required>
            <option value="">--Select Platform--</option>
            <option value="facebook" ${platform==='facebook'?'selected':''}>Facebook</option>
            <option value="instagram" ${platform==='instagram'?'selected':''}>Instagram</option>
            <option value="linkedin" ${platform==='linkedin'?'selected':''}>LinkedIn</option>
            <option value="twitter" ${platform==='twitter'?'selected':''}>Twitter</option>
        </select>
        <input type="url" name="social_link[]" placeholder="Social link URL" value="${link}" required>
        <button type="button" onclick="removeSocialRow(this,'${platform}')">Remove</button>
    `;
    container.appendChild(div);
}

function removeSocialRow(btn, platform) {
    btn.parentElement.remove();
    usedPlatforms.delete(platform);
}

function addSocialRowFromSelect() {
    const select = document.getElementById('platform-select');
    const platform = select.value;
    if (!platform) return alert('Please select a platform');
    addSocialRow(platform);
    select.value = '';
}

// Preload existing social links
window.onload = function() {
    <?php foreach ($social_links as $social):
        $platform = strpos($social['icon'], "facebook")!==false?"facebook":
                    (strpos($social['icon'], "instagram")!==false?"instagram":
                    (strpos($social['icon'], "linkedin")!==false?"linkedin":"twitter"));
    ?>
    addSocialRow('<?= $platform ?>','<?= htmlspecialchars($social['link']) ?>');
    <?php endforeach; ?>
};
</script>
</head>
<body>

<div class="container">
    <h2>Edit Navbar Info</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="success">✅ Navbar updated successfully!</div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($navbar['email'] ?? '') ?>" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($navbar['phone'] ?? '') ?>" required>

        <label>Working Hours:</label>
        <input type="text" name="working_hours" value="<?= htmlspecialchars($navbar['working_hours'] ?? '') ?>" required>

        <label>Social Links:</label>
        <div class="platform-add">
            <select id="platform-select">
                <option value="">-- Select Platform --</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
                <option value="linkedin">LinkedIn</option>
                <option value="twitter">Twitter</option>
            </select>
            <button type="button" onclick="addSocialRowFromSelect()">Add</button>
        </div>
        <div id="social-container"></div>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
