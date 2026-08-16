
<?php
$result = $conn->query("SELECT * FROM footer_content WHERE id=1");
$footer = $result->fetch_assoc();

$footer_result = $conn->query("SELECT setting_key, setting_value FROM navbar");
$footer_contact = [];
if ($footer_result && $footer_result->num_rows > 0) {
    while ($row = $footer_result->fetch_assoc()) {
        $footer_contact[$row['setting_key']] = $row['setting_value'];
    }
}
?>
<style>
    .footer-container{
        background: #EEEEEE;
    margin-left: 12px;
    margin-right: 12px;
    border-radius: 16px;
    }

    .footer-container-first{
      max-width: 1240px;
      margin:0 auto;

    }
    .footer-container-first-grid{
           display: grid;
    grid-template-columns: 2fr 1.3fr 2fr 1.8fr;
    gap: 50px 95px;
    padding-top:90px;
    padding-bottom:50px;
    }
    .logo-image{
      max-width:190px;
    transform: translateY(150%); /* start fully above container */
    transition: transform 0.5s ease-out;

    }
    .logo-image img{ 
   max-width: 100%;
    }
    ul{
      list-style: none;
    }
  a{
    text-decoration:none;
    color:black;
  }
  .logo-container-wrapper {
    overflow: hidden;
    height:70px;    
    position: relative;
  }
.useful-links li:is(.footer-dropdown) > a::after {
    content: "\f107";
     font-family: "Font Awesome 6 Free";
    font-weight: 700;
    position: absolute;
    font-size: 12px;
    right: 0;
    top: 49%;
    color:red;
    transform: translateY(-50%);
}

.useful-links li.footer-dropdown > a {
  position: relative; /* make the parent a reference */
  padding-right: 15px; /* add space for arrow */
  display: inline-block;

}
.footer-drpdown-content {
    max-height: 0;          /* hide initially */
    overflow: hidden;
    opacity: 0;
 padding: 0 15px;
    transition: max-height 0.4s ease, opacity 0.4s ease; 
}

.footer-drpdown-content.footer {
    max-height: 500px;      /* large enough to show content */
    opacity: 1;
    padding: 10px 15px; 
}

.footer-container-first-grid h4{
     font-size: 16px;
    opacity: 0.64;
    margin-bottom: 28px;
    color: rgb(0, 0, 0);
    text-transform: capitalize;
}
.footer-services-list {
    width: 100%;
    position: relative;
    overflow: visible; /* allow links to move */
}

.footer-services-list li {
    position: relative;
    margin-bottom:5px;
}

.footer-services-list li a {
    display: inline-block;
    position: relative;
    transition: color 0.2s ease, transform 0.2s ease;
        font-size: 20px;
    font-family:  "Roboto", sans-serif;;
    text-transform: capitalize;
    font-weight: 400;
    padding-left: 0px;
    line-height: 1;
    position: relative;
    z-index: 1;
    color: rgb(0, 0, 0);
    margin-bottom:10px;
}

.footer-services-list li a:hover {
    color: red;
    transform: translateX(10px);
}

