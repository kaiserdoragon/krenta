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
document.addEventListener('DOMContentLoaded', () => {
  const fixedHeader = document.getElementById('js-add_header');
  if (!fixedHeader) return;

  const SHOW_AT = 250;   // 表示しはじめる位置
  const HIDE_BELOW = 280; // 消しはじめる位置（少し下げてヒステリシス）
  let shown = false;

  const update = () => {
    const y = window.scrollY || window.pageYOffset;

    if (!shown && y > SHOW_AT) {
      shown = true;
      fixedHeader.classList.add('is-show');
    } else if (shown && y < HIDE_BELOW) {
      shown = false;
      fixedHeader.classList.remove('is-show');
    }
  };

  // 必要なら軽いスロットル（任意）
  let tid = null;
  window.addEventListener('scroll', () => {
    if (tid) return;
    tid = setTimeout(() => { tid = null; update(); }, 50);
  }, { passive: true });

  update(); // 初期表示を反映
});


//SPの時のフッター追従ボタン
(() => {
  const btn = document.getElementById('js_fixed-btn');
  if (!btn) return; // 要素がなければ即終了

  const THRESHOLD = 500;
  const controller = new AbortController();

  const update = () => {
    // スクロール量が閾値を超えたら is-active を付与
    btn.classList.toggle('is-active', window.scrollY >= THRESHOLD);
  };

  // 初期状態を反映
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', update, { once: true });
  } else {
    update();
  }

  // スクロール/リサイズで状態更新
  window.addEventListener('scroll', update, { passive: true, signal: controller.signal });
  window.addEventListener('resize', update, { passive: true, signal: controller.signal });
})();




// (function ($, root, undefined) {
//   console.log('jqueryのコードはここ');
//   //   $(window).scroll(function () {
//   //     // 画面スクロールの値を取得
//   //     var scroll = $(window).scrollTop();

//   //     // スクロールの値が200pxを超えると追従ヘッダーを表示
//   //     if (scroll > 200) {
//   //       $("#js-fixed-header").addClass("is-show");
//   //     } else {
//   //       $("#js-fixed-header").removeClass("is-show");
//   //     }
//   //   });
//   // });
// })(jQuery, this);
