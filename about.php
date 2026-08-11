<?php
include 'config.php';

$sec = $conn->query("SELECT * FROM who_we_are_section LIMIT 1")->fetch_assoc();
$imagesResult = $conn->query("SELECT * FROM who_we_are_images ORDER BY position ASC");
$images = [];
while($img = $imagesResult->fetch_assoc()) { $images[$img['position']] = "./assets/about/".$img['image_name']; }
$vision = $conn->query("SELECT * FROM vision_section LIMIT 1")->fetch_assoc();


$cards = $conn->query("SELECT * FROM our_story_cards ORDER BY id ASC");
$section = $conn->query("SELECT * FROM our_story_section LIMIT 1")->fetch_assoc();

$home = $conn->query("SELECT * FROM home_section LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="style.css"/>
  <title>About Us</title>


  <style>

      
   html {
  overflow-x: hidden; /* prevents page scroll horizontally */
}

body {
  scroll-behavior: smooth;
  /* remove overflow-x here if present */
}

        #main{
          width:100%;
          z-index:1;
          height:auto;
        }

      #loader {  
        position:absolute;
        width: 100%;
        height: 100vh;
        background-color:#A62C2C;
        z-index: 9999;
      }
      .noscroll {
    overflow: hidden;
    height: 100vh; 
    position: fixed;
    width: 100vw;
  }

      #loader #topheading{
        position:absolute;
        top:30%;
        left:50%;
        transform: translate(-50%,0);
        text-align:center;
      }
      #topheading h5{
      text-transform:uppercase;
      font-size:10px;
      font-weight:300;
      text-align:center;
      justify-content:center;
      color: white;

      }

      #loader h1{
        position:absolute;
        top:50%;
        left:50%;
        transform:translate(-50%,-50%);
        font-size:4vw;
        font-weight: 500;
        width:100%;
        display:flex;
        color: white;
        font-family:"gilroy";
      }
  

      .reveal .parent {
        overflow-y: hidden;
        width:100%;
        display:flex;
        justify-content:center;
        flex-wrap:wrap;
      }

      .reveal .parent .child {
        display:block;
        padding:10px;

      }
      .parent .child span{
        display:inline-block;
      }

  #about{
  color: black;
  font-family:italic;
  }

  #home{
    width: 100%;
    height:auto;
    background-color:white;
    padding-top:200px;
    }

  .row{
    display:flex;
  align-items: center;
  padding: 0 5vw;
  padding-right: 10vw;
  line-height: 1;
  color:#333;
  justify-content: space-between;
  overflow-x: hidden;
  }

  .row h1{
    font-size:7vw;
    font-weight:500;
  }

  .row #first{
  padding-top:20px;

  }
  #special{
    color:#C62E2E;
    font-size: 9vw;
    font-family: caveat;
    align-items:center;
  }

  #imagery{  
    display:flex;
    align-items:center;
    width:100%;

  }

  #imagery #imagelef{
    display:flex;
    width: 50%;
    height: auto;

  }
  #imagelef h3{
      font-family: "Roboto", sans-serif;
      font-weight: 400;
      line-height: 28px;
      font-size: 18px;
      color: #000;
      margin-bottom: 0px;
  }

  #imgrig{
  display:flex;
  justify-content: center;
  align-items: center;
  position:relative;
  width:50%;
  height:600px;
  overflow:hidden;
  }

  #imgrig .imgcntr{
    position:absolute;
    width:20vw;
    height:70%;
    background-color: red;
    border-radius:1vw;
    transition: all cubic-bezier(0.19, 1, 0.22,1 ) 2s;
    filter:grayscale();
  }

  #imgrig .imgcntr:hover{
  filter: grayscale(0);
  }
  #imgrig .imgcntr:nth-child(1) {
    transform:translate(-30%,-8%) rotate(-9deg);
  background-size:cover;
  background-position: center;
  }
  #imgrig .imgcntr:nth-child(2) {
    transform:translate(50%,-5%) rotate(-15deg);
    background-size:cover;
    background-position: center;

  }



  #vision{
      position: relative;
      width: 100%;
      height: 100vh;
      background-color: aquamarine;
      overflow: hidden;
  }

  #top{
      position: absolute;
      top: 0%;
      width: 100%;
      height: 50vh;
      background-color: rgb(255, 255, 255);
      z-index: 9;
      overflow: hidden;
  }

  #center{
      position: relative;
      width: 100%;
      height: 100vh;
      transform-origin: center;
      background-color: rgb(0, 0, 0);
      overflow: hidden;
  }

  #bottom{
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 50vh;
      background-color: rgb(255, 255, 255);
      overflow: hidden;
  }

  #vision h1 {
      font-size:21vw;
      position: absolute;

      left: 50%;
      transform: translate(-50%, -50%);
      white-space: nowrap; 
  }


  #top-h1 {
    top:100%;
    clip-path: inset(0 0 50% 0);   /* show top half */
    transform: translateY(0%);
  }

  #bottom-h1 {
    top:0%;
    clip-path: inset(50% 0 0 0);   /* show bottom half */
    transform: translateY(0%);
  }


  .content{

    height:100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100%;
      color: #fff;
  


  }

  .content h4{
      font-size: 13px;

  }

  .content h3{
      width: 50%;
      font-size: 20px;
      font-weight: 400;
  }


  .content h2{
      font-size: 20vw;

  }

  #bottom-h1 span{
      color: #000000ff; 
  }
  #top-h1 span{
      color: #000000ff; 
  }
  

  @media (max-width:1024px) and (min-width:769px){
    #imgrig .imgcntr{
      width:20vw;         
      height:32vw;         
    }
    #imgrig .imgcntr:nth-child(1){transform:translate(-25%,-5%) rotate(-8deg);}
    #imgrig .imgcntr:nth-child(2){transform:translate(40%,-5%) rotate(-12deg);}

    #home .row img{
      width:28px;          
    }
  }


  .who-we-are-section{
    max-width:1240px;
    margin:auto;
    padding-top:100px;
  }
  .who-we-are-header{
    padding:50px;
        max-width: 905px;
  }
  .who-we-are-header h2{
        text-transform: uppercase;
      font-size: 45px;
      font-weight: 600;
      color: #000;
      letter-spacing: -1px;
      line-height: 1.088;
      margin-bottom: 0;
  }

  .who-we-are-header h4{
        text-transform: capitalize;
      font-weight: 400;
      color: #7b7b7bff;
      letter-spacing: -1px;
      line-height: 1.088;
      margin-bottom:10px;
  }
  .highlight-letters {
    display: inline-block;
    line-height: 1.2;
  }

  .highlight-letters .word {
    display: inline-block;
    white-space: nowrap;  

  }
  .highlight-letters span span {
    display: inline-block;
  }
  .line {
    width: 0%;            /* start small */
    height: 0.2px;          /* better visibility than 0.3px */
    background-color: black;
    transition: width 0.3s ease-out;
  }
          .our-story{
    padding:50px;
  }
  .our-story-header{
    max-width:960px;
    margin:auto;
    margin-bottom:50px;
    text-align:center;
  }
  .class-header-container{
    display:flex;
    flex-direction:column;
    margin: 0 auto;
  }
  .class-header-container h2{
    font-size:90px;
    text-transform: uppercase;
    margin-bottom: 50px;
  }
  .our-story-par{
  font-size: 20px;
  color:gray;
  }
  .about-us{
    padding:0 50px;
  }
  .our-story-singleCard{
    position: sticky;
    top: 50px; /* sticks to top */
    margin-bottom: 50px; /* overlap the next card */
    z-index: 1;
  width: 100%; /* ensure it fills container */

  }


  .our-story-singleCard h6{
    position:absolute;
      bottom: 20px;
      left: 5px;
      writing-mode: vertical-lr;
      margin: 0;
      padding: 0;
      font-size: 18px;
      display: flex;
      gap: 10px;
      font-weight:400;
  }
  .our-story-card-wrapper{
    position:relative;
  }
  .our-story-cardContent{
  display:grid;
  grid-template-columns: 1fr 1fr;
      border-radius: 16px;
      background: #eaeaeaff;
      padding: 30px;
      align-items: center;
      gap: 20px;
    
  }
  .image-left-story{
  height:350px;
  position:relative;
  overflow:hidden;
  border-radius:10px;
  }
  .image-left-story img{
    height:100%;
    width:100%;
    box-shadow:  0 12px 30px rgba(0, 0, 0, 0.2);
    object-fit: cover;
  }


  .our-story-left p{
      line-height: 26px;
      margin-bottom: 21px;
      font-weight: 400;
      line-height: 28px;
      font-size: 18px;
      color: #000;
      margin-bottom: 0px;
      opacity:0.72;
  }
  .our-story-left {
  display:flex;
  flex-direction:column;
  justify-content: center;
  gap:10px;
  padding:20px;
  }
  .image-heading{
  font-size:45px;
  }
  .relative{
    position:relative;

  }
  .card-number{
    color:gray;
    font-weight:400;
  }
  .our-story-singleCard:nth-child(n) {
    z-index: calc(1 + n); /* n is the index (1,2,3...) */
  }


  @media screen  and (max-width: 960px){
  .class-header-container h2 {
  font-size:60px;
  }
  }
  @media screen and (max-width: 768px ){
    .class-header-container h2{
      font-size:30px;
    }
    #imagelef h3{
      font-size:15px;
    }
    .about-us{
    padding: 0 12px;
  }
  .who-we-are-section{
  padding-top:30px;
  }
  .who-we-are-header h2 {
  font-size:40px;
  }
  .our-story-cardContent{
    display:grid;
    grid-template-rows:0.5fr 0.5fr;
    grid-template-columns: none;
  }
  .our-story-left{
    padding:5px;
  }.image-heading{
    font-size:30px;
  }
  .our-story{
    padding:30px;
  }
    .row{
        flex-wrap:wrap;     
        gap:4px;
        align-items:center;
        justify-content:center;  
    }
    #abouthead{
  padding-top:20vw;
    }
    #special{               
        font-size:12vw;
        margin-right:0;
    }                                     
    #home .row img{
        width:30px;          
        margin-right:4px;   
    }
                                          
    #imagery{
        flex-direction:column;  
        gap:20px;
        margin-top:10vw;        
    }
    #imagery #imagelef{
        width:100%;
        padding:10px;
    }

    #imgrig{
        width:100%;
        height:100vw;            
    }
    #imgrig .imgcntr{
        width:50vw;              
        height:70vw;
    }
    #imgrig .imgcntr:nth-child(1){
        transform:translate(-10%,-5%) rotate(-5deg);
    }
    #imgrig .imgcntr:nth-child(2){
        transform:translate(20%,0) rotate(-9deg);
    }
    #home{
      padding-top:20px;
    }
    .our-story-singleCard h6{
      top:5%;
    }
    .our-story-singleCard{
      top:5px;
    }

  }
  @media screen and (max-width :500px){
  .who-we-are-header h2{
  font-size: 25px;
  }
  .who-we-are-header h4{
  font-size: 12px;
  }
  .who-we-are-header{
  padding:30px;
  }
  .our-story{
    padding:10px;
  }
    .content h3{
      width:70%;
      font-size:4.5vw;
    }
    .content h1{
      font-size:20px;
    }
  }
  @media screen and (max-width :400px){

  .image-left-story{
    height:250px;
  }
    .our-story-singleCard h6{
      font-size:15px;
    }
    .image-heading{
      font-size: 20px;
    }
    .our-story-par{
      font-size:15px;
    }
    #imagelef h3 {
      font-weight: 400;
      line-height: 20px;
      font-size: 12px;


      }  }

    

    </style>

