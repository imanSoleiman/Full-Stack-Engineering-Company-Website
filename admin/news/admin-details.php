<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])) die("News ID missing.");
$news_id = intval($_GET['id']);

// Fetch news card
$news = $conn->query("SELECT * FROM news_cards WHERE id=$news_id")->fetch_assoc();

// Fetch sections
$sections = $conn->query("SELECT * FROM news_sections WHERE news_id=$news_id ORDER BY section_order ASC");

// Fetch all comments for admin control
$comments = $conn->query("SELECT * FROM news_comments WHERE news_id=$news_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>News Card Details</title>
<style>
    /* Global Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7f9;
        margin: 0;
        padding: 0;
        color: #333;
    }
    .container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 20px;
    }
    h1, h2 {
        color: #222;
        margin-bottom: 20px;
    }

    /* Card Styles */
    .card {
        background: #fff;
        padding: 25px 30px;
        margin-bottom: 35px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }

    /* News Info */
    .card p {
        margin: 12px 0;
        font-size: 16px;
        line-height: 1.5;
    }
    .card img {
        border-radius: 10px;
        margin-top: 12px;
        max-width: 100%;
        height: auto;
        display: block;
    }

    /* Buttons */
    a.button, a.edit, a.delete {
        display: inline-block;
        padding: 10px 18px;
        margin: 6px 3px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 14px;
    }
    a.button {
        background: #007BFF;
        color: #fff;
    }
    a.button:hover { background: #0056b3; }

    a.edit {
        background: #28a745;
        color: #fff;
    }
    a.edit:hover { background: #1e7e34; }

    a.delete {
        background: #dc3545;
        color: #fff;
    }
    a.delete:hover { background: #a71d2a; }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        background: #fff;
    }
    th, td {
        padding: 14px 18px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    th {
        background: #007BFF;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
    }
    tr:hover {
        background: #f1faff;
    }

    /* Responsive Table */
    @media(max-width: 768px){
        table, thead, tbody, th, td, tr { display: block; }
        tr { margin-bottom: 18px; }
        td { padding-left: 50%; text-align: left; position: relative; }
        td::before {
            position: absolute;
            left: 15px;
            width: 45%;
            white-space: nowrap;
            font-weight: 600;
        }
        td:nth-of-type(1)::before { content: "Order"; }
        td:nth-of-type(2)::before { content: "Heading"; }
        td:nth-of-type(3)::before { content: "Content"; }
        td:nth-of-type(4)::before { content: "Actions"; }
    }
</style>
</head>
<body>
<div class="container">
    <h1>News Card Details</h1>

    <!-- News Info Card -->
    <div class="card">
        <p><strong>Title:</strong> <?= htmlspecialchars($news['title']) ?></p>
        <p><strong>Date:</strong> <?= $news['date_day'] . '/' . $news['date_month'] . '/' . $news['date_year'] ?></p>
        <p><strong>Image:</strong></p>
        <img src="../../assets/news/<?= $news['image'] ?>" alt="News Image">
    </div>

    <!-- News Sections -->
    <div class="card">
        <h2>News Sections</h2>
        <a class="button" href="add_section.php?news_id=<?= $news_id ?>">Add New Section</a>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Heading</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($sec = $sections->fetch_assoc()): ?>
                <tr>
                    <td><?= $sec['section_order'] ?></td>
                    <td><?= htmlspecialchars($sec['heading']) ?></td>
                    <td><?= htmlspecialchars(substr($sec['content'],0,100)) ?>...</td>
                    <td>
                        <a class="edit" href="edit_section.php?id=<?= $sec['id'] ?>">Edit</a>
                        <a class="delete" href="delete_section.php?id=<?= $sec['id'] ?>" onclick="return confirm('Delete this section?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Comments -->
    <div class="card">
        <h2>Comments for This News</h2>
        <a class="button" href="admin-comment.php?news_id=<?= $news_id ?>">View All Comments in Admin Panel</a>
    </div>
</div>
</body>
</html>
