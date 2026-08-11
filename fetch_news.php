<?php
include "./config.php";

// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$search = $_GET['search'] ?? '';
$yearFilter = $_GET['year'] ?? 'all';
$categoryFilter = $_GET['category'] ?? 'all';

$months = [
    'january'=>1,'jan'=>1,'february'=>2,'feb'=>2,
    'march'=>3,'mar'=>3,'april'=>4,'apr'=>4,'may'=>5,
    'june'=>6,'jun'=>6,'july'=>7,'jul'=>7,'august'=>8,'aug'=>8,
    'september'=>9,'sep'=>9,'october'=>10,'oct'=>10,'november'=>11,'nov'=>11,
    'december'=>12,'dec'=>12
];

// Categories mapping
$categoriesArr = [];
$catResult = $conn->query("SELECT id, name FROM news_categories");
while($catRow = $catResult->fetch_assoc()) {
    $categoriesArr[strtolower($catRow['name'])] = $catRow['id'];
}

// Base query
$query = "SELECT n.*, y.year AS year_name 
          FROM news_cards n
          JOIN news_years y ON n.year_id = y.id
          WHERE 1=1";

// Filters
if ($yearFilter !== 'all') $query .= " AND n.date_year = ".intval($yearFilter);
if ($categoryFilter !== 'all') $query .= " AND n.category_id = ".intval($categoryFilter);

// Exact day+month+year or day+month search
$exactMatched = false;
if (!empty($search)) {
    $searchLower = strtolower(str_replace(' ', '', $search)); // remove spaces

    // Match D/M/Y or M/D/Y
    if (preg_match('/^(\d{1,2})([a-z]+)(\d{4})$/i', $searchLower, $m) ||
        preg_match('/^([a-z]+)(\d{1,2})(\d{4})$/i', $searchLower, $m)) {
        $day = intval($m[1]) ?? intval($m[2]);
        $monthWord = $m[2] ?? $m[1];
        $year = intval($m[3]);
        if(isset($months[$monthWord])) {
            $month = $months[$monthWord];
            $query .= " AND n.date_day = $day AND n.date_month = $month AND n.date_year = $year";
            $exactMatched = true;
        }
    }
    // Match D/M or M/D (without year)
    elseif (preg_match('/^(\d{1,2})([a-z]+)$/i', $searchLower, $m) ||
            preg_match('/^([a-z]+)(\d{1,2})$/i', $searchLower, $m)) {
        $day = intval($m[1]) ?? intval($m[2]);
        $monthWord = $m[2] ?? $m[1];
        if(isset($months[$monthWord])) {
            $month = $months[$monthWord];
            $query .= " AND n.date_day = $day AND n.date_month = $month";
            $exactMatched = true;
        }
    }
}

// Normal search fallback
if (!$exactMatched && !empty($search)) {
    $words = preg_split('/\s+/', $conn->real_escape_string($search));
    $andParts = [];

    foreach ($words as $word) {
        $word = trim($word);
        if ($word === '') continue;
        $wordLower = strtolower($word);
        $orParts = [];

        if (is_numeric($wordLower)) {
            $num = intval($wordLower);
            $orParts[] = "(n.date_day = $num OR n.date_year = $num OR n.date_month = $num)";
        } elseif (isset($months[$wordLower])) {
            $monthNum = $months[$wordLower];
            $orParts[] = "n.date_month = $monthNum";
        }

        $orParts[] = "n.title LIKE '%$wordLower%'";
        foreach ($categoriesArr as $catName => $catId) {
            if (strpos($catName, $wordLower) !== false) $orParts[] = "n.category_id = $catId";
        }

        if(count($orParts) > 0) $andParts[] = '(' . implode(' OR ', $orParts) . ')';
    }

    if(count($andParts) > 0) $query .= " AND " . implode(' AND ', $andParts);
}

$query .= " ORDER BY n.date_year DESC, n.date_month DESC, n.date_day DESC";

$result = $conn->query($query);
if (!$result) die("SQL Error: " . $conn->error);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()):
        $monthName = date('F', mktime(0,0,0,$row['date_month'],10));
?>
<div class="news-card-container gray">
    <div class="top-month">
        <h2><?= $row['date_day'] ?></h2>
        <h1><?= $monthName ?></h1>
    </div>
    <img src="./assets/news/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>">
    <div class="news-details">
        <div class="new-details-text">
            <p><?= $row['date_day'] . ' ' . $monthName . ' ' . $row['date_year'] ?></p>
            <h2><?= htmlspecialchars($row['title']) ?></h2>
        </div>
        <div class="new-arrow">
            <a href="news_details.php?id=<?= $row['id'] ?>">
                <img src="./images/right-up.png" alt="View News">
            </a>
        </div>
    </div>
</div>
<?php
    endwhile;
} else {
    echo "<p>No results found.</p>";
}
?>