</head>
<body>
  <header> <?php include('header.php');?></header>

 <div id=main> 
  <div id="loader">
    <div id="topheading">
      <h5 class="reveal"> spectrumLB</h5>
    </div>
   <h1 class="reveal"><span>WHO </span> <span id="about"> WE </span> <span> ARE</span> </h1>
   </div>
</div>
   
<div id="home">
   <div class="row" id="abouthead">
    <h1 class="reveal" id="first"><?= htmlspecialchars($home['first_heading'] ?? 'Engineering Innovation') ?></h1>
   </div>
    <div class="row">
      <img src="images/down-arrow.svg" class="reveal">
      <h1 id="special" class="reveal"><?= htmlspecialchars($home['special_heading'] ?? 'Delivering') ?></h1>
      <h1 class="reveal"><?= htmlspecialchars($home['last_heading'] ?? 'Excellence') ?></h1>
    </div>
</div>

<div class="who-we-are-section">
   <div class="who-we-are-header">
       <h4 class="highlight-letters"><?= htmlspecialchars($sec['section_title'] ?? '') ?></h4>
       <h2 class="highlight-letters"><?= htmlspecialchars($sec['section_subtitle'] ?? '') ?></h2>
   </div>
   <div class="line"></div>
   <div class="about-us">
       <div id="imagery">
           <div id="imagelef">
               <h3><?= htmlspecialchars($sec['left_text'] ?? '') ?></h3>
           </div>
           <div id="imgrig">
               <?php foreach($images as $pos => $imgPath): ?>
                   <div class="imgcntr" data-pos="<?= $pos ?>" style="background-image: url('<?= $imgPath ?>');"></div>
               <?php endforeach; ?>
           </div>
       </div>
   </div>
