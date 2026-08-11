<?php
include './config.php';

$slides = [];
$res = $conn->query("SELECT * FROM slider_items WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
if ($res) $slides = $res->fetch_all(MYSQLI_ASSOC);

// Fetch side slide setting
$setting = $conn->query("SELECT show_side_slides FROM slider_settings WHERE id = 1")->fetch_assoc();
$showSideSlides = $setting ? (int)$setting['show_side_slides'] : 1;

// Fetch only services to show on home page
$sql = "SELECT sc.id, sc.title, sc.short_desc, sc.image
        FROM services_cards sc
        WHERE sc.show_on_homepage = 1
        ORDER BY sc.id ASC";
$result = $conn->query($sql);

$services = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $services[] = $row;
    }
}

//project
$s = "SELECT * FROM projects WHERE show_on_home = 1 LIMIT 6"; 
$r = $conn->query($s);



$left_cards = [];
$result = $conn->query("SELECT * FROM left_cards ORDER BY id ASC");
while($row = $result->fetch_assoc()){
    $left_cards[] = $row;
}


// Fetch About Section (text content)
$about_section = [];
$result = $conn->query("SELECT * FROM about_section LIMIT 1");
if($row = $result->fetch_assoc()){
    $about_section = $row;
}

// Fetch Right Points
$right_points = [];
$result = $conn->query("SELECT * FROM right_points ORDER BY id ASC");
while($row = $result->fetch_assoc()){
    $right_points[] = $row;
}

// Fetch Right Cards
$right_cards = [];
$result = $conn->query("SELECT * FROM right_cards ORDER BY id ASC");
while($row = $result->fetch_assoc()){
    $right_cards[] = $row;
}



// Fetch locations
$query = "SELECT * FROM locations WHERE show_on_home = 1 ORDER BY id DESC";

$company = mysqli_query($conn, $query);



// Fetch latest 5 news items
$sliderNews = $conn->query("
    SELECT n.id, n.title, n.image, n.date_day, n.date_month, n.date_year,
           s.content AS description
    FROM news_cards n
    LEFT JOIN news_sections s 
        ON n.id = s.news_id AND s.section_order = 1
    WHERE n.show_on_home = 1
    ORDER BY n.date_year DESC, n.date_month DESC, n.date_day DESC
    LIMIT 5
");


$headers = [];
$res = $conn->query("SELECT section, header, description FROM homepage_content");
while ($row = $res->fetch_assoc()) {
    $headers[$row['section']] = $row;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
<link rel="stylesheet" href="style.css"/>
<title>Spectrum</title>
<style> 
     * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  display: flex;
  flex-direction: column;
}

.carousel {
  height: 100vh;
  overflow: hidden;
  position: relative;
  width: 100%;
  background-color: white;
  box-sizing: border-box;
  margin-bottom:40px;
}

.carousel .list .item::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.31);
  z-index: 1;
  pointer-events: none;
}

.carousel .list .item {
  width: clamp(150px, 15vw, 180px);
  height: clamp(200px, 20vw, 250px);
  position: absolute;
  top: 80%;
  transform: translateY(-70%);
  left: 67%;
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.37);
  background-position: 50% 50%;
  background-size: cover;
  transition: 1s;
}

.carousel .list .item:nth-child(1),
.carousel .list .item:nth-child(2) {
  top: 0;
  left: 0;
  transform: translate(0, 0);
  border-radius: 0;
  width: 100%;
  height: 100%;
}


.list .item .content {
  position: absolute;
  top: 65%;
  left: 10%;
  transform: translateY(-60%);
  width: 80%;
  max-width: 600px;
  text-align: left;
  color: #fff;
  display: none;
  padding: 10px;
  margin-left: 10px;
  z-index: 2;
}

.list .item:nth-child(2) .content {
  display: block;

}

.content .title {
  font-size: clamp(24px, 5vw, 60px);
  text-transform: uppercase;
  color: #ffffffcb;
  font-weight: bold;
  line-height: 1;
  opacity: 0;
  animation: animate 1s ease-in-out 0.3s 1 forwards;
}
.content p{
font-size: 18px;
    line-height: 28px;
    display: flex;
    gap: 4px;
      opacity: 0;
    color: #fff;
    margin-bottom: 15px;
  animation: animate 1s ease-in-out 0.3s 1 forwards;
}

.content .des {
  margin-top: 10px;
  margin-bottom: 20px;
  font-size: clamp(14px, 2vw, 18px);
  margin-left: 5px;
  opacity: 0;
  animation: animate 1s ease-in-out 0.9s 1 forwards;
}

.content .btn {
  margin: 12px 0px;
  margin-left: 5px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  opacity: 0;
  animation: animate 1s ease-in-out 1.2s 1 forwards;
}

.content .btn button {
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  font-size: 16px;
  border-radius: 10px;
}

.content .btn button:hover {
  background-color: #ff4343cb;
  color: #fff;
}

.btn #btn1 {
    display: inline-block;
    flex: 0 0 auto;
    text-transform: capitalize;
    font-size: 16px;
    line-height: 1;
    background:red;
    color: #fff;
    padding: 10px 27px;
    border-radius: 8px;
    font-weight: 500;

}

.btn #btn1:hover {
  background-color: white;
  color: #000;
  box-shadow:10px 2px  12px rgba(22, 22, 22, 0.72);
}

.btn #btn2 {
  text-decoration: none;
  font-size: clamp(14px, 2vw, 20px);
  margin: 10px;
  color: white;
  background-color:transparent;
  border:solid white;
  padding: 10px 25px;
  border-radius: 10px;
  transition: 0.3s;
}

.btn #btn2:hover {
  background-color: black;
  color: white;
}

@keyframes animate {
  from {
    opacity: 0;
    transform: translate(0, 100px);
    filter: blur(33px);
  }

  to {
    opacity: 1;
    transform: translate(0);
    color: azure;
  }
}

.arrows {
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 24px;
  pointer-events: none;
}

.arrows button {
  pointer-events: auto;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.85);
  color: #000;
  border: 1px solid rgba(0, 0, 0, 0.15);
  outline: none;
  font-size: 25px;
  font-family: monospace;
  font-weight: bold;
  transition: 0.2s;
  cursor: pointer;
  margin: 10px;
  text-align: center;
}

.arrows button:hover {
  background: #ff2a2acb;
  color: #fff;
}

.carousel .timeRunning {
  position: absolute;
  z-index: 1000;
  width: 0%;
  height: 4px;
  background-color: #000000ff;
  left: 0;
  top: 0;
  animation: runningTime 5s linear 1 forwards;
}


@keyframes runningTime {
  from {
    width: 0%;
  }

  to {
    width: 100%;
  }
}

@media screen and (max-width: 1114px) {
  .carousel .list .item {
    width: 130px;
    height: 170px;
    top: 90%;
    left: 30%;
    object-fit: contain;
  }

  .list .item .content {
    left: 10%;
    width: 85%;
  }

  .content .title,
  .content .name {
    font-size: 50px;
  }

  .carousel .list .item:nth-child(3) {
    left: 30%;
  }

  .carousel .list .item:nth-child(4) {
    left: calc(30% + 160px);
  }

  .carousel .list .item:nth-child(5) {
    left: calc(30% + 340px);
  }

  .carousel .list .item:nth-child(6) {
    left: calc(30% + 340px);
  }

  .carousel .list .item:nth-child(n+6) {

    opacity: 0;
  }
}

@media screen and (max-width: 768px) {
  .carousel .list .item {
    width: 130px;
    height: 150px;
    top: 90%;
    left: 30%;
    object-fit: contain;
  }
  .carousel{
  height:40vh;
}
  .list .item .content {
    left: 9%;
    width: 90%;
  }

  .content .title,
  .content .name {
    font-size: 40px;
  }

  .content .des {
    font-size: 16px;
  }

  .carousel .list .item:nth-child(3) ,
  .carousel .list .item:nth-child(4) ,
  .carousel .list .item:nth-child(5),
  .carousel .list .item:nth-child(6) 
  .carousel .list .item:nth-child(n+6) {
    opacity: 0;
  }
   .arrows {
    padding: 0 16px;
  }

  .arrows button {
    width: 40px;
    height: 40px;
    font-size: 16px;
    margin: 8px;
  }
}


@media screen and (max-width: 768px) {
  .carousel .list .item {
    width: 110px;
    height: 150px;
    top: 90%;
    left: 30%;
    object-fit: contain;
  }

  .list .item .content {
    left: 9%;
    width: 65%;
  }

  .content .title,
  .content .name {
    font-size: 40px;
  }

  .content .des {
    font-size: 16px;
  }

  .carousel .list .item:nth-child(3) {
    left: 20%;
  }

  .carousel .list .item:nth-child(4) {
    left: calc(20% + 130px);
  }

  .carousel .list .item:nth-child(5) {
    left: calc(20% + 270px);
    opacity:0;
  }

  .carousel .list .item:nth-child(6) {
    left: calc(20% + 200px);
  }

  .carousel .list .item:nth-child(n+6) {
    left: calc(20% + 200px);
    opacity: 0;
  }
   .arrows {
    padding: 0 16px;
  }

  .arrows button {
    width: 40px;
    height: 40px;
    font-size: 16px;
    margin: 8px;
  }
}


