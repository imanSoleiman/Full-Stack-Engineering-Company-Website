<?php
header('Content-Type: application/json');
require_once './config.php'; 

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'No location ID provided']);
    exit;
}

$location_id = intval($_GET['id']);

// Fetch city, country, image_path, total staff count, and background image
$stmt = $conn->prepare("
  SELECT l.id, l.city, l.country, l.image_path,
         COALESCE(SUM(d.people_count), 0) AS staff_count,
         ld.background_image_path
  FROM locations l
  LEFT JOIN departments d ON d.location_id = l.id
  LEFT JOIN location_details ld ON ld.location_id = l.id
  WHERE l.id = ?
  GROUP BY l.id
");

$stmt->bind_param("i", $location_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Location not found']);
    exit;
}

$location = $result->fetch_assoc();
// Before returning JSON
$location['image_path'] = './assets/structure/' . $location['image_path'];
$location['background_image_path'] = './assets/structure/' . $location['background_image_path'];

// Fetch departments and include category + pluralized label
$stmt = $conn->prepare("
    SELECT name, people_count AS staff_count, category
    FROM departments
    WHERE location_id = ?
");
$stmt->bind_param("i", $location_id);
$stmt->execute();
$departments_result = $stmt->get_result();

// Helper function to pluralize category
function pluralizeCategory($category, $count) {
    // Trim and lowercase to avoid casing issues
    $category = trim($category);

    // Singular if count is 1
    if ($count == 1) {
        return ucfirst($category);
    }

    // Handle irregular exceptions
    $exceptions = [
        'person' => 'Persons',
        'child' => 'Children',
        'mouse' => 'Mice'
        // Add more if needed
    ];

    // Return exception plural or default 's' plural
    return $exceptions[strtolower($category)] ?? ucfirst($category) . 's';
}


$departments = [];
while ($row = $departments_result->fetch_assoc()) {
    $row['label'] = pluralizeCategory($row['category'], $row['staff_count']);
    $departments[] = $row;
}

// Output JSON
echo json_encode([
    'success' => true,
    'location' => $location,
    'departments' => $departments
]);
