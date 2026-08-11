<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Delete news
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM news_cards WHERE id=$id");
    header("Location: index.php");
    exit;
}

// Toggle homepage visibility
if(isset($_GET['toggle_home'])){
    $id = intval($_GET['toggle_home']);
    $conn->query("UPDATE news_cards SET show_on_home = IF(show_on_home=1, 0, 1) WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all news
$newsResult = $conn->query("
    SELECT n.id, n.title, n.date_day, n.date_month, n.date_year, n.image,
           y.year, c.name AS category, n.show_on_home
    FROM news_cards n
    LEFT JOIN news_years y ON n.year_id = y.id
    LEFT JOIN news_categories c ON n.category_id = c.id
    ORDER BY n.date_year DESC, n.date_month DESC, n.date_day DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>News Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 40px;
    color: #1f2937;
}
h1 {
    text-align: center;
    font-weight: 700;
    color: #1E3A8A;
    margin-bottom: 40px;
}
.add-button {
    display:inline-block; 
    padding:12px 18px; 
    background:#1E40AF; 
    color:#fff; 
    border-radius:8px; 
    font-weight:600; 
    transition:0.3s; 
    text-align:center;
}
.add-button:hover {
    background:#1e3a8a;
}
.admin-links {
    text-align:center;
    margin-bottom:40px;
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
}
.news-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:25px;
}
.news-card {
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
    transition: transform 0.25s, box-shadow 0.25s;
}
.news-card:hover {
    transform: translateY(-5px);
    box-shadow:0 12px 30px rgba(0,0,0,0.12);
}
.news-card img {
    width:100%;
    height:180px;
    object-fit:cover;
}
.news-card-content {
    padding:18px 20px;
}
.news-card-content h3 {
    margin:0 0 8px;
    font-size:1.25rem;
    color:#111827;
}
.news-card-content p {
    margin:0 0 12px;
    font-size:0.9rem;
    color:#4b5563;
}
.actions {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}
.actions a, .actions form button {
    flex:1;
    text-align:center;
    padding:8px 0;
    border-radius:6px;
    font-size:0.85rem;
    font-weight:500;
    color:#1f2937;
    background:#e5e7eb;
    border:none;
    cursor:pointer;
    transition:0.2s;
}
.actions a:hover, .actions form button:hover {
    background:#d1d5db;
}
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: .3s;
    border-radius: 24px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}
input:checked + .slider { background-color: #28a745; }
input:checked + .slider:before { transform: translateX(26px); }
</style>
</head>
<body>

<h1>News Admin Dashboard</h1>

<!-- Reminder Notice -->
<div style="max-width:700px; margin:0 auto 30px; padding:15px 20px; background:#FEF3C7; color:#92400E; border-left:5px solid #F59E0B; border-radius:8px; font-weight:500; text-align:center;">
    ⚠️ Please ensure the <strong>Year</strong> and <strong>Category</strong> exist before adding new news.
</div>

<div class="admin-links">
    <a href="add_news.php" class="add-button">+ Add News</a>
    <a href="manage_years.php" class="add-button">Manage Years</a>
    <a href="manage_categories.php" class="add-button">Manage Categories</a>
    <a href="admin-comment.php" class="add-button">Manage Comments</a>
</div>

<div class="news-grid">
<?php while($n = $newsResult->fetch_assoc()): 
    $monthName = date('M', mktime(0,0,0,$n['date_month'],10));
?>
    <div class="news-card">
        <img src="../../assets/news/<?= $n['image'] ?>" alt="<?= htmlspecialchars($n['title']) ?>">
        <div class="news-card-content">
            <h3><?= htmlspecialchars($n['title']) ?></h3>
            <p>
                <?= $n['date_day'] . ' ' . $monthName . ' ' . $n['date_year'] ?> |
                Year: <?= $n['year'] ?? 'Unassigned' ?> |
                Category: <?= htmlspecialchars($n['category'] ?? 'Unassigned') ?>
            </p>
            <div class="actions">
                <a href="edit_news.php?id=<?= $n['id'] ?>">Edit</a>
                <a href="admin-details.php?id=<?= $n['id'] ?>">Details</a>
                <a href="admin-comment.php?news_id=<?= $n['id'] ?>">View Comments</a>
                <a href="?delete=<?= $n['id'] ?>" onclick="return confirm('Delete this news?')">Delete</a>
                <form method="get" style="display:inline;">
                    <input type="hidden" name="toggle_home" value="<?= $n['id'] ?>">
                    <label class="switch">
                        <input type="checkbox" onchange="this.form.submit()" <?= $n['show_on_home'] ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </form>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
