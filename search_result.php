<?php
// Define pages and their possible search keywords
$pages = [
    'index.php' => ['home', 'index', 'main'],
    'about.php' => ['about', 'who we are', 'company', 'us'],
    'corporate.php' => ['corporate', 'responsibility', 'corporate responsibility'],
    'devision.php' => ['division', 'our division'],
    'structure.php' => ["company's structure", 'structure'],
    'services.php' => ['services', 'offerings', 'service'],
    'projects.php' => ['projects', 'project'],
    'contact.php' => ['contact', 'contact us'],
    'locate.php' => ['locate', 'location', 'find us'],
    'teams.php' => ['team', 'teams'],
    'careers.php' => ['careers', 'jobs', 'employment', 'apply for a job', 'job '],
    'news.php' => ['news', 'updates', 'announcements']
];

// Get the search query
$query = isset($_GET['query']) ? strtolower(trim($_GET['query'])) : '';

if ($query != '') {
    $bestMatch = '';
    $highestScore = 0;

    // Loop through all pages and their keywords
    foreach ($pages as $page => $keywords) {
        foreach ($keywords as $keyword) {
            similar_text($query, strtolower($keyword), $percent);
            if ($percent > $highestScore) {
                $highestScore = $percent;
                $bestMatch = $page;
            }
        }
    }

    // Redirect if match is reasonably close (>50%)
    if ($highestScore >= 50) {
        header("Location: $bestMatch");
        exit;
    } else {
        // No close match found
        echo "<script>alert('No matching page found'); window.history.back();</script>";
        exit;
    }
} else {
    // Empty query
    echo "<script>alert('Please type something to search'); window.history.back();</script>";
    exit;
}
?>
