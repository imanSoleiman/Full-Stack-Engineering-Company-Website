<?php
include '../../config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = $conn->real_escape_string($search);

$sql = "SELECT id, title FROM news_cards WHERE title LIKE '%$search%' ORDER BY date_year DESC, date_month DESC, date_day DESC LIMIT 10";
$result = $conn->query($sql);

$matches = [];
while($row = $result->fetch_assoc()){
    $matches[] = ['id'=>$row['id'], 'title'=>$row['title']];
}

header('Content-Type: application/json');
echo json_encode($matches);
