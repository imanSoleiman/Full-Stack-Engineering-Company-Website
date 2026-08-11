 gsap.registerPlugin(ScrollTrigger);

// Animate heading
gsap.fromTo(".project-section .text h1",
  { y: 50, opacity: 0 },
  { 
    y: 0, 
    opacity: 1, 
    duration: 1, 
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".project-section .text h1",
      start: "top 85%",
      end: "bottom 20%",   // optional, can be adjusted
      toggleActions: "play reverse play reverse" // fade in when entering, fade out when leaving
    }
  }
);

// Animate project cards
gsap.fromTo(".project-cards .cardOne",
  { y: 80, opacity: 0 },
  { 
    y: 0, 
    opacity: 1, 
    duration: 1, 
    ease: "power3.out",
    delay: 0.3,       // delay before animation starts
    stagger: 0.25,    // delay between each card
    scrollTrigger: {
      trigger: ".project-card-container",
      start: "top 80%",
      end: "bottom 20%",
      toggleActions: "play reverse play reverse"
    }
  }
);


const carousel = document.querySelector('.carousel'); const list = document.querySelector('.carousel .list'); const items = () => list ? list.querySelectorAll('.item') : []; const nextBtn = document.querySelector('.arrows .next'); const prevBtn = document.querySelector('.arrows .prev'); if (carousel && list) { function showSlider(direction, instant = false) { const els = items(); if (els.length < 2) return; if (instant) carousel.classList.add('no-animate'); if (direction === 'next') { list.appendChild(els[0]); } else { list.prepend(els[els.length - 1]); } if (!instant) { carousel.classList.add(direction); runTimeOut = setTimeout(() => { carousel.classList.remove('next'); carousel.classList.remove('prev'); }, 3000); } else { carousel.classList.remove('next'); carousel.classList.remove('prev'); requestAnimationFrame(() => { carousel.classList.remove('no-animate'); }); } } if (nextBtn) nextBtn.addEventListener('click', () => showSlider('next', true)); if (prevBtn) prevBtn.addEventListener('click', () => showSlider('prev', true)); let autoSlide = setInterval(() => { showSlider('next'); }, 5000); carousel.addEventListener('mouseenter', () => clearInterval(autoSlide)); carousel.addEventListener('mouseleave', () => { autoSlide = setInterval(() => showSlider('next'), 5000); });}
document.addEventListener('DOMContentLoaded', () => {
  const imageWrapper = document.querySelector('.swiper-wrapper');
  const serviceItems = Array.from(document.querySelectorAll('.service-item'));
  const topLabel = document.querySelector('.top-label p');

  // Accept different possible arrow markup: icon alone or a parent button
  const rightTriggers = Array.from(document.querySelectorAll(
    '.fa-arrow-right, .slider-next, .next-btn, .arrow-right'
  ));
  const leftTriggers = Array.from(document.querySelectorAll(
    '.fa-arrow-left, .slider-prev, .prev-btn, .arrow-left'
  ));

  // debug info
  console.log('slider init -> serviceItems:', serviceItems.length,
              'imageWrapper:', !!imageWrapper,
              'rightTriggers:', rightTriggers.length,
              'leftTriggers:', leftTriggers.length);

  if (!imageWrapper || serviceItems.length === 0) {
    console.warn('Slider init aborted: missing .swiper-wrapper or .service-item elements');
    return;
  }

  const totalSlides = serviceItems.length;
  let currentIndex = 0;
  let slideInterval = null;

  function showSlide(index) {
    // guard
    index = ((index % totalSlides) + totalSlides) % totalSlides;

    // move images
    imageWrapper.style.transform = `translateX(-${index * 100}%)`;

    // update text classes
    serviceItems.forEach((item, i) => {
      item.classList.remove('active', 'exit-left');
      if (i === index) {
        item.classList.add('active');
      } else if (i === ((index - 1 + totalSlides) % totalSlides)) {
        // previous slide (for exit animation)
        item.classList.add('exit-left');
      }
    });

    // update counter label if present
    if (topLabel) topLabel.textContent = `${index + 1}/${totalSlides}`;
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
  }
  function prevSlide() {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    showSlide(currentIndex);
  }

  // Attach click listeners to found triggers
  const attachClicks = (els, handler) => {
    els.forEach(el => {
      // make clickable area obvious
      el.style.cursor = 'pointer';
      // protect from default anchors
      el.addEventListener('click', (ev) => {
        ev.preventDefault();
        handler();
        resetInterval();
      });
    });
  };

  attachClicks(rightTriggers, nextSlide);
  attachClicks(leftTriggers, prevSlide);

  // Fallback: if no triggers found, use event delegation (works even if icon is inside button)
  if (rightTriggers.length === 0 || leftTriggers.length === 0) {
    document.addEventListener('click', (ev) => {
      const t = ev.target;
      if (t.closest('.slider-next, .next-btn, .arrow-right') || t.matches('.fa-arrow-right')) {
        ev.preventDefault();
        nextSlide();
        resetInterval();
      } else if (t.closest('.slider-prev, .prev-btn, .arrow-left') || t.matches('.fa-arrow-left')) {
        ev.preventDefault();
        prevSlide();
        resetInterval();
      }
    });
  }

  // keyboard support
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') { nextSlide(); resetInterval(); }
    if (e.key === 'ArrowLeft')  { prevSlide(); resetInterval(); }
  });

  // auto slide
  function resetInterval() {
    if (slideInterval) clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 3000);
  }

  // init
  showSlide(currentIndex);
  resetInterval();
});




