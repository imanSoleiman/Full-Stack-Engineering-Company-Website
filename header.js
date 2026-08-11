const dotsGrid = document.querySelector('.dots-grid');
const navLinks = document.querySelector('.nav-links');
const topNav = document.querySelector(".header-container");
let lastScroll = 0;

dotsGrid.addEventListener('click', () => {
  navLinks.classList.toggle('active');

  // When mobile menu opens, prevent body scroll behind it
  if (navLinks.classList.contains('active')) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = ''; // restore normal scroll
  }
});

window.addEventListener("scroll", () => {
  const currentScroll = window.pageYOffset;

  // Only hide header when mobile menu is NOT active
  if (!navLinks.classList.contains('active')) {
    if (currentScroll > lastScroll && currentScroll > 150) {
      topNav.classList.add("hidden");
    } else {
      topNav.classList.remove("hidden");
    }
  }

  lastScroll = currentScroll;
});

// Select main elements
const nav = document.querySelector('.nav-links');
const closeBtn = document.querySelector('.close-menu');
const dots = document.querySelector('.dots-grid');

// Open menu
dots.addEventListener('click', () => {
  nav.classList.add('active');
});

// Close menu
closeBtn.addEventListener('click', () => {
  nav.classList.remove('active');
});

// Handle dropdown toggles (mobile)
document.querySelectorAll('.navbar_items.dropdown > .navbar_links').forEach(link => {
  link.addEventListener('click', e => {
    const parentLi = link.parentElement;

    // Prevent navigation if has a dropdown
    if (link.nextElementSibling && link.nextElementSibling.classList.contains('dropdown-content')) {
      e.preventDefault();
    }

    // Close other dropdowns except this one
    document.querySelectorAll('.navbar_items.dropdown').forEach(li => {
      if (li !== parentLi) li.classList.remove('open');
    });

    // Toggle this one
    parentLi.classList.toggle('open');

    // Don't close the dropdown when clicking a link inside
    // So no extra code is needed here for links
  });
});

// Ensure clicking links inside dropdown doesn't close it
document.querySelectorAll('.dropdown-content li a').forEach(link => {
  link.addEventListener('click', e => {
    e.stopPropagation(); // This prevents the click from bubbling up and closing the parent dropdown
  });
});