@media screen and (max-width: 480px) {
  .carousel .list .item {
    width: 100px;
    height: 150px;
    top: 90%;
    left: 20%;
  }

  .list .item .content {

    width: 80%;
  }
  .content p{
    font-size:10px;
  }

  .content .title,
  .content .name {
    font-size: 25px;
  }

  .content .des {
    font-size: 14px;
  }

  .content .btn a {
    font-size: 14px;
    padding: 8px 16px;
  }
     .arrows {
    padding: 0 8px;
  }

  .arrows button {
    width: 30px;
    height: 30px;
    font-size: 16px;
  
  }
}


.container {
  position: relative;
  
}

.loading-page {
  position: absolute;
  top: 0;
  left: 0;
  background: black;
  overflow: hidden;
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  align-items: center;
  justify-content: center;

}

#svg {
  height: 150px;
  width: 150px;
  stroke: white;
  fill-opacity: 0;
  stroke-width: 1px;
  stroke-dasharray: 2500;
  animation: draw 20s ease;
}

@keyframes draw {
  0% {
    stroke-dashoffset: 2500;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

.name-container {
  height: 30px;
  overflow: hidden;
}

.logo-name {
  color: #fff;
  font-size: 20px;
  letter-spacing: 12px;
  text-transform: uppercase;
  margin-left: 20px;
  font-weight: bolder;
}
.services-container{
      padding-top: 400px;
    padding-bottom: 60px;
    margin-left: 12px;
    margin-right: 12px;
    background: #f6f7f8ff;
    border-radius: 16px;
        position: relative;
    z-index: 1;
    margin:20px;
    overflow:hidden;
}
.service-image-sliderOne{
  position: absolute;
    right: 12px;
    top: 12px;
    height: calc(100% - 24px);
    width: 45%;
    z-index: -1;
    overflow: hidden;
}

.swiper-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    z-index: 1;
    display: flex;
    box-sizing: content-box;
    transition: transform 0.8s ease-in-out;

}
.swiper-slide{
  position:relative;
  width:556px;
  flex-shrink:0;
}
.img-cover{
  width:100%;
  height:100%;
}
.img-cover img {
  width: 100%;     
  height: 100%;     
  object-fit: cover; 
  display: block;   
  border-radius: 12px; 
}

.services-content-wrap{
    max-width: 950px;
    width: 100%;
    display: grid;
    grid-template-columns: 1.3fr 1fr;
    gap: 50px 80px;
    align-items: end;
}
.services-content{
  max-width:1200px;
  margin:0 40px;
  padding:0 10px;
  color:white;
}
.none{
  display:none;
}
.services-right{
  position:relative;
  background-color:#E43636;
    border-radius: 8px;
    padding: 40px;
    padding-top: 140px;
    max-width: 384px;
    width: 100%;
    display: grid;
    color:white;
}
.swiper-slide{
  position:relative;
  display:flex;
  width:100%;
}

.services-list-wrapper{
  position: relative;
    width: 100%;
    height: 100%;
    z-index: 1;
    display: flex;

    transition: transform 0.8s ease-in-out; 
      overflow: hidden;
}

.services-Left h1{
  font-size:50px;
  margin-bottom:20px;
}

.top-right-arrow{
  display:flex;
  position:absolute;
  top:30px;
  right:50px;
  gap:2px;
  cursor: pointer;
}
.top-label{
  position:absolute;
  top:30px;
  left:20px;

}

.top-right-arrow i{
border:solid 1px gray;
padding:8px;
font-size:20px;
}
#btn{
    width: 150px;
    height: 50px;
    margin-top: 10px;
    padding: 10px 20px;
    border-radius: 10px;
    display: inline-block;
    flex: 0 0 auto;
    text-transform: capitalize;
    font-size: 1rem;
    line-height: 1;
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    background-color: transparent;
    display: flex;
    align-items: center;
    cursor: pointer;
    background-color: #e22b2b;
    box-shadow: inset 0 0 0 0 black;
    transition: ease-in-out 0.5s;
    text-decoration: none;
    text-align:center;
}

.service-item {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  opacity: 0;
  transform: translateX(100%); 
  transition: all 0.6s ease-in-out;
}

.service-item.active {
  opacity: 1;
  transform: translateX(0); 
  position: relative; 
}
.service-item.exit-left {
  transform: translateX(-100%); 
  opacity: 0;
  z-index: 0;
}

   #btn:hover{
      box-shadow:inset 300px 0 0 0 black;
      color:white;
    }
    .slider-item h4{
      font-size:20px;
      text-transform: uppercase;
      line-height:20px;
      margin-bottom:10px;
    }
   .slider-item p{
      font-size:15px;
      color:#fff;
      line-height: 22px;
    }
  

.project-section{
  padding:50px;
  padding-top:50px;
  position:relative;
}

.project-card-container{
  width:100%;
  height:100%;
  background-color:whitesmoke;
padding:30px;
padding-top:50px;
border-radius:10px;
position:relative;
  box-sizing: border-box;

}

.project-cards{
 width:100%;
 height:100%;
  display:grid;
  grid-template-columns:1fr 1fr 1fr;
  position:relative;
}
.cardOne{
  width:100%;
  height:100%;
  padding: 20px;
  box-sizing: border-box;
}
.project-image{
   width: 100%;
  height:300px;
}
.cardOne img{
 width:100%;
 height:100%;
 object-fit: cover;
 object-position:center;
 border-radius:10px;
}
.project-section-wrapper{
  display:flex;
  flex-direction:column;
  gap:20px;
justify-content: center;
width:100%;
height:100%;
}
.project-section-wrapper a{
  align-self:center;
}
.cardOne{
  display:flex;
  flex-direction:column;
  gap:10px;
}
.center{
  align-self:center;
}

@media screen and (max-width: 1024px) {
  .project-cards {
    grid-template-columns: repeat(2, 1fr);
  }
   .project-section{
    padding:20px;
  }
}
@media screen and (max-width: 600px){
  .project-cards{
    grid-template-columns: 1fr;
  }
  .project-section{
    padding:20px;
  }
  .project-card-container{
    padding:2px;
  }
  .text h1{
    font-size:19px;
  }
}

.who-we-are-section{
  width:100%;
  height:auto;
  padding:50px;
}
.who-we-section{
  display:flex;
}
.who-left{
width:50%;
display:grid;
grid-template-columns: repeat(2,1fr);
position:relative;
right:5%;
}
.who-left-one{
  width:100%;
  height:100%;
  position:absolute;
  left:32%;
  z-index:1;

}
.wh0-left-container{
position:relative;
}
.who-left-image{
  height:400px;
  width:100%;
  display:flex;
  align-items:flex-end;
  gap:10px;

}
.who-left-image img{
  width:100%;
  height:100%;
  object-fit:cover;
  border-radius:10px;
}
.who-left-card{
  display: inline-block;
  padding:30px 10px;
  border-radius:10px;
  width:65%;
  height:auto;
  background-color:black;
  display:flex;
  color:white;
  justify-content: center;
  border:1px solid red;
  flex-direction:column;
  align-items: center;
  margin-bottom:10px;
}
.right-section{
  width:50%;
  padding:20px 0px;
}
.right-points-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
grid-template-rows:repeat(2, 1fr);
gap:10px;
}
.right-point{
  display:flex;
  gap:10px;

}
.right-cards-container{
display:flex;
gap:20px;
}
.who-cards{
  display:flex;
  gap:20px;
  flex-direction:column;
  background-color:#efefef;
  width:150px;
  padding:20px 10px;
  border-radius:10px;
  justify-content:center;
  align-items:center;
   text-align:center;
}
.right-section-container{
  display:flex;
  flex-direction:column;
  gap:20px;
}
.right-points{
  display:flex;
  flex-direction:column;
  gap:10px;
}
.right-section-text h1{
  margin-bottom:10px;
}
.card-two p{
color:white;
z-index:1;
}
.who-left-two{
  width:100%;
  height:100%;
}
.card-two {
  position: relative;
  width: 65%; 
  height: 100px;
  border-radius: 10px;
    flex-shrink: 0;
  background-color:black;
}

.card-two-container{
  position:relative;
  width:100%;
  height:100%;
  overflow:hidden;
  display:flex;
  border-radius:10px;
  justify-content:center;
  align-items: center;
}
.card-two img{
  position:absolute;
width:60%;
top:-30px;
height:auto;
left:60%;
}
.check-mark{
  width:16px;
  height:18px;
}


.highlight-letters {
  display: inline-block;
  line-height: 1.2;
}