gsap.registerPlugin(ScrollTrigger);

// Animate left images/cards
document.querySelectorAll(".who-left-one, .who-left-two").forEach((item, index) => {
  gsap.from(item, {
    x: -100,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    delay: index * 0.2, // delay between items
    scrollTrigger: {
      trigger: item,
      start: "top 90%", 
      toggleActions: "play none none none",
      scrub: false
    }
  });
});

// Animate right section text
document.querySelectorAll(".right-section-text p, .right-section-text h1").forEach((item, index) => {
  gsap.from(item, {
    y: 50,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    delay: index * 0.15,
    scrollTrigger: {
      trigger: item,
      start: "top 90%",
      toggleActions: "play none none none"
    }
  });
});

// Animate right points grid
document.querySelectorAll(".right-points-grid .right-point").forEach((item, index) => {
  gsap.from(item, {
    y: 50,
    opacity: 0,
    duration: 0.8,
    ease: "power3.out",
    delay: index * 0.2,
    scrollTrigger: {
      trigger: item,
      start: "top 95%",
      toggleActions: "play none none none"
    }
  });
});

// Animate right cards
document.querySelectorAll(".right-cards-container .who-cards").forEach((item, index) => {
  gsap.from(item, {
    y: 50,
    opacity: 0,
    duration: 0.8,
    ease: "power3.out",
    delay: index * 0.2,
    scrollTrigger: {
      trigger: item,
      start: "top 95%",
      toggleActions: "play none none none"
    }
  });
});



gsap.registerPlugin(ScrollTrigger);

// Split text into letters but keep words together
const headings = document.querySelectorAll(".highlight-letters");

headings.forEach(heading => {
  const words = heading.textContent.split(" "); // split by words
  heading.innerHTML = words.map(word => {
    const letters = word.split("").map(letter => `<span>${letter}</span>`).join("");
    return `<span class="word">${letters}</span>`; // wrap each word
  }).join(" "); // keep spaces between words
});

// Split each .highlight-letters into words and letters
document.querySelectorAll(".highlight-letters").forEach(heading => {
  const text = heading.textContent.trim(); // keep text safe
  const words = text.split(/\s+/); // split by whitespace
  heading.innerHTML = words.map(word => {
    const letters = [...word] // spread handles apostrophes correctly
      .map(letter => `<span>${letter}</span>`)
      .join("");
    return `<span class="word">${letters}</span>`;
  }).join(" ");
});

// Animate each heading separately
document.querySelectorAll(".highlight-letters").forEach(heading => {
  gsap.fromTo(heading.querySelectorAll(".word span"),
    { color: "#424242", opacity: 0.3, x: 0 },
    { 
      color: "#ffffff",
      opacity: 1,
      x: 10,
      duration: 0.5,
      ease: "power3.out",
      stagger: 0.05,
      scrollTrigger: {
        trigger: heading,
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play reverse play reverse",
        scrub: 0.3
      }
    }
  );
});


document.querySelectorAll(".highlight-letters .red").forEach(heading => {
  const text = heading.textContent.trim(); // keep text safe
  const words = text.split(/\s+/); // split by whitespace
  heading.innerHTML = words.map(word => {
    const letters = [...word] // spread handles apostrophes correctly
      .map(letter => `<span>${letter}</span>`)
      .join("");
    return `<span class="word">${letters}</span>`;
  }).join(" ");
});

// Animate each heading separately
document.querySelectorAll(".red").forEach(heading => {
  gsap.fromTo(heading.querySelectorAll(".word span"),
    { color: "#ffffffff", opacity: 0.3, x: 0 },
    { 
      color: "#000000ff",
      opacity: 1,
      x: 10,
      duration: 0.5,
      ease: "power3.out",
      stagger: 0.05,
      scrollTrigger: {
        trigger: heading,
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play reverse play reverse",
        scrub: 0.3
      }
    }
  );
});







// Refresh ScrollTrigger after DOM rewrite
ScrollTrigger.refresh();


gsap.to(".services-container", {
  backgroundColor: "#000",   // fade target color
  ease: "power2.inOut",      // smooth easing
  scrollTrigger: {
    trigger: ".services-container",
    start: "top 80%",        // when 20% of the section enters viewport
    end: "top 20%",          // fully black when section is near center
    scrub: true              // makes fade smooth with scroll
  }
});
const wrapper = document.querySelector(".spectrum-slider-wrapper");
const slides = gsap.utils.toArray(".company-single-slider");
const slideCount = slides.length;
const slideWidth = slides[0].offsetWidth + 50; // width + gap

// Duplicate slides for seamless looping
wrapper.innerHTML += wrapper.innerHTML;
const allSlides = gsap.utils.toArray(".company-single-slider");

