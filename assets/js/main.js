(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
  const mobileNavCloseBtn = document.querySelector('.mobile-nav-close');
  const navMenuOverlay = document.querySelector('.navmenu');

  function openMobileNav() {
    document.querySelector('body').classList.add('mobile-nav-active');
    mobileNavToggleBtn.classList.remove('bi-list');
    mobileNavToggleBtn.classList.add('bi-x');
  }

  function closeMobileNav() {
    document.querySelector('body').classList.remove('mobile-nav-active');
    mobileNavToggleBtn.classList.remove('bi-x');
    mobileNavToggleBtn.classList.add('bi-list');
  }

  function toggleMobileNav() {
    if (document.querySelector('body').classList.contains('mobile-nav-active')) {
      closeMobileNav();
    } else {
      openMobileNav();
    }
  }

  // Toggle mobile navigation
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', toggleMobileNav);
  }

  // Close mobile navigation
  if (mobileNavCloseBtn) {
    mobileNavCloseBtn.addEventListener('click', closeMobileNav);
  }

  // Close mobile navigation when clicking on overlay
  if (navMenuOverlay) {
    navMenuOverlay.addEventListener('click', function(e) {
      if (e.target === navMenuOverlay) {
        closeMobileNav();
      }
    });
  }

  // Close mobile navigation when clicking on nav links
  const navLinks = document.querySelectorAll('.navmenu a');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth < 1200) {
        closeMobileNav();
      }
    });
  });

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  if (scrollTop) {
    scrollTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );
      try {
        if (typeof Swiper !== 'undefined') {
      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
          }
        } else {
          throw new Error('Swiper is not defined');
        }
      } catch (e) {
        console.error('Swiper initialization failed:', e);
        // Fallback: show all slides if Swiper fails
        const wrapper = swiperElement.querySelector('.swiper-wrapper');
        if (wrapper) {
          wrapper.style.display = 'flex';
          wrapper.style.flexWrap = 'wrap';
        }
        swiperElement.querySelectorAll('.swiper-slide').forEach(slide => {
          slide.style.display = 'block';
          slide.style.width = 'auto';
        });
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      faqItem.parentNode.classList.toggle('faq-active');
    });
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

  // Smooth scroll for View More Products button
  var viewMoreBtn = document.querySelector('.view-more-btn');
  if (viewMoreBtn) {
    viewMoreBtn.addEventListener('click', function(e) {
    e.preventDefault();
    const productsSection = document.querySelector('#products');
    const headerOffset = 80; // Adjust this value based on your header height
    const elementPosition = productsSection.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    });

    // Add highlight animation to products section
    productsSection.classList.add('highlight-section');
    setTimeout(() => {
      productsSection.classList.remove('highlight-section');
    }, 1500);
  });
  }

  /**
   * Top Scroll Progress Bar
   */
  function updateTopProgressBar() {
    var bar = document.querySelector('.top-progress-bar-inner');
    if (!bar) return;
    var scrollTop = window.scrollY || document.documentElement.scrollTop;
    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
    var percent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
    bar.style.width = percent + '%';
  }
  window.addEventListener('scroll', updateTopProgressBar);
  window.addEventListener('resize', updateTopProgressBar);
  window.addEventListener('load', updateTopProgressBar);

})();