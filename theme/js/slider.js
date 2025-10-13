
window.onload = function () {
  const swiper = new Swiper(".swiper", {
    loop: true,
    speed: 1500,
    // autoplay: {
    //   delay: 2000,
    // },
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
};