</div>


    
<div class="our-story">
  <div class="our-story-header">
    <div class="class-header-container">
      <p class="highlight-letters"><?= htmlspecialchars($section['section_title']) ?></p>
      <h2 class="highlight-letters"><?= htmlspecialchars($section['section_subtitle']) ?></h2>
      <p class="our-story-par">
        <?= htmlspecialchars($section['section_paragraph']) ?>
      </p>
    </div>
  </div>

  <div class="our-story-card-wrapper">

    <?php 
    $counter = 1;
    while ($card = $cards->fetch_assoc()): 
    ?>
    <div class="our-story-singleCard">
      <h6>
        <span class="card-number">
          <?= str_pad($counter, 2, "0", STR_PAD_LEFT) ?>
        </span>
        <span><?= htmlspecialchars($card['title']) ?></span>
      </h6>
      <div class="our-story-cardContent">
        <div class="image-left-story">
          <img src="./assets/about/<?= htmlspecialchars($card['image_path']) ?>" 
               alt="<?= htmlspecialchars($card['title']) ?>">
        </div>
        <div class="our-story-left">
          <h4 class="image-heading"><?= htmlspecialchars($card['title']) ?></h4>
          <p><?= htmlspecialchars($card['description']) ?></p>
        </div>
      </div>
    </div>
    <?php 
      $counter++;
    endwhile; 
    ?>

  </div>
