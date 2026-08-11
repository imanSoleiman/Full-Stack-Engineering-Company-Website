<?php
include('./config.php');

$result = $conn->query("SELECT * FROM team_intro WHERE id=1 LIMIT 1");

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Decode headings safely
    $headings = json_decode($data['headings'], true);
    if (!is_array($headings)) {
        $headings = []; // fallback if JSON is invalid or empty
    }
} else {
    $data = [
        "headings" => "[]",
        "paragraph" => "",
        "image_path" => ""
    ];
    $headings = [];
}

$sec= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM teams_section WHERE id=1"));
$cards = mysqli_query($conn, "SELECT * FROM teams_focus_cards");



// Fetch Middle East section content (header, text, image)
$sectionQuery = $conn->query("SELECT * FROM middle_east_section LIMIT 1");
$section = $sectionQuery->fetch_assoc();

// Fetch countries list for this section
$countries = $conn->query("SELECT * FROM middle_east_countries ORDER BY id ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="./style.css" rel="styleSheet">
    <title>Teams</title> 
    
    <style>

.spectrum-section {
  max-width: 1200px;
  margin: auto;
  margin-top:10px;
  padding: 40px 20px;
  text-align: center;
  font-family: Arial, sans-serif;
  background: #fff; 
}

.spectrum-section h2 {
  font-size: 34px;
  margin-bottom: 10px;
  color: #222;
}

.spectrum-section .tagline {
  font-size: 16px;
  color: #555;
  margin-bottom: 50px;
}

.stats {
  display: flex;
  justify-content: center;
  gap: 30px;
  margin-bottom: 60px;
  flex-wrap: wrap;
}
.stat-card {
  background: #ffe5e5ff;
  padding: 30px 20px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  flex: 1 1 250px;
  transition: transform 0.3s;
}
.stat-card:hover {
transform: translatey(-3px);
}
.left-part{
  margin-bottom:100px;
}
#second-section-team{
    width:100%;
    padding:20px 80px;
    margin:50px auto;
}

.teams-section-container{
    display:flex;
    width:100%;
    justify-content:space-evenly;
    height:auto;
    align-items:center;

}

.second-section-img{
width:500px;
height:450px;
}

.second-section-img img{
width:100%;
height:100%;
object-fit: cover;
border-radius:20px;
object-position: center;
}
#second-section-team{
    width:100%;
}
.text-second{
    width:40%;
    display:flex;
    flex-direction:column;
    gap:20px;
}
.focus-cards{
    margin:10px 0px;
    align-items:flex-start;
    display:flex;
    flex-direction:column;
    gap:10px;
    height:100%;
   
}
.focus-card{
    width:100%;
    height:60px;
    display:inline-block;
    display:flex;
    background-color:white;
    align-items:center;
    border-radius:5px;
    padding:10px;
    gap:10px;
    margin:5px 0;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      transition:ease-in-out 0.2s ;
}
.focus-card:hover{
   transform: translateY(-3px);
}
.focus-card i{
    font-size:17px;
    color:red; 
      background-color: #ffc8c8ff;
    border-radius:50%;
    padding:5px;
  
}
.focus-card h3{
    font-size:15px;
}
.focus-card-text{
    display:flex;
    flex-direction: column;
}
.focus-card-text p{
    display:flex;
    flex-direction: column;
    color:gray;
    font-size:10px;
}
.text{
  color:gray;
  font-size:18px;
  line-height:1.5rem;
}

    .team-intro{
      background-image: url('./images/Happy-work-team-cheering-and-celebrating-at-meeting-team-collaboration.jpg');
      width:100%;
      height:600px;
      position:relative;
      background-position: center center;
      background-size:cover;
      background-repeat: no-repeat;
      border-radius: 0px 0px 2px 20px;
      overflow: hidden;
      
    }
    .team-intro::after {
    content: "";
    width: 100%;
    background: #000000cf;
    opacity: 50%;
    position: absolute;
    height:600px;
    top:0;
    left:0;
    z-index:1;
    
    }
    .team-first-container{
      position: relative;
     width:1500px;
     margin:auto;
     z-index:2;
     height:100%;
    }
    .p-container{
      display:flex;
      align-items:flex-end;
      height:100%;
      margin:0 20px;
    }
    .left-part h1{
      color:white;
      font-size:3rem;
      text-transform: uppercase;
      overflow: hidden;
    }
     .left-part h1 span{
   display:inline-block;
   transform:translateY(100%);
     }  
    

     .left-part p{
      color:white;
      font-size:20px;
      margin:10px 0px;
      
  
    }
    .left-part{
      width:80%;
    }



    @media (max-width: 1400px) {
  .team-first-container {
    width: 1300px;
  }
     .left-part h1{
      color:white;
      font-size:2.5rem;
      text-transform: uppercase;

    }

}