.highlight-letters .word {
  display: inline-block;  /* keep letters of a word together */
  white-space: nowrap;    /* prevents word from breaking */

}
.highlight-letters span span {
  display: inline-block;
}
.company-spectrum-locations{
position:relative;
overflow:hidden;

padding:20px;
padding-top:150px;
margin-bottom:30px;

}
.company-spectrum-header{
  max-width:1300px;
margin:auto;
}
.company-header-container{
    display:flex;
    align-items: end;
    gap: 15px;
    justify-content: space-between;
}
.company-left{
  width:55%;
  margin-right:20px;
}
.company-left h3{
    text-transform: uppercase;
    font-size: 70px;
    font-weight: 600;
    color: #000;
    letter-spacing: -2px;
    line-height: 0.9;
    margin-bottom: 0;

}
.company-left h6{
  color:gary;
  font-size:16px;
font-family: "Roboto", sans-serif;
font-weight:500;

}
.right-spectrum{
      max-width: 500px;
    font-weight: 400;
    line-height: 28px;
    font-size: 18px;
    color: #000;
    margin-bottom: 0px;
}
.spectrum-slider-structure{
  margin: 50px -150px;
  

}
.news-slider-structure{
    margin: 50px 0px;

}
.spectrum-slider-container{
    touch-action: pan-y;
}
.news-slider-container{
    touch-action: pan-y;
}

.spectrum-slider-wrapper{
  display:flex;
  position:relative;
  height:100%;
  width:100%;
  z-index:1;
}

.news-slider-wrapper{
  display:flex;
  position:relative;
  height:100%;
  width:100%;
  z-index:1;
}
.company-single-slider{
  height:320px;
  flex-shrink:0;
    width: 320px; 
    cursor: grab;
transition-property: transform;
  display:block;
  margin-right:50px;
}

.company-single-container{
  height: 100%;
  width:100%;
    border-radius: 16px;
    position: relative;
    z-index: 1;
  overflow:hidden;
  touch-action: pan-x;    
  cursor: grab;          
  -webkit-user-select: none; 
  user-select: none;      
 
}
  .company-single-container::after{
  content:"";
    width: 100%;
    height: 160px;
    position: absolute;
    bottom: 0;
    left: 0;
    background: linear-gradient(0deg, rgb(0, 0, 0) 0%, rgba(0, 212, 255, 0) 100%);
}
.company-single-container img{
height:100%;
width:100%;
object-fit: cover;
    max-width: 100%;

    vertical-align: middle;
 box-sizing: border-box;
}

.company-single-container h4{
    position: absolute;
    bottom: 12px;
    text-align: center;
    left: 0;
    width: 100%;
    font-size: 32px;
   color:white;
   font-family:"Teko", sans-serif;
    padding: 0 10px;
    z-index: 1;
    text-transform: uppercase;
}
.company-news{
  background-image:url('./images/8578ed25438fa9c3e2706c092fbcb51b.jpg');
  position:relative;
overflow:hidden;
padding:20px;
padding-top:150px;
margin-bottom:30px;
background-size: cover;
background-position: center;
}
.company-news-header{
max-width:600px;
display:flex;
flex-direction: column;
justify-content:center;
padding:20px;
}
.company-news-header h3{
      text-transform: uppercase;
    font-size: 70px;
    font-weight: 600;
    color: #ffffffff;
    letter-spacing: -2px;
    line-height: 0.9;
    margin-bottom: 0;
}
.company-news-header h6{
   font-size:16px;
    color: #ffffffff;
    margin-bottom: 10;
}
.gray{
    background-image:url('./images/t5-card-bg.png');
  border-radius:10px;
  width:520px;
  height: 320px;
    flex-shrink: 0;
    cursor: grab;
    transition-property: transform;
    display: block;
    margin-right: 50px;
}
.news-single-container{
  height: 320px;
    border-radius: 16px;
    position: relative;
    z-index: 1;
    overflow: hidden;
    touch-action: pan-x;
    cursor: grab;
    -webkit-user-select: none;
    user-select: none;
    color:white;
    display:flex;
    flex-direction:column;
gap:10px;
justify-content: center;
align-items:flex-start;
padding:20px;
}
.slider-bullets {
  text-align: center;
  margin-top: 15px;
}

.slider-bullets span {
  display: inline-block;
  width: 10px;
  height: 10px;
  background: #ccc;
  border-radius: 50%;
  margin: 0 5px;
  cursor: pointer;

}

.slider-bullets span.active {
  background: #ff0000ff; /* Active color */
}
/* Read More Button Style */
.news-single-container a {
    display: inline-block;
    margin-top: 12px;
    padding: 8px 16px;

    color: #fff;
    text-decoration: none;
    font-weight: 600;
    border-radius: 6px;
    transition: background-color 0.3s, transform 0.2s;
}

.news-single-container a:hover {
    background-color: #f31c1cff; /* slightly lighter blue */
    transform: translateY(-2px);
}
.final-spectrum-page{

  padding:20px;
  display:flex;
  justify-content: center;
}
.final-section{
  display:grid;
  grid-template-columns: repeat(2,1fr);
  gap: 50px; 
   max-width:1500px;
}
.final-spectrum-left{
 background-image:url('./images/d3069074a097a03eefd743add101a7d1.jpg');
 border-radius:20px;
}
.final-flex-spectrum{
  display:flex;
  gap:20px;
  justify-content:center;
  align-items:center;
  padding:30px;
}
.final-flex-spectrum h2{
  width:40%;
  text-transform: uppercase;
  font-weight: 500;
}
.final-flex-spectrum a{
  display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    background: #b9262c;
    width: 54px;
    height: 54px;
    border-radius: 8px;
    color: #fff;
    font-size: 20px;
    transition:ease-in-out 0.3s;
}
.final-flex-spectrum a:hover{
  background-color:black;  
}
.final-spectrum-right-container a:hover{
  background-color:black;
  color:white;
}


.final-spectrum-right-container a:hover i{
transform :rotate(90deg);
}


.final-flex-spectrum a:hover i{
transform: rotate(90deg);
}

.final-flex-spectrum i, .final-spectrum-right-container i{
  transform: rotate(50deg);
  transition:ease-in-out 0.2s;
}
.spectrum-icon{
      animation: rotate 10s linear infinite;
}
@keyframes rotate{
  0%{
    transform:rotate(0deg);
  }
  100%{
    transform: rotate(360deg);
  }
}
.final-spectrum-right{
  background-image:url('images/WhatsApp\ Image\ 2025-09-12\ at\ 00.56.33_3274f75b.jpg');
  border-radius:10px;
  color:white;

}
.final-spectrum-right-container {
display:flex;
padding:50px;
gap:20px;
justify-content:center;
align-items: center;
}
.final-spectrum-right-container h2{
  font-size: 30px;
    text-transform: uppercase;
    line-height: 1.06;
    width:60%;
    
}
.final-spectrum-right-container a{
  display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    background:#e8eae9;
    width: 54px;
    height: 54px;
    border-radius: 8px;
    color: #000000ff;
    font-size: 20px;
    transition:ease-in-out 0.2s;
}

  @media screen and (max-width:1026px){
      .services-content{
        max-width:700px;
      }
      .services-Left h1 {
        font-size:30px;
      }
      .services-content-wrap{
        grid-template-columns:1fr 1fr;
      }
      .services-right{ 
        padding:30px;
        padding-top:120px;
       
      }
      .services-container{
        padding-top:300px;
      }
    }
    @media screen and (max-width:968px){
      .company-left h3{
          font-size:50px;
      }
      .company-spectrum-header{
        max-width:900px;
      }
      .right-spectrum {
        font-size:15px;
        width:100%;
      }
      .final-flex-spectrum a{
        align-self:self-start;
      }
      .spectrum-icon{
        width:100px;
      }
      .final-flex-spectrum{
        display:flex;
        flex-direction:column;
      }
      .final-flex-spectrum h2{
        width:100%;
      }
      .final-spectrum-right-container{
        display:flex;
        flex-direction:column;
        justify-content: center;
        align-items: flex-start;
        padding-top: 100px;
      }
      .final-spectrum-right-container h2{
        width:100%;
      }
 
    }
     @media screen and (max-width:768px){
      .company-left{
        width:100%;
      }

            .company-left h3{
          font-size:36px;
      }
      .company-spectrum-header{
        max-width:700px;
      }
      .right-spectrum {
        font-size:12px;
        line-height:20px;
      }
      .services-content{
        max-width:100%;
      }
      .services-Left h1 {
        font-size:30px;
      }
      .services-content-wrap{
        grid-template-rows:1fr 1fr;
       grid-template-columns: none; 
      }
      .services-right{ 
        padding:30px;
        padding-top:120px;
       
      }
      .services-container{
        padding-top:90px;
      }
      .service-image-sliderOne{
        display:none;
      }

      .who-we-section{
        display:flex;
        flex-direction:column;
      }
      .who-left{
        width:100%;
      }
      .who-left-one{
        position:absolute;
        left:0;
      }
      .right-section{
        width:100%;
      }
      .who-left-card{
       width:95%;
      }
      .card-two{
        width:100%;
      }
      .right-section-container{
        padding-top:100px;
        margin-top:40px;
      }
      .who-left-image{
        height:300px;
      }
           .right-cards-container{
display:flex;
gap:20px;
flex-wrap:wrap;

}
.who-cards{
  width:100%;
}

.company-single-slider{
        width:250px;
        height:250px;
      
      } 
    .company-news-header h3{
      font-size:50px;
    }
.final-section {
  display: grid;
  grid-template-rows: repeat(2, 1fr);
  grid-template-columns: none;
}

  
  }

    @media screen and (max-width :650px){
      .company-header-container{
         display:flex;
         flex-direction:column;
      }
       .company-left{
        width:100%;
        margin:0px;
      }

            .company-left h3{
          font-size:40px;
      }
      .company-spectrum-header{
        max-width:700px;
      }
      .right-spectrum {
        font-size:12px;
        line-height:20px;
      }
    }

    @media screen and (max-width:450px){
  
      .services-Left h1 {
        font-size:24px;
      }
      .slider-item h4{
        font-size:15px;
      }
       .slider-item p{
        font-size:13px;
      }
      
.top-right-arrow i{
  font-size:13px;
}
.services-content{
margin:0 20px;
}
.services-container{
  margin:10px;
}
.who-left-image{
  height:200px;
}
.who-cards p{
  font-size:13px;
} 
.right-points-grid{
  display:grid;
  grid-template-columns:  repeat(auto-fit, minmax(200px, 1fr));
}
          .company-left h3{
          font-size:30px;
      }

.company-single-slider{
        width:360px;
        height:300px;
      
      } 
      .spectrum-slider-structure{
        margin:50px 0px;
      
      }
         .company-news-header h3{
      font-size:40px;
    }
    .company-news{
      padding-top:100px;
    }

    .gray{
      width:350px;
       margin-right:210px;
    }
    .who-we-are-section{
      padding:20px;
    }
    .who-left{
      right:0%;
    }
}

    @media screen and (max-width:390px){
     .who-we-are-section {
      padding: 30px ;
     }
     .company-single-slider{
        width:300px;
        height:300px;
        margin-right:0px;
      
      } 
     .gray{
      width:340px;
       margin-right:210px;
    }
    .company-news{
         padding:10px;
         padding-top:90px;
    }
    }
    @media screen and (max-width:340px){
          .company-single-slider{
        width:300px;
        height:300px;
        margin-right:0px;
      
      } 
     .gray{
      width:300px;
       margin-right:240px;
       margin-left:10px;
    }
    .company-news{
         padding:0px;
         padding-top:90px;
    }
    }
    .intro-title h1{
      text-transform:uppercase;
      font-size: 6rem;
      font-weight: 400;
      line-height:1;
    }
    .preloader , .split-overlay{
      position:fixed;
      width:100vw;
      height:100svh;
      background-color:black;
      color:white;
    }
    .preloader{
      z-index:9999;

    }
    .split-overlay{
      z-index:888;
    }
    .intro-title{
      position:absolute;
      top:50%;
      left:50%;
      transform: translate(-50%, -50%);
      width:100%;
      text-align:center;
    }
