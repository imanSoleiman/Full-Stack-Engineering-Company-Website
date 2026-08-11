<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php'); // database connection

$message = ""; // message to show to the user

if (isset($_POST['delete_location'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM offices WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = "<p class='success'>Office deleted successfully!</p>";
}

// Handle add office
if(isset($_POST['add_office'])){
    $country = trim($_POST['country']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $fax = trim($_POST['fax'] ?? '');
    $pobox = trim($_POST['pobox'] ?? '');

    $checkStmt = $conn->prepare("SELECT id FROM offices WHERE LOWER(country) = LOWER(?) AND LOWER(city) = LOWER(?)");
    $checkStmt->bind_param("ss", $country, $city);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if($checkResult->num_rows > 0){
        $message = "<p class='error'>The office in <strong>$city, $country</strong> has already been added.</p>";
    } else {
        $cleanAddress = preg_replace('/,+/', '', $address);
        $cleanAddress = trim($cleanAddress);

        $query = urlencode("$cleanAddress, $city, $country");
        $url = "https://nominatim.openstreetmap.org/search?q=$query&format=json&limit=1";
        $opts = ["http" => ["header" => "User-Agent: MyApp/1.0\r\n"]];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);
        $data = json_decode($response, true);

        $lat = isset($data[0]['lat']) ? floatval($data[0]['lat']) : null;
        $lng = isset($data[0]['lon']) ? floatval($data[0]['lon']) : null;

        if(!$lat || !$lng){
            $queryFallback = urlencode("$city, $country");
            $urlFallback = "https://nominatim.openstreetmap.org/search?q=$queryFallback&format=json&limit=1";
            $responseFallback = @file_get_contents($urlFallback, false, $context);
            $dataFallback = json_decode($responseFallback, true);
            $lat = isset($dataFallback[0]['lat']) ? floatval($dataFallback[0]['lat']) : null;
            $lng = isset($dataFallback[0]['lon']) ? floatval($dataFallback[0]['lon']) : null;
        }

        if(!$lat || !$lng){
            $message = "<p class='error'>Could not find coordinates for <strong>$city, $country</strong>. Please check the address.</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO offices (country, city, address, phone, fax, pobox, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssdd", $country, $city, $address, $phone, $fax, $pobox, $lat, $lng);
            $stmt->execute();
            $message = "<p class='success'>Office in <strong>$city, $country</strong> added successfully!</p>";
        }
    }
}

$result = $conn->query("SELECT * FROM offices ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Offices</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 20px;
    color: #333;
}
h1, h2 {
    text-align: center;
    color: #007bff;
}
h2 { margin-top: 40px; }
.container {
    max-width: 1100px;
    margin: 0 auto;
}

/* Messages */
.success { color: #28a745; font-weight: bold; }
.error { color: #dc3545; font-weight: bold; }

/* Form Styling */
form#officeForm {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}
form#officeForm input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
}
form#officeForm button {
    grid-column: 1 / -1;
    padding: 12px;
    background: #007bff;
    color: #fff;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}
form#officeForm button:hover {
    background: #0056b3;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
}
table th, table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    text-align: left;
    vertical-align: middle;
}
table th {
    background: #007bff;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
}
tr:nth-child(even) { background: #f9f9f9; }
tr:hover { background: #f1faff; }

/* Action Buttons */
button.delete-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}
button.delete-btn:hover { background: #a71d2a; }

a.edit-btn {
    background: #28a745;
    color: #fff;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.3s;
}
a.edit-btn:hover { background: #1e7e34; }

/* Responsive */
@media(max-width:768px){
    table, form#officeForm {
        font-size: 14px;
    }
    form#officeForm { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="container">
<h1>Manage Offices</h1>

<h2>Add New Office</h2>
<?= $message ?>
<form id="officeForm" method="POST">
    <input type="text" name="country" placeholder="Country" required>
    <input type="text" name="city" placeholder="City" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="text" name="fax" placeholder="Fax (optional)">
    <input type="text" name="pobox" placeholder="P.O. Box (optional)">
    <button type="submit" name="add_office"><i class="fa fa-plus"></i> Add Office</button>
</form>

<h2>Existing Offices</h2>
<div style="overflow-x:auto;">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Country</th>
            <th>City</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Fax</th>
            <th>P.O. Box</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['country']) ?></td>
        <td><?= htmlspecialchars($row['city']) ?></td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['fax']) ?></td>
        <td><?= htmlspecialchars($row['pobox']) ?></td>
        <td>
            <a class="edit-btn" href="edit_office.php?id=<?= $row['id'] ?>"><i class="fa fa-edit"></i> Edit</a>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete_location" class="delete-btn" onclick="return confirm('Delete this office?')"><i class="fa fa-trash"></i> Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
</div>

</body>
</html>
