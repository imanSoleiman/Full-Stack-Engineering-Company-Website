<?php 
include "../../config.php";
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Professional Excellence</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
/* Reset */
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #f4f6f8; color: #333; padding: 20px; }
h1 { text-align: center; margin: 40px 0 20px; color: #007bff; }
.section-header { display: flex; justify-content: space-between; align-items: center; margin: 40px 0 10px; flex-wrap: wrap; }
.section-header h2 { color: #555; font-size: 1.4rem; }
.button {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    color: #fff;
    background: #007bff;
    font-weight: 600;
    transition: 0.3s;
}
.button.red { background: #dc3545; }
.button:hover { opacity: 0.85; }
.slide-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.slide-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.slide-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.slide-card img {
    width: 100%;
    height: 160px;
    object-fit: cover;
}
.slide-card-content {
    padding: 15px;
}
.slide-card-content h3 {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 10px;
}
.slide-card-content .actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.slide-card-content .actions a {
    font-size: 0.9rem;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: #fff;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: 0.3s;
}
.slide-card-content .actions a.button { background: #007bff; }
.slide-card-content .actions a.button.red { background: #dc3545; }
.slide-card-content .actions a:hover { opacity: 0.85; }
.admin-links {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 20px;
}
.admin-links a { display: flex; align-items: center; gap: 8px; font-weight: 600; }
</style>
</head>
<body>

<h1>Professional Excellence Admin</h1>

<!-- Slides Section -->
<div class="section-header">
    <h2>Slides</h2>
    <a href="add_slide.php" class="button"><i class="fa fa-plus"></i> Add New Slide</a>
</div>

<div class="slide-grid">
    <?php
    $slides = $conn->query("SELECT * FROM professional_slides ORDER BY slide_order ASC");
    while ($s = $slides->fetch_assoc()):
    ?>
    <div class="slide-card">
        <img src="../../assets/teams/<?= htmlspecialchars($s['image']) ?>" alt="<?= htmlspecialchars($s['title']) ?>">
        <div class="slide-card-content">
            <h3><?= htmlspecialchars($s['title']) ?></h3>
            <div class="actions">
                <a href="edit_slide.php?id=<?= $s['id'] ?>" class="button"><i class="fa fa-edit"></i> Edit</a>
                <a href="add_list.php?slide_id=<?= $s['id'] ?>" class="button"><i class="fa fa-list"></i> Add List</a>
                <a href="delete_slide.php?id=<?= $s['id'] ?>" class="button red" ><i class="fa fa-trash"></i> Delete</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Admin Links -->
<div class="section-header">
    <h2>Other Sections</h2>
</div>
<div class="admin-links">
    <a href="edit_team.php" class="button"><i class="fa fa-users"></i> Edit Team Intro</a>
    <a href="team_section.php" class="button"><i class="fa fa-people-group"></i> Team Section</a>
    <a href="middle_east.php" class="button"><i class="fa fa-globe"></i> Middle East</a>
</div>

</body>
</html>
