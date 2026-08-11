<?php
include '../../config.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Add year
if(isset($_POST['add_year'])){
    $year = (int)$_POST['year'];
    $currentYear = date("Y");
    $minYear = 1900; // Minimum valid year

    if($year < $minYear || $year > $currentYear){
        $message = "Please enter a valid year between $minYear and $currentYear.";
    } else {
        $check = $conn->prepare("SELECT id FROM news_years WHERE year = ?");
        $check->bind_param("i", $year);
        $check->execute();
        $result = $check->get_result();

        if($result->num_rows > 0){
            $message = "You entered this year before!";
        } else {
            $stmt = $conn->prepare("INSERT INTO news_years (year) VALUES (?)");
            $stmt->bind_param("i", $year);
            $stmt->execute();
            $newYearId = $stmt->insert_id;
            header("Location: manage_years.php?year_id=$newYearId");
            exit;
        }
    }
}

// Delete year
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM news_years WHERE id=$id");
    header("Location: manage_years.php");
    exit;
}

// Fetch all years
$years = $conn->query("SELECT * FROM news_years ORDER BY year DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Years</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f2f5;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 900px;
        margin: 50px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-size: 28px;
        font-weight: 600;
    }
    form {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    input[type="number"] {
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
        width: 220px;
        transition: all 0.3s;
    }
    input[type="number"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.5);
        outline: none;
    }
    button {
        padding: 12px 25px;
        border: none;
        background: #007bff;
        color: white;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    button:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }
    .message {
        text-align: center;
        color: #dc3545;
        margin-bottom: 20px;
        font-weight: bold;
        font-size: 16px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    th, td {
        padding: 15px 12px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }
    th {
        background: #007bff;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 14px;
    }
    tr:hover {
        background: #f1f9ff;
    }
    a.delete-btn {
        color: white;
        background: #dc3545;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s;
        font-size: 14px;
    }
    a.delete-btn:hover {
        background: #a71d2a;
        transform: translateY(-1px);
    }
    @media(max-width: 600px){
        form {
            flex-direction: column;
            align-items: center;
        }
        input[type="number"], button {
            width: 100%;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h2>Manage Years</h2>

    <?php if(!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="number" name="year" placeholder="Enter Year" max="<?= date('Y') ?>" required>
        <button type="submit" name="add_year">Add Year</button>
    </form>

    <table>
        <tr>
            <th>Year</th>
            <th>Action</th>
        </tr>
        <?php while($row = $years->fetch_assoc()): ?>
            <tr>
                <td><?= $row['year'] ?></td>
                <td>
                    <a class="delete-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this year?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
