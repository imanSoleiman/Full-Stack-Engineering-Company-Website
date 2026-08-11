<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include('../../config.php');

if(!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = $_GET['id'];

// Fetch category
$cat = $conn->query("SELECT * FROM spectrum_categories WHERE id=$id")->fetch_assoc();

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $stmt = $conn->prepare("UPDATE spectrum_categories SET name=? WHERE id=?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Category</title>
<style>
  /* General Reset */
  * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

  body {
    background: #f4f6f8;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 50px 20px;
  }

  .edit-category-container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 450px;
  }

  .edit-category-container h1 {
    font-size: 28px;
    color: #1e3a8a;
    margin-bottom: 25px;
    text-align: center;
  }

  form {
    display: flex;
    flex-direction: column;
  }

  label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
  }

  input[type="text"] {
    padding: 12px 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
  }

  input[type="text"]:focus {
    border-color: #2563eb;
    box-shadow: 0 0 5px rgba(37, 99, 235, 0.5);
    outline: none;
  }

  button[type="submit"] {
    background-color: #2563eb;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    padding: 12px 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  button[type="submit"]:hover {
    background-color: #1e40af;
  }

  @media (max-width: 500px) {
    .edit-category-container {
      padding: 30px 20px;
    }
  }
</style>
</head>
<body>

<div class="edit-category-container">
    <h1>Edit Category</h1>
    <form method="POST">
        <label>Category Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
        <button type="submit" name="submit">Update Category</button>
    </form>
</div>

</body>
</html>
