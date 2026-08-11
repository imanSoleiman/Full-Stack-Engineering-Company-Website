<?php
include './config.php';


$sql = "
  SELECT 
    l.id, l.city, l.country, l.image_path,
    COUNT(d.id) AS departments_count,
    COALESCE(SUM(d.people_count), 0) AS staff_count
  FROM locations l
  LEFT JOIN departments d ON d.location_id = l.id
  GROUP BY l.id
  ORDER BY l.city ASC
";

$result = mysqli_query($conn, $sql);

$locations = [];
while ($row = mysqli_fetch_assoc($result)) {
  $locations[] = $row;
}

$totalStaff = 0;
$officeCount = count($locations);
$countries = [];

foreach ($locations as $loc) {
    $totalStaff += $loc['staff_count'];
    $countries[] = $loc['country'];
}
$uniqueCountries = count(array_unique($countries));

$result = $conn->query("SELECT * FROM company_structure WHERE id = 1");
$company = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>company structure </title>
    <link href="style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>

      
        body{
            margin:0;
            padding:0;
        }
  

main{
  overflow-x: hidden;
}

#locations {
width:100%;
    width: 100%;
    height: auto;
    padding: 20px;
    margin: 100px auto;
    padding-top:100px;
}

.locations-container {
  display: flex;
  flex-direction: column;
  flex-wrap:wrap;
  gap: 30px;
}

.locations-head {
  text-align: center;
  padding: 50px;
}

.location-cards {
display:flex;
gap:30px;
flex-wrap:wrap;
width:100%;
justify-content: center;
}

.location-cards-content {
  display: flex;
  background-color: white;
  border-radius: 20px;
  box-shadow: 
    0 2px 6px rgba(0, 0, 0, 0.12),
    0 8px 20px rgba(0, 0, 0, 0.08),
    0 12px 40px rgba(0, 0, 0, 0.04);
  overflow: hidden;
  flex-direction: row;
  width:550px;

}

.location-cards img {
  width: 40%;
  height: auto;
  object-fit: cover;
}

.card-text {
  padding: 30px 20px;
  margin: 10px;
  width: 100%;
}

.staff {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px 10px;
  margin: 10px;
  justify-content: center;
}

.location-head {
  display: flex;
  gap: 10px;
  align-items: center;
}

.gray {
  color: gray;
}

.staff h1 {
  color: red;
  font-size: 30px;
}

.redss i {
  color: red;
}

.detail-link {
  color: blue;
  cursor: pointer;
  margin-top: 15px;
  text-decoration: underline;
}

/* Popup Form Styles */

.popup-form {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  overflow: auto; 

}

.form-content {
   margin:20px;
  background: white;
  border-radius: 15px;
  position: relative;
  max-width: 900px;
   max-height: 500px; 
  overflow-y: auto;
}

.close-btn {
  position: absolute;
  top: 15px;           /* slightly lower for better spacing */
  right: 20px;
  font-size: 28px;     /* bigger for visibility */
  font-weight: bold;
  color: white;        /* contrast with top image */
  cursor: pointer;
  z-index: 1002;       /* higher than popup content & overlay */
  background: rgba(0, 0, 0, 0.5); /* subtle circle behind it */
  width: 36px;
  height: 36px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;  /* circle style */
  transition: background 0.3s ease;
}
.top-image{
    width:100%;
    height:250px;
    object-fit: fill;
    display:flex;
    flex-direction:column;
    justify-content: flex-end;
    padding:10px 20px;
    color:white;
    font-size:25px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
}
.top-image p{
    font-size:20px;
}
.form-content{
    display:flex;
    flex-direction:column;
    align-items: center;
    gap:30px;
}
.form-content-header{
    text-align:center;

}
.form-content-header h1{
  color:red;
  font-size:30px;
}
.department-structure{
    display:flex;
    flex-direction:column;
    align-items: flex-start;
    width:100%;
    gap:30px;
}
.department-cards{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
}
.department-cards-content{
    display:flex;
    justify-content: space-between;
    width:230px;
    padding:20px 4px;
    background-color:aliceblue;
    align-items:center;
    border-radius:10px;
}
.department-cards-content h3{
font-size:13px;
}
.departments-card-left{
    display:flex;
    flex-direction:column;
    align-items:center;
     font-size:13px;
}
.departments-card-left h1{

    align-items:center;
     font-size:20px;
     color:red;
}
.detail-link{
    text-decoration:none;
    color:black;
}
.detail-link:hover{
    text-decoration:none;
    color:red;
}
.locations-head h1{
  font-size:40px;
  text-transform: uppercase;
  font-weight: 700;
}
.lcoation-head h1{
  font-size:10px;
}

