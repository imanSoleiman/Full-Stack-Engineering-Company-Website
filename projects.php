<?php include('./config.php');
include 'config.php'; // adjust path if needed

// Fetch data
$query = $conn->query("SELECT * FROM project_intro WHERE id=1");
$data = $query->fetch_assoc();



$leftClass = "left-part"; // default class
if ((int)$data['show_right_image'] === 0 || empty($data['right_image'])) {
  $leftClass .= " full-width"; // add class if right image is hidden
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Projects</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
    }

    .project-container {
      margin: 50px;
      padding: 10px;
      height: auto;
    }

    .filter-buttons {
      display: flex;
      flex-wrap: nowrap;
      /* Prevent wrapping */
      overflow-x: auto;
      /* Enable horizontal scrolling */
      -webkit-overflow-scrolling: touch;
      margin-bottom: 2rem;
      padding: 0 1rem;
      /* Add horizontal padding for scroll */
      scroll-behavior: smooth;
      scrollbar-width: none;
      /* Firefox */
    }

    /* Optional: add smooth momentum for iOS */
    @supports (-webkit-overflow-scrolling: touch) {
      .filter-buttons {
        -webkit-overflow-scrolling: touch;
      }
    }

    .filter-buttons button {
      background-color: #ede6e69c;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 20px;
      font-weight: 500;
      flex: 0 0 auto;
      cursor: pointer;
      margin: 10px;
    }

    .filter-buttons button:hover,
    .filter-buttons button.active {
      background-color: #e22b2b;
      color: #fff;
    }

    .card-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 2rem;
    }

    .project-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      width: 300px;
      flex-shrink: 0;
      transition: transform 0.3s ease;
    }

    .card-image-container {
      overflow: hidden;
      position: reltaive;
      height: 200px;
    }

    .card-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .card-image-container:hover .card-img {
      transform: scale(1.1);
    }

    .category-tag,
    .status-tag {
      position: absolute;
      top: 10px;
      padding: 0.3rem 0.7rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      color: white;
    }

    .category-tag {
      left: 10px;
      background-color: #d61a1a;
    }

    .card-content {
      padding: 1rem;
    }

    .card-content h3 {
      margin: 0.5rem 0;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .card-content p {
      font-size: 0.85rem;
      margin: 0.3rem 0;
      color: #555;
    }

    .view-details {
      display: inline-block;
      margin-top: 1rem;
      color: #e22b2b;
      font-weight: 500;
      text-decoration: none;
      font-size: 0.9rem;
      cursor: pointer;
    }

    .modal {
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
      padding: 20px;


    }

    .modal-content {
      background: #fff;
      max-width: 1000px;
      width: 100%;
      overflow-y: auto;
      position: relative;
      animation: fadeIn 0.5s ease;
      border-radius: 20px;
      max-height: 500px;
    }

    .modal-content img {
      width: 100%;
      height: 300px;
      object-fit: cover;

    }

    .modal-body {
      padding: 2rem;
    }

    .tags {
      display: flex;
      gap: 10px;
      margin-bottom: 1rem;
    }

    .tag {
      padding: 0.3rem 0.8rem;
      font-size: 0.75rem;
      font-weight: 600;
      border-radius: 20px;
      color: #000000ff;
      background: #4e4f5523;
    }

    .modal-title {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: #111;
    }

    .project-info {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      font-size: 0.95rem;
      color: #333;
      margin-bottom: 1.5rem;
    }

    .project-info div {
      flex: 1 1 200px;
    }

    .project-description {
      margin-bottom: 1rem;
      font-size: 1rem;
      color: #444;
    }

    .key-features {
      margin-top: 1rem;
    }

    .key-features h4 {
      margin-bottom: 0.5rem;
      font-size: 1rem;
    }

    .key-features ul {
      padding-left: 1rem;
      columns: 2;
    }

    .key-features ul li {
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    .close {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 22px;
      font-weight: bold;
      color: #333;
      cursor: pointer;
      background: #eee;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    @media(max-width: 600px) {
      .modal-body {
        padding: 1rem;
      }

      .key-features ul {
        columns: 1;
      }

      .modal-content img {
        height: 180px;
      }
    }

    @media (max-width: 600px) {
      .modal-text ul {
        columns: 1;
      }

      .modal-content img {
        height: 180px;
      }
    }

    @media (max-width:400px) {
      .project-card {
        width: 100%;
      }

      .project-container {
        margin: 20px;
      }
    }

    .project-intro {
      background-image: url('./images/b1-bg-1.png');
      width: 100%;
      height: 800px;
      position: relative;
      background-position: center center;
      background-size: cover;
      background-repeat: no-repeat;
      border-radius: 0px 0px 2px 20px;
      overflow: hidden;

    }

    .project-intro::after {
      content: "";
      width: 100%;
      background: #000000cf;
      opacity: 52%;
      position: absolute;
      height: 800px;
      top: 0;
      left: 0;
      z-index: 1;

    }

    .project-first-container {
      position: relative;
      width: 1500px;
      margin: auto;
      z-index: 2;
      height: 100%;


    }

    .p-container {
      position: absolute;
      bottom: 0;
      display: flex;
      align-items: center;
      justify-content: space-evenly;
      margin: 0 20px;
    }

    .right-part {
      width: 38%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .right-part img {
      width: 100%;
      height: auto;
      object-fit: contain;
    }

    .left-part h1 {
      color: white;
      font-size: 3.5rem;
      text-transform: uppercase;
      overflow: hidden;

    }

    .left-part h1 span {
      display: inline-block;
      transform: translateY(100%);
    }


    .left-part p {
      color: white;
      font-size: 20px;
      margin: 10px 0px;

    }

    .left-part {
      width: 40%;
    }

    .left-part a {
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

    }

    .left-part a:hover {
      box-shadow: inset 300px 0 0 0 black;
      color: white;
    }

    .left-part.full-width {
      width: 100%;
      margin-bottom: 20px;
    }

    @media (max-width: 1400px) {
      .project-first-container {
        width: 1300px;
      }

      .left-part h1 {
        color: white;
        font-size: 2.5rem;
        text-transform: uppercase;

      }

    }

    @media (max-width: 1200px) {
      .project-first-container {
        max-width: 1000px;
      }

      .left-part {
        width: 50%;
      }

      .project-intro {
        height: 590px;
      }

    }

    @media (max-width: 992px) {
      .project-intro {
        padding-top: 120px;
      }

      .project-first-container {
        width: 800px;

      }

    }

    @media (max-width: 768px) {
      .p-container {
        flex-direction: column;
        display: flex;

      }

      .project-first-container {
        width: 600px;
      }

      .left-part {
        width: 100%;
      }

      .right-part {
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

      .project-intro {
        height: 800px;

      }
    }

    @media (max-width: 560px) {

      .project-first-container {
        width: 100%;
      }

      .left-part {
        width: 80%;
      }

      .right-part {
        width: 300px;
      }

      .left-part h1 {
        font-size: 25px;
      }

      .left-part p {
        font-size: 16px;
      }

      .left-part a {
        width: 120px;
        font-size: 12px;
        height: 40px;
      }
    }

    @media (max-width: 330px) {
      .right-part {
        width: 100%;
      }
    }

    .word {
      position: relative;
    }

    .line-mask {
      position: absolute;
      top: 0;
      right: 0;
      background-color: #0d0d0d;
      opacity: 0.8;
      height: 100%;
      width: 100%;
      z-index: 2;
    }
  </style>
</head>

<body>

  <?php include('header.php') ?>


  <section class="project-intro" style="background: url('./assets/projects_uploads/<?= htmlspecialchars($data['background_image']) ?>') no-repeat center center/cover;">

    <div class="project-first-container">
      <div class="p-container">
        <div class="<?= $leftClass ?>">
          <div class="heading">
            <?php if (!empty($data['heading1'])): ?>
              <h1><span class="create"><?= htmlspecialchars($data['heading1']) ?></span></h1>
            <?php endif; ?>
            <?php if (!empty($data['heading2'])): ?>
              <h1><span class="create"><?= htmlspecialchars($data['heading2']) ?></span></h1>
            <?php endif; ?>
            <?php if (!empty($data['heading3'])): ?>
              <h1><span class="create"><?= htmlspecialchars($data['heading3']) ?></span></h1>
            <?php endif; ?>
            <?php if (!empty($data['heading4'])): ?>
              <h1><span class="create"><?= htmlspecialchars($data['heading4']) ?></span></h1>
            <?php endif; ?>
          </div>

          <p><?= nl2br(htmlspecialchars($data['paragraph'])) ?></p>
          <a id="scrollBtn">discover more</a>
        </div>

        <!-- RIGHT PART (optional) -->
        <?php if ((int)$data['show_right_image'] === 1 && !empty($data['right_image'])): ?>
          <div class="right-part">
            <img src="./assets/projects_uploads/<?= htmlspecialchars($data['right_image']) ?>" alt="Project Right Image">

          </div>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <section class="project-container " id="projects">
    <div class="filter-buttons">
      <?php
      $categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
      ?>
      <button class="filter-btn <?= !isset($_GET['category_id']) || $_GET['category_id'] == 0 ? 'active' : '' ?>" data-id="0">All</button>
      <?php foreach ($categories as $cat): ?>
        <button class="filter-btn <?= isset($_GET['category_id']) && $_GET['category_id'] == $cat['id'] ? 'active' : '' ?>" data-id="<?= $cat['id'] ?>">
          <?= htmlspecialchars($cat['name']) ?>
        </button>
      <?php endforeach; ?>
    </div>


    <div class="card-container">
      <?php
      $filter = isset($_GET['category_id']) && $_GET['category_id'] > 0 ? "WHERE projects.category_id = " . intval($_GET['category_id']) : "";
      $query = "SELECT projects.*, categories.name AS category_name 
          FROM projects 
          LEFT JOIN categories ON projects.category_id = categories.id 
          $filter ORDER BY projects.id DESC";
      $projects = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

      if (empty($projects)) {
        echo '<p style="color: #555; text-align: center; font-size: 1.1rem; font-weight: 500; width: 100%; height:420px; display:flex; align-items:center; color: red;justify-content:center; background-color: #f9f9f9; border-radius: 8px;">
           No projects available in this category yet. Check back soon for updates!
          </p>';
      } else {
        foreach ($projects as $project):
      ?>
          <div class="project-card">
            <div class="card-image-container">
              <img src="./assets/projects_uploads/<?php echo htmlspecialchars($project['image_path']); ?>" class="card-img" />
            </div>
            <div class="card-content">
              <div class="tags">
                <div class="tag"><?= htmlspecialchars($project['category_name']) ?></div>
              </div>
              <h3><?= htmlspecialchars($project['name']) ?></h3>
              <p>
                <?php if (!empty($project['location_url'])): ?>
                  <a href="<?= htmlspecialchars($project['location_url']) ?>" target="_blank" title="View on Map" style="text-decoration: none;">
                    <i class="fas fa-map-marker-alt" style="color: #e74c3c;"></i>
                  </a>
                <?php endif; ?>
                <?= htmlspecialchars($project['location']) ?>
              </p>


              <a class="view-details"
                data-id="<?= $project['id'] ?>"
                data-name="<?= htmlspecialchars($project['name']) ?>"
                data-location="<?= htmlspecialchars($project['location']) ?>"
                data-category="<?= htmlspecialchars($project['category_name']) ?>"
                data-image="./assets/projects_uploads/<?= $project['image_path'] ?>">
                View Details
              </a>
            </div>
          </div>
      <?php
        endforeach;
      }
      ?>
    </div>

    </div>
    <?php foreach ($projects as $project): ?>
      <!-- Project Card -->
      <div class="project-card">
        <!-- ... your existing card code ... -->

        <!-- Modal Popup For This Project -->
        <?php
        // Fetch popup data for this project
        $popup = $conn->query("SELECT popup_description, popup_key_features FROM project_popups WHERE project_id = {$project['id']}")->fetch_assoc();
        ?>

        <div class="modal" id="popup-<?= $project['id'] ?>" style="display:none;">
          <div class="modal-content">
            <span class="close" onclick="closeModal(<?= $project['id'] ?>)">&times;</span>
            <img src="./assets/projects_uploads/<?php echo $project['image_path']; ?>" />

            <div class="modal-body">
              <div class="tags">
                <div class="tag"><?= htmlspecialchars($project['category_name']) ?></div>
              </div>
              <div class="modal-title"><?= htmlspecialchars($project['name']) ?></div>
              <div class="project-info">
                <div>Location: <?= htmlspecialchars($project['location']) ?></div>
              </div>

              <div class="project-description">
                <?= htmlspecialchars($popup['popup_description'] ?? 'No description provided.') ?>
              </div>

              <div class="key-features">
                <h4>Key Features:</h4>
                <ul>
                  <?php
                  $features = json_decode($popup['popup_key_features'] ?? '[]', true); // decode JSON array
                  if (is_array($features)):
                    foreach ($features as $feature):
                      if (trim($feature) !== ''):
                  ?>
                        <li><?= htmlspecialchars($feature) ?></li>
                  <?php
                      endif;
                    endforeach;
                  endif;
                  ?>

                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>
    <?php endforeach; ?>

  </section>



  <?php include 'footer.php' ?>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/gsap.min.js"
    integrity="sha512-qF6akR/fsZAB4Co1QDDnUXWnaQseLGXoniuSuSlPQK6+aWhlMZcHzkasCSlnWoe+TJuudlka1/IQ01Dnhgq95g=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/ScrollTrigger.min.js"
    integrity="sha512-IHDCHrefnBT3vOCsvdkMvJF/MCPz/nBauQLzJkupa4Gn4tYg5a6VGyzIrjo6QAUy3We5HFOZUlkUpP0dkgE60A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      // Modal functions
      function openModal(id) {
        const modal = document.getElementById('popup-' + id);
        if (modal) modal.style.display = 'flex';
      }


      function bindViewDetails() {
        document.querySelectorAll('.view-details').forEach(button => {
          button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            openModal(id);
          });
        });

        document.querySelectorAll('.modal').forEach(modal => {
          modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
          });
        });
      }

      bindViewDetails();

      // Filter buttons
      document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const categoryId = this.dataset.id;

          // Update active class
          document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
          this.classList.add('active');

          // Animate out old cards
          gsap.to(".card-container .project-card", {
            opacity: 0,
            y: 30,
            duration: 0.3,
            stagger: 0.05,
            onComplete: loadProjects
          });

          function loadProjects() {
            fetch(window.location.pathname + '?category_id=' + categoryId)
              .then(res => res.text())
              .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Replace cards
                document.querySelector('.card-container').innerHTML =
                  doc.querySelector('.card-container').innerHTML;

                // Replace modals
                const allModals = doc.querySelectorAll('.modal');
                document.querySelectorAll('.modal').forEach(m => m.remove());
                allModals.forEach(m => document.body.appendChild(m));

                // Animate in new cards
                gsap.fromTo(".card-container .project-card", {
                  opacity: 0,
                  y: 30
                }, {
                  opacity: 1,
                  y: 0,
                  duration: 0.4,
                  stagger: 0.05,
                  delay: 0.1
                });

                // Re-bind modal buttons
                bindViewDetails();
              });
          }
        });
      });

    });


    const button = document.getElementById('scrollBtn');
    const target = document.getElementById('projects');

    button.addEventListener('click', () => {
      target.scrollIntoView({
        behavior: 'smooth'
      });
    });

    let t = gsap.timeline({
      defaults: {
        ease: "SlowMo.easeOut"
      }
    });
    t.to(".create", {
      y: '0%',
      duration: 0.7,
      stagger: 0.2
    });

    gsap.from(".project-intro .right-part img", {
      x: 900, // start lower (push it down)
      opacity: 0, // invisible at first
      duration: 2, // smooth timing
      ease: "power3.out",
      scrollTrigger: {
        trigger: ".project-intro",
        start: "top 80%", // when section comes into view
        toggleActions: "play none none reverse" // play when in view, reverse when out
      }
    });

    function closeModal(id) {
      const modal = document.getElementById('popup-' + id);
      if (modal) modal.style.display = 'none';
    }
  </script>
  <script src="header.js"></script>
  <script src="footer.js"></script>
</body>

</html>