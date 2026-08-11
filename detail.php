<?php
include('config.php'); // your database connection

if (!isset($_GET['id'])) {
    echo "Service not found.";
    exit;
}

$service_id = intval($_GET['id']);

// Fetch service details from database
$stmt = $conn->prepare("SELECT * FROM services_details WHERE id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Service not found.";
    exit;
}

$service = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 <link rel="stylesheet" href="style.css">
<title>detail services</title>

<style>

    .container {
        max-width: 1200px;
        margin:  auto;
        padding: 40px 20px;
        display: flex;
        gap: 30px;
        
    }
    .main-content {
        flex: 2;
     
    }
    .main-content h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }
    .main-content p {
        color: #666;
        margin-bottom: 15px;
    }
    .main-content h3 {
        font-size: 22px;
        margin-top: 30px;
        margin-bottom: 15px;
        color: #333;
    }
    .main-content ul {
        list-style: none;
        padding: 0;
    }
    .main-content ul li {
        margin-bottom: 8px;
        color: #555;
        position: relative;
        padding-left: 15px;
    }
    .main-content ul li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: orange;
    }
    .service-detail-container{  
  position: relative; 

  width: 100%;
  height: 400px;
  color: white;
  display: flex;
 
    }
    .service-name{
        display:flex;
        align-items:center;
        z-index:2;
        width:100%;
        justify-content: center ;
        font-size:30px;
    }

    .service-detail-container::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(30, 30, 30, 0.15);
  z-index: 1;
}
@media (max-width: 1020px) {
  .service-detail-container {
    height: 300px;
  }
  .service-name h1 {
    font-size: 1.8rem;
  }
      .container {
        padding: 30px 15px;
        gap: 20px;
    }
    .main-content h2 {
        font-size: 1.8rem;
    }

}

/* Mobile size adjustments */
@media (max-width: 600px) {
  .service-detail-container {
    height: 220px;
    background-position: center;
  }
  .service-name h1 {
    font-size: 1.4rem;
  }
      .container {
        padding: 20px 10px;
    }
    .main-content h2 {
        font-size: 1.5rem;
        text-align: center;
    }
    .main-content p {
        font-size: 0.95rem;
        text-align: center;
    }
    .main-content h3 {
        font-size: 1.2rem;
        text-align: center;
    }
    .main-content ul {
        padding-left: 5px;
    }

}

/* Extra small devices */
@media (max-width: 400px) {
  .service-detail-container {
    height: 180px;
  }
  .service-name h1 {
    font-size: 1.2rem;
  }
      .main-content h2 {
        font-size: 1.3rem;
    }
    .main-content p {
        font-size: 0.9rem;
    }

}

</style>
</head>
<body>
<?php include('header.php');?>

<div class="service-detail-container" 
     style="background: url('./assets/service_page_uploads/<?php echo $service['image']; ?>') center center / cover no-repeat;">
    <div class="service-name">
        <h1><?php echo $service['title']; ?></h1>
    </div>
</div>

<div class="container">
    <div class="main-content">
        <h2><?php echo $service['title']; ?></h2>
        <p><?php echo $service['description']; ?></p>

        <h3><?php echo $service['section_title']; ?></h3>
        <ul>
            <?php
            $items = explode("|", $service['list_items']); 
            foreach ($items as $item) {
                echo "<li>" . htmlspecialchars($item) . "</li>";
            }
            ?>
        </ul>
    </div>
</div>



<?php include('footer.php');?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
<script src="header.js"></script>
<script src="footer.js"></script>
</body>
</html>
