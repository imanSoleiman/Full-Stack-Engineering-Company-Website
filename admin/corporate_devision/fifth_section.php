<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';

$message = ''; // Success message

// Fetch section data
$sectionData = $conn->query("SELECT * FROM corporate_fifth_section WHERE id = 1")->fetch_assoc();

// Update section header and paragraph
if (isset($_POST['save_section'])) {
    $title = $_POST['section_title'];
    $paragraph = $_POST['section_paragraph'];
    $stmt = $conn->prepare("UPDATE corporate_fifth_section SET section_title=?, section_paragraph=? WHERE id=1");
    $stmt->bind_param("ss", $title, $paragraph);
    $stmt->execute();
    $sectionData = $conn->query("SELECT * FROM corporate_fifth_section WHERE id = 1")->fetch_assoc();
    $message = 'Section updated successfully!';
}

// Add new card
if (isset($_POST['add_card'])) {
    $card_title = $_POST['card_title'];
    $card_text = $_POST['card_text'];
    $stmt = $conn->prepare("INSERT INTO corporate_fifth_cards (card_title, card_text) VALUES (?, ?)");
    $stmt->bind_param("ss", $card_title, $card_text);
    $stmt->execute();
    $message = 'Card added successfully!';
}

// Delete card
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM corporate_fifth_cards WHERE id=$id");
    $message = 'Card deleted successfully!';
}

// Update card inline
if (isset($_POST['update_card'])) {
    $id = intval($_POST['card_id']);
    $title = $_POST['card_title'];
    $text = $_POST['card_text'];
    $stmt = $conn->prepare("UPDATE corporate_fifth_cards SET card_title=?, card_text=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $text, $id);
    $stmt->execute();
    $message = 'Card updated successfully!';
}

// Get all cards
$cards = $conn->query("SELECT * FROM corporate_fifth_cards");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Environmental Responsibility Section</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; }
.container { max-width: 800px; margin: 50px auto; background-color: #fff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
h2, h3 { color: #333; }
h2 { text-align: center; margin-bottom: 25px; }
form label { display: block; margin-bottom: 6px; font-weight: 600; color: #555; }
form input[type="text"], form textarea { width: 100%; padding: 12px 15px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; transition: 0.3s; }
form input[type="text"]:focus, form textarea:focus { border-color: #007bff; outline: none; box-shadow: 0 0 5px rgba(0,123,255,0.2); }
form textarea { resize: vertical; }
button { display: inline-block; padding: 12px 20px; background-color: #007bff; color: #fff; font-size: 16px; font-weight: bold; border: none; border-radius: 10px; cursor: pointer; transition: 0.3s; margin-top: 5px; }
button:hover { background-color: #0056b3; }
.card { border: 1px solid #e0e0e0; border-radius: 10px; padding: 15px 20px; margin-bottom: 15px; background-color: #f9fafb; display: flex; flex-wrap: wrap; align-items: center; }
.card form { flex-grow: 1; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.card .info { flex-grow: 1; }
.card .info strong { font-size: 16px; display: block; margin-bottom: 5px; }
.card .info p { margin: 0; color: #555; }
.card a { color: #ff4d4f; text-decoration: none; font-weight: bold; margin-left: 15px; }
.card a:hover { text-decoration: underline; }
hr { border: 0; border-top: 1px solid #e0e0e0; margin: 30px 0; }
.message { padding: 12px 20px; background-color: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
@media (max-width: 768px) { .container { padding: 20px; } .card { flex-direction: column; align-items: flex-start; } .card a { margin-top: 10px; margin-left: 0; } }
</style>
</head>
<body>

<div class="container">
<h2>Edit Environmental Responsibility Section</h2>

<?php if ($message): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<!-- Section Form -->
<form method="POST">
    <label>Section Title:</label>
    <input type="text" name="section_title" value="<?= htmlspecialchars($sectionData['section_title']) ?>" required>
    <label>Paragraph:</label>
    <textarea name="section_paragraph" rows="4" required><?= htmlspecialchars($sectionData['section_paragraph']) ?></textarea>
    <button type="submit" name="save_section">Save Section</button>
</form>

<hr>

<!-- Add Card Form -->
<h3>Add New Card</h3>
<form method="POST">
    <label>Card Title:</label>
    <input type="text" name="card_title" required>
    <label>Card Text:</label>
    <textarea name="card_text" rows="3" required></textarea>
    <button type="submit" name="add_card">Add Card</button>
</form>

<hr>

<!-- Existing Cards with inline edit -->
<h3>Existing Cards</h3>
<?php while ($card = $cards->fetch_assoc()): ?>
    <div class="card">
        <form method="POST">
            <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
            <input type="text" name="card_title" value="<?= htmlspecialchars($card['card_title']) ?>" required>
            <textarea name="card_text" rows="2" required><?= htmlspecialchars($card['card_text']) ?></textarea>
            <button type="submit" name="update_card">Update</button>
            <a href="?delete=<?= $card['id'] ?>" onclick="return confirm('Are you sure you want to delete this card?')">Delete</a>
        </form>
    </div>
<?php endwhile; ?>

</div>
</body>
</html>
