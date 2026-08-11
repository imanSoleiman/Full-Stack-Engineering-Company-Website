<?php
include('./config.php'); // Database connection

// Fetch main header
$main_result = $conn->query("SELECT * FROM division_page LIMIT 1");
if($main_result && $main_result->num_rows > 0){
    $main = $main_result->fetch_assoc();
    $header_text = $main['main_header'];
    $description_text = $main['description'];
} else {
    $header_text = "Our Division";
    $description_text = "Add a description in the admin panel.";
}

// Fetch all cards
$cards_result = $conn->query("SELECT * FROM division_cards");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
<link rel="stylesheet" href="style.css"/>
<title>our devision</title>
<style>

    .valuesCards {
  margin: 50px auto 0;
  width: 250px;
  height: 350px;

}

.valuesCards__inner{
  width: 100%;
  height: 100%;
  position: relative;
  transition: transform 1s;
  transform-style: preserve-3d;
  cursor: pointer;
}

.valuesCards__inner.is-flipped {
  transform: rotateY(180deg);
}

.valuesCards__face{ 
  position:absolute;
  text-align:center;
  justify-content:center;
  width:100%;
  height:100%;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  overflow:hidden;
  border-radius: 16px;
  box-shadow: 0px 3px 18px 3px rgba(0 , 0, 0, 0.2);
}

.valuesCards__face--front{
background:black;
display:flex;
align-items: center;
justify-content:center;
}

.valuesCards__face--front h2{
color:#fff;
font-size:32px;
}
.valuesCards__face--back{
transform: rotateY(180deg);
}
.valuesCards__content{
  width:100%;
  height:100%;
}
.valuesCards__header{
  position:relative;
  padding: 100px 30px 40px;
  color:white;
}

.valuesCards__header::after{
content:'';
display:block;

position:absolute;
top:0;
left:0;
right:0;
bottom:0;
z-index:-1;
border-radius: 0 0 50% 0;

}
.valuesCards__header img{
  width:100px;
  height:100px;
  margin:0 auto 30px;
}
.valuesCards__body{
padding:30px;
}
.valuesCards-wrapper {
  display: flex;
  justify-content: center;
  gap: 40px;
  flex-wrap: wrap;
margin:auto;
  margin-bottom:30px;
  max-width: 1200px;
  padding: 0 20px; 
   padding-top:150px;
}
.valueHeader{
  width:100%;
  height: auto;
  justify-content: center;
  margin-top:30px;
}
.valueHeader h1{
    font-size:50px;
    text-transform: uppercase;

}
.valueHeader  p{
    font-size:20px;
}
.valueHeader span{
 font-weight:800;
 font-size:50px;
 color:#6482AD;
}
.valuesCards__face {
    display:flex;
    flex-direction:column;
}
.white{
  color:white;  
}

</style>
</head>
<body>
       <?php include('header.php');?> 
    


<div class="valuesCards-wrapper">
    <div class="valueHeader">
        <h1><?= htmlspecialchars($header_text) ?></h1>
        <p><?= htmlspecialchars($description_text) ?></p>
    </div>

    <?php if($cards_result && $cards_result->num_rows > 0): ?>
        <?php while($card = $cards_result->fetch_assoc()): ?>
        <div class="valuesCards">
            <div class="valuesCards__inner">
                <!-- Front face -->
                <div class="valuesCards__face valuesCards__face--front">

                    <h2><?= htmlspecialchars($card['title_front']) ?></h2>
                    <p class="white">Click to see details</p>
                </div>
                <!-- Back face -->
                <div class="valuesCards__face valuesCards__face--back">
                    <div class="valuesCards__content">
                       <div class="valuesCards__header" 
     style="background-image: url('./assets/devision/<?= htmlspecialchars($card['image_path']) ?>'); 
            background-size: cover; 
            background-position: center; 
            border-radius: 0 0 50% 0;">
</div>

                        <div class="valuesCards__body">
                            <p><?= htmlspecialchars($card['description_back']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; width:100%;">No division cards available.</p>
    <?php endif; ?>
</div>


 <?php include('footer.php');?>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
 <script>
    gsap.from(".valueHeader h1", {
  duration: 1.2,
  y: 50,             // start 50px lower
  opacity: 0,        // start invisible
  ease: "power3.out",
  delay: 0.2
});

gsap.from(".valueHeader p", {
  duration: 1.2,
  y: 30,             // start 30px lower
  opacity: 0,
  ease: "power3.out",
  delay: 0.5
});
const card = document.querySelectorAll('.valuesCards__inner');

card.forEach(card => {
  card.addEventListener('click', function() {
    card.classList.toggle('is-flipped');
  });
});

 </script>
 <
 <script src="header.js"></script>
 <script src="footer.js"></script>

</body>
</html>