// Keep track of the current position
let sliderIndex = 0;
let autoSlide;

// Auto-slide function
function nextSlide() {
  sliderIndex++;
  gsap.to(wrapper, {
    x: -slideWidth * sliderIndex,
    duration: 0.8,
    ease: "power2.inOut",
    onComplete: () => {
      if (sliderIndex >= slideCount) {
        sliderIndex = 0;
        gsap.set(wrapper, { x: 0 });
      }
    }
  });
}

// Start auto-slide safely
function startAutoSlide() {
  clearInterval(autoSlide);
  autoSlide = setInterval(nextSlide, 3000);
}

// Start auto-slide initially
startAutoSlide();

// Make slider draggable
Draggable.create(wrapper, {
  type: "x",
  inertia: true,
  edgeResistance: 0.9,
  bounds: { minX: -slideWidth * slideCount, maxX: 0 },
  onRelease() {
    // Snap to nearest slide
    sliderIndex = Math.round(-this.x / slideWidth) % slideCount;
    if (sliderIndex < 0) sliderIndex += slideCount; // handle negative index
    gsap.to(wrapper, {
      x: -slideWidth * sliderIndex,
      duration: 0.5,
      ease: "power2.out"
      // no need to restart autoSlide — it never stopped
    });
  }
});
// Elements
document.addEventListener("DOMContentLoaded", () => {
  const newsSliderWrapper = document.querySelector(".news-slider-wrapper"); // track that moves
  const newsSlides = gsap.utils.toArray(".gray"); // each slide
  const newsSliderContainer = document.querySelector(".news-slider-container"); // visible container
  const newsBulletsContainer = document.querySelector(".slider-bullets"); // bullets holder

  // Measurements
  const totalSlides = newsSlides.length;
  const wrapperWidth = newsSliderWrapper.scrollWidth;
  const containerWidth = newsSliderContainer.offsetWidth;

  // Detect exact slide width (with gap if exists)
  const singleSlideWidth =
    newsSlides[1].offsetLeft - newsSlides[0].offsetLeft || newsSlides[0].offsetWidth;

  let currentSlideIndex = 0;
  let autoSlideInterval;

  // Bullets array
  const newsBullets = [];

  // Create bullets dynamically
  newsSlides.forEach((_, idx) => {
    const bullet = document.createElement("span");
    if (idx === 0) bullet.classList.add("active");
    bullet.addEventListener("click", () => goToNewsSlide(idx));
    newsBulletsContainer.appendChild(bullet);
    newsBullets.push(bullet);
  });

  // Update bullets
  function updateNewsBullets() {
    newsBullets.forEach(b => b.classList.remove("active"));
    newsBullets[currentSlideIndex].classList.add("active");
  }

  // Go to specific slide
  function goToNewsSlide(index) {
    currentSlideIndex = index;
    gsap.to(newsSliderWrapper, {
      x: -singleSlideWidth * currentSlideIndex,
      duration: 0.8,
      ease: "power2.inOut"
    });
    updateNewsBullets();
  }

  // Auto-slide
  function moveToNextNewsSlide() {
    currentSlideIndex++;
    if (currentSlideIndex >= totalSlides) currentSlideIndex = 0;
    goToNewsSlide(currentSlideIndex);
  }

  // Start auto-slide
  function startNewsAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(moveToNextNewsSlide, 4000);
  }
  startNewsAutoSlide();

  // Make draggable with snapping
  Draggable.create(newsSliderWrapper, {
    type: "x",
    inertia: true,
    edgeResistance: 0.9,
    bounds: { minX: containerWidth - wrapperWidth, maxX: 0 },
    snap: endValue => {
      return Math.round(endValue / singleSlideWidth) * singleSlideWidth;
    },
    onDragStart() {
      clearInterval(autoSlideInterval); // pause auto-slide while dragging
    },
    onDragEnd() {
      currentSlideIndex = Math.round(-this.x / singleSlideWidth);
      if (currentSlideIndex < 0) currentSlideIndex = 0;
      if (currentSlideIndex >= totalSlides) currentSlideIndex = totalSlides - 1;
      updateNewsBullets();
      startNewsAutoSlide(); // resume auto-slide
    }
  });
});



// Select all elements with class "red"
const redHeadings = document.querySelectorAll(".red");

redHeadings.forEach(heading => {
  const words = heading.textContent.split(" "); // split by words
  heading.innerHTML = words.map(word => {
    const letters = word.split("").map(letter => `<span>${letter}</span>`).join("");
    return `<span class="word">${letters}</span>`; // wrap each word
  }).join(" "); // keep spaces between words
});

// Animate letters on scroll
gsap.fromTo(".red span span",
  { color: "#373737ff", opacity: 0.3, x: 0 },
  { 
    color: "#000000ff",
    opacity: 1,
    x: 10,
    duration: 0.5,
    ease: "power3.out",
    stagger: 0.05,
    scrollTrigger: {
      trigger: ".red",
      start: "top 80%",
      end: "bottom 20%",
      toggleActions: "play reverse play reverse",
      scrub: 0.3
    }
  }
);