@media (max-width: 1200px) {
  .team-first-container {
    max-width: 1000px;
  }
  .left-part{
    width:50%;
  }
    .team-intro{
    height:390px;
}
.professional-container{
  width: 950px!important;
  left:0;
}


}

@media (max-width: 992px) {
  .team-intro{
    padding-top:120px;
  }
  .team-first-container {
    width: 800px;
   
  }
  .teams-section-container{
  display:flex;
  flex-direction:column;
  gap:10px;
  justify-content:center;
}
.text-second{
  width:90%;
}

}

@media (max-width: 768px) {
.middle-east-image {
display:none;
} 
  .team-first-container {
    width: 600px;
  }
  .left-part
   {
    width: 100%;
  }
  .right-part{
   width: 60%;
}
  .left-part h1 {
    font-size: 32px;
  }
  .left-part p {
    font-size: 16px;
  }
  .left-part button {
    font-size: 14px;
  }
  .middle-east-container{
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-content:center;
  }
  
  .middle-east-left{
    width: 90% !important;
  }
  .middle-east-list {
    width:100%!important;
  }


}

@media (max-width: 560px) {

  .team-first-container {
    width: 100%;
  }
  .left-part
   {
    width: 80%;
  }
  .right-part{
   width: 300px;
}
  .left-part h1 {
    font-size: 25px;
  }
  .left-part p {
    font-size: 16px;
  }

  .left-part a {
    width:120px;
    font-size:12px;
    height:40px;
  }
  .second-section-img{
width:100%;
height:300px;
}
#second-section-team{
 padding:20px 20px;
 margin-bottom: 0px;
}
}

@media (max-width: 330px) {
  .right-part{
   width: 100%;
}  
}
.professional-container{
  margin:auto;
  padding:10px;
  max-width:1200px;
  position: relative; 
   height:auto;
   overflow:hidden
}

.professional-content{
  display:grid;
  grid-template-columns: 200px 1.2fr 1fr ;
  gap:10px;

}
.professional-text{
  height:300px;
  position: relative;
  left:30%;
  top:20%;
  align-self:self-start;
  z-index:1;
}
.professional-text h1{
border-radius:10px;
display: inline-block;
    font-size: 50px;
    background: #ffffffff;
    line-height: 1.05;
    padding: 0 8px;
    letter-spacing: -2px;
    border-radius: 8px;
    padding-left: 0;
    text-transform: uppercase;
    margin:5px 0px;
}
.image-swiper{
    position: relative; 
  height:700px;
  width:100%;

}
.image-swiper img{
    height: 100%;
  width:100%;
    object-fit: cover;
    object-position:center center;
    border-radius:30px;
}
.list{
  margin-bottom:40px;
}
.list p,.country-item p{
     font-size: 15px;
    line-height: 24px;
    color:#363539;
}
.list h2{
  margin-bottom:5px;
  text-transform: uppercase;
  font-size: 20px;
}
.professional-list{
  padding:0px 10px;
  display:flex;
  justify-content:center;
  flex-direction:column;

}
.arrow{ 
    display: flex;
    justify-content: space-between;
    position: absolute;
    top: 60%;
    width: 40%;
    left: 20%;
    z-index: 1;
 
}
.arrow i{
  background-color: #e8e8e87d;
  font-size:24px;
   backdrop-filter: blur(9px);  
  padding:10px;
  border-radius: 50%;
  margin:0px 15px;
  cursor:pointer;
  transition: 0.3s ease-in-out;
}
.arrow i:hover{
  background-color:red;
}
#professional-exellence{
overflow:hidden;
  position: relative; 
  width: 100%;
  height:auto;
    margin:5% auto;
    max-width:1200px;
}
.next{
  display:none;
}
.line-section {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height:auto;
  margin:30px 0;
}
.join-text{
  padding:20px;
  margin:50px 0px;
  display:flex;
  flex-direction:column;
  justify-content: center;
  align-items: center;
  gap:20px;
}
.join-text a{
  color:#e22b2b;
  font-weight: 600;
  font-size:20px;
}
.join-text a:hover{
  color:gray;
}
.line {
  width: 0;
  height: 1px;
  background: #282828ff;

}