.outro-title{
  position:absolute;
  top:50%;
  left: calc(50% + 10rem);
  transform: translate(-50%, -50%);

}
.container{
  position:relative;
width:100%;
height:100%;
min-height:100svh;
z-index:100000px;
clip-path:polygon(0 48%, 0 48%, 0 52% , 0 52%);


}
.intro-title .char, .outro-title .char{
    position:relative;
    display:inline-block;
    overflow:hidden;
}
.intro-title .char , .outro-title .char{
    margin-top:0.75rem;
}

.intro-title .char span , .outro-title .char span 
{
position:relative;
display:inline-block;
transform:translateY(-100%);
will-change:transform;
}
@media (max-width :1000px){
  .intro-title h1{
    font-size:2.5rem;

  }

  .outro-title{
    left:calc(50% + 5rem);
  }
}



</style>
</head>

<body>



<div class="preloader">
   <div class="intro-title">
    <h1>spectrum</h1>
   </div>
   <div class="outro-title">
    <h1>Lb</h1>
   </div>
</div>
<div class="split-overlay">
 <div class="intro-title">
    <h1>spectrum</h1>
   </div>
   <div class="outro-title">
    <h1>Lb</h1>
   </div>
</div>

<div class="container">
    <?php include('header.php');?> 
<div class="carousel <?php if($showSideSlides) echo 'with-sides'; ?>" style="z-index: 888;">

  <div class="list">
    <?php if (!$slides): ?>
      <div class="item" style="background-color:#ccc;">
        <div class="content">No slides yet.</div>
      </div>
    <?php else: ?>
     <?php foreach ($slides as $index => $s): ?>
  <div class="item" style="background-image: url('./assets/home/<?= htmlspecialchars($s['image_path']) ?>');">
    <div class="content">
      <p><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>/<?= count($slides) ?></p>
      <div class="title"><?= htmlspecialchars($s['title']) ?></div>
      <div class="des"><?= htmlspecialchars($s['description']) ?></div>
      <div class="btn">
        <a id="btn1" href="about.php">about us</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>

    <?php endif; ?>
  </div>

  <div class="arrows">
    <div><button class="prev">&laquo;</button></div>
    <div><button class="next">&raquo;</button></div>
  </div>
</div>

<style>
<?php if ($showSideSlides): ?>
  /* When side slides are ON */
.carousel .list .item:nth-child(3) { top: 80%; left: 50% }
.carousel .list .item:nth-child(4) { top: 80%; left: calc(50% + 200px)}
.carousel .list .item:nth-child(5) { top: 80%; left: calc(50% + 400px) }
.carousel .list .item:nth-child(6) { top: 80%; left: calc(50% + 600px)}
<?php else: ?>

.carousel .list .item:nth-child(3) { top: 80%; left: 50%;  visibility: hidden; }
.carousel .list .item:nth-child(4) { top: 80%; left: 50%;  visibility: hidden; }
.carousel .list .item:nth-child(5) { top: 80%; left: 50%; visibility: hidden;}
.carousel .list .item:nth-child(6) { top: 80%; left: 50%;  visibility: hidden;}

<?php endif; ?>
</style>


<div class="who-we-are-section ">
   <div class="who-we-section">
     <div class="who-left">
      <div class="wh0-left-container">
        <div class="who-left-one">
         <div class="who-left-card">
                <h2><?= $left_cards[0]['title'] ?? '20+' ?></h2>
            <p>years experience</p>
         </div>
         <div class="who-left-image">
            <!-- Dynamic Image Only -->
            <img src="./assets/home/<?= $left_cards[0]['image1'] ?>" alt="Left Card Image">
            <div class="card-two">
                <div class="card-two-container">
                  <img src="./images/setting (2).png">
                   <p><?= htmlspecialchars($left_cards[0]['overlay_text'] ?? 'kjkflj') ?></p>
                </div>
           </div>
         </div>
       </div>
      </div>
     
     <div class="who-left-two">
    <div class="who-left-image">
  <img src="./assets/home/<?= $left_cards[0]['image2'] ?? 'fallback-bottom.jpg' ?>" alt="Bottom Left Image">
    </div>
</div>
     </div>
     <div class="right-section">
        <div class="right-section-container">
           <div class="right-section-text">
               <p><?= $about_section['subheading'] ?? 'about us' ?></p>
               <h1><?= $about_section['heading'] ?? 'Reliable and excellence company' ?></h1>
               <p><?= $about_section['description'] ?? 'Your company description goes here.' ?></p>
           </div>
           <div class="right-points">
            <p><?= $about_section['intro_text'] ?? '' ?></p>
             <div class="right-points-grid">
                 <?php foreach($right_points as $point): ?>
                     <div class="right-point">
                        <img class="check-mark" src="./images/checked (2).png">
                        <p><?= $point['text'] ?></p>
                     </div>
                 <?php endforeach; ?>
             </div>
           </div>

           <div class="right-cards">
              <div class="right-cards-container">
                  <?php foreach($right_cards as $card): ?>
                      <div class="who-cards">
                          <h2><?= $card['title'] ?></h2>
                          <p><?= $card['description'] ?></p>
                      </div>
                  <?php endforeach; ?>
              </div>
           </div>
         
           <a href="./about.php" id="btn">know more</a>
        </div>
     </div>
   </div>
</div>


<div class="services-container">
  <div class="service-image-sliderOne">
    <div class="swiper-wrapper">
      <?php foreach($services as $index => $service): ?>
      <div class="swiper-slide <?= $index === 0 ? 'active' : 'none' ?>" data-index="<?= $index ?>">
        <div class="img-cover">
          <img src="./assets/service_page_uploads/<?= $service['image'] ?>" alt="<?= $service['title'] ?>">
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="services-content">
    <div class="services-content-wrap">
      <div class="services-Left">
        <p>our services</p>
  <h1 class="highlight-letters"><?= htmlspecialchars($headers['services']['header']) ?></h1>
      </div>
      <div class="services-right">
        <div class="services-list-container">
          <div class="services-list-wrapper">
            <?php foreach($services as $index => $service): ?>
            <div class="service-item <?= $index === 0 ? 'active' : '' ?>">
              <div class="slider-item" data-index="<?= $index ?>">
                <h4><?= $service['title'] ?></h4>
                <p><?= $service['short_desc'] ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="top-right">
          <div class="top-label"><p>1/<?= count($services) ?></p></div>
          <div class="top-right-arrow">
            <i class="fa-solid fa-arrow-left left"></i>
            <i class="fa-solid fa-arrow-right right "></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="company-spectrum-locations">
  <div class="company-spectrum-header">
    <div class="company-header-container">
      <div class="company-left">
        <h6> company structure</h6> 
    <h3 class="highlight-letters red"><?= htmlspecialchars($headers['structure']['header']) ?></h3>

      </div>
     <p class="right-spectrum"><?= htmlspecialchars($headers['structure']['description']) ?></p>

    </div>
  </div>

  <div class="spectrum-slider-structure">
    <div class="spectrum-slider-container">
      <div class="spectrum-slider-wrapper">

        <?php while ($loc = mysqli_fetch_assoc($company)) { ?>
          <div class="company-single-slider">
            <div class="company-single-container">
              <img src="./assets/structure/<?= htmlspecialchars($loc['image_path']) ?>" alt="<?= htmlspecialchars($loc['city']) ?>">
               <h4><?= !empty($loc['country']) ? htmlspecialchars($loc['country']) : '' ?></h4>

            </div>
          </div>
        <?php } ?>

      </div>
    </div> 
  
  </div>
           <a href="./structure.php" id="btn" class="center"> discover all</a>
        </div>

  </div>