.footer-services-list li a .circle {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: red;
    position: absolute;
    left: -16px;
    top: 50%;
    transform: translateY(-50%) scale(0);
    opacity: 0;
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.footer-services-list li a:hover .circle {
    transform: translateY(-50%) scale(1);
    opacity: 1;
}
.useful-links li{
margin-bottom:5px;
  font-weight:500;

}
.useful-links li a:hover{
 transition: ease-in-out 0.2s; 
}
.useful-links li a:hover{
  color:red;
}
.get_in_touch_content{
  display:flex;
  gap:5px;
  align-items:center;
}
.footer-info i{
      color: red;
    display: inline-block;
    font-size:13px;

  }
  .footer-container-second{
    padding:55px;
       border-top: 1px solid rgba(0, 0, 0, 0.2);
    border-bottom: 1px solid rgba(0, 0, 0, 0.2);
    position:relative;
  }
  .footer-container-second-content{
    max-width:1240px;
    margin:20px;
    padding:10px;
  }
  .footer-container-second-content p{
    margin-right:50px;
  }
  .footer-go-top{
position:absolute;
top:0px;
height:100%;
display:flex;
align-items: center;
right:0px;
border-left: 1px solid rgba(0, 0, 0, 0.2);

  }
  .footer-got-top-btn{
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:10px;
    cursor:pointer;

  }
  .text-wrapper{
    display:flex;
    justify-content: flex-start;
    overflow:hidden;
  }
.text-wrapper h1{
font-size:60px;
letter-spacing:1px;
animation:move-text 6000ms linear infinite;
white-space: nowrap;
padding:0 2rem;
text-transform: uppercase;
 }

 @keyframes move-text {
  0%{
    transform:translateX(0);
  }
  100%{
    transform:translateX(-100%);
  }
 }
 .text-slider , .last-section{
  padding:55px 0px;
  border-bottom:  1px solid rgba(0, 0, 0, 0.2);
 }
 .last-section-container{
  display:flex;
  justify-content:space-around;
  margin:20px;

 }
.last-section-right{
  display:flex;
  gap:20px;
}
@media screen and (max-width: 960px){
  .footer-container-first-grid{
        display: grid;
        grid-template-columns:none;
    grid-template-rows: 0.5fr 1fr 1fr 1fr;
    gap: 50px 95px;
  padding-top:10px;
    padding-bottom: 0px;
padding:50px;
  }
 
.footer-container-second{
      padding: 20px;
}
.last-section-container{
    display: flex;
    justify-content: space-around;
    flex-direction: column;
    margin: 20px;
    gap: 10px;
}
}  

@media screen and (max-width:500px){
  .footer-container-second{
    padding:3px;
  }
  .last-section-right p{
  font-size:12px;
}  
.footer-container-first-grid{
  padding:20px;
  padding-top: 50px;
}

}


</style>

<div class="footer-container">
  <div class="footer-container-first">
    <div class="footer-container-first-grid">

     <div class="logo-container-wrapper"> 
    <div class="logo-image"> 

        <a href="./index.php"> 

            <img 
                src="<?= htmlspecialchars(
                    image_url(
                        $footer['logo'],
                        './assets/home'
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>" 
                alt="Spectrum Logo"
            >

        </a> 

    </div> 
</div>


      <div class="useful-links">
        <h4>Useful Links</h4>
        <ul>
          <li>
            <a href="./index.php">Home</a>
          </li>

          <li class="footer-dropdown">
            <a href="#">About Us</a>
            <ul class="footer-drpdown-content">
              <li><a href="./about.php">Who We Are</a></li>
              <li><a href="./structure.php">Company Structure</a></li>
              <li><a href="./corporate.php">Corporate Division</a></li>
              <li><a href="./division.php">Our Division</a></li>
            </ul>
          </li>

          <li>
            <a href="./projects.php">Projects</a>
          </li>

          <li>
            <a href="./news.php">News</a>
          </li>

          <li class="footer-dropdown">
            <a href="#">Management Policies</a>
            <ul class="footer-drpdown-content">
              <li><a href="./about.php">Gulf Spectrum</a></li>
              <li><a href="./structure.php">Spectrum</a></li>
            </ul>
          </li>

          <li class="footer-dropdown">
            <a href="#">Contact</a>
            <ul class="footer-drpdown-content">
              <li><a href="./about.php">Contact Us</a></li>
              <li><a href="./structure.php">Locate Us</a></li>
            </ul>
          </li>
        </ul>
      </div>

   <div class="footer-services">
  <h4>Our Services</h4>
  <ul class="footer-services-list">
    <li><a href="about.php"><span class="circle"></span> Architectural</a></li>
    <li><a href="about.php"><span class="circle"></span> Mechanical Engineering</a></li>
    <li><a href="about.php"><span class="circle"></span> Transportation Planning</a></li>
  </ul>
</div>

    
      <div class="get_in_touch ">
         <h4>get in touch</h4>

<ul>
    <li class="footer-info">
        <a class="get_in_touch_content" href="mailto:<?= htmlspecialchars($footer_contact['email'] ?? '') ?>">
            <i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($footer_contact['email'] ?? 'email') ?>
        </a>
    </li>

    <li class="footer-info">
        <a class="get_in_touch_content" href="tel:<?= htmlspecialchars($footer_contact['phone'] ?? '') ?>">
            <i class="fa-solid fa-phone-volume"></i> <?= htmlspecialchars($footer_contact['phone'] ?? 'phone') ?>
        </a>
    </li>

    <li class="footer-info get_in_touch_content">
        <i class="fa-regular fa-clock"></i> <?= htmlspecialchars($footer_contact['working_hours'] ?? 'Monday-Friday Opening') ?>
    </li>
</ul>
      </div>

    </div>
  </div>

<div class="footer-container-second">
     <div class="footer-container-second-content">
        <p><?= $footer['description'] ?></p>
    </div>

   <div class="footer-go-top">
      <div class="footer-got-top-btn" id="goTopBtn">
          <i class="fa-solid fa-arrow-up"></i>
          <h3>go top </h3>
      </div>
   </div>
</div>

  

<div class="last-section">
    <div class="last-section-text">
         <div class="last-section-container">
            <div>
              <p>©2025, Spectrum engineering consultant . All Rights Reserved.</p>
            </div>

            <div class="last-section-right">
              <p>Privacy Policy</p>
              <p>Terms and condition</p>
               <p>Terms and condition</p>
            </div>
         </div>
    </div>
</div>
</div>
