<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch categories
$result = $conn->query("SELECT * FROM news_categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage News Categories</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7f8;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
        font-size: 28px;
        font-weight: 600;
    }

    .add-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 20px;
        background: #28a745;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .add-btn:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    th, td {
        padding: 15px 12px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    th {
        background: #007bff;
        color: white;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 0.05em;
    }

    tr:hover {
        background: #f1f9ff;
    }

    a.action-btn {
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        transition: all 0.3s;
        margin: 0 3px;
        display: inline-block;
    }

    a.edit-btn {
        background: #17a2b8;
    }

    a.edit-btn:hover {
        background: #138496;
        transform: translateY(-1px);
    }

    a.delete-btn {
        background: #dc3545;
    }

    a.delete-btn:hover {
        background: #a71d2a;
        transform: translateY(-1px);
    }

    @media(max-width: 600px){
        .container {
            padding: 20px;
        }
        th, td {
            padding: 12px 8px;
        }
        .add-btn {
            width: 100%;
            text-align: center;
        }
        a.action-btn {
            margin: 3px 0;
            display: block;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h1>Manage News Categories</h1>
    <a href="add_category.php" class="add-btn">+ Add Category</a>

    <table>
        <tr>
        
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
        
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>
                <a href="edit_category.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">Edit</a>
                <a href="delete_category.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this category? All related news will be deleted!')" class="action-btn delete-btn">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
