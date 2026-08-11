 <?php
 include('config.php');
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
 ?>
 
 <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  </head>
  <body>
    

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
  <script>
    const textSlides = document.querySelectorAll('.service-item .slider-item');
const imageWrapper = document.querySelector('.swiper-wrapper');
const leftArrow = document.querySelector('.fa-arrow-left');
const rightArrow = document.querySelector('.fa-arrow-right');
const topLabel = document.querySelector('.top-label p');
const serviceItems = document.querySelectorAll('.service-item');

let currentIndex = 0;
const totalSlides = document.querySelectorAll('.service-item').length;

function showSlide(index) {
    // Images slide normally
    imageWrapper.style.transform = `translateX(-${index * 100}%)`;

    // Text slides in place
    serviceItems.forEach((item, i) => {
        item.classList.remove('active', 'exit-left');
        if (i === index) item.classList.add('active');
        else if (i === (currentIndex - 1 + totalSlides) % totalSlides) item.classList.add('exit-left');
    });

    // Update counter
    topLabel.textContent = `${index + 1}/${totalSlides}`;
}
// Next/Prev
function nextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
}
function prevSlide() {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    showSlide(currentIndex);
}

// Arrow click
rightArrow.addEventListener('click', () => { nextSlide(); resetInterval(); });
leftArrow.addEventListener('click', () => { prevSlide(); resetInterval(); });

// Auto-slide
let slideInterval = setInterval(nextSlide, 3000);
function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 3000);
}

// Initialize
showSlide(currentIndex);


  </script>
  </body>
  </html>