<?php
session_start();
include('../config.php');
if (!isset($_SESSION['admin_logged_in'])) exit;

$result = $conn->query("SELECT * FROM job_applications WHERE is_read = 0 ORDER BY submission_date DESC LIMIT 5");

$applications = [];
while($row = $result->fetch_assoc()){
    $applications[] = [
        'id' => $row['id'],
        'name' => htmlspecialchars($row['first_name'].' '.$row['last_name']),
        'email' => htmlspecialchars($row['email']),
        'position' => htmlspecialchars($row['position'])
    ];
}

echo json_encode($applications);
