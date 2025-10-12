/*------------------------------- //
     フェードインアニメーション
// -------------------------------*/

// 画面内に入ったらクラス名を付ける
const Target = document.querySelectorAll(".is-target"); // ターゲットとする対象を指定

const Options = {
  root: null, // 基準にする要素(nullはbody)
  rootMargin: "0px 0px", // 上下左右の位置
  threshold: "0",
};

// Observerのインスタンスを作成
const Observer = new IntersectionObserver(change_target, Options);

// 取得した要素を監視対象に登録
for (let i = 0; i < Target.length; i++) {
  Observer.observe(Target[i]);
}

// クラス名を付け外しする
function change_target(entries, Observer) {
  entries.forEach((entry, index) => {
    const Target = entry.target;
    if (entry.isIntersecting) {
      Target.classList.add("is-animation");
    } else {
      Target.classList.remove("is-animation");
    }
  });
}

/*------------------------------- //
     タブ切り替え
// -------------------------------*/

const TabSelect_nav = document.querySelectorAll(".tab_select--nav button");
const TabSelect_tab = document.getElementsByClassName("tab_select--tab");
if (TabSelect_nav) {
  [...TabSelect_nav].forEach((element, i) => {
    element.addEventListener("click", (e) => {
      // navのクラス付け替え
      [...TabSelect_nav].map((el) => el.classList.remove("is-active"));
      element.classList.add("is-active");
      // tabのクラス付け替え
      [...TabSelect_tab].map((el) => el.classList.remove("is-show"));
      TabSelect_tab[i].classList.add("is-show");
    });
  });
}

/*------------------------------- //
     アコーディオン
// -------------------------------*/

// jQueryのアコーディオンを再現したコード /////////////////////////
// 要素をスライドしながら非表示にする関数(jQueryのslideUpと同じ)
const slideUp = (el, duration = 300) => {
  el.style.height = el.offsetHeight + "px";
  el.offsetHeight;
  el.style.transitionProperty = "height, margin, padding";
  el.style.transitionDuration = duration + "ms";
  el.style.transitionTimingFunction = "ease";
  el.style.overflow = "hidden";
  el.style.height = 0;
  el.style.paddingTop = 0;
  el.style.paddingBottom = 0;
  el.style.marginTop = 0;
  el.style.marginBottom = 0;
  setTimeout(() => {
    el.style.display = "none";
    el.style.removeProperty("height");
    el.style.removeProperty("padding-top");
    el.style.removeProperty("padding-bottom");
    el.style.removeProperty("margin-top");
    el.style.removeProperty("margin-bottom");
    el.style.removeProperty("overflow");
    el.style.removeProperty("transition-duration");
    el.style.removeProperty("transition-property");
    el.style.removeProperty("transition-timing-function");
    el.classList.remove("is-open");
  }, duration);
};
// 要素をスライドしながら表示する関数(jQueryのslideDownと同じ)
const slideDown = (el, duration = 300) => {
  el.classList.add("is-open");
  el.style.removeProperty("display");
  let display = window.getComputedStyle(el).display;
  if (display === "none") {
    display = "block";
  }
  el.style.display = display;
  let height = el.offsetHeight;
  el.style.overflow = "hidden";
  el.style.height = 0;
  el.style.paddingTop = 0;
  el.style.paddingBottom = 0;
  el.style.marginTop = 0;
  el.style.marginBottom = 0;
  el.offsetHeight;
  el.style.transitionProperty = "height, margin, padding";
  el.style.transitionDuration = duration + "ms";
  el.style.transitionTimingFunction = "ease";
  el.style.height = height + "px";
  el.style.removeProperty("padding-top");
  el.style.removeProperty("padding-bottom");
  el.style.removeProperty("margin-top");
  el.style.removeProperty("margin-bottom");
  setTimeout(() => {
    el.style.removeProperty("height");
    el.style.removeProperty("overflow");
    el.style.removeProperty("transition-duration");
    el.style.removeProperty("transition-property");
    el.style.removeProperty("transition-timing-function");
  }, duration);
};
// 要素をスライドしながら交互に表示/非表示にする関数(jQueryのslideToggleと同じ)
const slideToggle = (el, duration = 300) => {
  if (window.getComputedStyle(el).display === "none") {
    return slideDown(el, duration);
  } else {
    return slideUp(el, duration);
  }
};

// アコーディオンを全て取得
const accordionTriggers = document.querySelectorAll(".accordion_block");
if (accordionTriggers) {
  [...accordionTriggers].forEach((trigger) => {
    // 開閉させる要素を取得
    const content = trigger.querySelector(".accordion_block--content");
    if (content) {
      // 要素を展開or閉じる
      slideToggle(content);
      // Triggerにクリックイベントを付与
      trigger.addEventListener("click", () => {
        trigger.getElementsByClassName("careers_block--btn")[0].classList.toggle("is-open");
        slideToggle(content);
      });
    }
  });
}