.middle-east-left{
    display:flex;
  flex-direction:column;
  justify-content: space-between;
  align-items: flex-start;
  width:40%;
  padding-top:50px;
  height:100%;
}
.middle-east-container{
display:flex;
justify-content: space-around;
  width:1200px;
  height:auto;
  position: relative;
  padding:20px;
  

}
.middle-east-section{
  width:100%;
  display:flex;
  justify-content: center;
  align-items: center;
 background-color: #f7f7f7ff;
 
}
.middle-east-image {
position:absolute;
margin-top:50px;
bottom:0px;
width:400px;
height:300px;
}
.middle-east-image img{
object-fit: cover;
   width:100%;
  height:100%;
}
.middle-east-list{
 display:flex;
 flex-direction:column;
 justify-content:space-evenly;
 height:100%;
 width:40%;
}
.country-item{
  padding: 20px 0px; 
  display:flex;
  flex-direction: column;
  
border-bottom: solid 1px gray;
}
.middle-east-text h2{
  text-transform: uppercase;
  font-size:35px;
  letter-spacing: 2px;
  margin-bottom: 10px;
}
@media screen and ( max-width:1025px){
.professional-container{
  width:900px;
  left:0%;
}
.list h2{ 
font-size: 15px;
}
.professional-text h1{
  font-size:40px;
}
}


@media (max-width: 936px) {
  .professional-content {
    display: flex;            
    flex-direction: column;   
    align-items: center;     
  }
  .arrow{
          display: flex;
    justify-content: space-between;
    position: absolute;
    top: 300px;
    width: 100%;
    left: 0px;
    z-index: 1;
  }


  .professional-text {
    position: static;   
   height: 100px;
  }
  .image-swiper{
    position: relative; 
        height: 400px;
  width:100%;
  }
.professional-container{
width:100% !important;
}
  .image-swiper img {
    height: 100%;
    width: 100%;
    border-radius: 20px;
  }

  .professional-list {
    width: 90%;
    padding: 0 20px;
    align-self: self-start;
    margin:20px 0px;
  }
.left-part{
  margin-bottom:30px;
}
.left-partp{
  font-size:14px;
}
}

@media (max-width: 600px) {
  
.professional-text h1{

    font-size: 30px;

}
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

</style>
</head>
<body>


<?php include('./header.php');?>


 <section class="team-intro" style="background-image: url('<?= $data['image_path'] ?>');">
  <div class="team-first-container">
    <div class="p-container"> 
      <div class="left-part">
        <div class="heading">
          <?php foreach($headings as $h): ?>
            <h1><span class="create"><?= htmlspecialchars($h) ?></span></h1>
          <?php endforeach; ?>
        </div>
        <p><?= htmlspecialchars($data['paragraph']) ?></p>
      </div>
    </div>
  </div>
</section>

<section id="second-section-team">
  <div class="teams-section-container">
    <div class="text-second">
      <h1><?= htmlspecialchars($sec['heading']) ?></h1>
      <p class="text"><?= htmlspecialchars($sec['paragraph']) ?></p>
      
      <div class="focus-cards">
        <?php while($card = mysqli_fetch_assoc($cards)) { ?>
        <div class="focus-card">
          <i class="ri-map-pin-line"></i>
          <div class="focus-card-text">
            <h3><?= htmlspecialchars($card['title']) ?></h3>
            <p><?= htmlspecialchars($card['region']) ?></p>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>

    <div class="second-section-img">
  <img src="./assets/teams/<?= $sec['image'] ?>" alt="Engineering Work">

    </div>
  </div>
</section>


<section class="middle-east-section">
  <div class="middle-east-container">
    
    <div class="middle-east-left">
      <div class="middle-east-text">
        <h2><?php echo htmlspecialchars($section['header']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($section['content'])); ?></p>
      </div>

      <div class="middle-east-image">
        <?php if (!empty($section['image_path'])) { 
          $imagePath = "./assets/teams/" . $section['image_path'];
        ?>
          <img src="<?php echo $imagePath; ?>" alt="Middle East">
        <?php } ?>
      </div>
    </div>

    <div class="middle-east-list">
      <?php while ($row = $countries->fetch_assoc()) { ?>
        <div class="country-item">
          <h3><?php echo htmlspecialchars($row['country_name']); ?></h3>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
        </div>
      <?php } ?>
    </div>

  </div>
</section>



<section id="professional-exellence">
    <div class="arrow">
              <i class="fa-solid fa-arrow-left"></i>
              <i class="fa-solid fa-arrow-right"></i>
            </div>
  <?php
  $slides = $conn->query("SELECT * FROM professional_slides ORDER BY slide_order ASC");
  $index = 0;

  while ($slide = $slides->fetch_assoc()):
      $lists = $conn->query("SELECT * FROM professional_lists WHERE slide_id={$slide['id']}");
  ?>
     <div class="professional-container" style="display: <?= $index === 0 ? 'grid' : 'none' ?>;">
        <div class="professional-content">
          
          <!-- Title -->
          <div class="professional-text">
            <p></p>
            <?php 
              $words = explode(" ", $slide['title']);
              foreach ($words as $w) {
                echo "<h1>" . htmlspecialchars($w) . "</h1>";
              }
            ?>
          </div>

          <!-- Image -->
          <div class="image-swiper"> 
      
            <img src="assets/teams/<?= htmlspecialchars($slide['image']) ?>" alt="Professional Excellence Slide"/>
          </div>

          <!-- Lists -->
          <div class="professional-list">
            <?php while ($list = $lists->fetch_assoc()): ?>
            <div class="list">
              <h2><span><?= htmlspecialchars($list['heading']) ?></span></h2>
              <p><?= htmlspecialchars($list['description']) ?></p>
            </div>
            <?php endwhile; ?>
          </div>

        </div>
     </div>
  <?php 
  $index++;
  endwhile; 
  ?>
  </section>




<section class="line-section">
   <div class="line"></div>
   <div class="join-text">
     <h2>Join Our Team: Recruiting Skilled Construction Workers for Exciting, Rewarding Opportunities</h2>
  <a href="./careers.php">join our team</a>
  </div>

  <div class="line"></div>
</section>


<?php include('./footer.php');?>
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
  const cards = document.querySelectorAll("#second-section-team .focus-card");

  if(cards.length > 0){
    cards.forEach(card => {
      // Animate icon first
      gsap.from(card.querySelector("i"), {
        x: -30,
        opacity: 0,
        duration: 0.6,
        ease: "power2.out",
        scrollTrigger: {
          trigger: card,
          start: "top 90%",
        }
      });

      // Animate text
      gsap.from(card.querySelector(".focus-card-text"), {
        y: 40,
        opacity: 0,
        duration: 0.8,
        delay: 0.1,
        scale: 0.95,
        ease: "power3.out",
        scrollTrigger: {
          trigger: card,
          start: "top 90%",
        }
      });
    });
  }
});

  let t= gsap.timeline({defaults:{ease:"SlowMo.easeOut"}});
  t.to(".create",{y:'0%', duration:0.7,stagger:0.2});


