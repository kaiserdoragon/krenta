"use strict";

document.addEventListener("DOMContentLoaded", () => {
  const gnavBtns = document.querySelectorAll(".gnav_btn");
  const navs = document.querySelectorAll(".gnav");

  // グロナビ開閉ボタン
  gnavBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const self = e.currentTarget; // クリックされたボタン
      const isOpen = self.classList.toggle("js-open"); // クラスON/OFF（結果の真偽を返す）

      // ボタンが開状態のときだけナビにも "js-open" を付与
      navs.forEach((nav) => nav.classList.toggle("js-open", isOpen));
    });
  });

  // メニュー内のリンクが押されたらメニューを閉じる
  document.querySelectorAll(".gnav a[href]").forEach((link) => {
    link.addEventListener("click", () => {
      navs.forEach((nav) => nav.classList.remove("js-open"));
      gnavBtns.forEach((btn) => btn.classList.remove("js-open"));
    });
  });

  // メニューのどこかが押されたら閉じる
  navs.forEach((nav) => {
    nav.addEventListener("click", () => {
      navs.forEach((n) => n.classList.remove("js-open"));
      gnavBtns.forEach((btn) => btn.classList.remove("js-open"));
    });
  });
});


// ヘッダーのスクロール位置を取得 /////////////////////////////////////////////
// headerの高さ分スクロールしたら、-fixedクラスをつける。
const Header = document.getElementById("js-header");
if (Header) {
  const options = {
    root: null,
    rootMargin: `${Header.offsetHeight}px 0px ${document.body.clientHeight}px 0px`,
    threshold: 1,
  };

  const observer = new IntersectionObserver(change_header, options);
  observer.observe(document.body);
  function change_header(entries) {
    if (!entries[0].isIntersecting) {
      Header.classList.add("-fixed");
    } else {
      Header.classList.remove("-fixed");
    }
  }
}

// アンカーリンクのスムーススクロール //////////////////////////////////////////////
// iOSでスムーススクロールをするためには「<script src=" https://polyfill.io/v3/polyfill.min.js?features=smoothscroll"></script>」を読み込む必要がある。
const headerHeight = ((load) => {
  return load ? document.getElementsByClassName("header")[0].offsetHeight : 0;
})(true); // ※ヘッダー高さをロード時にはかりたいときは、ここをtrueにする

const anchor = document.querySelectorAll("a[href*='#']:not(.is-noscroll)"); // 発火しない場合は「.is-noscroll」
[...anchor].forEach((element) => {
  const target = ((hash) => {
    return hash
      ? document.querySelector(element.hash)
      : console.error(`リンクが空です。 ${element.outerHTML}`);
  })(element.hash);

  if (target) {
    element.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({
        top: target.offsetTop - headerHeight,
        behavior: "smooth",
      });
    });
  }
});

//別URLからやってきたときに発火
window.addEventListener("load", () => {
  const url = window.location.href;
  if (url.indexOf("#") !== -1) {
    const id = url.split("#");
    const target = document.getElementById(id[id.length - 1]);
    if (target) {
      window.scroll({ top: 0 });
      window.scroll({ top: target.offsetTop - headerHeight, behavior: "smooth" });
    }
  }
});



//上から出てくるヘッダー
document.addEventListener('DOMContentLoaded', function () {
  var fixedHeader = document.getElementById('js-fixed-header');
  if (!fixedHeader) return;

  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      window.requestAnimationFrame(function () {
        var scroll = window.scrollY || window.pageYOffset;
        fixedHeader.classList.toggle('is-show', scroll > 200);
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
});




(function ($, root, undefined) {
  // console.log('jqueryのコードはここ');
  //   $(window).scroll(function () {
  //     // 画面スクロールの値を取得
  //     var scroll = $(window).scrollTop();

  //     // スクロールの値が200pxを超えると追従ヘッダーを表示
  //     if (scroll > 200) {
  //       $("#js-fixed-header").addClass("is-show");
  //     } else {
  //       $("#js-fixed-header").removeClass("is-show");
  //     }
  //   });
  // });
})(jQuery, this);
