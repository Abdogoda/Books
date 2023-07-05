var swiper = new Swiper(".books-slider", {
 loop: true,
 centeredSlides: true,
 autoplay: {
  delay: 3000,
  disableOnInteraction: false,
 },
 breakpoints: {
  0: {
   slidesPerView: 1,
  },
  500: {
   slidesPerView: 2,
  },
  1024: {
   slidesPerView: 3,
  },
 },
});

var swiper = new Swiper(".featured-slider", {
 effect: "coverflow",
 spaceBetween: 10,
 loop: true,
 centeredSlides: true,
 autoplay: {
  delay: 6000,
  disableOnInteraction: false,
 },
 coverflowEffect: {
  rotate: 10,
  stretch: 0,
  depth: 50,
  modifier: 1,
  slideShadows: true,
 },
 navigation: {
  nextEl: ".swiper-button-next",
  prevEl: ".swiper-button-prev",
 },
 breakpoints: {
  0: {
   slidesPerView: 1,
  },
  480: {
   slidesPerView: 2,
  },
  768: {
   slidesPerView: 3,
  },
  1024: {
   slidesPerView: 4,
  },
 },
});

var swiper = new Swiper(".arrivals-slider", {
 spaceBetween: 10,
 loop: true,
 centeredSlides: true,
 autoplay: {
  delay: 6000,
  disableOnInteraction: false,
 },
 breakpoints: {
  0: {
   slidesPerView: 1,
  },
  768: {
   slidesPerView: 2,
  },
  1024: {
   slidesPerView: 3,
  },
 },
});

var swiper = new Swiper(".reviews-slider", {
 spaceBetween: 10,
 grabCursor: true,
 loop: true,
 centeredSlides: true,
 autoplay: {
  delay: 6000,
  disableOnInteraction: false,
 },
 breakpoints: {
  0: {
   slidesPerView: 1,
  },
  500: {
   slidesPerView: 2,
  },
  1024: {
   slidesPerView: 3,
  },
 },
});

var swiper = new Swiper(".blogs-slider", {
 spaceBetween: 10,
 grabCursor: true,
 loop: true,
 centeredSlides: true,
 autoplay: {
  delay: 6000,
  disableOnInteraction: false,
 },
 breakpoints: {
  0: {
   slidesPerView: 1,
  },
  768: {
   slidesPerView: 2,
  },
  1024: {
   slidesPerView: 3,
  },
 },
});
