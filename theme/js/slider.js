const swiper = new Swiper(".swiper", {
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {
    el: ".swiper-pagination", //必須の設定：ページネーションのclass指定
    type: "bullets",
    clickable: "clickable",
  },
  loop: true, //繰り返し指定
  spaceBetween: 10, //スライド感の余白
});
