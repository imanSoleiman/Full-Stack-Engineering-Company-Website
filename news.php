<?php
include './config.php'; 

$recentPosts = $conn->query("
    SELECT n.id, n.title, n.image, n.date_day, n.date_month, n.date_year,
           s.content AS description
    FROM news_cards n
    LEFT JOIN news_sections s 
        ON n.id = s.news_id AND s.section_order = 1
    ORDER BY n.date_year DESC, n.date_month DESC, n.date_day DESC
    LIMIT 3
");


$result = $conn->query("SELECT * FROM news_intro WHERE id=1");
$row = $result ? $result->fetch_assoc() : null;

// Use variables to store each field
$bg_image = $row['background_image'] ?? 'default.jpg';
$header1 = $row['header_line1'] ?? 'Default Line 1';
$header2 = $row['header_line2'] ?? 'Default Line 2';
$header3 = $row['header_line3'] ?? 'Default Line 3';
$header4 = $row['header_line4'] ?? 'Default Line 4';
$paragraph = $row['paragraph'] ?? 'Default paragraph';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>News</title>
    <style>
        .first-section{
            padding-top:200px;
            background-color:whitesmoke;

        }
        .first-section-container{
            display:flex;
            flex-direction:column;
            justify-content:flex-start;
            margin-left:50px;
            gap:20px;
        }
        .first-section h1{
            font-size:40px;
            text-transform: uppercase;
            color:black;

        }
        .line{
            height:1px;
            background-color:gray;
            width:100%;

        }
        
.filter-bar {
  display: flex;
  gap: 10px;
  align-items: center;
  margin: 20px 0;

  /* Make it scrollable on small screens */
  overflow-x: auto;
  -webkit-overflow-scrolling: touch; /* smooth scrolling for iOS */
  scroll-behavior: smooth;           /* optional smooth scroll */
}

/* Optional: hide scrollbar but still scrollable */
.filter-bar::-webkit-scrollbar {
  height: 6px;
}

.filter-bar::-webkit-scrollbar-thumb {
  background-color: rgba(0,0,0,0.3);
  border-radius: 3px;
}

.filter-bar::-webkit-scrollbar-track {
  background: transparent;
}
.filter-bar::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Opera */
}
.filter-btn {
  padding: 15px 40px;
  border: none;
  border-radius: 6px;
  background-color: #eaeaea;
  color: #333;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.filter-btn:hover {
  background-color: #d4d4d4;
}

.filter-btn.active {
  background-color: #ff2222ff;
  color: white;
}
.second-section{
    padding:50px 100px;
    display:flex;
    flex-direction:column;
}

.new-cards-section{
display:grid;
grid-template-columns: 65%  auto;
gap:10px;
}
.news-card-left{
    width:100%;
}
.news-card-grid{
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:10px;
}
.news-card-containers{
    position:relative;
    display:flex;
    flex-direction:column;
    gap:15px;
}
.news-card-container{
  position:relative;
  overflow: hidden;
  width:100%;
  height:300px;
  border-radius:10px;
}
.news-card-container img{
    width:100%;
height:100%;
object-fit:cover;
border-radius:10px;

}
.news-card-container img{
  filter:grayscale(1);
  transition: all 0.4s cubic-bezier(0.55, 0.085, 0, 0.99);
  cursor:pointer;
}
.news-card-container img:hover{
  filter:grayscale(0);
  transform:scale(105%);
}
.new-card-right{
  background-color:whitesmoke;
  border-radius:10px;
  padding:20px;
  position:relative;
}
.categories-list{
  display:flex;
  justify-content: space-between;

}
.categories{
  display:flex;
  flex-direction:column;
  gap:10px;
}
.recent-post{
  display:flex;
  flex-direction:column;
  gap:20px;
  width:100%;
}
.recent-post-container{
  width:90%;
  display:flex;
  flex-direction:column;
  gap:10px;
}
.recent-posts{
  display:grid;
  grid-template-columns:0.5fr 1fr;
gap:10px;
}
.recent-posts img{
width:100%;
height:130px;
object-fit: cover;
padding:10px 0px;

}

    .search-box {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      width: 100%;
    }

    .search-box input {
      border: none;
      outline: none;
      padding: 12px 15px;
      font-size: 16px;
      flex: 1;
    }

    .search-box button {
      background: #c20505ff; 
      border: none;
      padding:15px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .search-box button:hover {
      background: #000000ff;
    }

    .search-box button i {
      color: #fff;
      font-size: 18px;
    }
    .news-details{
      display:flex;
      justify-content:space-around;
      align-items: center;
      position: absolute;
      width:100%;
      bottom:0px;
        background-color: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
      padding:20px;
        transform: translateY(100%);
  opacity: 0;
  transition: transform 0.5s ease, opacity 0.5s ease;
    }
    .news-card-container:hover .news-details {
  transform: translateY(0);
  opacity: 1;
}
.news-card-container:hover img {
  filter: grayscale(0);
  transform: scale(105%);
}
    .news-card-container:hover .news-deta {
  transform: translateY(0);
  opacity: 1;
}


.new-arrow{
  width:40px;
  height:40px;
  background-color:white;
  position:relative;
  transition:ease-in-out 0.2s;
  flex-shrink:0;
}
.new-arrow:hover{
  background-color:red;

}
.new-arrow img{
  width:100%;
  height:100%;
  padding:10px;
}
.top-month{
  background-color:rgba(0, 0, 0, 0.8)  ;
  color:white;
  position:absolute;
  top:3%;
  padding:10px 20px;
  display:flex;
  flex-direction:column;
  z-index:5;
  justify-content:center;
  align-items:center;
   left:3%;
  border-radius:10px;
  transition:ease-in-out 0.2s;
}
.top-month h1{
    letter-spacing: 4px;
    font-weight: 400;
    font-size: 14px;
    text-transform: uppercase;
}

    .news-card-container:hover .top-month  {
 background-color:rgba(255, 255, 255, 0.6)  ;
 color:black;
}
.search-bar{
  display:flex;
  flex-direction:column;
  gap:10px;
}
.desc{
font-size:13px;
}
.circle{
  width:10px;
  height:10px;
  border-radius:50%;
  border:1px solid black;
}

.circle.active {
    background-color: #ff2222; /* the color you want */
    border-color: #ff2222;     /* optional: match border */
    transition: all 0.3s ease;
}

.categories-left-section{
  display:flex;
  align-items:center;
  width:50%;
  gap:10px;
  }

#news-results.loading {
    position: relative;
    min-height: 100px; /* ensures space for spinner */
    opacity: 0.5; /* fade effect */
}

