<?php 
include('../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM job_applications ORDER BY submission_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Job Applicants</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 20px;
}
h2 {
    color: #1E3A8A;
    margin-bottom: 20px;
}
.table-container {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: left;
}
th {
    background: #1E90FF; /* Blue header */
    color: #fff;
    text-transform: uppercase;
    font-size: 14px;
}
tr:hover {
    background: #f0f8ff;
}
.download-btn {
    color: #1E90FF;
    font-weight: 600;
}
.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}
.status-employed {
    background: #e6f4ff;
    color: #0052cc;
}
.status-unemployed {
    background: #fdeaea;
    color: #b52b2b;
}
</style>
</head>
<body>

<h2><i class="fa fa-users"></i> Job Applicants</h2>

<div class="table-container">
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Position</th>
        <th>Start Date</th>
        <th>Employment</th>
        <th>Social Status</th>
        <th>Children</th>
        <th>Resume</th>
        <th>Submitted On</th>
    </tr>
    <?php 
    while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['position']) ?></td>
        <td><?= htmlspecialchars($row['start_date']) ?></td>
        <td>
            <span class="status-badge <?= $row['employment']=='employed' ? 'status-employed' : 'status-unemployed' ?>">
                <?= ucfirst($row['employment']) ?>
            </span>
        </td>
        <td><?= htmlspecialchars($row['social_status']) ?></td>
        <td>
            <?php if($row['social_status']=='married'): ?>
                <?= htmlspecialchars($row['num_children']) ?> Children, Gender: <?= htmlspecialchars($row['children_gender']) ?>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
        <td>
            <?php if($row['resume'] != ''): ?>
                <a href="download_resume.php?file=<?= urlencode($row['resume']) ?>" class="download-btn">
                    <i class="fa fa-download"></i> Resume
                </a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['submission_date']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

</body>
</html>
