<?php
include('./config.php'); // Your database connection

// Fetch all categories
$categories = $conn->query("SELECT * FROM gulf_spectrum_categories ORDER BY id ASC");

// For each category, fetch its images
$category_images = [];
while($cat = $categories->fetch_assoc()) {
    $images = $conn->query("SELECT * FROM gulf_spectrum_images WHERE category_id=".$cat['id']." ORDER BY id ASC");
    $category_images[$cat['id']] = [
        'name' => $cat['name'],
        'images' => $images->fetch_all(MYSQLI_ASSOC)
    ];
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
    <link href="./style.css" rel="stylesheet">
    <title>gulf spectrum</title>
    <style>
      .gulf-spectrum{
        padding:50px; 
          padding-top:150px;
              border-radius: 16px;
    margin-left: 16px;
    margin-right: 16px;
    padding-bottom:150px;
background-color:black;
    color:white;
      }
      .gulf-spectum-container{
        max-width:900px;
        margin: 10px auto;
        display:flex;
        flex-direction:column;
        gap:50px;
    }

    .gulf-spectrum-header h2{
 
    text-transform: uppercase;
    font-size: 50px;
    font-weight: 600;
    color: #ffffffff;
    letter-spacing: -2px;
    line-height: 0.766;
    margin-bottom: 0;
    }
    .gulf-spectrum-categories{
    display: flex;
    justify-content: center;

    }
    .gulf-spectrum-categories li button {
    position: relative;
    background: transparent;
    border: none;
    outline: none;
    color: #fff;
    font-size: 20px;
    font-weight: 600;
    padding: 20px 40px;
    opacity: 0.6;
    cursor: pointer;
    transition: opacity 0.3s ease;
}
    .single-spectrum-image{
       display:grid;
       grid-template-columns:1fr 1fr;
       gap:50px;
    }
    .image-gulf{
        height:550px;
        border-radius:10px;
        position:relative;
        overflow:hidden;
        width:450px;
    }
    .image-gulf img{
        height:100%;
        width:100%;
    }
    .gulf-spectrum-info{
        display:flex;
        flex-direction:column;
     gap:30px;
        justify-content:center;
    }
    .gulf-spectrum-info h3{
        font-size:50px;
    }
    .view-image a{
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


.gulf-spectrum-categories li button::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 20px;
    right: 20px;
    height: 3px;
    background: red;
    border-radius: 2px;
    transform: scaleX(0);        /* Start small */
    transform-origin: center;    /* Grows from center */
    transition: transform 0.4s ease;
}

.gulf-spectrum-categories li button.active {
    opacity: 1;
}

.gulf-spectrum-categories li button.active::after {
    transform: scaleX(1);        /* Animate to full width */
}
.single-spectrum-image {
    display: none;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.single-spectrum-image.active {
    display: grid;
    opacity: 1;
    transform: translateY(0);
    margin-bottom:30px;
}
/* Popup background */
.popup {
  display: none; 
  position: fixed;
  z-index: 1000;
  left: 0; 
  top: 0;
  width: 100%; 
  height: 100%;
  background: rgba(0,0,0,0.8);
  justify-content: center; 
  align-items: center;
  animation: fadeIn 0.4s ease;
}

/* Popup image */
.popup-content {
  max-width: 80%;
  max-height: 80%;
  border-radius: 10px;
  box-shadow: 0 0 20px rgba(0,0,0,0.6);
  animation: scaleIn 0.4s ease;
}

/* Close button */
.popup-close {
  position: absolute;
  top: 30px;
  right: 50px;
  color: white;
  font-size: 40px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.popup-close:hover {
  transform: scale(1.2);
}

/* Animations */
@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity: 1;}
}

@keyframes scaleIn {
  from {transform: scale(0.7);}
  to {transform: scale(1);}
}
@media screen and (max-width: 900px){
.single-spectrum-image{
    grid-template-rows:1fr 0.5fr;
    grid-template-columns: none;
}
.gulf-spectrum-info{
    display:flex;
    justify-content: flex-start;
}
}
@media screen and (max-width: 500px){
    .image-gulf{
        width:100%;
        height:450px;
    }
    .gulf-spectrum-info{
        gap:10px;
    }
    .gulf-spectrum-info h3{
        font-size:30px;
    }
    .single-spectrum-image{
        gap:20px;
    }
    .gulf-spectrum{
        padding:30px;
        margin-left:10px;
        margin-right:10px;
        padding-top:150px;
    }
    .gulf-spectrum-header h2{
        font-size:30px;
        line-height: 1;
        letter-spacing:-1px;
    }
}

.popup-download {
  position: absolute;
  top: 30px;
  right: 100px; /* place it beside the X */
  color: white;
  font-size: 30px;
  cursor: pointer;
  text-decoration: none;
  transition: transform 0.3s ease;
}

.popup-download:hover {
  transform: scale(1.2);
  color: red;
}



    </style>
</head>
<body>
<?php include('header.php'); ?>

<div class="gulf-spectrum">
    <div class="gulf-spectum-container">
        <div class="gulf-spectrum-header">
            <h2>Quality Policies at Gulf Spectrum</h2>
        </div>

        <ul class="gulf-spectrum-categories">
            <?php foreach($category_images as $cat_id => $cat): ?>
                <li><button data-type="<?= $cat_id ?>"><?= $cat['name'] ?></button></li>
            <?php endforeach; ?>
        </ul>

        <div class="gulf-spectrum-images">
            <?php foreach($category_images as $cat_id => $cat): ?>
                <?php foreach($cat['images'] as $index => $img): ?>
                <div class="single-spectrum-image <?= $index === 0 ? 'active' : '' ?>" data-category-id="<?= $cat_id ?>">

                        <div class="image-gulf"> 
                            <img src="./assets/gulfspectrum/<?= $img['image_name'] ?>" alt="<?= $img['header'] ?>">
                        </div>
                        <div class="gulf-spectrum-info">
                            <h3><?= $img['header'] ?></h3>
                            <p><?= $img['description'] ?></p>
                            <div class="view-image">
                                <a href="./assets/gulfspectrum/<?= $img['image_name'] ?>">View image</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>

        <!-- Image Popup -->
      <div id="imagePopup" class="popup">
  <span class="popup-close">&times;</span>
  <a id="popupDownload" class="popup-download" download>
    <i class="fa-solid fa-download"></i>
  </a>
  <img class="popup-content" id="popupImg" alt="popup image">
</div>


    </div>
</div>

<?php include('footer.php'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/Draggable.min.js"></script>
<script>
// Keep all your original JS for buttons, GSAP animation, and image popup
document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".gulf-spectrum-categories button");
    const allImages = document.querySelectorAll(".single-spectrum-image");

    // Add data-category-id to each image div in PHP:
    // <div class="single-spectrum-image" data-category-id="<?= $cat_id ?>">

    buttons.forEach(btn => {
        const catId = btn.dataset.type;

        btn.addEventListener("click", () => {
            // Remove active from all buttons and images
            buttons.forEach(b => b.classList.remove("active"));
            allImages.forEach(img => img.classList.remove("active"));

            btn.classList.add("active");

            // Show all images of this category
            allImages.forEach(img => {
                if (img.dataset.categoryId === catId) {
                    img.classList.add("active");
                    gsap.fromTo(img, 
                        {opacity: 0, y: 50}, 
                        {opacity: 1, y: 0, duration: 0.6, ease: "power2.out"}
                    );
                }
            });
        });
    });

    // Activate first category by default
    buttons[0]?.click();

    // Popup functionality
    const popup = document.getElementById("imagePopup");
    const popupImg = document.getElementById("popupImg");
    const closeBtn = document.querySelector(".popup-close");
    const popupDownload = document.getElementById("popupDownload");

    document.querySelectorAll(".view-image a").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const imgSrc = this.getAttribute("href");

            popup.style.display = "flex";
            popupImg.src = imgSrc;

            // Set download link
            popupDownload.href = imgSrc;
            popupDownload.setAttribute("download", imgSrc.split('/').pop());
        });
    });

    closeBtn.addEventListener("click", () => {
        popup.style.display = "none";
    });

    popup.addEventListener("click", (e) => {
        if (e.target === popup) {
            popup.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const popup = document.getElementById("imagePopup");
    const popupImg = document.getElementById("popupImg");
    const closeBtn = document.querySelector(".popup-close");
    const popupDownload = document.getElementById("popupDownload");

    document.querySelectorAll(".view-image a").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const imgSrc = this.getAttribute("href");

            popup.style.display = "flex";
            popupImg.src = imgSrc;

            // 🔑 make download work
            popupDownload.href = imgSrc;
            popupDownload.setAttribute("download", imgSrc.split('/').pop());
        });
    });

    closeBtn.addEventListener("click", () => {
        popup.style.display = "none";
    });

    popup.addEventListener("click", (e) => {
        if (e.target === popup) {
            popup.style.display = "none";
        }
    });
});

</script>
<script src="header.js"></script>
<script src="footer.js"></script>
</body>
</html>