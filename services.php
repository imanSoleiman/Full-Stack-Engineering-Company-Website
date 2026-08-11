<?php
include './config.php'; // adjust path if needed
$servicess = $conn->query("SELECT * FROM services_cards ORDER BY created_at DESC");


$res = $conn->query("SELECT * FROM services_background_image WHERE id=1");
$da = $res->fetch_assoc();



$section = $conn->query("SELECT * FROM cadd_section LIMIT 1")->fetch_assoc();

// Fetch CAD columns
$columns = $conn->query("SELECT * FROM cadd_columns WHERE cadd_section_id={$section['id']}");


$sections = $conn->query("SELECT * FROM sections");
$sectionsData = [];
while($row = $sections->fetch_assoc()) {
    $sectionsData[$row['section_id']] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
       
      
        #first-part{
            width:100%;
            height: 100%;
        
          
        }
    .services-first-part {
  position: relative; 

  width: 100%;
  height: 600px;
  color: white;
  display: flex;
    }

.services-first-part::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(16, 16, 16, 0.37);
  z-index: 1;
}

.services-text {
  position: relative;
  z-index: 2;
  color:white;
}

       .services-text{
        width:70%;
        margin:30px;
        display:flex;
        flex-direction:column;
        justify-content:flex-end;
        padding:50px;

       }
       .services-text{
        font-size:20px;
           text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6);
       }
       .line{
        height:24px;
        width:5px;
        background-color:brown;
        border-radius:2px;

       }
       .services-first-line{
        display: flex;
        align-items:center;
       gap:15px;
       }
       .services-first-line h1{
    font-size:40px;
       }

       @media screen and (max-width:600px){
.services-text{
        font-size:15px;
           text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6);
       }
       }


#second-part{
    width:100%;
    height: auto;
    padding:20px;
    margin:20px auto;
}
.second-service{
    display:flex;
    flex-direction:column;
    gap:20px;
    justify-content: center;
    align-items:center;
    width:100%;
    height: 100%;
}
.service-text h1{
    font-size:30px;
    text-transform: uppercase;
    margin:10px;
}
.service-cards{
    display:flex;
    gap:50px;
    flex-wrap:wrap;
    justify-content:center;
    overflow-y: hidden;
}
.service-card1{
    height:auto;
    display:flex;
    flex-direction:column;
    width: 500px; 
}
.image-service-section{
       position:relative;
        height:250px; 
}
.image-service-section img{
  height:100%;
    width:100%;
    object-fit: cover;
}
.image-service-section .line-image{
    width:100%;
height:5px;
background-color:gray;
position:absolute;
top:100%;
transition:ease 0.3s;
}


.service-card-text{
    display:flex;
    flex-direction:column;
    justify-content: flex-start;
    gap:10px;
}
.service-card-text h1{
color:#2b2b2b;
margin:20px 0px 10px 0px;
font-size:20px;
}
.learn-more a{
text-decoration: none;
font-size:20px;
color:gray;
}
.service-cards p{
   color: #646874;
}
.service-text{
    text-align: center;
    margin-bottom:40px;
}
.image-service-section:hover .line-image {
    background-color: red;
}
.view-more{
    display:flex;
    gap:10px;
    align-items: center;
}
.view-more a, .view-more div{
    font-size:20px;
    font-weight: 500;
    color:rgba(215, 23, 23, 1);
    
 
}
.view-more div{
        transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(45deg) skew(0deg, 0deg);
    transform-style: preserve-3d;
    transition:ease-in-out 0.3s;
}
.view-more:hover div{
    transform:none;
}

.view-more:hover a,
.view-more:hover div {
    color: gray;
}
#third-part{
    width:100%;
    margin:50px 0px;
}

.third-part-header{
display:flex;
flex-direction:column;
align-items:center;
justify-content: cetner;
gap:10px;
padding:20px;
width:80%;
margin:auto;
}
.third-part-header p{
line-height:30px;
}
#fourth-part{
    width:100%;
    background-color:rgba(212, 202, 202, 0.24);
    height:auto;
  
}
.fourth-container{
    display:flex;
    align-items:center;
    justify-content: center;
    padding:50px;
    gap:60px;
margin:20px 0px;
}
.cad-text-two{
    display:flex;
    justify-content: space-evenly;
    align-items: flex-start;
    gap:20px;
}
.cad-column{
    display:flex;
    flex-direction:column;
}
.cadd-image {
    width:40%;
    
}
.cadd-image img {
    width:100%;
    border-radius:10px;
    
}
.cad-text-container{
    width:40%;
    display:flex;
    flex-direction:column;
    justify-content: center;
}
.cadd-text{
display:flex;
flex-direction:column;
gap:50px;
}
.cadd-text-one h1{
font-size:40px;
margin-bottom:10px;
}
.cad-column{
    display:flex;
    flex-direction:column;
    align-items: flex-start;
    gap:20px;

}
.circle {
    display: inline-block;
    width: 8px; 
    height: 8px;
    background-color: red; 
    border-radius: 50%;
    margin-right: 3px;
    vertical-align: middle;
}
.qa-section {
  padding: 50px;
  line-height: 1.6;
  color: #34495e;
}

.qa-section h2 {
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 15px;
  padding-bottom: 6px;
  border-bottom: 2px solid #2980b9;
  font-size: 1.8rem;
}

.qa-section p {
  margin-bottom: 16px;
  font-size: 1rem;
}

@media (max-width: 1480px) {
   .cad-text-two{
    display:flex;
    flex-direction:column;
    gap:30px;
   } 
}

