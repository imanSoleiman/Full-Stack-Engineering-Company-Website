<?php
session_start();
include("../config.php"); // adjust path

// 1. If admin not logged in → redirect to login
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}


$message = ""; // Variable to store feedback

// Handle form submission
if(isset($_POST['position_name']) && $_POST['position_name'] != '') {
    $position_name = $conn->real_escape_string($_POST['position_name']);

    // Check if position already exists
    $check = $conn->query("SELECT id FROM job_positions WHERE position_name='$position_name'");
    if($check->num_rows > 0){
        $message = "This position already exists!";
    } else {
        $conn->query("INSERT INTO job_positions (position_name) VALUES ('$position_name')");
        $message = "Position added successfully!";
    }
}

// Handle delete action
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM job_positions WHERE id=$id");
    header("Location: add_position.php");
    exit();
}

// Fetch existing positions
$result = $conn->query("SELECT * FROM job_positions ORDER BY position_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Job Positions</title>
<style>
body { font-family: Arial; padding: 20px; background: #f4f4f4; }
.container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; }
h2 { text-align: center; }
form input[type=text] { width: 70%; padding: 10px; margin-right: 10px; }
form button { padding: 10px 20px; background: #E43636; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
form button:hover { background: #b32b2b; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
table, th, td { border: 1px solid #ccc; }
th, td { padding: 10px; text-align: left; }
a.delete { color: red; text-decoration: none; }
.message { margin: 10px 0; padding: 10px; border-radius: 5px; }
.success { background-color: #d4edda; color: #155724; }
.error { background-color: #f8d7da; color: #721c24; }
</style>
</head>
<body>
<div class="container">
    <h2>Manage Job Positions</h2>

    <!-- Display message -->
    <?php if($message != ""): ?>
        <div class="message <?php echo ($check->num_rows > 0) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="position_name" placeholder="New Position" required>
        <button type="submit">Add Position</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Position Name</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['position_name']); ?></td>
            <td><a class="delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this position?');">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
