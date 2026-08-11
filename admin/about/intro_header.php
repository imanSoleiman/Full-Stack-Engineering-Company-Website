<?php
session_start(); 

include("../../config.php");
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch current headings
$home = $conn->query("SELECT * FROM home_section LIMIT 1")->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first = trim($_POST['first_heading']);
    $special = trim($_POST['special_heading']);
    $last = trim($_POST['last_heading']);

    if ($home) {
        $stmt = $conn->prepare("UPDATE home_section SET first_heading=?, special_heading=?, last_heading=? WHERE id=?");
        $stmt->bind_param("sssi", $first, $special, $last, $home['id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO home_section (first_heading, special_heading, last_heading) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $first, $special, $last);
        $stmt->execute();
    }

    header("Location: intro_header.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Home Section</title>
<style>
    /* Reset some default styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7fa;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        background: #fff;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        max-width: 600px;
        width: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .container:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        font-size: 28px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    label {
        font-weight: 600;
        color: #555;
    }

    input {
        padding: 12px 15px;
        font-size: 16px;
        border: 1.5px solid #ddd;
        border-radius: 8px;
        transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
        outline: none;
    }

    button {
        padding: 14px;
        font-size: 16px;
        font-weight: 600;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    button:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }

    .success-message {
        text-align: center;
        background: #e6ffed;
        color: #2b7a0b;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #a6f0a0;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Home Section</h2>

    <?php if(isset($_GET['success'])): ?>
        <div class="success-message">✅ Home section updated successfully!</div>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>First Heading:</label>
            <input type="text" name="first_heading" value="<?= htmlspecialchars($home['first_heading'] ?? '') ?>" required>
        </div>

        <div>
            <label>Special Heading:</label>
            <input type="text" name="special_heading" value="<?= htmlspecialchars($home['special_heading'] ?? '') ?>" required>
        </div>

        <div>
            <label>Last Heading:</label>
            <input type="text" name="last_heading" value="<?= htmlspecialchars($home['last_heading'] ?? '') ?>" required>
        </div>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