</div>

<div id="vision">
  <div id="top"><h1 id="top-h1">VIS<span>I</span>ON</h1></div>
  <div id="center">
    <div class="content">
      <h4><?= htmlspecialchars($vision['heading'] ?? '') ?></h4>
      <h3><?= htmlspecialchars($vision['subheading'] ?? '') ?></h3>
    </div>
  </div>
<div id="bottom"><h1 id="bottom-h1">VIS<span>I</span>ON</h1></div>
</div>


 <?php include('footer.php');?>
<script src="https://unpkg.com/split-type"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script
      src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/gsap.min.js"
      integrity="sha512-qF6akR/fsZAB4Co1QDDnUXWnaQseLGXoniuSuSlPQK6+aWhlMZcHzkasCSlnWoe+TJuudlka1/IQ01Dnhgq95g=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/ScrollTrigger.min.js"
      integrity="sha512-IHDCHrefnBT3vOCsvdkMvJF/MCPz/nBauQLzJkupa4Gn4tYg5a6VGyzIrjo6QAUy3We5HFOZUlkUpP0dkgE60A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script>
document.addEventListener("DOMContentLoaded", () => {
  const headings = document.querySelectorAll(".highlight-letters");

  headings.forEach(heading => {
    // Split words and letters
    const words = heading.textContent.split(" ");
    heading.innerHTML = words.map(word => {
      const letters = word.split("").map(letter => `<span>${letter}</span>`).join("");
      return `<span class="word">${letters}</span>`;
    }).join(" ");

    // Animate each heading individually
    gsap.fromTo(
      heading.querySelectorAll("span span"),
      { color: "#ffc8c8f2", opacity: 0.3, x: 0 },
      { 
        color: "#000000ff",
        opacity: 1,
        x: 13,
        ease: "power2.out",
        stagger: 0.09,
        scrollTrigger: {
          trigger: heading,
          start: "top 80%",
          end: "bottom 20%",
          scrub: true
        }
      }
    );
  });
});

gsap.registerPlugin(ScrollTrigger);

gsap.to(".our-story", {
  backgroundColor: "#000",

  scrollTrigger: {
    trigger: ".our-story",
    start: "top 50%",   // when middle of section is ~60% down viewport
    end: "center 40%",     // finish fading when middle is ~40% up
    scrub: 1.5             // smooth fade
  }
});

    </script>
    <script src="about.js"></script>
    <script src="header.js"></script>
    <script src="footer.js"></script>
</body>
</html>
