
<?php
include('config.php'); // adjust path if needed
$query = $conn->query("SELECT * FROM contact_info WHERE id=1 LIMIT 1");
$contact = $query->fetch_assoc();

// Fetch navbar values
$navbar_query_result = $conn->query("SELECT setting_key, setting_value FROM navbar");
$navbar = [];
if ($navbar_query_result && $navbar_query_result->num_rows > 0) {
    while ($row = $navbar_query_result->fetch_assoc()) {
        $navbar[$row['setting_key']] = $row['setting_value'];
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secretKey = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe"; // test secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($verifyUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
    $responseData = json_decode($response);

    if (!$responseData->success) {
        echo "reCAPTCHA failed. Please try again.";
        exit;
    }

    // Continue processing the form (send email, save to DB, etc.)
    echo "success";
}


// Fetch contact info from DB

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="style.css" rel="stylesheet">
  <title>contact us</title>

  <style>
       
       .contact-us{
        padding:60px;
        padding-top:190px;
       }

       .contact-us-container{
        max-width:100%;
        margin:20px;
       }
       .contact-us-wrapper{
        display:flex;
        justify-content:center;

       }
       .contact-left{
         width:60%;

       }
       .contact-header{
          display:flex;
          flex-direction:column;
       }
       .contact-header h3{
    text-transform: uppercase;
    font-size: 45px;
    font-weight: 600;
      margin-bottom:50px;
    color: #000;
    letter-spacing: -1px;
    line-height: 1.088;
 ;
       }
       form{
max-width: 384px;
       }
       .contact-form-item{
        display:flex;
        gap:10px;
        flex-direction:column;
         margin-bottom:50px;

    
       }
       .input{
        display: block;
    width: 100%;
    border: none;
    outline: none;
    margin: 0;
    padding: 0px 0px;
    border-bottom: 1px solid #D9D9D9;
    font-size: 16px;
    font-weight: 400;
    color: #000;

       }
      .input:focus {
  border-color: #ff0000ff;
  outline: none;
  
}
      .input:focus .label{
  color:red;
  
}


.textarea{
  height:100px;
}
.wrapper-btn{
  width:300px;
  position:relative;
  overflow:hidden;
  height:60px;
  margin-top:10px;
}
.class-button-container{
  width:100%;
  height:100%;
position: absolute;
top:0;
left:0;
}

.submit-btn {
  background:red;
  color: white;
  padding: 12px 30px;
  font-size: 16px;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
    transform: translateY(100px); /* start below */
  transition: all 0.3s ease;
}
.contact-right{
  position:relative;
overflow:hidden;
border-radius:10px;
width:50%;
}
.contact-right-container{
  max-width:600px;
}
.contact-image{
  height:500px;
}
.contact-image img{
  height:100%;
  width:100%;
  object-fit:cover;
  object-position: center;
}
.bottom-image-contact{ 
   display:grid;
  width:100%;

  grid-template-columns: 1fr 1fr;
}
.bottom-left-address{
background-color:red;
padding:40px 10px;
color:white;
}

.bottom-contact-info{
background-color:#FEEDE8;;
padding:40px 10px;
display:flex;
flex-direction:column;
gap:10px;
}
.bottom-left-address h3{
font-size:18px;
font-weight:400;
}

.bottom-left-address p{
font-size:18px;
margin-bottom:10px;
}
.contact-info{
  display:flex;
  gap:10px;
}
.contact-info i{
  color:red;
  font-size:13px;
}


/* Add transition for labels */
.contact-form-item label {
  position: relative;
  display: block;
  font-weight: 500;
  color: #555;
  transition: all 0.3s ease;
}

/* Floating effect on focus */
.input:focus + label,
.input:not(:placeholder-shown) + label {
  color: red;
  transform: translateY(-20px);
  font-size: 14px;
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



/* Popup Overlay */
.popup-overlay {
  display: none; /* hidden by default */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.6);
  z-index: 9999;
  justify-content: center;
  align-items: center;
}

/* Popup Box */
.popup-content {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  text-align: center;
  max-width: 400px;
  width: 90%;
  animation: fadeInUp 0.4s ease-out;
}

.popup-content h2 {
  color: green;
  margin-bottom: 10px;
}

.close-popup {
  position: absolute;
  top: 12px;
  right: 16px;
  font-size: 22px;
  font-weight: bold;
  color: #333;
  cursor: pointer;
  transition: color 0.3s ease;
  display:block;
}

.close-popup:hover {
  color: red;
}

/* Popup Animation */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
@media screen and (max-width: 768px){
  .contact-us-wrapper{
    display:flex;
    flex-direction:column;
  }
  .contact-us{
    padding:20px;
    padding-top:100px;
  }
  .contact-right{
    width:100%;
  }
  .contact-header h3{
    font-size:30px;
  }
  .contact-image{
    height:300px;
  }
  .bottom-left-address{
    padding:30px 10px;

  }
  .bottom-contact-info{
    padding:30px 10px;
  }
}


  </style>

</head>
<body>
  <?php include ('header.php');?>
<div class="contact-us">
  <div class="contact-us-container">
    <div class="contact-us-wrapper">

      <!-- Left Section (Form) -->
      <div class="contact-left">
        <div class="contact-header">
          <h6>Contact Us</h6>
          <h3 class="highlight-letters ">Reach Out for Expert Engineering Solutions</h3>
        </div>

      <form class="contact-form-container" action="contact_process.php" method="POST">
  <div class="contact-form-item">
    <label for="name">Your Name</label>
    <input id="name" name="name" class="input" type="text" placeholder="Enter your name" required>
  </div>

  <div class="contact-form-item">
    <label for="email">Your Email</label>
    <input id="email" name="email" class="input" type="email" placeholder="Enter your email" required>
  </div>

  <div class="contact-form-item">
    <label for="subject">Subject</label>
    <input id="subject" name="subject" class="input" type="text" placeholder="Enter subject" required>
  </div>

  <div class="contact-form-item">
    <label for="message">Message</label>
    <textarea id="message" name="message" class="input" placeholder="Write your message" required></textarea>
  </div>

<div id="html_element"></div>
<div id="recaptcha-error" style="color: red; font-size: 14px; margin-top: 5px; display: none; margin-bottom:10px">
  ❌ Please verify that you are not a robot.
</div>

  <div class="contact-form-item wrapper-btn">
    <div class="class-button-container">
      <button type="submit" class="submit-btn">Send Message</button>
    </div>
  </div>
</form>

      </div>


 <!-- Contact Right Section -->
<div class="contact-right-container">
    <div class="contact-image">
        <img src="./assets/contact/<?= htmlspecialchars($contact['image_path']) ?>" alt="Contact Image">
    </div>

    <div class="bottom-left-address">
        <p>Address</p>
        <h3><?= htmlspecialchars($navbar['address'] ?? $contact['address']) ?></h3>
    </div>

    <div class="bottom-contact-info">
        <p>Get in touch</p>
        <div class="contact-info">
            <i class="fa-regular fa-envelope"></i>
            <p><?= htmlspecialchars($navbar['email']) ?></p>
        </div>
        <div class="contact-info">
            <i class="fa-light fa-phone-volume"></i>
            <p><?= htmlspecialchars($navbar['phone']) ?></p>
        </div>
        <div class="contact-info">
            <i class="fa-regular fa-clock"></i>
            <p><?= htmlspecialchars($navbar['working_hours']) ?></p>
        </div>
    </div>
</div>
</div>

    <!-- Success Popup -->
<div id="success-popup" class="popup-overlay">
<div class="popup-content">
  <span class="close-popup">&times;</span>
  <h2> Message Sent Successfully</h2>
  <p>Thank you for reaching out. We’ll get back to you soon.</p>
</div>

</div>

  </div>
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

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
  var onloadCallback = function() {
    grecaptcha.render('html_element', {
      'sitekey' : '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' // test key
    });
  };
</script>

  <script src="header.js"></script>
  <script src="footer.js"></script>
  <script>




  // Animate contact form fields on page load
  gsap.from(".contact-form-item", {
    opacity: 0,
    y: 50,
    duration: 0.8,
    stagger: 0.2,
    ease: "power3.out"
  });


  // Animate the right image and bottom info on scroll
  gsap.registerPlugin(ScrollTrigger);

  gsap.from(".contact-right-container", {
    scrollTrigger: {
      trigger: ".contact-right-container",
      start: "top 80%",
      toggleActions: "play none none none"
    },
    opacity: 0,
    x: 100,
    duration: 1,
    ease: "power2.out"
  });

  gsap.from(".bottom-image-contact", {
    scrollTrigger: {
      trigger: ".bottom-image-contact",
      start: "top 80%",
      toggleActions: "play none none none"
    },
    opacity: 0,
    y: 50,
    duration: 1,
    stagger: 0.2,
    ease: "power2.out"
  });


  gsap.registerPlugin(ScrollTrigger);

  gsap.to(".submit-btn", {
    scrollTrigger: {
      trigger: ".wrapper-btn", // when the button's container scrolls into view
      start: "top 90%", // when top of container hits 90% of viewport
      toggleActions: "play none none none",
    },
    y: 0, // move to original position
    opacity: 1, // fade in
    duration: 1,
    ease: "power3.out"
  });const headings = document.querySelectorAll(".highlight-letters");

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
    { color: "#dcdcdcf2", opacity: 0, y: 20 },
    { 
      color: "#000000ff",
      opacity: 1,
      y: 0,
      ease: "power2.out",       // smoother easing
      stagger: 0.03,            // smaller stagger
      duration: 0.8,            // duration per letter
      scrollTrigger: {
        trigger: heading,
        start: "top 80%",       // when heading enters viewport
        toggleActions: "play none none none",
      }
    }
  );
});const form = document.querySelector(".contact-form-container");
const popupOverlay = document.getElementById("success-popup");
const popupContent = document.querySelector(".popup-content");
const recaptchaError = document.getElementById("recaptcha-error");