#news-results.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 40px;
    height: 40px;
    margin: -20px 0 0 -20px;
    border: 4px solid #3498db;
    border-top: 4px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Smooth fade-in effect for new content */
#news-results.fade-in {
    animation: fadeIn 0.5s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@media screen and (max-width: 1024px){
  .new-cards-section{
display:grid;
grid-template-columns: 500px auto;
gap:10px;
}
}
@media screen and (max-width:950px){
  .new-cards-section{
grid-template-columns: 400px auto;
}
}
@media screen and (max-width: 900px){
  .new-cards-section{
display:grid;
grid-template-columns: none;
grid-template-rows:auto auto;
}
.second-section{
  padding:20px;
}
}
@media screen and (max-width: 500px){
  .news-card-grid {
    display: grid;
    grid-template-columns:none;
    gap: 10px;
}
.filter-btn{
  padding: 10px 40px;
}
.news-card-container{
  height:200px;
}
.news-details{
  padding:10px;
}

}


 .project-intro{
      background-image: url('./images/d78a969ab20854f240f405d55ebff1d9.jpg');
      width:100%;
      height:800px;
      position:relative;
      background-position: center center;
      background-size:cover;
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
  height:800px;
  top:0;
  left:0;
  z-index:1;
    
    }
    .project-first-container{
      position: relative;
     width:1500px;
     margin:auto;
     z-index:2;
     height:100%;

     
    }
    .p-container{
      position: absolute;
      bottom:0;
      margin-left:20px;
    }

    .left-part h1{
      text-shadow: black;
      color:white;
      font-size:3.5rem;
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
      width:100%;
      margin-bottom:25px;
    }

    .left-part a{ 
       width:150px;
     height:50px;
     margin-top:10px;
      padding:10px 20px;
      border-radius:10px;
      display:inline-block;
    flex: 0 0 auto;
    text-transform: capitalize;
    font-size: 1rem;
    line-height: 1;
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    background-color:transparent;
    display:flex;
    align-items:center;
    cursor:pointer;
    background-color:#e22b2b;
    box-shadow: inset 0 0 0 0 black;
    transition: ease-in-out 0.5s;

    }
    .left-part a:hover{
      box-shadow:inset 300px 0 0 0 black;
      color:white;
    }
    

    @media (max-width: 1400px) {
  .project-first-container {
    width: 1300px;
  }
     .left-part h1{
      color:white;
      font-size:2.5rem;
      text-transform: uppercase;

    }

}

@media (max-width: 1200px) {
  .project-first-container {
    max-width: 1000px;
  }
  .left-part{
    width:50%;
  }
    .project-intro{
    height:590px;
}

}

@media (max-width: 992px) {
  .project-intro{
    padding-top:120px;
  }
  .project-first-container {
    width: 800px;
   
  }

}

