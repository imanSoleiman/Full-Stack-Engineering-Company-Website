<?php
session_start();

include('../config.php');

// Only logged-in admins can access
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch all messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Contact Messages</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* General Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 20px;
}
h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

/* Table Container */
.table-container {
    overflow-x: auto;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
table th, table td {
    padding: 14px 16px;
    border-bottom: 1px solid #e0e0e0;
    text-align: left;
}
table th {
    background-color: #007bff;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
}
tr:nth-child(even) {
    background-color: #f9f9f9;
}
tr:hover {
    background-color: #f1faff;
}

/* Highlight unread messages */
tr.new-message {
    font-weight: bold;
    background-color: #fff0f0;
}

/* Status Labels */
td span {
    font-weight: bold;
}
.status-new {
    color: #d9534f;
}
.status-read {
    color: #28a745;
}

/* Responsive */
@media (max-width: 768px) {
    table th, table td {
        padding: 10px;
        font-size: 14px;
    }
    .contact-right{
        width:100%;
    }
}
</style>
</head>
<body>

<h1>Contact Messages</h1>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr class="<?= $row['is_read'] == 0 ? 'new-message' : '' ?>">
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['subject']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <?php if($row['is_read'] == 0): ?>
                    <span class="status-new">New</span>
                <?php else: ?>
                    <span class="status-read">Read</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7" style="text-align:center; padding:15px;">No messages found</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

</body>
</html>
<?php $conn->close(); ?>
