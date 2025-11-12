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



// SP(<=767px)のときだけフッター追従ボタンを有効化
(() => {
  const btn = document.getElementById('js_fixed-btn');
  if (!btn) return;

  const THRESHOLD = 500;
  const mql = window.matchMedia('(max-width: 767px)');
  let controller = null;

  const update = () => {
    btn.classList.toggle('is-active', window.scrollY >= THRESHOLD);
  };

  const enable = () => {
    if (controller) return; // すでに有効
    controller = new AbortController();
    const opts = { passive: true, signal: controller.signal };

    // 初期反映
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', update, { once: true });
    } else {
      update();
    }

    // スクロール/リサイズで状態更新（SP時のみ有効）
    window.addEventListener('scroll', update, opts);
    window.addEventListener('resize', update, opts);
  };

  const disable = () => {
    if (!controller) return;
    controller.abort();   // まとめてリスナー解除
    controller = null;
    btn.classList.remove('is-active'); // デスクトップへ戻ったら非表示に
  };

  // 初期判定
  mql.matches ? enable() : disable();

  // 767pxをまたいだら有効/無効を切り替え
  mql.addEventListener('change', (e) => (e.matches ? enable() : disable()));
})();


(function ($, root, undefined) {
  console.log('jqueryのコードはここ');

  // PC(>=768px)のときだけスクロールで出現するヘッダーを有効化
  $(function () {
    var mql = window.matchMedia('(min-width: 768px)');

    var $win = $(window);
    var $header = $('#js-fixed-header');
    var $main = $('main');

    var threshold = 0;
    var ticking = false;
    var enabled = false;

    function recalcThreshold() {
      threshold = $main.length ? $main.offset().top : 0;
      apply(); // リサイズ直後にも状態反映
    }

    function apply() {
      var sc = $win.scrollTop();
      if (sc > threshold) {
        $header.addClass('is-visible');
      } else {
        $header.removeClass('is-visible');
      }
    }

    function onScroll() {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          apply();
          ticking = false;
        });
        ticking = true;
      }
    }

    function enable() {
      if (enabled) return;
      enabled = true;
      recalcThreshold(); // 初期状態を即反映（途中位置リロード対策）
      $win.on('scroll.fixedHeader', onScroll);
      $win.on('resize.fixedHeader', recalcThreshold);
    }

    function disable() {
      if (!enabled) return;
      enabled = false;
      $win.off('.fixedHeader');      // 名前空間付きで一括解除
      $header.removeClass('is-visible');
    }

    function check() {
      if (mql.matches) enable();
      else disable();
    }

    // 初期判定
    check();

    // 768px をまたいだら有効/無効を切り替え
    if (mql.addEventListener) {
      mql.addEventListener('change', check);
    } else if (mql.addListener) { // 古いブラウザ向けフォールバック（非推奨API）
      mql.addListener(check);
    }
  });


})(jQuery, this);