.compnay-structure-first {
  position: relative;
  background-repeat: no-repeat;
  background-size: cover;
  padding: 50px;
  background-position: center;
  padding-top: 150px;
  padding-bottom: 100px;
  border-radius: 0 0 10px 10px;
  height: 500px;
}

.compnay-structure-first::after {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.25); /* dim factor */
  border-radius: 0 0 10px 10px;
  z-index:1;
}

.companys-structure-container{
z-index:2;
display:flex;
max-width:1200px;
justify-content:space-between;
height:100%;
align-items: flex-end;
}
.companys-structure-numbers{
  background-color: white;
  position:absolute;
  top:80%;
  right:20%;
padding:100px;
border-radius:10px;
  padding-bottom:50px;
  padding-top:50px;
   z-index:2;
     box-shadow: 
    0 2px 6px rgba(0, 0, 0, 0.12),
    0 8px 20px rgba(0, 0, 0, 0.08),
    0 12px 40px rgba(0, 0, 0, 0.04);
}
.company-structure-numbers-container{
  display:flex;
 gap:50px;
 align-items:center;

}
.company-cardOne{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:10px;
}
.company-structure-header{
  max-width:500px;
color:white;
z-index:2;
margin-bottom:10px;

}
.company-structure-header h1{
font-size:40px;
text-transform: uppercase;
}
.company-cardOne h3{
font-size:30px;
}

.company-cardOne p{
font-size:15px;
text-transform: uppercase;
}


@media screen and (max-width:600px){
  .compStruc div h1{
       font-size:30px;
  }
  .compnay-structure-first{
    padding:30px;
  }

  .companys-structure-numbers{
    position:absolute;
    top:100%;
    right:0%;
    padding:20px;
    padding-top:50px;
    padding-bottom:50px;
  }
  .company-cardOne{
    display:flex;
    justify-content: flex-start;
    align-items: flex-start;
  }
  
}
@media screen and (max-width:500px){
  .location-cards-content{
    display:flex;
    flex-direction:column;
  }
  .location-cards img{
    width:100%;
  }
}


@media screen and (max-width: 400px){
  .compStruc div{
    margin:10px;
    padding-left: 10px;
    padding-bottom:10px;
  }
  .presence-stats{
    display:flex;
    flex-direction:column;
        justify-content: center;
    align-items: center;
  }
  .company-structure-numbers-container{
    display: flex;
    gap: 20px;
    align-items: center;
  }
}
 


</style>
</head>
<body>
   <?php include('header.php') ;?>
   <main>
<div class="compnay-structure-first" style="background-image: url('./assets/structure/<?= $company['background_image'] ?>');">
   <div class="companys-structure-container">
       <div class="company-structure-header">
            <h1><?= $company['heading'] ?></h1>
            <p><?= $company['description'] ?></p>
       </div>

  <div class="companys-structure-numbers">
        <div class="company-structure-numbers-container">
          <div class="company-cardOne">
             <h3 id="staffCount" data-target="<?= $totalStaff ?>">0</h3>
             <p>Total Staff</p>
          </div>


             <div class="company-cardOne">
                <h3 id="officeCount" data-target="<?= $officeCount ?>">0</h3>
               <p>Office Locations</p>
          </div>

             <div class="company-cardOne">
                <h3 id="countryCount" data-target="<?= $uniqueCountries ?>">0</h3>
              <p>Countries</p>
          </div>
   </div>
     </div>
</div>
</div>

<section id="locations">
  <div class="locations-container">
    <div class="locations-head">
      <h1>Office Location</h1>
      <p>Strategic locations across Middle East, Central Asia and North America</p>
    </div>
 <div class="location-cards">
 
    <?php foreach ($locations as $loc): ?>
      <div class="location-cards-content" data-location-id="<?= $loc['id'] ?>">

    <img src="./assets/structure/<?= htmlspecialchars($loc['image_path']) ?>" alt="<?= htmlspecialchars($loc['city']) ?>">



        <div class="card-text">
          <div class="location-head redss">
            <i class="ri-map-pin-line w-5 h-5"></i>
            <h1><?= htmlspecialchars($loc['city']) ?></h1>
          </div>
          <p><?= htmlspecialchars($loc['country']) ?></p>
          <div class="staff">
            <h1><?= $loc['staff_count'] ?></h1>
            <p>Total Staff</p>
          </div>
          <div class="location-head gray">
            <i class="ri-building-2-line w-4 h-4"></i>
            <p><?= $loc['departments_count'] ?> Departments</p>
          </div>
          <p class="detail-link" data-location-id="<?= $loc['id'] ?>">Click to view detailed structure</p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

<div class="popup-form" id="popupForm" style="display:none;">
  <div class="form-content">
    <span class="close-btn" id="closeForm">&times;</span>
    <div class="top-image" id="popupTopImage" style="background-size: cover; background-position: center; height: 400px; color: white; padding: 20px;">
    </div>
    <div class="form-content" id="popupDetails">
    </div>
  </div>