@media (max-width: 768px) {
  .p-container {
    flex-direction: column;
    display:flex;
  
  }  
  .project-first-container {
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
  
    .project-intro{
    height:800px;
  
  }
}

@media (max-width: 560px) {

  .project-first-container {
    width: 100%;
  }
  .left-part
   {
    width: 100%;
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
}

@media (max-width: 330px) {
  .right-part{
   width: 100%;
}  
}

@media screen and (max-width: 400px) {
  .project-intro {
    height: 400px;
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
    <?php include('header.php');?>
  
<section class="project-intro" style="background-image: url('./assets/news/<?php echo $bg_image; ?>');">
  <div class="project-first-container">
    <div class="p-container"> 
      <div class="left-part">
        <div class="heading">
          <h1><span class="create"><?= htmlspecialchars($header1) ?></span></h1>
          <h1><span class="create"><?= htmlspecialchars($header2) ?></span></h1>
          <h1><span class="create"><?= htmlspecialchars($header3) ?></span></h1>
          <h1><span class="create"><?= htmlspecialchars($header4) ?></span></h1>
        </div>
        <p><?= htmlspecialchars($paragraph) ?></p>
        <a id="scrollBtn">latest news</a>
      </div>
    </div>
  </div>
</section>


     <section class="second-section"  id="news-section">
   <div class="filter-bar">
    <button class="filter-btn active" data-year="all">All</button>
    <?php
    // Fetch years from DB
    $yearsResult = $conn->query("SELECT * FROM news_years ORDER BY year DESC");
    while($y = $yearsResult->fetch_assoc()):
    ?>
        <button class="filter-btn" data-year="<?= $y['year'] ?>"><?= $y['year'] ?></button>
    <?php endwhile; ?>
</div>

        <div class="new-cards-section">
            <div class="news-card-left">
         <div class="news-card-grid" id="news-results">
              <?php
              // Fetch news from DB
              $newsResult = $conn->query("
                  SELECT n.*, y.year AS year_name 
                  FROM news_cards n
                  JOIN news_years y ON n.year_id = y.id
                  ORDER BY n.date_year DESC, n.date_month DESC, n.date_day DESC
              ");

while($row = $newsResult->fetch_assoc()):
    $monthName = date('M', mktime(0,0,0,$row['date_month'],10)); // Convert month number to abbreviation
?>
    <div class="news-card-container gray" data-year="<?= $row['date_year'] ?>">
        <div class="top-month">
            <h2><?= $row['date_day'] ?></h2>
            <h1><?= $monthName ?></h1>
        </div>
        <img src="./assets/news/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>">
        <div class="news-details">
            <div class="new-details-text">
                <p><?= $row['date_day'] . ' ' . date('F', mktime(0,0,0,$row['date_month'],10)) . ' ' . $row['date_year'] ?></p>
                <h2><?= htmlspecialchars($row['title']) ?></h2>
            </div>
            <div class="new-arrow">
                <a href="news_details.php?id=<?= $row['id'] ?>">
                    <img src="./images/right-up.png" alt="View News">
                </a>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>
              
<div style="text-align:center; margin-top:20px;">
    <button id="loadMoreBtn" style="padding:10px 20px; background-color:#ff2222; color:white; border:none; border-radius:5px; cursor:pointer;">
        Load More
    </button>
</div>


            </div>   

           <div class="new-card-right">
              <div class="news-card-containers">
<div class="search-box">
    <input type="text" id="searchInput" placeholder="Search here...">
    <button onclick="doSearch()">
      <i class="fas fa-search"></i>
    </button>
    <ul id="autocompleteList" class="autocomplete-dropdown"></ul>
</div>
                </div>
<div class="categories">
    <h1>Categories</h1>
    <?php
    // Fetch all categories with the count of news items in each
    $catQuery = $conn->query("
        SELECT c.id, c.name, COUNT(n.id) AS news_count
        FROM news_categories c
        LEFT JOIN news_cards n ON n.category_id = c.id
        GROUP BY c.id, c.name
        ORDER BY c.name ASC
    ");

    while($cat = $catQuery->fetch_assoc()):
    ?>
<div class="categories-list" data-category="<?= $cat['id'] ?>">
    <div class="categories-left-section">
        <div class="circle"></div>
        <p><?= htmlspecialchars($cat['name']) ?></p>
    </div>
    <p>(<?= $cat['news_count'] ?>)</p>
</div>

    <?php endwhile; ?>
</div>


<div class="recent-post">
    <h1>Recent Posts</h1>
    <div class="recent-post-container">
        <?php while($post = $recentPosts->fetch_assoc()): 
            $monthName = date('M', mktime(0,0,0,$post['date_month'],10));
        ?>
        <div class="recent-posts">
            <img src="./assets/news/<?= $post['image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
            <div class="recent-post-text">
                <h4><?= htmlspecialchars($post['title']) ?></h4>
                <p class="desc"><?= htmlspecialchars(substr($post['description'],0,100)) ?>...</p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>


              </div>
           </div>

        </div>

   </section>


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

document.addEventListener('DOMContentLoaded', () => {
    const newsItems = document.querySelectorAll('#news-results .news-card-container');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const initialItems = 4; // Number of items shown initially
    let expanded = false; // Track toggle state

    // Hide items beyond the initialItems
    newsItems.forEach((item, index) => {
        if(index >= initialItems) item.style.display = 'none';
    });

    // Hide button if not needed
    if(newsItems.length <= initialItems){
        loadMoreBtn.style.display = 'none';
    }

    loadMoreBtn.addEventListener('click', () => {
        if(!expanded){
            // Show all hidden items with animation
            const hiddenItems = Array.from(newsItems).slice(initialItems);
            hiddenItems.forEach(item => {
                item.style.display = 'block';
                gsap.fromTo(item, {opacity:0, y:20}, {opacity:1, y:0, duration:0.5, ease:"power2.out"});
            });
            loadMoreBtn.textContent = 'Show Less';
            expanded = true;
        } else {
            // Hide items beyond initialItems
            const hiddenItems = Array.from(newsItems).slice(initialItems);
            hiddenItems.forEach(item => {
                gsap.to(item, {opacity:0, y:20, duration:0.5, ease:"power2.in", onComplete:()=> {
                    item.style.display = 'none';
                }});
            });
            loadMoreBtn.textContent = 'Load More';
            expanded = false;
        }
    });
});

const searchInput = document.getElementById('searchInput');
const filterBtns = document.querySelectorAll('.filter-btn');
const newsResults = document.getElementById('news-results');
const categoryCards = document.querySelectorAll('.categories-list');

let selectedYear = 'all';
let selectedCategory = 'all';
let debounceTimer;

// Update search bar placeholder based on category/year
function updateSearchPlaceholder() {
    let placeholder = 'Search';
    if(selectedCategory !== 'all') {
        const activeCard = document.querySelector(`.categories-list[data-category="${selectedCategory}"] p`);
        if(activeCard) placeholder += ' in "' + activeCard.textContent + '"';
    }
    if(selectedYear !== 'all') {
        placeholder += ' from ' + selectedYear;
    }
    placeholder += '...';
    searchInput.placeholder = placeholder;
}

// Trigger search on Enter
searchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') doSearch(searchInput.value.trim());
});

// Trigger search on button click
document.querySelector('.search-box button').addEventListener('click', () => {
    doSearch(searchInput.value.trim());
});

// Year filter buttons
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedYear = btn.dataset.year;
        updateSearchPlaceholder();
        doSearch(searchInput.value.trim());
    });
});

