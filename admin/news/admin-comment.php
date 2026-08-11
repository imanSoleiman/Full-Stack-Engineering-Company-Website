<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Approve comment
if(isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE news_comments SET approved=1 WHERE id=$id");
}

// Toggle show in recent
if(isset($_GET['toggle_recent'])) {
    $id = intval($_GET['toggle_recent']);
    $current = $conn->query("SELECT show_in_recent FROM news_comments WHERE id=$id")->fetch_assoc()['show_in_recent'];
    $new = $current ? 0 : 1;
    $conn->query("UPDATE news_comments SET show_in_recent=$new WHERE id=$id");
}

// Delete comment
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM news_comments WHERE id=$id");
}

// Filter by news_id if passed
if(isset($_GET['news_id'])) {
    $news_id = intval($_GET['news_id']);
    $comments = $conn->query("
        SELECT c.*, n.title 
        FROM news_comments c
        JOIN news_cards n ON c.news_id = n.id
        WHERE c.news_id = $news_id
        ORDER BY c.created_at DESC
    ");
    $filtered_title = $conn->query("SELECT title FROM news_cards WHERE id=$news_id")->fetch_assoc()['title'];
} else {
    $comments = $conn->query("
        SELECT c.*, n.title 
        FROM news_comments c
        JOIN news_cards n ON c.news_id = n.id
        ORDER BY c.created_at DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Comments Panel</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    th {
        background: #007bff;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    tr:hover {
        background: #f1f9ff;
    }
    a.action-btn {
        display: inline-block;
        padding: 6px 10px;
        margin: 2px 0;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        color: white;
        transition: 0.3s;
    }
    a.approve { background: #28a745; }
    a.approve:hover { background: #1e7e34; }
    a.toggle { background: #17a2b8; }
    a.toggle:hover { background: #117a8b; }
    a.delete { background: #dc3545; }
    a.delete:hover { background: #a71d2a; }
    @media(max-width: 768px){
        table, thead, tbody, th, td, tr {
            display: block;
        }
        tr { margin-bottom: 15px; }
        th {
            background: #007bff;
            color: white;
            position: sticky;
            top: 0;
        }
        td {
            padding-left: 50%;
            text-align: left;
            position: relative;
        }
        td::before {
            position: absolute;
            left: 15px;
            width: 45%;
            white-space: nowrap;
            font-weight: bold;
        }
        td:nth-of-type(1)::before { content: "ID"; }
        td:nth-of-type(2)::before { content: "News Title"; }
        td:nth-of-type(3)::before { content: "Name"; }
        td:nth-of-type(4)::before { content: "Email"; }
        td:nth-of-type(5)::before { content: "Comment"; }
        td:nth-of-type(6)::before { content: "Approved"; }
        td:nth-of-type(7)::before { content: "Show in Recent"; }
        td:nth-of-type(8)::before { content: "Actions"; }
    }
</style>
</head>
<body>
<div class="container">
    <h1>Admin Comments Panel <?php if(isset($filtered_title)) echo "for: " . htmlspecialchars($filtered_title); ?></h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>News Title</th>
                <th>Name</th>
                <th>Email</th>
                <th>Comment</th>
                <th>Approved</th>
                <th>Show in Recent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $comments->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['comment']) ?></td>
                <td><?= $row['approved'] ? 'Yes' : 'No' ?></td>
                <td><?= $row['show_in_recent'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a class="action-btn approve" href="?approve=<?= $row['id'] ?><?php if(isset($news_id)) echo '&news_id='.$news_id; ?>">Approve</a>
                    <a class="action-btn toggle" href="?toggle_recent=<?= $row['id'] ?><?php if(isset($news_id)) echo '&news_id='.$news_id; ?>">Toggle Recent</a>
                    <a class="action-btn delete" href="?delete=<?= $row['id'] ?><?php if(isset($news_id)) echo '&news_id='.$news_id; ?>" onclick="return confirm('Delete comment?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
