document.querySelectorAll('.footer-dropdown > a').forEach(drop => {
    drop.addEventListener('click', function(e) {
        e.preventDefault(); // prevent page jump
        const menu = this.nextElementSibling; // the dropdown ul
        menu.classList.toggle('footer');      // toggle slide animation

        // Close other open dropdowns
        document.querySelectorAll('.footer-drpdown-content').forEach(otherMenu => {
            if (otherMenu !== menu) {
                otherMenu.classList.remove('footer');
            }
        });
    });
});
const words = document.querySelectorAll('.text-wrapper h1');

words.forEach(word => {
  word.addEventListener('mouseenter', () => {
    word.style.color = 'red'; // change hovered word color
    words.forEach(w => {
      w.style.animationPlayState = 'paused'; // pause all words
    });
  });

  word.addEventListener('mouseleave', () => {
    word.style.color = ''; // reset color
    words.forEach(w => {
      w.style.animationPlayState = 'running'; // resume all words
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const goTopBtn = document.getElementById("goTopBtn");

  if (goTopBtn) {
    // Check if page is scrollable
    const isScrollable = document.documentElement.scrollHeight > window.innerHeight;

    if (!isScrollable) {
      goTopBtn.style.display = "none"; // hide if no scroll
    }

    goTopBtn.addEventListener("click", function () {
      if (isScrollable) {
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      }
    });
  }
});

  gsap.registerPlugin(ScrollTrigger);

  gsap.to(".logo-image", {
    y: "0%",       // slide into original position
    opacity: 1,
    duration: 1,
    scrollTrigger: {
      trigger: ".footer-container-first",  // start when this container is near viewport
      start: "top 80%",                     // adjust scroll start
      end: "top 60%",
      scrub: true                           // smooth scroll animation
    }
  });

