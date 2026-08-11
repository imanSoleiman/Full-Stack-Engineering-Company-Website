<?php
session_start();
include('../config.php');
if (!isset($_SESSION['admin_logged_in'])) exit;

// Fetch counts from database
$contactCount = $conn->query("SELECT COUNT(*) as total FROM contact_messages")->fetch_assoc()['total'];
$applicationCount = $conn->query("SELECT COUNT(*) as total FROM job_applications")->fetch_assoc()['total'];
$projectCount = $conn->query("SELECT COUNT(*) as total FROM projects")->fetch_assoc()['total'];
$serviceCount = $conn->query("SELECT COUNT(*) as total FROM services_cards")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard Overview</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}
body {
    background: #f5f7fa;
    color: #333;
}

/* Container */
.dashboard-container {
    max-width: 1300px;
    margin: 60px auto;
    padding: 20px;
}

/* Welcome Header */
.welcome-header {
    text-align: center;
    margin-bottom: 50px;
}
.welcome-header h1 {
    font-size: 40px;
    color: #1f2937;
    font-weight: 700;
}
.welcome-header p {
    font-size: 18px;
    color: #6b7280;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap: 25px;
    margin-bottom: 50px;
}
.stat-card {
    background: linear-gradient(135deg, #4f46e5, #3b82f6);
    color: #fff;
    border-radius: 16px;
    padding: 30px 20px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}
.stat-card i {
    font-size: 36px;
    margin-bottom: 15px;
}
.stat-card h2 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}
.stat-card p {
    font-size: 15px;
    color: rgba(255,255,255,0.85);
}

/* Quick Links */
.quick-links {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 25px;
}
.quick-link {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    text-align: center;
    padding: 30px 20px;
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.quick-link i {
    font-size: 32px;
    color: #3b82f6;
    margin-bottom: 12px;
}
.quick-link span {
    display: block;
    font-weight: 600;
    font-size: 16px;
    color: #1f2937;
}
.quick-link:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .welcome-header h1 {
        font-size: 32px;
    }
    .stat-card h2 {
        font-size: 24px;
    }
    .quick-link i {
        font-size: 28px;
    }
}
</style>
</head>
<body>

<div class="dashboard-container">
    <div class="welcome-header">
        <h1>Welcome Back, Admin!</h1>
        <p>Here’s a quick overview of your website’s key metrics and management tools.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <i class="fa-solid fa-users"></i>
            <h2><?= $contactCount ?></h2>
            <p>Contact Messages</p>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-file-lines"></i>
            <h2><?= $applicationCount ?></h2>
            <p>Applications</p>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-image"></i>
            <h2><?= $projectCount ?></h2>
            <p>Projects</p>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-briefcase"></i>
            <h2><?= $serviceCount ?></h2>
            <p>Services</p>
        </div>
    </div>

    <div class="quick-links">
        <div class="quick-link" onclick="parent.loadPage('servicesPage/services_list.php')">
            <i class="fa-solid fa-briefcase"></i>
            <span>Manage Services</span>
        </div>
        <div class="quick-link" onclick="parent.loadPage('company_structure/company_locations.php')">
            <i class="fa-solid fa-building"></i>
            <span>Company Structure</span>
        </div>
        <div class="quick-link" onclick="parent.loadPage('projects/index.php')">
            <i class="fa-solid fa-folder-plus"></i>
            <span>Manage Projects</span>
        </div>
        <div class="quick-link" onclick="parent.loadPage('admin_applicants.php')">
            <i class="fa-solid fa-file-invoice"></i>
            <span>Applicants / CVs</span>
        </div>
    </div>
</div>

</body>
</html>
