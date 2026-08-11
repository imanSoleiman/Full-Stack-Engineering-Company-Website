<?php 
session_start();

include('config.php');

$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$sql = "INSERT INTO contact_messages (name, email, subject, message) 
        VALUES ('$name', '$email', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = true; // store success flag
} else {
    $_SESSION['success'] = false;
}

$conn->close();
header("Location: contact.php");
exit;
?>