</div>


<div class="project-section">
    <div class="project-section-wrapper">
        <div class="text">
            <p>our projects</p>
           <h1><?= htmlspecialchars($headers['projects']['header']) ?></h1>
        </div>

        <div class="project-card-container">
            <div class="project-cards">
                <?php while($row = $r->fetch_assoc()): ?>
                    <div class="cardOne">
                        <div class="project-image">
                         <img src="./assets/projects_uploads/<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        </div>
                        <h4><?= htmlspecialchars($row['name']); ?></h4>
                        <p>
    <?php if (!empty($row['location_url'])): ?>
        <a href="<?= htmlspecialchars($row['location_url']) ?>" target="_blank" title="View on Map " style="text-decoration:none">
            <i class="fas fa-map-marker-alt" style="color: #e74c3c; text-decoration:none ; "></i>
        </a>
    <?php else: ?>
        <i class="fas fa-map-marker-alt" style="display: none"></i>
    <?php endif; ?>
    <?= htmlspecialchars($row['location']) ?>
</p>

                    </div>
                <?php endwhile; ?>
            </div>
            <a href="./projects.php" id="btn" class="center">discover more</a>
        </div>
    </div>
</div>

<div class="company-news">
  <div class="company-news-header"> 
        <h6>latest news</h6> 
       <h3 class="highlight-letters"><?= htmlspecialchars($headers['news']['header']) ?></h3>
  </div>

  <div class="news-slider-structure">
    <div class="news-slider-container">
        <div class="news-slider-wrapper">
            <?php while($news = $sliderNews->fetch_assoc()): 
                $monthName = date('M', mktime(0,0,0,$news['date_month'],10));
            ?>
            <div class="gray">
                <div class="news-single-container">
                    <h2><?= htmlspecialchars($news['title']) ?></h2>
                    <p><?= $news['date_day'] . ' ' . $monthName . ' ' . $news['date_year'] ?></p>
                    <p><?= htmlspecialchars(substr($news['description'],0,200)) ?>...</p>
                    <a href="news_details.php?id=<?= $news['id'] ?>">Read More</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
  </div>
  <div class="slider-bullets"></div>