// Category filter (exclusive selection)
// Category filter (toggle selection)
categoryCards.forEach(card => {
    const circle = card.querySelector('.circle');
    card.addEventListener('click', () => {
        // If clicked category is already active, deselect it
        if (circle.classList.contains('active')) {
            circle.classList.remove('active');
            selectedCategory = 'all'; // show all news
        } else {
            // Remove active class from all circles
            categoryCards.forEach(c => c.querySelector('.circle').classList.remove('active'));
            // Activate clicked category
            circle.classList.add('active');
            selectedCategory = card.dataset.category;
        }

        updateSearchPlaceholder();
        doSearch(searchInput.value.trim());
    });
});


// Live search with debounce
searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        doSearch(searchInput.value.trim());
    }, 300);

    if (searchInput.value.trim() === '') doSearch('');
});

// AJAX search function
function doSearch(searchText = '') {
    const search = encodeURIComponent(searchText);
    const year = encodeURIComponent(selectedYear);
    const category = encodeURIComponent(selectedCategory);

    newsResults.classList.add('loading');
    newsResults.classList.remove('fade-in');

    const xhr = new XMLHttpRequest();
    xhr.open('GET', `fetch_news.php?search=${search}&year=${year}&category=${category}`, true);
    xhr.onload = function() {
        if (this.status === 200) {
            newsResults.innerHTML = this.responseText;
            newsResults.classList.remove('loading');
            newsResults.classList.add('fade-in');
        } else {
            console.error('Error loading news');
            newsResults.classList.remove('loading');
        }
    };
    xhr.send();
}

// Initialize placeholder
updateSearchPlaceholder();


const button = document.getElementById('scrollBtn');
const target = document.getElementById('news-section'); // 👈 match your section id

button.addEventListener('click', () => {
  target.scrollIntoView({ behavior: 'smooth' });
});

// GSAP animation
let t = gsap.timeline({ defaults: { ease: "slow(0.7, 0.7, false)" } });
t.to(".create", { y: '0%', duration: 0.7, stagger: 0.2 });





</script>



    <?php include('footer.php');?>
    <script src="footer.js"></script>
    <script src="header.js"></script>
     
</body>
</html>