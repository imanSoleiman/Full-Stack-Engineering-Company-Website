<?php include('./config.php');

if(!isset($_GET['id'])) die("News ID missing.");
$news_id = intval($_GET['id']);

// Fetch news card
$news = $conn->query("SELECT * FROM news_cards WHERE id=$news_id")->fetch_assoc();
if(!$news) die("News not found.");

// Fetch sections
$sections = $conn->query("SELECT * FROM news_sections WHERE news_id=$news_id ORDER BY section_order ASC");

// Fetch approved comments
$comments = $conn->query("SELECT * FROM news_comments WHERE news_id=$news_id AND approved=1 ORDER BY created_at DESC");

// Handle comment submission
// Handle comment submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO news_comments (news_id, name, email, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $news_id, $name, $email, $comment);
    if($stmt->execute()) {
        // Redirect back with a success message
       header("Location: news_details.php?id=$news_id&msg=success#comments");
exit;

    } else {
        echo "Error: " . $conn->error;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>news_details</title>
    <link href="./style.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
       #news-first-section{
        width:100%;
        padding:90px;
        padding-top:200px;
        background-color:whitesmoke;
        border-radius:0 0 10px 10px;
        text-transform:uppercase;
        position:relative;

       }
       .news-details{
        padding:50px;

       }
       .news-details-container{
        display:flex;
        flex-direction:column;
        justify-content: center;
        gap:20px;

       }
       .news-details-container img{
         width:90%;
        height:400px;
        border-radius:10px;
        object-fit:cover;
       }
       .news-details-text{
        padding:0 100px;
       }
.news-details-text p{ 
     opacity: 72%;
    font-weight: 400;
    line-height: 28px;
    font-size: 18px;
    margin-bottom: 18px;
}
.news-details-text h1{
  line-height: 1.25;
    font-weight: 600;
    margin-bottom: 25px;
    text-transform: uppercase;  
}
.news-first-container{
   max-width:40%;
}
.news-first-container h1 {
        font-size: 45px;
        letter-spacing: 0px;
        line-height: 1;

}
#btn{
    width: 200px;
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
    text-decoration: none;
    text-align:center;
}

   #btn:hover{
      box-shadow:inset 300px 0 0 0 black;
      color:white;
    }
    .card-details{
        position:absolute;
        width:300px;
        padding:80px 0px;
        background-color:#e3e3e3;
        right:20%;
        z-index:2;
        border-radius:10px;
   
    }
    .card-text{
        display:flex;
        flex-direction:column;
        gap:10px;
        text-transform: lowercase;
        justify-content:center;
        align-items:flex-start;
padding:0px 30px;
    }
    .card-text-desc span{
       font-weight:700;
       text-transform: uppercase;
       margin-right:4px;
    }

    .comments-flex {
  display: flex;
  gap: 30px;
  justify-content: center;
  align-items: flex-start;
  padding: 50px 20px;
  flex-wrap: wrap; /* makes it responsive */
}

/* Recent Comments */
.recent-comments {
  flex: 1;
  min-width: 320px;
  max-width: 500px;
}

.comments-title {
  font-size: 1.6rem;
  font-weight: 600;
  margin-bottom: 25px;
  color: #222;
}

.comment-item {
  background: #f9fafb;
  border: 1px solid #eee;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
  transition: box-shadow 0.3s ease;
}

.comment-item:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.05);
}

.comment-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.comment-author {
  font-weight: 600;
  color: #111;
}

.comment-date {
  font-size: 0.85rem;
  color: #666;
}

.comment-text {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #333;
}

/* Leave a Comment Form */
.comment-section {
  flex: 1;
  min-width: 320px;
  max-width: 500px;
}