@media (max-width: 768px) {
    .fourth-container {
        flex-direction: column;
        padding: 20px;
        gap: 30px;
    }

    .cad-text-container, 
    .cadd-image {
        width: 100%;
    }

    .cadd-text-one h1 {
        font-size: 28px;
        text-align: center;
    }

    .cadd-text-one p {
        font-size: 14px;
        line-height: 1.5;
        text-align: center;
    }

    .cad-text-two {
        flex-direction: column;
        gap: 20px;
        
    }

    .cad-column {
        align-items: flex-start;
    }

    .cadd-image img {
        max-width: 100%;
        height: auto;
    }
    .services-text{
        padding:20px;
    }
    .services-first-part{
      height:400px;
    }
    .services-first-line h1{
      font-size:30px;
    }
}

@media (max-width: 480px) {
    .cadd-text-one h1 {
        font-size: 24px;
    }
    .cadd-text-one p {
        font-size: 13px;
    }
    .services-text{
      width:100%;
      margin:2px;
    }
    .third-part-header{
      width:100%;
    }
    .qa-section{
      padding:20px;
    }
}
@media (max-width: 580px ){
.service-card1{
width:340px;
}
}



    </style>
</head>
<body>
    <?php include('header.php') ?>
<section id="first-part"> 
<div class="services-first-part" 
     style="background: url('./assets/service_page_uploads/<?php echo $da['background_image']; ?>') center center / cover no-repeat;">

    <div class="overlay"></div> 
    <div class="services-text">
      <div class="services-first-line"> 
        <div class="line"></div>
        <h1>Our services</h1>
      </div>
      <p><?php echo $da['description']; ?></p>
    </div>
  </div>
</section>

<section id="second-part">
  <div class="second-service">
     <div class="service-text">
         <h1>Our Services</h1>
         <p>The firm's staff is specialized in the following areas & services:</p>
     </div>

     <div class="service-cards">
        <?php while($service = $servicess->fetch_assoc()): ?>
            <div class="service-card1">
                <div class="image-service-section">
                  <img src="./assets/service_page_uploads/<?php echo $service['image']; ?>" alt="<?php echo $service['title']; ?>">
                   <div class="line-image"></div>
                </div>
                
                <div class="service-card-text">
                    <h1><?php echo $service['title']; ?></h1>
                    <p><?php echo $service['short_desc']; ?></p>
                    <div class="view-more">
                       <a href="detail.php?id=<?php echo $service['detail_page_id']; ?>">view more</a>
                       <div>➔</div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
     </div>
  </div>
</section>

<section id="third-part">
    <div class="third-part-header">
        <h1><?= $sectionsData['third-part']['header'] ?></h1>
        <p><?= nl2br($sectionsData['third-part']['content']) ?></p>
    </div>
</section>

<section id="fourth-part">
    <div class="fourth-container">
        <div class="cad-text-container">
            <div class="cadd-text">
                <div class="cadd-text-one">
                    <h1><?= htmlspecialchars($section['header']) ?></h1>
                    <p><?= nl2br(htmlspecialchars($section['description'])) ?></p>
                </div>

                <div class="cad-text-two">
                    <?php
                    $i = 0;
                    echo '<div class="cad-column">';
                    while($col = $columns->fetch_assoc()) {
                        echo "<h3><span class='circle'></span> ".htmlspecialchars($col['text'])."</h3>";
                        $i++;
                        if($i % 2 == 0) echo '</div><div class="cad-column">';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>

        <div class="cadd-image">
            <img src="./assets/service_page_uploads/<?= htmlspecialchars($section['image']) ?>" alt="CADD Image">
        </div>
    </div>
</section>

<section class="qa-section">
  <h2><?= $sectionsData['qa-section']['header'] ?></h2>
  <p><?= nl2br($sectionsData['qa-section']['content']) ?></p>
</section>



<?php include ('footer.php');?>
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
gsap.registerPlugin(ScrollTrigger);

// Animate hero text
gsap.from("#first-part .services-text", {
  y: -100,
  opacity: 0,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: "#first-part",
    start: "top 80%",
    toggleActions: "play none none none"
  }
});

// Animate second-part title
gsap.from(".service-text", {
  y: 30,
  opacity: 0,
  duration: 0.8,
  ease: "power2.out",
  scrollTrigger: {
    trigger: ".service-text",
    start: "top 85%",
    toggleActions: "play none none none"
  }
});

// Animate each service card (already done, but cleaner loop)
gsap.utils.toArray(".service-card1").forEach((card) => {
  gsap.from(card, {
    y: 30,
    opacity: 0,
    duration: 0.8,
    ease: "power2.out",
    scrollTrigger: {
      trigger: card,
      start: "top 85%",
      toggleActions: "play none none none"
    }
  });
});

// Animate third-part text
gsap.from("#third-part .third-part-header", {
  y: 40,
  opacity: 0,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: "#third-part",
    start: "top 85%",
    toggleActions: "play none none none"
  }
});

// Animate fourth-part text and image
gsap.from("#fourth-part .cad-text-container", {
  x: -50,
  opacity: 0,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: "#fourth-part",
    start: "top 85%",
    toggleActions: "play none none none"
  }
});

gsap.from("#fourth-part .cadd-image", {
  x: 50,
  opacity: 0,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: "#fourth-part",
    start: "top 85%",
    toggleActions: "play none none none"
  }
});

// Animate QA section
gsap.from(".qa-section", {
  y: 40,
  opacity: 0,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: ".qa-section",
    start: "top 85%",
    toggleActions: "play none none none"
  }
});



</script>
<script src="header.js"></script>
<script src="footer.js"></script>
</body>
</html>