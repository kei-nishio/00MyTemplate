document.addEventListener("DOMContentLoaded", function () {
  // ***********
  // ** vanilla JS
  // ***********
  // ** 基本設定
  // ***********
  gsap.registerPlugin(ScrollTrigger); // ScrollTriggerを使うための記述
  let mm = gsap.matchMedia(); // メディアクエリを取得
  const pageUrl = window.location.href; // 現在のページの絶対URL
  const initialWindowsWidth = window.innerWidth; // ページ読み込み時のウィンドウ幅
  let lastWindowWidth = window.innerWidth; // リサイズ時のウィンドウ幅
  let currentWindowWidth = window.innerWidth; // 現在のウィンドウ幅
  window.addEventListener("resize", () => {
    currentWindowWidth = window.innerWidth;
    console.log("currentWindowWidth: " + currentWindowWidth + "lastWindowWidth: " + lastWindowWidth);
    lastWindowWidth = currentWindowWidth;
  });
  const breakpoint = 768; // レスポンシブ幅
  const headerHeight = document.querySelector(".js-header").offsetHeight; // ヘッダーの高さ
  // ** 強制リロード
  // ***********
  function forceReload() {
    window.location.href = window.location.href; // 現在のURLにリダイレクト
  }
  // ** PC版のみの処理
  // ***********
  if (document.querySelector("main").classList.contains("top") && currentWindowWidth >= breakpoint) {
    console.log("PC only process");
  }
  // ** SP版のみの処理
  // ***********
  if (document.querySelector("main").classList.contains("top") && currentWindowWidth < breakpoint) {
    console.log("SP only process");
  }
  // ** タブの切り替え
  // ***********
  class TabSwitcher {
    constructor(tabSelector, contentSelector, openClass) {
      this.tabs = document.querySelectorAll(tabSelector);
      this.contents = document.querySelectorAll(contentSelector);
      this.openClass = openClass;
      this.init();
    }
    init() {
      this.tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => this.activateTab(index));
      });
    }
    activateTab(index) {
      this.resetTabs();
      this.tabs[index].classList.add(this.openClass);
      this.contents[index].classList.add(this.openClass);
    }
    resetTabs() {
      this.tabs.forEach(tab => tab.classList.remove(this.openClass));
      this.contents.forEach(content => content.classList.remove(this.openClass));
    }
  }
  const tabSwitcher = new TabSwitcher('.js-tab', '.js-tab-content', 'is-open');
  // ** NEWSのセレクトボックスからカテゴリーアーカイブに遷移する
  // ***********
  window.redirectToUrl = function (select) {
    let url = select.value;
    if (url) window.location.href = url;
  };
  // ** ハンバーガーメニューとドロワーメニュー
  // ***********
  class DrawerToggle {
    constructor(headerSelector, hamburgerSelector, drawerMenuSelector, drawerMaskSelector) {
      this.header = document.querySelector(headerSelector);
      this.hamburger = document.querySelector(hamburgerSelector);
      this.drawer = document.querySelector(drawerMenuSelector);
      this.drawerMask = document.querySelector(drawerMaskSelector);
      this.initEvents();
    }
    initEvents() {
      [this.hamburger, this.drawer].forEach(element => {
        element.addEventListener('click', () => this.toggleDrawer());
      });
    }
    toggleDrawer() {
      [this.header, this.hamburger, this.drawer, this.drawerMask].forEach(el => {
        el.classList.toggle('is-active');
      });
      if (this.hamburger.classList.contains('is-active')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    }
  }
  const drawerToggle = new DrawerToggle('.js-header', '.js-hamburger', '.js-drawer-menu', '.js-drawer-mask');
  // ** ヘッダーをスクロールで非表示にする
  // ** scrollTop > window.innerHeight * 0.2 はスマホのバウンディング対応
  // ***********
  let lastScrollTop = 0;
  window.addEventListener("scroll", () => {
    let scrollTop = document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop && scrollTop > window.innerHeight * 0.2) {
      gsap.to(".js-header", { duration: 0.5, autoAlpha: 0 });
    } else {
      gsap.to(".js-header", { duration: 0.5, autoAlpha: 1 });
    }
    lastScrollTop = scrollTop;
  });
  // ** 横スクロールアニメーション
  // ***********
  let scrollHorizonContainer = document.querySelector(".js-scroll-horizon-container");
  let scrollHorizonContainerWidth = scrollHorizonContainer.offsetWidth;
  let scrollHorizonSlides = document.querySelectorAll(".js-scroll-horizon-slide");
  gsap.to(scrollHorizonSlides, {
    xPercent: -100 * (scrollHorizonSlides.length - 1),
    ease: "none",
    scrollTrigger: {
      // markers: true,
      id: "stScrollHorizon",
      trigger: ".js-scroll-horizon",
      start: "bottom bottom",
      pin: scrollHorizonContainer,
      scrub: 0.5,
      end: () => {
        "+=" + scrollHorizonContainerWidth;
      },
      anticipatePin: 1,
      invalidateOnRefresh: true,
    },
  });
  // ** ホバー時追従アニメーション
  // ***********
  let hoverFollowers = document.querySelectorAll(".js-hover-follower");
  hoverFollowers.forEach((target) => {
    let img = target.querySelector(".js-hover-follower-image");
    // gsap.set(img, { autoAlpha: 0 }); // cssで非表示にする場合は不要
    target.addEventListener("mouseenter", function () {
      gsap.to(img, { autoAlpha: 1, duration: 0.3 });
    });
    target.addEventListener("mouseleave", function () {
      gsap.to(img, { autoAlpha: 0, duration: 0.3 });
    });
    target.addEventListener("mousemove", function (e) {
      let rect = target.getBoundingClientRect();
      let x = e.clientX - rect.left;
      let y = e.clientY - rect.top;
      gsap.to(img, { duration: 0.1, x: x, y: y, xPercent: 75, yPercent: -50 });
    });
  });
  // ** 2要素間の高さの差分を取得
  // ***********
  class GetDifferenceOfTwoElements {
    constructor(id1, id2) {
      this.id1 = id1; // 引数で受け取ったidをプロパティに代入
      this.id2 = id2; // 引数で受け取ったidをプロパティに代入
      this.updateDiff(); // 差分を更新するメソッドを初期化時にも呼び出す
    }
    updateDiff() {
      const topFirst = document.getElementById(this.id1).getBoundingClientRect().top;
      const topEnd = document.getElementById(this.id2).getBoundingClientRect().top;
      this.topDiff = Math.abs(topFirst - topEnd);
    }
    adjust() {
      this.updateDiff(); // 調整のたびに位置差を更新
      return this.topDiff;
    }
  }
  const difference = new GetDifferenceOfTwoElements("first", "end");
  // ** URLのアンカーを抜き出す
  // ***********
  let anchors = document.querySelectorAll("a");
  anchors.addEventListener("click", function (anchor) {
    let targetHref = anchor.getAttribute("href");
    if (targetHref && targetHref.startsWith("#")) {
      let linkAnchor = targetHref.substring(1); // 「#」以下を変数として抜き出す
      console.log("linkAnchor: " + linkAnchor);
    }
  });
  // ** Swiper
  // ***********
  const swiper = new Swiper(".js-swiper .swiper", {
    direction: "horizontal",
    loop: true,
    effect: "fade",
    speed: 3000,
    allowTouchMove: false,
    slidesPerView: 1,
    spaceBetween: 10,
    centeredSlides: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    breakpoints: {
      768: {
        allowTouchMove: true,
      },
    },
    pagination: {
      el: ".js-swiper .swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".js-swiper .swiper-button-next",
      prevEl: ".js-swiper .swiper-button-prev",
    },
  });
  // ** ギャラリーモーダル
  // ***********
  let modal = document.querySelector(".js-modal");
  let modalOriImages = document.querySelectorAll(".js-modal-ori-image");
  let modalAddImageBox = document.querySelector(".js-modal-add-image-box");
  let modalClose = document.querySelector(".js-modal-close");
  const addImageToModal = (image) => {
    let modalAddImage = document.createElement("img");
    modalAddImage.src = image.src;
    modalAddImage.alt = image.alt;
    modalAddImageBox.appendChild(modalAddImage);
    modal.classList.add("is-active");
    document.body.style.overflow = "hidden";
  };
  const closeModal = (event) => {
    if (event.target === modal || event.target === modalClose) {
      modal.classList.remove("is-active");
      document.body.style.overflow = "";
      modalAddImageBox.innerHTML = "";
    }
  };
  modalOriImages.forEach(modalOriImage => {
    modalOriImage.addEventListener("click", () => addImageToModal(modalOriImage));
  });
  [modal, modalClose].forEach(element => {
    element.addEventListener("click", closeModal);
  });
  // ** target-id付リンクを踏んだ時にリンク先の表示要素を切り替える
  // ***********
  let tabSelectors = document.querySelectorAll(".js-tab");
  let tabContents = document.querySelectorAll(".js-tab-content");
  let linkId = new URL(pageUrl).searchParams.get("id");
  if (linkId) {
    tabSelectors.forEach((tab) => {
      tab.classList.remove("is-active");
    });
    tabContents.forEach((content) => {
      content.classList.remove("is-active");
    });
    let tabSelectorTarget = document.querySelector(`[data-target='${linkId}']`);
    let tabContentTarget = document.getElementById(linkId);
    tabSelectorTarget.classList.add("is-active");
    tabContentTarget.classList.add("is-active");
  }
  // ***********
  // ** FVアニメーション（Lottie）
  // ** lottie.min.js の読み込みが必要
  // ***********
  const pageUrlNonLocate = pageUrl.replace(/\/en/g, ""); // 多言語設定がある場合は「/en」を削除
  let ltAnimationFv = lottie.loadAnimation({
    container: document.getElementById("animation"),
    renderer: "svg",
    loop: true,
    autoplay: true,
    path: `${pageUrlNonLocate}/wp-content/themes/yourTheme/assets/images/animation/animation.json`,
  });
  // ***********
  // ** ScrollHint https://www.appleple.com/blog/oss/scroll-hint.html
  // ** 言語設定によるスクロールヒントの切り替え
  // ***********
  const currentLanguage = document.documentElement.lang;
  let scrollableText;
  if (currentLanguage === "ja") {
    scrollableText = "スクロールできます";
  } else {
    scrollableText = "Scroll";
  }
  new ScrollHint(".js-scrollable", {
    scrollHintIconAppendClass: "scroll-hint-icon-white",
    i18n: {
      scrollable: scrollableText,
    },
  });
});