</div>


 <div class="final-spectrum-page">
    <div class="final-section">
        <div class="final-spectrum-left">
           <div class="final-flex-spectrum">
              <div class="spectrum-icon">
                  <svg width="129" height="134" viewBox="0 0 129 134" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.3998 25.4L18.5998 33.5L17.5998 35.1L9.6998 32.8L15.1998 38.9L14.1998 40.5L3.7998 37.5L4.7998 36L13.1998 38.6L7.1998 32.1L8.1998 30.5L16.4998 33.1L10.4998 26.8L11.3998 25.4Z" fill="black"></path>
                                <path d="M17.7996 20.9L19.8996 23.1L22.4996 20.6L23.4996 21.6L20.8996 24.1L23.0996 26.4L25.9996 23.6L26.9996 24.6L22.8996 28.6L15.5996 21.2L19.6996 17.2L20.6996 18.2L17.7996 20.9Z" fill="black"></path>
                                <path d="M40.0997 10.9C40.6997 11.2 41.0997 11.6 41.3997 12.1C41.5997 12.6 41.6997 13.1 41.6997 13.6C41.5997 14.1 41.3997 14.6 41.0997 15C40.6997 15.5 40.2997 15.8 39.5997 16.1L35.8997 18L31.1997 8.69999L34.7997 6.89999C35.3997 6.59999 35.9997 6.39999 36.5997 6.39999C37.1997 6.39999 37.6997 6.49999 38.0997 6.69999C38.4997 6.89999 38.7997 7.29999 39.0997 7.69999C39.3997 8.19999 39.4997 8.79999 39.2997 9.29999C39.1997 9.79999 38.8997 10.3 38.4997 10.7C38.9997 10.6 39.5997 10.6 40.0997 10.9ZM34.6997 11.8L36.5997 10.8C37.0997 10.5 37.3997 10.2 37.5997 9.89999C37.7997 9.49999 37.6997 9.09999 37.4997 8.69999C37.2997 8.29999 36.9997 8.09999 36.5997 7.99999C36.1997 7.89999 35.6997 7.99999 35.1997 8.19999L33.2997 9.19999L34.6997 11.8ZM39.8997 14C40.0997 13.6 39.9997 13.2 39.7997 12.7C39.5997 12.2 39.1997 12 38.7997 11.9C38.3997 11.8 37.8997 11.9 37.3997 12.1L35.3997 13.1L36.8997 16L38.9997 15C39.3997 14.7 39.6997 14.4 39.8997 14Z" fill="black"></path>
                                <path d="M45.7998 4.00001L46.5998 7.00001L50.0998 6.00001L50.4998 7.30001L46.9998 8.30001L47.8998 11.4L51.7998 10.3L52.1998 11.6L46.6998 13.1L43.7998 3.20001L49.2998 1.70001L49.6998 3.00001L45.7998 4.00001Z" fill="black"></path>
                                <path d="M58.1 9.39999L61.6 8.99999L61.7 10.4L56.5 10.9L55.5 0.499988L57.2 0.299988L58.1 9.39999Z" fill="black"></path>
                                <path d="M67.8998 0.1L67.4998 10.5L65.7998 10.4L66.1998 0L67.8998 0.1Z" fill="black"></path>
                                <path d="M75.5997 2.30001L74.9997 5.30001L78.5997 6.00001L78.2997 7.40001L74.7997 6.70001L74.1997 9.80001L78.1997 10.6L77.8997 12L72.1997 10.9L74.1997 0.600006L79.8997 1.70001L79.5997 3.10001L75.5997 2.30001Z" fill="black"></path>
                                <path d="M94.4 6.90001L86.8 15L85 14.3L85.3 3.20001L87 3.80001L86.6 13L92.8 6.20001L94.4 6.90001Z" fill="black"></path>
                                <path d="M100 11.9L98.3 14.4L101.3 16.4L100.5 17.6L97.5 15.6L95.7 18.2L99.1 20.5L98.3 21.7L93.5 18.5L99.3 9.79999L104.1 13L103.3 14.2L100 11.9Z" fill="black"></path>
                                <path d="M115.5 23.8L107.6 30.6L106.5 29.3L114.4 22.5L115.5 23.8Z" fill="black"></path>
                                <path d="M115 41.6L114.1 40.1L118.1 31.7L111.4 35.7L110.5 34.2L119.5 28.8L120.4 30.3L116.4 38.7L123.1 34.7L124 36.2L115 41.6Z" fill="black"></path>
                                <path d="M1.89984 95.3L2.99984 92.9C2.69984 92.5 2.49984 92.1 2.29984 91.6C1.89984 90.7 1.79984 89.8 1.89984 88.9C1.99984 88 2.39984 87.1 2.89984 86.4C3.49984 85.7 4.19984 85.1 5.19984 84.7C6.09984 84.3 7.09984 84.2 7.99984 84.3C8.89984 84.4 9.79984 84.8 10.4998 85.4C11.1998 86 11.7998 86.7 12.0998 87.6C12.4998 88.5 12.5998 89.4 12.4998 90.4C12.3998 91.3 11.9998 92.2 11.4998 92.9C10.8998 93.6 10.1998 94.2 9.19984 94.6C8.29984 95 7.39984 95.1 6.59984 95C5.69984 94.9 4.89984 94.6 4.19984 94.1L2.69984 97.4L1.89984 95.3ZM4.19984 87.5C3.79984 88 3.49984 88.6 3.39984 89.2C3.29984 89.8 3.39984 90.4 3.59984 91.1C3.89984 91.7 4.19984 92.3 4.69984 92.6C5.19984 93 5.79984 93.2 6.39984 93.3C7.09984 93.4 7.69984 93.3 8.49984 93C9.19984 92.7 9.79984 92.3 10.1998 91.8C10.5998 91.3 10.8998 90.7 10.9998 90.1C11.0998 89.5 10.9998 88.9 10.6998 88.2C10.3998 87.6 10.0998 87 9.59984 86.7C9.19984 86.2 8.59984 86 7.99984 86C7.29984 85.9 6.69984 86 5.99984 86.3C5.19984 86.6 4.59984 87 4.19984 87.5Z" fill="black"></path>
                                <path d="M16.9997 97.3L11.5997 101.1C10.9997 101.6 10.5997 102.1 10.4997 102.6C10.3997 103.2 10.5997 103.8 10.9997 104.4C11.3997 105 11.8997 105.4 12.4997 105.5C13.0997 105.6 13.6997 105.4 14.2997 105L19.6997 101.2L20.6997 102.6L15.2997 106.4C14.5997 106.9 13.8997 107.2 13.1997 107.2C12.4997 107.2 11.8997 107.1 11.2997 106.7C10.6997 106.4 10.1997 105.9 9.79968 105.3C9.39968 104.7 9.09968 104 8.99968 103.4C8.89968 102.7 8.99968 102.1 9.19968 101.4C9.49968 100.8 9.99968 100.2 10.6997 99.7L16.0997 95.9L16.9997 97.3Z" fill="black"></path>
                                <path d="M22.6997 116.2L19.4997 113.3L17.4997 114.4L16.1997 113.2L25.9997 107.9L27.4997 109.2L23.2997 119.5L21.9997 118.3L22.6997 116.2ZM23.2997 114.9L25.2997 110.2L20.7997 112.7L23.2997 114.9Z" fill="black"></path>
                                <path d="M30.2996 122.5L33.2996 124.3L32.5996 125.5L28.0996 122.8L33.4996 113.9L34.9996 114.8L30.2996 122.5Z" fill="black"></path>
                                <path d="M43.2 119L39.1 128.6L37.5 127.9L41.6 118.3L43.2 119Z" fill="black"></path>
                                <path d="M54.0997 122.3L53.7997 123.7L51.0997 123L48.8997 131.8L47.1997 131.4L49.3997 122.6L46.6997 121.9L46.9997 120.5L54.0997 122.3Z" fill="black"></path>
                                <path d="M65.7997 123.2L61.9997 129.7L61.7997 133.5L60.0997 133.4L60.2997 129.6L57.1997 122.8L59.0997 122.9L61.2997 128.1L63.9997 123.1L65.7997 123.2Z" fill="black"></path>
                                <path d="M88.9998 118L89.2998 128.9L87.4998 129.5L82.8998 122.7L83.1998 130.9L81.3998 131.5L75.2998 122.5L76.9998 121.9L81.6998 129.3L81.2998 120.5L83.0998 119.9L87.7998 127.2L87.1998 118.5L88.9998 118Z" fill="black"></path>
                                <path d="M98.9999 123.2C98.0999 123.3 97.1999 123.1 96.2999 122.7C95.4999 122.3 94.6999 121.7 94.1999 120.8C93.6999 119.9 93.2999 119.1 93.2999 118.1C93.1999 117.2 93.3999 116.3 93.7999 115.4C94.1999 114.5 94.7999 113.9 95.5999 113.3C96.3999 112.8 97.2999 112.4 98.1999 112.4C99.0999 112.3 99.9999 112.5 100.9 112.9C101.7 113.3 102.4 113.9 103 114.8C103.6 115.7 103.9 116.5 103.9 117.5C104 118.4 103.8 119.3 103.4 120.2C103 121 102.4 121.7 101.5 122.3C100.8 122.8 99.9999 123.1 98.9999 123.2ZM102.1 119.6C102.4 119 102.5 118.4 102.4 117.8C102.3 117.1 102.1 116.5 101.6 115.9C101.2 115.3 100.7 114.8 100.1 114.5C99.4999 114.2 98.8999 114.1 98.2999 114.1C97.6999 114.1 97.0999 114.4 96.4999 114.7C95.8999 115 95.4999 115.6 95.1999 116.1C94.8999 116.7 94.7999 117.3 94.8999 117.9C94.9999 118.6 95.1999 119.2 95.5999 119.8C95.9999 120.4 96.4999 120.9 97.0999 121.2C97.6999 121.5 98.2999 121.7 98.8999 121.6C99.4999 121.6 100.1 121.3 100.7 121C101.3 120.7 101.8 120.2 102.1 119.6Z" fill="black"></path>
                                <path d="M114.3 111.1L109.6 110L108.7 111L111.7 113.9L110.5 115.1L102.9 108L105.4 105.4C105.9 104.8 106.5 104.4 107.1 104.2C107.7 104 108.2 103.9 108.8 104.1C109.3 104.2 109.8 104.5 110.2 104.9C110.7 105.4 111 105.9 111.1 106.6C111.2 107.3 111.1 108 110.7 108.7L115.6 109.8L114.3 111.1ZM107.7 110L109 108.6C109.4 108.1 109.7 107.7 109.6 107.2C109.6 106.7 109.4 106.3 109 106C108.6 105.6 108.2 105.5 107.7 105.5C107.2 105.5 106.8 105.8 106.4 106.2L105.1 107.6L107.7 110Z" fill="black"></path>
                                <path d="M122.6 99.6L116.5 100.6L120.5 103L119.6 104.5L110.7 99.1L111.6 97.6L115.7 100.1L113.7 94.2L114.8 92.4L116.9 99L123.7 97.8L122.6 99.6Z" fill="black"></path>
                                <path d="M126.4 90.8C126 91.2 125.5 91.5 125 91.7C124.5 91.9 123.9 91.8 123.4 91.6L124 89.9C124.4 90 124.8 90 125.2 89.8C125.6 89.6 125.9 89.2 126.1 88.7C126.3 88.1 126.3 87.7 126.2 87.3C126 86.9 125.8 86.6 125.3 86.5C125 86.4 124.7 86.4 124.4 86.5C124.1 86.6 123.9 86.8 123.7 87C123.5 87.2 123.2 87.6 122.9 88.1C122.5 88.7 122.1 89.1 121.8 89.5C121.5 89.9 121.1 90.1 120.6 90.2C120.1 90.4 119.6 90.3 119 90.1C118.4 89.9 118 89.6 117.7 89.1C117.4 88.6 117.2 88.2 117.1 87.6C117.1 87 117.2 86.4 117.4 85.7C117.7 84.7 118.3 84.1 118.9 83.6C119.6 83.2 120.4 83.1 121.2 83.3L120.6 85.1C120.2 85 119.9 85.1 119.5 85.3C119.1 85.5 118.9 85.9 118.7 86.4C118.5 86.9 118.5 87.3 118.7 87.7C118.8 88.1 119.1 88.3 119.6 88.5C119.9 88.6 120.2 88.6 120.4 88.5C120.7 88.4 120.9 88.2 121.1 88C121.3 87.8 121.6 87.4 121.9 87C122.3 86.4 122.7 85.9 123 85.6C123.3 85.3 123.7 85 124.2 84.8C124.7 84.6 125.2 84.7 125.8 84.9C126.3 85.1 126.7 85.4 127.1 85.8C127.4 86.2 127.7 86.7 127.7 87.4C127.8 88 127.7 88.7 127.5 89.4C127.2 89.8 126.9 90.3 126.4 90.8Z" fill="black"></path>
                                <path d="M2.8 65.9C4.3464 65.9 5.6 64.6464 5.6 63.1C5.6 61.5536 4.3464 60.3 2.8 60.3C1.2536 60.3 0 61.5536 0 63.1C0 64.6464 1.2536 65.9 2.8 65.9Z" fill="black"></path>
                                <path d="M125.9 65.9C127.446 65.9 128.7 64.6464 128.7 63.1C128.7 61.5536 127.446 60.3 125.9 60.3C124.354 60.3 123.1 61.5536 123.1 63.1C123.1 64.6464 124.354 65.9 125.9 65.9Z" fill="black"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M40 67C55.2548 67 64 58.2548 64 43C64 58.2548 72.7452 67 88 67C72.7452 67 64 75.7452 64 91C64 75.7452 55.2548 67 40 67Z" fill="black"></path>
                            </svg>
              </div>

             <h2><?= htmlspecialchars($headers['final-left']['header']) ?></h2>
             <a href="careers.php"><i class="fa-solid fa-arrow-up"></i></a>
           </div>
        </div>
        <div class="final-spectrum-right">
           <div class="final-spectrum-right-container">
             <h2 class="highlight-letters"><?= htmlspecialchars($headers['final-right']['header']) ?></h2>
              <a href="contact.php"><i class="fa-solid fa-arrow-up"></i></a>
           </div>
        </div>
    </div>
</div> 

 <?php include('footer.php');?>

</div>


  


</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/Draggable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/CustomEase.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/SplitText.min.js"></script>

 <script src="header.js"></script>
 <script>

 gsap.registerPlugin(ScrollTrigger);

// Animate heading
gsap.fromTo(".project-section .text h1",
  { y: 50, opacity: 0 },
  { 
    y: 0, 
    opacity: 1, 
    duration: 1, 
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".project-section .text h1",
      start: "top 85%",
      end: "bottom 20%",   // optional, can be adjusted
      toggleActions: "play reverse play reverse" // fade in when entering, fade out when leaving
    }
  }
);

// Animate project cards
gsap.fromTo(".project-cards .cardOne",
  { y: 80, opacity: 0 },
  { 
    y: 0, 
    opacity: 1, 
    duration: 1, 
    ease: "power3.out",
    delay: 0.3,       // delay before animation starts
    stagger: 0.25,    // delay between each card
    scrollTrigger: {
      trigger: ".project-card-container",
      start: "top 80%",
      end: "bottom 20%",
      toggleActions: "play reverse play reverse"
    }
  }
);


