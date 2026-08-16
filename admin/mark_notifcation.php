// mark_message_read.php
<?php
require_once __DIR__ . '/session.php';
if (!isset($_SESSION['admin_logged_in'])) exit;

if(isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $conn->query("UPDATE contact_messages SET is_read = 1 WHERE id = $id");
}
echo json_encode(['success' => true]);
