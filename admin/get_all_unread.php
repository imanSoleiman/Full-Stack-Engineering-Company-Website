<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

try {

    include('../config.php');

    if (!isset($_SESSION['admin_logged_in'])) {
        http_response_code(401);

        echo json_encode([
            'messages' => [],
            'applications' => [],
            'error' => 'Unauthorized'
        ]);

        exit;
    }

    // Messages
    $messages = [];

    $msgResult = $conn->query("
        SELECT id, name, email, subject
        FROM contact_messages
        WHERE is_read = 0
        ORDER BY created_at DESC
        LIMIT 5
    ");

    while ($row = $msgResult->fetch_assoc()) {
        $messages[] = $row;
    }

    // Applications
    $applications = [];

    $appResult = $conn->query("
        SELECT id, first_name, last_name, email, position
        FROM job_applications
        WHERE is_read = 0
        ORDER BY submission_date DESC
        LIMIT 5
    ");

    while ($row = $appResult->fetch_assoc()) {
        $applications[] = [
            'id' => $row['id'],
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'email' => $row['email'],
            'position' => $row['position']
        ];
    }

    echo json_encode([
        'messages' => $messages,
        'applications' => $applications
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'messages' => [],
        'applications' => [],
        'error' => 'Unable to load notifications'
    ]);
}