const carousel = document.querySelector('.carousel'); const list = document.querySelector('.carousel .list'); const items = () => list ? list.querySelectorAll('.item') : []; const nextBtn = document.querySelector('.arrows .next'); const prevBtn = document.querySelector('.arrows .prev'); if (carousel && list) { function showSlider(direction, instant = false) { const els = items(); if (els.length < 2) return; if (instant) carousel.classList.add('no-animate'); if (direction === 'next') { list.appendChild(els[0]); } else { list.prepend(els[els.length - 1]); } if (!instant) { carousel.classList.add(direction); runTimeOut = setTimeout(() => { carousel.classList.remove('next'); carousel.classList.remove('prev'); }, 3000); } else { carousel.classList.remove('next'); carousel.classList.remove('prev'); requestAnimationFrame(() => { carousel.classList.remove('no-animate'); }); } } if (nextBtn) nextBtn.addEventListener('click', () => showSlider('next', true)); if (prevBtn) prevBtn.addEventListener('click', () => showSlider('prev', true)); let autoSlide = setInterval(() => { showSlider('next'); }, 5000); carousel.addEventListener('mouseenter', () => clearInterval(autoSlide)); carousel.addEventListener('mouseleave', () => { autoSlide = setInterval(() => showSlider('next'), 5000); });}
document.addEventListener('DOMContentLoaded', () => {
  const imageWrapper = document.querySelector('.swiper-wrapper');
  const serviceItems = Array.from(document.querySelectorAll('.service-item'));
  const topLabel = document.querySelector('.top-label p');

  // Accept different possible arrow markup: icon alone or a parent button
  const rightTriggers = Array.from(document.querySelectorAll(
    '.fa-arrow-right, .slider-next, .next-btn, .arrow-right'
  ));
  const leftTriggers = Array.from(document.querySelectorAll(
    '.fa-arrow-left, .slider-prev, .prev-btn, .arrow-left'
  ));

  // debug info
  console.log('slider init -> serviceItems:', serviceItems.length,
              'imageWrapper:', !!imageWrapper,
              'rightTriggers:', rightTriggers.length,
              'leftTriggers:', leftTriggers.length);

  if (!imageWrapper || serviceItems.length === 0) {
    console.warn('Slider init aborted: missing .swiper-wrapper or .service-item elements');
    return;
  }

  const totalSlides = serviceItems.length;
  let currentIndex = 0;
  let slideInterval = null;

  function showSlide(index) {
    // guard
    index = ((index % totalSlides) + totalSlides) % totalSlides;

    // move images
    imageWrapper.style.transform = `translateX(-${index * 100}%)`;

    // update text classes
    serviceItems.forEach((item, i) => {
      item.classList.remove('active', 'exit-left');
      if (i === index) {
        item.classList.add('active');
      } else if (i === ((index - 1 + totalSlides) % totalSlides)) {
        // previous slide (for exit animation)
        item.classList.add('exit-left');
      }
    });

    // update counter label if present
    if (topLabel) topLabel.textContent = `${index + 1}/${totalSlides}`;
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
  }
  function prevSlide() {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    showSlide(currentIndex);
  }

  // Attach click listeners to found triggers
  const attachClicks = (els, handler) => {
    els.forEach(el => {
      // make clickable area obvious
      el.style.cursor = 'pointer';
      // protect from default anchors
      el.addEventListener('click', (ev) => {
        ev.preventDefault();
        handler();
        resetInterval();
      });
    });
  };

  attachClicks(rightTriggers, nextSlide);
  attachClicks(leftTriggers, prevSlide);

  // Fallback: if no triggers found, use event delegation (works even if icon is inside button)
  if (rightTriggers.length === 0 || leftTriggers.length === 0) {
    document.addEventListener('click', (ev) => {
      const t = ev.target;
      if (t.closest('.slider-next, .next-btn, .arrow-right') || t.matches('.fa-arrow-right')) {
        ev.preventDefault();
        nextSlide();
        resetInterval();
      } else if (t.closest('.slider-prev, .prev-btn, .arrow-left') || t.matches('.fa-arrow-left')) {
        ev.preventDefault();
        prevSlide();
        resetInterval();
      }
    });
  }

  // keyboard support
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') { nextSlide(); resetInterval(); }
    if (e.key === 'ArrowLeft')  { prevSlide(); resetInterval(); }
  });

  // auto slide
  function resetInterval() {
    if (slideInterval) clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 3000);
  }

  // init
  showSlide(currentIndex);
  resetInterval();
});




gsap.registerPlugin(ScrollTrigger);

// Animate left images/cards
document.querySelectorAll(".who-left-one, .who-left-two").forEach((item, index) => {
  gsap.from(item, {
    x: -100,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    delay: index * 0.2, // delay between items
    scrollTrigger: {
      trigger: item,
      start: "top 90%", 
      toggleActions: "play none none none",
      scrub: false
    }
  });
});

// Animate right section text
document.querySelectorAll(".right-section-text p, .right-section-text h1").forEach((item, index) => {
  gsap.from(item, {
    y: 50,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    delay: index * 0.15,
    scrollTrigger: {
      trigger: item,
      start: "top 90%",
      toggleActions: "play none none none"
    }
  });
});

// Animate right points grid
document.querySelectorAll(".right-points-grid .right-point").forEach((item, index) => {
  gsap.from(item, {
    y: 50,
    opacity: 0,
    duration: 0.8,
    ease: "power3.out",
    delay: index * 0.2,
    scrollTrigger: {
      trigger: item,
      start: "top 95%",
      toggleActions: "play none none none"
    }
  });
});

// Animate right cards
document.querySelectorAll(".right-cards-container .who-cards").forEach((item, index) => {
  gsap.from(item, {
    y: 50,
    opacity: 0,
    duration: 0.8,
    ease: "power3.out",
    delay: index * 0.2,
    scrollTrigger: {
      trigger: item,
      start: "top 95%",
      toggleActions: "play none none none"
    }
  });
});



gsap.registerPlugin(ScrollTrigger);

// Split text into letters but keep words together
const headings = document.querySelectorAll(".highlight-letters");

headings.forEach(heading => {
  const words = heading.textContent.split(" "); // split by words
  heading.innerHTML = words.map(word => {
    const letters = word.split("").map(letter => `<span>${letter}</span>`).join("");
    return `<span class="word">${letters}</span>`; // wrap each word
  }).join(" "); // keep spaces between words
});

// Split each .highlight-letters into words and letters
document.querySelectorAll(".highlight-letters").forEach(heading => {
  const text = heading.textContent.trim(); // keep text safe
  const words = text.split(/\s+/); // split by whitespace
  heading.innerHTML = words.map(word => {
    const letters = [...word] // spread handles apostrophes correctly
      .map(letter => `<span>${letter}</span>`)
      .join("");
    return `<span class="word">${letters}</span>`;
  }).join(" ");
});

// Animate each heading separately
document.querySelectorAll(".highlight-letters").forEach(heading => {
  gsap.fromTo(heading.querySelectorAll(".word span"),
    { color: "#424242", opacity: 0.3, x: 0 },
    { 
      color: "#ffffff",
      opacity: 1,
      x: 10,
      duration: 0.5,
      ease: "power3.out",
      stagger: 0.05,
      scrollTrigger: {
        trigger: heading,
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play reverse play reverse",
        scrub: 0.3
      }
    }
  );
});


document.querySelectorAll(".highlight-letters .red").forEach(heading => {
  const text = heading.textContent.trim(); // keep text safe
  const words = text.split(/\s+/); // split by whitespace
  heading.innerHTML = words.map(word => {
    const letters = [...word] // spread handles apostrophes correctly
      .map(letter => `<span>${letter}</span>`)
      .join("");
    return `<span class="word">${letters}</span>`;
  }).join(" ");
});

// Animate each heading separately
document.querySelectorAll(".red").forEach(heading => {
  gsap.fromTo(heading.querySelectorAll(".word span"),
    { color: "#ffffffff", opacity: 0.3, x: 0 },
    { 
      color: "#000000ff",
      opacity: 1,
      x: 10,
      duration: 0.5,
      ease: "power3.out",
      stagger: 0.05,
      scrollTrigger: {
        trigger: heading,
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play reverse play reverse",
        scrub: 0.3
      }
    }
  );
});







// Refresh ScrollTrigger after DOM rewrite
ScrollTrigger.refresh();


gsap.to(".services-container", {
  backgroundColor: "#000",   // fade target color
  ease: "power2.inOut",      // smooth easing
  scrollTrigger: {
    trigger: ".services-container",
    start: "top 80%",        // when 20% of the section enters viewport
    end: "top 20%",          // fully black when section is near center
    scrub: true              // makes fade smooth with scroll
  }
});
const wrapper = document.querySelector(".spectrum-slider-wrapper");
const slides = gsap.utils.toArray(".company-single-slider");
const slideCount = slides.length;
const slideWidth = slides[0].offsetWidth + 50; // width + gap

// Duplicate slides for seamless looping
wrapper.innerHTML += wrapper.innerHTML;
const allSlides = gsap.utils.toArray(".company-single-slider");

