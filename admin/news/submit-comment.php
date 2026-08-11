<?php
include('../../config.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $news_id = $_POST['news_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO news_comments (news_id, name, email, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $news_id, $name, $email, $comment);

    if($stmt->execute()) {
        header("Location: news_details.php?id=$news_id&msg=success");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
