<?php
session_start();
include('../config.php');
if (!isset($_SESSION['admin_logged_in'])) exit;

// Fetch up to 5 unread messages
$result = $conn->query("SELECT * FROM contact_messages WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5");

$messages = [];
while($row = $result->fetch_assoc()){
    $messages[] = [
        'id' => $row['id'],
        'name' => htmlspecialchars($row['name']),
        'email' => htmlspecialchars($row['email']),
        'subject' => htmlspecialchars($row['subject'])
    ];
}

echo json_encode($messages);
