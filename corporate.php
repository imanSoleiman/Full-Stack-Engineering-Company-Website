<?php
include ('./config.php');
$result = $conn->query("SELECT * FROM corporate_first_section WHERE id = 1");
$data = $result->fetch_assoc();

$res = $conn->query("SELECT * FROM corporate_second_section WHERE id = 1");
$dt = $res->fetch_assoc();


$re = $conn->query("SELECT * FROM corporate_third_section WHERE id = 1");
$da = $re->fetch_assoc();

$section = $conn->query("SELECT * FROM corporate_fourth_section WHERE id = 1")->fetch_assoc();
$cards = $conn->query("SELECT * FROM corporate_fourth_cards");



$sec = $conn->query("SELECT * FROM corporate_fifth_section WHERE id = 1")->fetch_assoc();
$c = $conn->query("SELECT * FROM corporate_fifth_cards");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&family=Edu+VIC+WA+NT+Hand:wght@400..700&family=Merriweather:wght@600&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="style.css"/> 
   <title>corporate responsibility </title>   

    <style>
      main{
        overflow-x: hidden;
      }

.container{
    width: 100%;
    padding: 15vh;
    padding-top:2vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 2rem;
    height:auto;
}

.card1, .card2, .card3, .card4, .card5{
    position: sticky;
    top: 10vh;
    width: 50rem;
    padding: 5rem 1rem;
    background: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 1rem;
  box-shadow: 0 2px 8px rgba(20, 60, 70, 0.15);
    border-radius: 25px;
     background-color: #fff1f2;
}


.card1 h1, .card2 h1, .card3 h1, .card4 h1{
    font-size: 2rem;
    font-weight: 600;
    color: black;
}

.card1 p, .card2 p, .card3 p, .card4 p{
    color: black;
    padding: 0 1rem;
}
.support-header {
    padding: 60px 30px 30px;
    font-size:20px;
}

.support-header h2 {
    font-weight:600;
    font-size: 3rem;
    color: #151516ff;
    position: relative;
    display: inline-block;
    margin-bottom: 21px;
    margin-top:30px;
}

@media only screen and (max-width: 798px){
  .container {
    flex-direction: column;
    padding: 2rem 2rem;
    padding-bottom:10rem;
  }

  .card1, .card2, .card3, .card4, .card5 {
    width: 100%;
    padding: 2rem 1rem;

    position: relative;
  }
  .support-header h1 {
    font-size:30px;
       margin-bottom: 10px;
  }
  .card1 h1, .card2 h1, .card3 h1, .card4 h1{
    font-size: 2rem;
    font-weight: 600;
    color: black;
}
    }
.responsibility {
  padding: 60px 20px;
  padding-top:150px;
  background-color: #fff;
  color: #1a1a1a;
}

.respons-container {
  display: flex;
  gap: 40px;
  flex-wrap: wrap;
  align-items: flex-start;
  max-width: 1200px;
  margin: 0 auto;
}

.response-left {
  flex: 1 1 500px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
}

.response-left h1 {
  font-size: 2.2rem;
  font-weight: bold;
  color: #0f172a;
}

.response-left p {
  font-size: 1.05rem;
  line-height: 1.7;
  color: #475569;
}

.responsibility-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 10px;
}

