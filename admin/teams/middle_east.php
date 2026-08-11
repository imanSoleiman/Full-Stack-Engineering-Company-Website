<?php
include '../../config.php'; 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch section content
$section = $conn->query("SELECT * FROM middle_east_section LIMIT 1")->fetch_assoc();

// Fetch countries
$countries = $conn->query("SELECT * FROM middle_east_countries");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Middle East Section</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 1000px;
        margin: auto;
    }

    h1 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 40px;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    .card h2 {
        color: #2563eb;
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 22px;
    }

    .card p {
        font-size: 15px;
        color: #333;
        line-height: 1.6;
    }

    .card img {
        max-width: 100%;
        border-radius: 8px;
        margin-top: 15px;
        border: 1px solid #ddd;
    }

    .card a.edit-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 15px;
        background: #10b981;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        transition: background 0.3s;
    }

    .card a.edit-btn:hover {
        background: #059669;
    }

    /* Country list */
    ul {
        list-style-type: none;
        padding: 0;
        margin-top: 15px;
    }

    ul li {
        background: #f9fafb;
        margin-bottom: 10px;
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #e5e7eb;
        transition: background 0.2s;
    }

    ul li:hover {
        background: #f3f4f6;
    }

    .country-actions a {
        margin-left: 10px;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 13px;
        text-decoration: none;
        color: #fff;
        transition: 0.3s;
    }

    .country-actions a.edit {
        background: #2563eb;
    }

    .country-actions a.edit:hover {
        background: #1d4ed8;
    }

    .country-actions a.delete {
        background: #dc3545;
    }

    .country-actions a.delete:hover {
        background: #b91c1c;
    }

    .add-btn {
        display: inline-block;
        margin-bottom: 15px;
        padding: 8px 16px;
        background: #10b981;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        transition: background 0.3s;
    }

    .add-btn:hover {
        background: #059669;
    }

    @media (max-width: 600px) {
        .card {
            padding: 15px 20px;
        }

        ul li {
            flex-direction: column;
            align-items: flex-start;
        }

        .country-actions {
            margin-top: 8px;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h1><i class="fa-solid fa-globe"></i> Manage Middle East Section</h1>

    <!-- Section content -->
    <div class="card">
        <h2><?php echo htmlspecialchars($section['header']); ?></h2>
        <p><?php echo htmlspecialchars($section['content']); ?></p>
        <?php if ($section['image_path']) { 
            $imagePath = "../../assets/teams/" . $section['image_path'];
        ?>
            <img src="<?php echo $imagePath; ?>" alt="Section Image">
        <?php } ?>
        <a href="edit_section.php?id=<?php echo $section['id']; ?>" class="edit-btn"><i class="fa-solid fa-pen-to-square"></i> Edit Section</a>
    </div>

    <!-- Country list -->
    <div class="card">
        <h2>Countries</h2>
        <a href="add_country.php" class="add-btn"><i class="fa-solid fa-plus"></i> Add Country</a>
        <ul>
            <?php while($row = $countries->fetch_assoc()) { ?>
                <li>
                    <div><strong><?php echo htmlspecialchars($row['country_name']); ?></strong> - <?php echo htmlspecialchars($row['description']); ?></div>
                    <div class="country-actions">
                        <a href="edit_country.php?id=<?php echo $row['id']; ?>" class="edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <a href="delete_country.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this country?')" class="delete"><i class="fa-solid fa-trash"></i> Delete</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
</body>
</html>