// Function to close popup
function closePopup() {
  popupOverlay.style.display = "none";
}

// Close popup when clicking X
document.querySelector(".close-popup").addEventListener("click", closePopup);

// Close popup when clicking outside the popup content
popupOverlay.addEventListener("click", (e) => {
  if (e.target === popupOverlay) { // only if clicking on overlay, not content
    closePopup();
  }
});

form.addEventListener("submit", function(e) {
  e.preventDefault();

  // 1. Check if reCAPTCHA is completed
  const recaptchaResponse = grecaptcha.getResponse();
  if (!recaptchaResponse) {
    recaptchaError.style.display = "block"; // show inline message
    return;
  } else {
    recaptchaError.style.display = "none"; // hide message if verified
  }

  // 2. Submit form via AJAX
  let formData = new FormData(this);

  fetch("contact_process.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(result => {
    if (result.includes("success")) {
      popupOverlay.style.display = "flex"; // show popup
      this.reset(); // reset form
      grecaptcha.reset(); // reset reCAPTCHA
    } else {
      recaptchaError.textContent = "❌ Something went wrong. Please try again.";
      recaptchaError.style.display = "block";
    }
  })
  .catch(error => {
    recaptchaError.textContent = "❌ Something went wrong. Please try again.";
    recaptchaError.style.display = "block";
    console.error("Error:", error);
  });
});



</script>

</body>
</html>