// Keep track of the current position
let sliderIndex = 0;
let autoSlide;

// Auto-slide function
function nextSlide() {
  sliderIndex++;
  gsap.to(wrapper, {
    x: -slideWidth * sliderIndex,
    duration: 0.8,
    ease: "power2.inOut",
    onComplete: () => {
      if (sliderIndex >= slideCount) {
        sliderIndex = 0;
        gsap.set(wrapper, { x: 0 });
      }
    }
  });
}

// Start auto-slide safely
function startAutoSlide() {
  clearInterval(autoSlide);
  autoSlide = setInterval(nextSlide, 3000);
}

// Start auto-slide initially
startAutoSlide();

// Make slider draggable
Draggable.create(wrapper, {
  type: "x",
  inertia: true,
  edgeResistance: 0.9,
  bounds: { minX: -slideWidth * slideCount, maxX: 0 },
  onRelease() {
    // Snap to nearest slide
    sliderIndex = Math.round(-this.x / slideWidth) % slideCount;
    if (sliderIndex < 0) sliderIndex += slideCount; // handle negative index
    gsap.to(wrapper, {
      x: -slideWidth * sliderIndex,
      duration: 0.5,
      ease: "power2.out"
      // no need to restart autoSlide — it never stopped
    });
  }
});
// Elements
document.addEventListener("DOMContentLoaded", () => {
  const newsSliderWrapper = document.querySelector(".news-slider-wrapper"); // track that moves
  const newsSlides = gsap.utils.toArray(".gray"); // each slide
  const newsSliderContainer = document.querySelector(".news-slider-container"); // visible container
  const newsBulletsContainer = document.querySelector(".slider-bullets"); // bullets holder

  // Measurements
  const totalSlides = newsSlides.length;
  const wrapperWidth = newsSliderWrapper.scrollWidth;
  const containerWidth = newsSliderContainer.offsetWidth;

  // Detect exact slide width (with gap if exists)
  const singleSlideWidth =
    newsSlides[1].offsetLeft - newsSlides[0].offsetLeft || newsSlides[0].offsetWidth;

  let currentSlideIndex = 0;
  let autoSlideInterval;

  // Bullets array
  const newsBullets = [];

  // Create bullets dynamically
  newsSlides.forEach((_, idx) => {
    const bullet = document.createElement("span");
    if (idx === 0) bullet.classList.add("active");
    bullet.addEventListener("click", () => goToNewsSlide(idx));
    newsBulletsContainer.appendChild(bullet);
    newsBullets.push(bullet);
  });

  // Update bullets
  function updateNewsBullets() {
    newsBullets.forEach(b => b.classList.remove("active"));
    newsBullets[currentSlideIndex].classList.add("active");
  }

  // Go to specific slide
  function goToNewsSlide(index) {
    currentSlideIndex = index;
    gsap.to(newsSliderWrapper, {
      x: -singleSlideWidth * currentSlideIndex,
      duration: 0.8,
      ease: "power2.inOut"
    });
    updateNewsBullets();
  }

  // Auto-slide
  function moveToNextNewsSlide() {
    currentSlideIndex++;
    if (currentSlideIndex >= totalSlides) currentSlideIndex = 0;
    goToNewsSlide(currentSlideIndex);
  }

  // Start auto-slide
  function startNewsAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(moveToNextNewsSlide, 4000);
  }
  startNewsAutoSlide();

  // Make draggable with snapping
  Draggable.create(newsSliderWrapper, {
    type: "x",
    inertia: true,
    edgeResistance: 0.9,
    bounds: { minX: containerWidth - wrapperWidth, maxX: 0 },
    snap: endValue => {
      return Math.round(endValue / singleSlideWidth) * singleSlideWidth;
    },
    onDragStart() {
      clearInterval(autoSlideInterval); // pause auto-slide while dragging
    },
    onDragEnd() {
      currentSlideIndex = Math.round(-this.x / singleSlideWidth);
      if (currentSlideIndex < 0) currentSlideIndex = 0;
      if (currentSlideIndex >= totalSlides) currentSlideIndex = totalSlides - 1;
      updateNewsBullets();
      startNewsAutoSlide(); // resume auto-slide
    }
  });
});



// Select all elements with class "red"
const redHeadings = document.querySelectorAll(".red");

redHeadings.forEach(heading => {
  const words = heading.textContent.split(" "); // split by words
  heading.innerHTML = words.map(word => {
    const letters = word.split("").map(letter => `<span>${letter}</span>`).join("");
    return `<span class="word">${letters}</span>`; // wrap each word
  }).join(" "); // keep spaces between words
});

// Animate letters on scroll
gsap.fromTo(".red span span",
  { color: "#373737ff", opacity: 0.3, x: 0 },
  { 
    color: "#000000ff",
    opacity: 1,
    x: 10,
    duration: 0.5,
    ease: "power3.out",
    stagger: 0.05,
    scrollTrigger: {
      trigger: ".red",
      start: "top 80%",
      end: "bottom 20%",
      toggleActions: "play reverse play reverse",
      scrub: 0.3
    }
  }
);



 </script>
 <script src="footer.js"></script>
 <script>



  document.addEventListener("DOMContentLoaded", () => {
  gsap.registerPlugin(CustomEase, SplitText);
  CustomEase.create("hop", ".8,0,.3,1");

  const SplitTextElements = (
    selector,
    type = "words,chars",
    addFirstChar = false
  ) => {
    const elements = document.querySelectorAll(selector);
    elements.forEach(element => {
      const splitText = new SplitText(element, {
        type: type,
        wordsClass: "word",
        charsClass: "char",
      });

      if (type.includes("chars")) {
        splitText.chars.forEach((char, index) => {
          const originalText = char.textContent;
          char.innerHTML = `<span>${originalText}</span>`;
          if (addFirstChar && index == 0) {
            char.classList.add("first-char");
          }
        });
      }
    });
  };




    document.fonts.ready.then(() => {

  SplitTextElements(".intro-title h1", "words,chars", true);
  SplitTextElements(".outro-title h1");

  const isMobile = window.innerWidth <= 1000;

  gsap.set(
    [
      ".split-overlay .intro-title .first-char span",
      ".split-overlay .outro-title .char span",
    ],
    { y: "0%" }
  );

  gsap.set(".split-overlay .intro-title .first-char", {
    x: isMobile ? "7.5rem" : "18rem",
    fontWeight: "900",
    scale: 0.75,
  });
   gsap.set(".split-overlay .outro-title .char ", {
display:"none",
  });

gsap.set(".split-overlay .intro-title .first-char", {
   x: isMobile ? "7.5rem" : "5rem",
   fontSize: isMobile ? "7rem" : "14rem",
   fontWeight: "900",
   color: "red",   // ✅ quotes added
   scale: 1.5, 
});


  const tl = gsap.timeline({ defaults: { ease: "hop" } });

  tl.to(".preloader .intro-title .char span", {
      y: "0%",
      duration: 0.75,
      stagger: 0.05,
    }, 0.5
  )
  .to(".preloader .intro-title .char:not(.first-char) span", {
      y: "100%",
      duration: 0.75,
      stagger: 0.05,
    }, 2
  )
  .to(".preloader .outro-title .char span", {
      y: "0%",
      duration: 0.75,
      stagger: 0.075,
    }, 2.5
  )
  .to(".preloader .intro-title .first-char", {
      x: isMobile ? "4rem" : "10.25rem",
      duration: 1
    }, 3.5
  )
.to(".preloader .outro-title .char", {
    x: isMobile ? "-5rem" : "-10rem",
    fontSize: isMobile ? "1rem" : "2rem", // Added font-size for phone/laptop
    duration: 1,
}, 3.5)
  .to(".preloader .intro-title .first-char", {
      x: isMobile ? "7.5rem" : "5rem",
      fontSize: isMobile ? "7rem" : "14rem",
      fontWeight: "900",
      scale: 1.5,        // 🔥 make bigger instead of smaller
      duration: 0.75,
      color: "red",
      onComplete: () => {
        gsap.set(".preloader", {
          clipPath: "polygon(0 0, 100% 0, 100% 50%, 0 50%)",
        });
        gsap.set(".split-overlay", {
          clipPath: "polygon(0 50%, 100% 50%, 100% 100%, 0 100%)",
        });
      }
    }, 4.5
  )
  .to(".preloader .outro-title .char", {
      y: isMobile ? "-3rem" : "-3rem",
       x: isMobile ? "3rem" : "-9rem",  // Added x animation
      fontSize: "2rem",
      fontWeight: "500",
      duration: 0.75,
    }, 4.5
  )
  .to(".container", {
      clipPath: "polygon(0% 48%, 100% 48%, 100% 52%, 0% 52%)",
      duration: 1,
    }, 5
  );
  tl.to ([".preloader", ".split-overlay"],{
  y:(i) => (i==0 ? "-50%" : "50%"),
  duration : 1,
  }, 6)
.to(
  ".container",{
    clipPath:"polygon(0% 0%, 100% 0% ,100% 100% ,0% 100%)",
    duration:1,
  },
  6
);
});

  });


 </script>

</body>
</html>