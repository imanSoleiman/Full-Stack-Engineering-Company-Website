<?php
include '../../config.php';

$message = "";
$success = "";

if(isset($_POST['submit'])){
    $name = trim($_POST['name']);

    if(empty($name)){
        $message = "Category name cannot be empty.";
    } else {
        // Check if the category already exists
        $check = $conn->prepare("SELECT id FROM news_categories WHERE name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $result = $check->get_result();

        if($result->num_rows > 0){
            $message = "This category was added before!";
        } else {
            // Insert new category
            $stmt = $conn->prepare("INSERT INTO news_categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            if($stmt->execute()){
                $success = "Category added successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add News Category</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 40px;
            color: #1f2937;
        }
        h1 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #111827;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1rem;
            transition: border 0.2s;
        }
        input[type="text"]:focus {
            border-color: #2563eb;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #1e40af;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message.error {
            color: red;
        }
        .message.success {
            color: green;
        }
    </style>
</head>
<body>

<h1>Add News Category</h1>

<?php if(!empty($message)): ?>
    <div class="message error"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if(!empty($success)): ?>
    <div class="message success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST">
    <label>Category Name:</label>
    <input type="text" name="name" placeholder="Enter category name" required>
    
    <button type="submit" name="submit">Add Category</button>
</form>

</body>
</html>