</div>
    </div>

</section>     

   </main>
   <?php include('footer.php');?> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/ScrollTrigger.min.js"></script>

   <script>




function animateCount(id, duration = 2000) {
  const el = document.getElementById(id);
  const target = +el.getAttribute("data-target");
  if(target === 0) return; // Avoid divide by zero
  const stepTime = Math.max(Math.floor(duration / target), 1);

  let count = 0;
  const timer = setInterval(() => {
    count++;
    el.textContent = count;
    if (count >= target) {
      clearInterval(timer);
      el.textContent = target; // Ensure exact final value
    }
  }, stepTime);
}

document.addEventListener("DOMContentLoaded", () => {
  animateCount("staffCount", 2000);
  animateCount("officeCount", 2000);
  animateCount("countryCount", 2000);
});

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.detail-link').forEach(button => {
    button.addEventListener('click', () => {
      const locationId = button.getAttribute('data-location-id');
      openPopup(locationId);
    });
  });
});

// GSAP timeline for popup animation
function animatePopupOpen() {
  const popupForm = document.getElementById('popupForm');
  const popupTopImage = document.getElementById('popupTopImage');
  const popupDetails = document.getElementById('popupDetails');

  gsap.set([popupTopImage, ...popupDetails.children], {opacity: 0, y: 30}); // initial state

  gsap.to(popupForm, {opacity: 1, duration: 0.3});

  gsap.to(popupTopImage, {opacity: 1, y: 0, duration: 0.5, ease: "power3.out"});

  gsap.to(popupDetails.children, {
    opacity: 1,
    y: 0,
    duration: 0.5,
    stagger: 0.1,
    ease: "power3.out"
  });
}

function animatePopupClose(callback) {
  const popupForm = document.getElementById('popupForm');
  const popupTopImage = document.getElementById('popupTopImage');
  const popupDetails = document.getElementById('popupDetails');

  const tl = gsap.timeline({
    onComplete: callback
  });

  tl.to([...popupDetails.children].reverse(), {opacity: 0, y: 30, duration: 0.3, stagger: 0.05, ease: "power3.in"})
    .to(popupTopImage, {opacity: 0, y: -30, duration: 0.3, ease: "power3.in"}, "<")
    .to(popupForm, {opacity: 0, duration: 0.2}, "<");
}

function openPopup(locationId) {
  fetch('get_location_details.php?id=' + locationId)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const topImage = document.getElementById('popupTopImage');
        topImage.style.backgroundImage = `url('${data.location.background_image_path}')`;
        topImage.innerHTML = `<h2>${data.location.city}</h2><p>${data.location.country}</p>`;

        let detailsHTML = `
          <div class="form-content-header">
            <h1>${data.location.staff_count || 0}</h1>
            <p>Total Staff Members</p>
          </div>
          <div class="department-structure">
            <h1>Departments Structure</h1>
            <div class="department-cards">`;

        data.departments.forEach(dep => {
          detailsHTML += `
            <div class="department-cards-content">
              <h3>${dep.name}</h3>
              <div class="departments-card-left">
                <h1>${dep.staff_count}</h1>
                <p>${dep.label}</p>
              </div>
            </div>`;
        });

        detailsHTML += `</div></div>`;
        const popupDetails = document.getElementById('popupDetails');
        popupDetails.innerHTML = detailsHTML;

        const popupForm = document.getElementById('popupForm');
        popupForm.style.display = 'flex';

        animatePopupOpen(); // animate popup opening
      } else {
        alert('Error loading location details');
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
      alert('Failed to load location details.');
    });
}

// Close popup handlers with animation
document.getElementById('closeForm').addEventListener('click', () => {
  animatePopupClose(() => {
    document.getElementById('popupForm').style.display = 'none';
  });
});

document.getElementById('popupForm').addEventListener('click', (e) => {
  if (e.target.id === 'popupForm') {
    animatePopupClose(() => {
      document.getElementById('popupForm').style.display = 'none';
    });
  }
});

gsap.registerPlugin(ScrollTrigger);

const cards = document.querySelectorAll('.location-cards-content');

cards.forEach((card, index) => {
  const xStart = index % 2 === 0 ? -200 : 200;

  gsap.fromTo(
    card,
    { x: xStart, opacity: 0, scale: 0.95 },
    {
      x: 0,
      opacity: 1,
      scale: 1,
      ease: "power2.out",      // slightly softer easing
      scrollTrigger: {
        trigger: card,
        start: "top 95%",      // animation starts lower
        end: "top 40%",        // animation lasts longer
        scrub: 1.2,            // slower smoothness
      }
    }
  );
});




   </script>

   <script src="footer.js"></script>
   <script src="header.js"></script>

   
</body>
</html>