document.querySelectorAll('#professional-exellence .list h2').forEach(h2 => {
  if (!h2.querySelector('span')) {
    const span = document.createElement('span');
    span.className = 'create';
    span.textContent = h2.textContent.trim();
    h2.textContent = '';
    h2.appendChild(span);
  }
});

// --- Slider selection ---
const containers = document.querySelectorAll('.professional-container');
const nextArrow = document.querySelectorAll('.arrow i.fa-arrow-right');
const prevArrow = document.querySelectorAll('.arrow i.fa-arrow-left');
const wrapper = document.getElementById('professional-exellence'); // lock height here

let currentIndex = 0;
let isAnimating = false;

// Initial visibility
containers.forEach((c, i) => {
  c.style.display = i === currentIndex ? 'grid' : 'none';
  gsap.set(c, { xPercent: 0, opacity: i === currentIndex ? 1 : 0 });
});

// Helper: animate content of a slide after it enters (keep yours)
function animateSlideContent(slide) {
  const h2Spans = slide.querySelectorAll('.list h2 span');
  const paras   = slide.querySelectorAll('.list p');
  const img     = slide.querySelector('.image-swiper img');

  gsap.set(h2Spans, { yPercent: 100, opacity: 0 });
  gsap.set(paras,   { y: 10, opacity: 0 });
  if (img) gsap.set(img, { scale: 1.08, opacity: 0 });

  const tl = gsap.timeline();
  if (img) tl.to(img, { scale: 1, opacity: 1, duration: 0.8, ease: "power3.out" }, 0);
  tl.to(h2Spans, { yPercent: 0, opacity: 1, duration: 0.6, stagger: 0.12, ease: "power3.out" }, 0.05)
    .to(paras,   { y: 0, opacity: 1, duration: 0.45, stagger: 0.08, ease: "power2.out" }, "<0.05");
}