.comment-container {
  background: #fff;
  padding: 40px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.comment-title {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #222;
}

.comment-subtitle {
  font-size: 0.95rem;
  color: #666;
  margin-bottom: 25px;
}

.comment-form .form-group {
  margin-bottom: 20px;
}

.comment-form label {
  display: block;
  font-weight: 500;
  margin-bottom: 6px;
  color: #333;
}

.comment-form input,
.comment-form textarea {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 0.95rem;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}


.comment-btn {
  background: #d80a0aff;
  color: #fff;
  border: none;
  padding: 14px 25px;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 12px;
  cursor: pointer;
  transition:ease-in-out 0.2s;
}

.comment-btn:hover {
  background: #000000ff;
}

@media screen and (max-width :700px){
    .card-details {
    position: absolute;
    width: 200px;
    padding: 60px 0px;
    background-color: #e3e3e3;
    right: 20%;
    top:94%;
    border-radius: 10px;
}
.news-details{
  padding:10px;
}
}
@media screen and (max-width: 500px){
  .card-details {
    position: absolute;
    width: 200px;
    padding: 60px 0px;
    background-color: #e3e3e3;
    right: 10%;
    top:90%;
    border-radius: 10px;
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
    <?php include ('header.php');?>

<section id="news-first-section">
    <div class="news-first-container">
        <h1 class="highlight-letters"><?= htmlspecialchars($news['title']) ?></h1>
        <a id="btn" href="./news.php">Back to news page</a>
    </div>

    <div class="card-details">
        <div class="card-text">
            <div class="card-text-desc">
                <p><span>Date:</span> <?= $news['date_day'] . ' ' . date('F', mktime(0,0,0,$news['date_month'],10)) . ' ' . $news['date_year'] ?></p>
            </div>
            <div class="card-text-desc">
                <p><span>Category:</span> <?= htmlspecialchars($news['title']) ?></p>
            </div>
            <div class="card-text-desc">
                <p><span>Comments:</span> (<?= $comments->num_rows ?>)</p>
            </div>
        </div>
    </div>
</section>

<section class="news-details">
    <div class="news-details-container">
        <img src="./assets/news/<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>">
        
        <?php while($sec = $sections->fetch_assoc()): ?>
        <div class="news-details-text">
            <h1><?= htmlspecialchars($sec['heading']) ?></h1>
            <p><?= nl2br(htmlspecialchars($sec['content'])) ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="comments-flex" id="comments">
    <!-- Leave a Comment -->
     
    <div class="comment-section">
        <div class="comment-container">
          <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
<div style="background-color:#d4edda; color:#155724; padding:15px; border-radius:10px; margin-bottom:20px; text-align:center; font-weight:600;">
    Your comment was successfully submitted!
</div>
<?php endif; ?>

            <h2 class="comment-title">Leave a Comment</h2>
            <p class="comment-subtitle">Your email address will not be published. Required fields are marked *</p>
            
            <form class="comment-form" action="" method="post">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="comment">Comment *</label>
                    <textarea id="comment" name="comment" rows="5" placeholder="Write your comment..." required></textarea>
                </div>

                <button type="submit" class="comment-btn">Post Comment</button>
            </form>
        </div>
    </div>

    <!-- Recent Comments -->
    <div class="recent-comments">
        <h2 class="comments-title">Recent Comments</h2>
        <?php while($c = $comments->fetch_assoc()): ?>
        <div class="comment-item">
            <div class="comment-header">
                <strong class="comment-author"><?= htmlspecialchars($c['name']) ?></strong>
                <span class="comment-date"><?= date("F d, Y", strtotime($c['created_at'])) ?></span>
            </div>
            <p class="comment-text"><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</section>


<?php include('footer.php'); ?>

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
    const msgBox = document.querySelector('.comment-container div');
    if(msgBox) {
        setTimeout(() => {
            msgBox.style.transition = "opacity 0.8s";
            msgBox.style.opacity = 0;
        }, 4000); // disappears after 4 seconds
    }
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

gsap.registerPlugin(ScrollTrigger);

gsap.to(".our-story", {
  backgroundColor: "#000",
  scrollTrigger: {
    trigger: ".our-story",
    start: "top 100%",   // when middle of section is ~60% down viewport
    end: "center 40%",     // finish fading when middle is ~40% up
    scrub: 1.5             // smooth fade
  }
});

gsap.registerPlugin(ScrollTrigger);

document.addEventListener("DOMContentLoaded", () => {

  // 1️⃣ Animate the heading letters
  const headings = document.querySelectorAll(".highlight-letters");
  headings.forEach(heading => {
    const words = heading.textContent.split(" ");
    heading.innerHTML = words.map(word => {
      const letters = word.split("").map(letter => `<span>${letter}</span>`).join("");
      return `<span class="word">${letters}</span>`;
    }).join(" ");

    gsap.fromTo(
      heading.querySelectorAll("span span"),
      { color: "#ffc8c8f2", opacity: 0.3, y: 20 },
      { 
        color: "#000",
        opacity: 1,
        y: 0,
        ease: "power2.out",
        stagger: 0.04,
        scrollTrigger: {
          trigger: heading,
          start: "top 90%",
          end: "bottom 20%",
          toggleActions: "play none none reverse"
        }
      }
    );
  });

  // 2️⃣ Animate the Back button
  gsap.from("#btn", {
    opacity: 0,
    y: 50,
    duration: 0.8,
    delay: 0.2,
    ease: "power3.out",
    scrollTrigger: {
      trigger: "#btn",
      start: "top 95%",
      toggleActions: "play none none reverse"
    }
  });

  // 3️⃣ Animate card-details section
  gsap.from(".card-details", {
    x: 100,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".card-details",
      start: "top 90%",
      toggleActions: "play none none reverse"
    }
  });

  // 4️⃣ Animate News Image
  gsap.from(".news-details-container img", {
    scale: 0.8,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".news-details-container img",
      start: "top 90%",
      toggleActions: "play none none reverse"
    }
  });

  // 5️⃣ Animate News Sections (heading + paragraph)
  gsap.utils.toArray(".news-details-text").forEach((section, i) => {
    gsap.from(section, {
      y: 50,
      opacity: 0,
      duration: 1,
      delay: i * 0.2,
      ease: "power3.out",
      scrollTrigger: {
        trigger: section,
        start: "top 90%",
        toggleActions: "play none none reverse"
      }
    });
  });

  // 6️⃣ Animate Comments Section
  gsap.from(".comments-flex", {
    y: 50,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".comments-flex",
      start: "top 90%",
      toggleActions: "play none none reverse"
    }
  });

  // 7️⃣ Animate each individual comment item
  gsap.utils.toArray(".comment-item").forEach((comment, i) => {
    gsap.from(comment, {
      y: 30,
      opacity: 0,
      duration: 0.8,
      delay: i * 0.1,
      ease: "power3.out",
      scrollTrigger: {
        trigger: comment,
        start: "top 95%",
        toggleActions: "play none none reverse"
      }
    });
  });

});

    </script>
<script src="footer.js"></script>
<script src="header.js"></script>
</body>
</html>