.responsibility-card1 {
  background-color: #fff1f2;
  padding: 20px;
  border-radius: 12px;
  width: 280px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.responsibility-card1:hover {
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  transform: translateY(-3px);
}

.responsibility-card1 .icon {
  background-color: #ffe4e6;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
}

.responsibility-card1 i {
  font-size: 20px;
  color: #dc2626;
}

.responsibility-card1 h2 {
  font-size: 1.1rem;
  margin-bottom: 6px;
  color: #1a1a1a;
}

.responsibility-card1 p {
  font-size: 0.95rem;
  color: #475569;
}

.response-right {
  flex: 1 1 400px;
display: flex;
  justify-content: center;
  align-items: center;
}

.response-right img {
  width: 100%;
  max-width: 500px;
  border-radius: 10px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  object-fit: cover;
  height:500px;
}
.social-people{
 display:flex;
flex-direction:column;
gap:30px;
align-items:flex-start;
width:100%;

}
.social-container-head{
  display:flex;
  gap:10px;
  align-items:flex-start;
}
.social-container-head {
  display:flex;
  gap:10px;
  align-items:flex-start;
}
.social-container-text h3{
font-size:15px;
}
.responsibility-header {
  display:flex;
  flex-direction: column;
  gap:20px;
  align-items:center;
  margin:10px;
}
.second-part{ 
  display:flex;
  flex-direction:column;
  align-items: center;
  width:100%;
  margin:10px;
 
}
 .customer-satisfaction{
  max-width:900px;
  display:flex;
  gap:10px;
  flex-direction:column;
  align-items:center;
  margin:20px;


}
 p {
  font-size: 1.1rem;
  margin-bottom: 6px;
  color:  #475569;
}

.customer-satisfaction h1 {
  font-size: 2rem;
  margin: 6px;
  color: #1a1a1a;
}
.social-container{
display:flex;
flex-direction:row;
align-items:center;
justify-content: center;
width:100%;
gap:20px;
flex-wrap:wrap;
margin:20px 0px;
}

.social-cards{
width:300px;
height:200px;
box-shadow: 0 2px 8px rgba(20, 60, 70, 0.15);
padding:20px;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
gap:15px;
transition:ease 0.3s;
}

.social-cards:hover{
transform:translatey(-4px);
}

.social-cards i{
display:flex;
background-color: rgb(254 226 226 );
padding:15px;
font-size:15px;
border-radius:100%;
}
.social-cards h2{
  font-size: 1.1rem;
  margin-bottom: 6px;
  color: #1a1a1a;
}
.social-responisibility{
  background-color:rgb(254 226 226 );
  display:flex;
  flex-direction:column;
  width:100%;
  padding:10px;
  align-items:center;
  gap:20px;
  margin:20px 0px;
  height:300px;
  justify-content: center;
}
.social-responsibility-header {
  display:flex;
  flex-direction:column;
  justify-content:center;
  font-size:25px;
  gap:10px;
}
.social-responsibility-container{
  max-width:800px;
  padding:20px;
}

.red{
  color:red;
  font-size:20px;
}
@media (max-width: 900px) {
  .respons-container {
    flex-direction: column;
    align-items: center;
  }

  .response-left, .response-right {
    flex: 1 1 100%;
  }

  .responsibility-cards {
    justify-content: center;
  }

  .responsibility-card1 {
    width: 100%;
    max-width: 320px;
  }
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



    </style>
</head>
<body>
<?php include('header.php');?>

<main>  
  
<section class="responsibility">
  <div class="respons-container">
    <div class="response-left">
      <h1><?= htmlspecialchars($data['section_header']) ?></h1>
      <p><?= nl2br(htmlspecialchars($data['paragraph1'])) ?></p>
      <p><?= nl2br(htmlspecialchars($data['paragraph2'])) ?></p>

      <div class="responsibility-cards">
        <div class="responsibility-card1">
          <div class="icon"><i class="fas fa-chart-line"></i></div>
          <h2><?= htmlspecialchars($data['card1_title']) ?></h2>
          <p><?= htmlspecialchars($data['card1_text']) ?></p>
        </div>
        <div class="responsibility-card1">
          <div class="icon"><i class="fas fa-shield-alt"></i></div>
          <h2><?= htmlspecialchars($data['card2_title']) ?></h2>
          <p><?= htmlspecialchars($data['card2_text']) ?></p>
        </div>
      </div>
    </div>

    <div class="response-right">
   <img src="./assets/corporate/<?php echo htmlspecialchars($data['image_path']); ?>" alt="Responsibility Image">
    </div>
  </div>
</section>

 
<section class="social-responisibility">
  <div class="social-responsibility-container">
    <div class="social-responsibility-header">
      <h1><?= htmlspecialchars($dt['section_title']) ?></h1>
      <p class="red"><?= htmlspecialchars($dt['section_subtitle']) ?></p>
    </div>

    <div class="social-responisibility-details">
      <p><?= nl2br(htmlspecialchars($dt['section_paragraph'])) ?></p>
    </div>
  </div>
</section>


<section class="responsibility">
  <div class="respons-container">
    <div class="response-right">
<img src="./assets/corporate/<?= htmlspecialchars($da['image_path']) ?>" alt="Responsibility Image">

    </div>

    <div class="response-left">
      <h1><?= htmlspecialchars($da['header_title']) ?></h1>
      <p><?= nl2br(htmlspecialchars($da['paragraph1'])) ?></p>
      <p><?= nl2br(htmlspecialchars($da['paragraph2'])) ?></p>

      <div class="social-people">
        <div class="social-container-head">
          <div class="icon"><i class="fas fa-shield-alt"></i></div>
          <div class="social-container-text">
            <h3><?= htmlspecialchars($da['card1_title']) ?></h3>
            <p><?= htmlspecialchars($da['card1_text']) ?></p>
          </div>
        </div>

        <div class="social-container-head">
          <div class="icon"><i class="fas fa-shield-alt"></i></div>
          <div class="social-container-text">
            <h3><?= htmlspecialchars($da['card2_title']) ?></h3>
            <p><?= htmlspecialchars($da['card2_text']) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="second-part">
  <div class="customer-satisfaction">
    <h1><?= htmlspecialchars($section['section_title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($section['section_paragraph'])) ?></p>
  </div>

  <div class="social-container">
    <?php while ($card = $cards->fetch_assoc()): ?>
      <div class="social-cards">
        <i class="<?= htmlspecialchars($card['icon_class']) ?>"></i>
        <h2><?= htmlspecialchars($card['card_title']) ?></h2>
        <p><?= htmlspecialchars($card['card_text']) ?></p>
      </div>
    <?php endwhile; ?>
  </div>
</section>



<section class="support-section"> 
  <div class="support-header">
    <h2 class="highlight-letters"><?= htmlspecialchars($sec['section_title']) ?></h2>
    <p><?= htmlspecialchars($sec['section_paragraph']) ?></p>
  </div> 

<div class="container">
  <?php $i = 1; ?>
  <?php while ($card = $c->fetch_assoc()): ?>
    <div class="card<?= $i ?>">
      <h1><?= htmlspecialchars($card['card_title']) ?></h1>
      <p><?= htmlspecialchars($card['card_text']) ?></p>
    </div>
    <?php $i++; ?>
  <?php endwhile; ?>
</div>

</section>

</main>



</div>
<?php include('footer.php');?>
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
      const totalCards = document.querySelectorAll('[class^="card"]').length;

for (let i = 1; i <= totalCards; i++) {
  gsap.to(`.card${i}`, {
    scale: 0.7,
    opacity: 0,
    scrollTrigger: {
      trigger: `.card${i}`,
      start: "top 15%",
      end: "bottom 15%",
      scrub: true
    }
  });
}


// Animate section headers
gsap.utils.toArray("section h1, section h2").forEach((el) => {
  gsap.from(el, {
    scrollTrigger: {
      trigger: el,
      start: "top 80%",
      toggleActions: "play none none reverse"
    },
    y: 50,
    opacity: 0,
    duration: 1,
    ease: "power3.out"
  });
});

// Animate paragraphs
gsap.utils.toArray("section p").forEach((el) => {
  gsap.from(el, {
    scrollTrigger: {
      trigger: el,
      start: "top 85%",
      toggleActions: "play none none reverse"
    },
    y: 20,
    opacity: 0,
    duration: 0.8,
    ease: "power2.out"
  });
});

// Animate responsibility cards
gsap.utils.toArray(".responsibility-card1, .social-cards").forEach((card, i) => {
  gsap.from(card, {
    scrollTrigger: {
      trigger: card,
      start: "top 90%",
      toggleActions: "play none none reverse"
    },
    y: 60,
    opacity: 0,
    duration: 1,
    ease: "back.out(1.7)",
    delay: i * 0.1
  });
});

// Animate big sticky cards in support-section
gsap.utils.toArray(".container > div").forEach((card, i) => {
  gsap.from(card, {
    scrollTrigger: {
      trigger: card,
      start: "top 75%",
      toggleActions: "play none none reverse"
    },
    y: 80,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    delay: i * 0.15
  });
});

// Animate images
gsap.utils.toArray("img").forEach((img) => {
  gsap.from(img, {
    scrollTrigger: {
      trigger: img,
      start: "top 85%",
      toggleActions: "play none none reverse"
    },
    scale: 0.9,
    opacity: 0,
    duration: 1.2,
    ease: "power2.out"
  });
});

// Animate icons
gsap.utils.toArray(".icon i").forEach((icon) => {
  gsap.from(icon, {
    scrollTrigger: {
      trigger: icon,
      start: "top 85%",
      toggleActions: "play none none reverse"
    },
    scale: 0,
    rotation: 180,
    duration: 0.6,
    ease: "back.out(2)"
  });
});


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
      { color: "#ffc8c8f2", opacity: 0.3, y: 20 },
      { 
        color: "#000000ff",
        opacity: 1,
        y: 0,
        ease: "power2.out",
        stagger: 0.04,
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


    </script>
    
<script src="header.js"></script>
<script src="footer.js"></script>
</body>
</html>