// Core slide transition (JS-only fix; no CSS changes)
function showSlide(nextIndex, direction) {
  if (nextIndex === currentIndex || isAnimating) return;
  isAnimating = true;

  const currentSlide = containers[currentIndex];
  const nextSlide    = containers[nextIndex];

  // Prevent layout jump: lock wrapper height during the transition
  const lockH = Math.max(currentSlide.offsetHeight, nextSlide.offsetHeight);
  wrapper.style.height = lockH + 'px';

  // Prepare slides
  nextSlide.style.display = 'grid';
  gsap.killTweensOf([currentSlide, nextSlide]);
  gsap.set(currentSlide, { xPercent: 0, opacity: 1 });
  gsap.set(nextSlide,    { xPercent: 100 * direction, opacity: 0 });

  const tl = gsap.timeline({
    defaults: { duration: 0.6, ease: "power2.inOut" },
    onComplete: () => {
      currentSlide.style.display = 'none';

      // reset current slide content so it can animate again when revisited
      gsap.set(currentSlide.querySelectorAll('.list h2 span'), { yPercent: 100, opacity: 0 });
      gsap.set(currentSlide.querySelectorAll('.list p'), { y: 10, opacity: 0 });

      // IMPORTANT: update index & release height lock
      currentIndex = nextIndex;
      wrapper.style.height = ''; // back to auto

      isAnimating = false;
    }
  });

  tl.to(currentSlide, { xPercent: -100 * direction, opacity: 0 }, 0)
    .to(nextSlide,    { xPercent: 0, opacity: 1 }, 0)
    .add(() => animateSlideContent(nextSlide), "-=0.3");
}

function updateSlideNumber(slideIndex) {
  const total = containers.length;
  const p = containers[slideIndex].querySelector('.professional-text p');
  if (p) p.textContent = `${slideIndex + 1}/${total}`;
}

// On page load, set first slide number and animate
updateSlideNumber(currentIndex);
animateSlideContent(containers[currentIndex]);

// Arrows
nextArrow.forEach(a => a.addEventListener('click', () => {
  const next = (currentIndex + 1) % containers.length;
  updateSlideNumber(next);
  showSlide(next, 1);
}));

prevArrow.forEach(a => a.addEventListener('click', () => {
  const prev = (currentIndex - 1 + containers.length) % containers.length;
  updateSlideNumber(prev);
  showSlide(prev, -1);
}));


gsap.registerPlugin(ScrollTrigger);

gsap.timeline({
  scrollTrigger: {
    trigger: ".line-section",
    start: "top 80%", 
    toggleActions: "play none none reverse"
  }
})
.to(".line-section .line:first-of-type", { width: "90%", duration: 0.6, ease: "power2.out" })
.to(".line-section .line:last-of-type",  { width: "90%", duration: 0.6, ease: "power2.out" }, "+=0.3");




gsap.registerPlugin(ScrollTrigger);

// Animate title words (each <h1>)
gsap.from("#professional-exellence .professional-text h1", {
  y: 80,
  opacity: 0,
  stagger: 0.15,  // one after another
  duration: 0.8,
  ease: "power3.out",
  scrollTrigger: {
    trigger: "#professional-exellence",
    start: "top 70%",   // when section enters viewport
  }
});



// 🌟 Second Section Team (text + image reveal)
gsap.from("#second-section-team .text-second h1", {
  x: -80,
  opacity: 0,
  duration: 0.9,
  ease: "power3.out",
  scrollTrigger: {
    trigger: "#second-section-team",
    start: "top 75%",
  }
});

gsap.from("#second-section-team .text-second .text", {
  x: -60,
  opacity: 0,
  duration: 0.8,
  delay: 0.2,
  ease: "power2.out",
  scrollTrigger: {
    trigger: "#second-section-team",
    start: "top 75%",
  }
});

gsap.from("#second-section-team .second-section-img img", {
  scale: 1.2,
  opacity: 0,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: "#second-section-team .second-section-img",
    start: "top 80%",
  }
});

// 🌟 Middle East Section (fade in + slide-up)
gsap.from(".middle-east-text h2", {
  y: 60,
  opacity: 0,
  duration: 0.8,
  ease: "power3.out",
  scrollTrigger: {
    trigger: ".middle-east-section",
    start: "top 75%",
  }
});

gsap.from(".middle-east-text p", {
  y: 40,
  opacity: 0,
  duration: 0.8,
  delay: 0.2,
  ease: "power2.out",
  scrollTrigger: {
    trigger: ".middle-east-section",
    start: "top 75%",
  }
});

gsap.from(".middle-east-image img", {
  x: 100,
  opacity: 0,
  duration: 1,
  ease: "power2.out",
  scrollTrigger: {
    trigger: ".middle-east-image",
    start: "top 80%",
  }
});

gsap.from(".middle-east-list .country-item", {
  y: 50,
  opacity: 0,
  duration: 0.7,
  stagger: 0.2,
  ease: "power2.out",
  scrollTrigger: {
    trigger: ".middle-east-list",
    start: "top 85%",
  }
});


</script>
<script src="header.js"></script>
<script src="footer.js"></script>

</body>
</html>