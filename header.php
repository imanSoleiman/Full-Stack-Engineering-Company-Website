<?php
include "./config.php"; // your DB connection

// Fetch navbar values
$navbar_result = $conn->query("SELECT setting_key, setting_value FROM navbar");
$navbar = [];
if ($navbar_result && $navbar_result->num_rows > 0) {
  while ($row = $navbar_result->fetch_assoc()) {
    $navbar[$row['setting_key']] = $row['setting_value'];
  }
}

$result = $conn->query("SELECT * FROM footer_content WHERE id=1");
$header = $result->fetch_assoc();

?>



<nav>
  <div class="header-container">
    <div class="header-container-wrapper">
      <div class="top-nav">
        <div class="top-nav-info">
          <a href="mailto:<?= $navbar['email'] ?>"><i class="fa-regular fa-envelope"></i> <?= $navbar['email'] ?></a>
          <a href="tel:<?= $navbar['phone'] ?>"><i class="fa-solid fa-phone-volume"></i> <?= $navbar['phone'] ?></a>
          <a href="#"><i class="fa-regular fa-clock"></i> <?= $navbar['working_hours'] ?></a>
        </div>

        <div class="top-nav-social">
          <?php
          $social_result = $conn->query("SELECT * FROM navbar_social ORDER BY id ASC");
          while ($social = $social_result->fetch_assoc()) {
            $link = htmlspecialchars($social['link']);
            $icon = htmlspecialchars($social['icon']); // e.g., "fab fa-facebook-f"
            echo '<a href="' . $link . '" target="_blank"><i class="' . $icon . '"></i></a>';
          }
          ?>
        </div>

      </div>

      <div class="original-nav">
        <div class="original-nav-container">
          <div class="original-nav-logo">
            <a href="index.php">
              <img src="./assets/home/<?= htmlspecialchars($header['logo']) ?>" alt="Spectrum Logo" />
            </a>
          </div>



          <div class="nav-links">

            <div class="close-menu"><i class="fa-solid fa-xmark"></i></div>


            <ul class="navbar_menu">
              <div class="search-container">
                <form action="search_result.php" method="GET">
                  <input type="text" name="query" placeholder="Search..." class="search-input" />
                  <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                  </button>
                </form>
              </div>


              <li class="navbar_items"><a href="index.php" class="navbar_links">Home</a></li>

              <li class="navbar_items dropdown">
                <div class="navbar_links drop">
                  <div class="flex">
                    <span>About us</span> <i class="fa fa-chevron-down"></i>
                  </div>
                  <ul class="dropdown-content">
                    <li><a href="about.php"><span class="circle"></span> Who we are</a></li>
                    <li><a href="structure.php"><span class="circle"></span> Company's Structure</a></li>
                    <li><a href="corporate.php"><span class="circle"></span> Corporate responsibility</a></li>

                    <li><a href="devision.php"><span class="circle"></span> Our division</a></li>

                  </ul>
                </div>


              </li>



              <li class="navbar_items"><a href="services.php" class="navbar_links">Services</a></li>
              <li class="navbar_items"><a href="projects.php" class="navbar_links">Projects</a></li>

              <li class="navbar_items dropdown">
                <div class="navbar_links drop">
                  <div class="flex">
                    <span>contact us</span> <i class="fa fa-chevron-down"></i>
                  </div>
                  <ul class="dropdown-content">
                    <li><a href="contact.php"><span class="circle"></span> contact us</a></li>
                    <li><a href="locate.php"><span class="circle"></span> locate us</a></li>
                  </ul>
                </div>
              </li>

              <li class="navbar_items"><a href="teams.php" class="navbar_links">Team</a></li>
              <li class="navbar_items"><a href="careers.php" class="navbar_links">Careers</a></li>

              <li class="navbar_items dropdown">
                <div class="navbar_links drop">
                  <div class="flex">
                    <span>Management policies</span> <i class="fa fa-chevron-down"></i>
                  </div>
                  <ul class="dropdown-content">
                    <li><a href="gulf-spectrum.php"><span class="circle"></span> gulf spectrum</a></li>
                    <li><a href="spectrum.php"><span class="circle"></span> spectrum</a></li>
                  </ul>
                </div>


              </li>

              <li class="navbar_items"><a href="news.php" class="navbar_links">news</a></li>
              <div class="navbar-details">
                <div class="navbar-details-left">
                  <p>Quick contact </p>
                  <a href="mailto:<?= $navbar['email'] ?>"><i class="fa-regular fa-envelope"></i> <?= $navbar['email'] ?></a>
                  <a href="tel:<?= $navbar['phone'] ?>"><i class="fa-solid fa-phone-volume"></i> <?= $navbar['phone'] ?></a>
                  <a href="#"><i class="fa-regular fa-clock"></i> <?= $navbar['working_hours'] ?></a>
                </div>

                <div class="navbar-details-right">
                  <p>We are on social media</p>
                  <?php
                  $social_result = $conn->query("SELECT * FROM navbar_social ORDER BY id ASC");
                  while ($social = $social_result->fetch_assoc()) {
                    $link = htmlspecialchars($social['link']);
                    $icon = htmlspecialchars($social['icon']); // e.g., "fab fa-facebook-f"

                    // Infer social name from icon
                    if (str_contains($icon, 'facebook')) $social_name = 'Facebook';
                    elseif (str_contains($icon, 'instagram')) $social_name = 'Instagram';
                    elseif (str_contains($icon, 'linkedin')) $social_name = 'LinkedIn';
                    elseif (str_contains($icon, 'twitter')) $social_name = 'Twitter';
                    else $social_name = 'Social';

                    echo '<a href="' . $link . '" target="_blank">' . $social_name . '</a> ';
                  }
                  ?>
                </div>

            </ul>

          </div>



          <div class="dots-grid">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>

      </div>
    </div>


</nav>