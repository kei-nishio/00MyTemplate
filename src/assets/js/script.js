document.addEventListener('DOMContentLoaded', () => {
  // ! 基本設定 ***********
  gsap.registerPlugin(ScrollTrigger); // ScrollTriggerを使うための記述
  // const pageUrl = window.location.href; // 現在のページの絶対URL
  // const initialWindowsWidth = window.innerWidth; // ページ読み込み時のウィンドウ幅
  // let lastWindowWidth = window.innerWidth; // リサイズ時のウィンドウ幅
  // let currentWindowWidth = window.innerWidth; // 現在のウィンドウ幅
  // window.addEventListener('resize', () => {
  //   currentWindowWidth = window.innerWidth;
  //   // console.log('currentWindowWidth: ' + currentWindowWidth + 'lastWindowWidth: ' + lastWindowWidth);
  //   lastWindowWidth = currentWindowWidth;
  // });
  // const breakpoint = 768; // レスポンシブ幅
  // const headerHeight = document.querySelector('header').offsetHeight; // ヘッダーの高さ
  // ! 関数 ***********
  function forceReload() {
    window.location.href = window.location.href; // 強制リロード
  }
  function resetInlineCssStyle(element) {
    element.style.cssText = ''; // インラインスタイルの初期化
  }

  // ! PC版のみの処理 ***********
  if (document.querySelector('main').classList.contains('top') && currentWindowWidth >= breakpoint) {
    console.log('PC only process');
  }

  // ! SP版のみの処理 ***********
  if (document.querySelector('main').classList.contains('top') && currentWindowWidth < breakpoint) {
    console.log('SP only process');
  }

  // ! タブ・クラス名トグル ***********
  class ToggleClass {
    constructor(tabSelector, contentSelector, openClass, exclusive = false, outsideClose = false) {
      this.tabs = document.querySelectorAll(tabSelector);
      this.contents = document.querySelectorAll(contentSelector);
      this.openClass = openClass; // クラス名
      this.exclusive = exclusive; // 他のタブクラスを排他的に外すかどうか
      this.outsideClose = outsideClose; // タブ外をクリックでクラス名を外す
      this.init();
    }
    init() {
      this.tabs.forEach((tab, index) => {
        tab.addEventListener('click', (event) => {
          event.stopPropagation(); // イベントのバブルを防止
          this.toggleClass(index);
        });
      });
      if (this.outsideClose) {
        document.addEventListener('click', (event) => {
          const clickedInsideTab = Array.from(this.tabs).some((tab) => tab.contains(event.target));
          const clickedInsideContent = Array.from(this.contents).some((content) => content.contains(event.target));
          if (!clickedInsideTab && !clickedInsideContent) {
            this.resetClass();
          }
        });
      }
    }
    toggleClass(index) {
      const isActive = this.tabs[index].classList.contains(this.openClass);

      if (this.exclusive) {
        this.resetClass();
      }

      if (isActive) {
        this.tabs[index].classList.remove(this.openClass);
        this.contents[index].classList.remove(this.openClass);
      } else {
        this.tabs[index].classList.add(this.openClass);
        this.contents[index].classList.add(this.openClass);
      }
    }
    resetClass() {
      this.tabs.forEach((tab) => tab.classList.remove(this.openClass));
      this.contents.forEach((content) => content.classList.remove(this.openClass));
    }
  }
  let tabSwitcher;
  if (false) {
    tabSwitcher = new ToggleClass('.js-menu-tab', '.js-menu-content', 'is-open', true, true);
  }

  // ! セレクトボックスからカテゴリーアーカイブに遷移する ***********
  window.redirectToUrl = function (select) {
    let url = select.value;
    if (url) window.location.href = url;
    /* 
    参考：php側のコード
    <?php if (wp_is_mobile()): ?>
      <label for="select-hoge" style="display: none;">○○から探す</label>
      <select id="select-hoge" class="foo-list" onchange="redirectToUrl(this)">
        <option value="default" selected class="hoge-item">ここから選択する</option>
        <?php foreach ($terms as $term) : ?>
          <option value="<?php echo esc_url(get_term_link($term)); ?>" class="hoge-item"><?php echo esc_html($term->name); ?></option>
        <?php endforeach; ?>
      </select>
    <?php endif; ?>
    */
  };

  // ! ハンバーガーメニューとドロワーメニュー ***********
  class DrawerToggle {
    constructor(headerSelector, hamburgerSelector, drawerMenuSelector, drawerMaskSelector, breakpoint = 768) {
      this.header = document.querySelector(headerSelector);
      this.hamburger = document.querySelector(hamburgerSelector);
      this.drawer = document.querySelector(drawerMenuSelector);
      this.drawerMask = document.querySelector(drawerMaskSelector);
      this.breakpoint = breakpoint;
      this.init();
    }

    init() {
      this.hamburger.addEventListener('click', () => this.toggleDrawer());
      window.addEventListener('resize', () => this.handleResize());
    }

    openDrawer() {
      [this.header, this.hamburger, this.drawer, this.drawerMask].forEach((el) => {
        el.classList.add('is-active');
      });
      document.documentElement.style.overflowY = 'hidden';
      document.body.style.overflowY = 'hidden';
      // Uncomment below if additional styles are needed for open state
      // this.drawer.style.display = 'block';
      // setTimeout(() => {
      //   this.drawer.style.visibility = 'visible';
      //   this.drawer.style.opacity = '1';
      // }, 100);
    }

    closeDrawer() {
      [this.header, this.hamburger, this.drawer, this.drawerMask].forEach((el) => {
        el.classList.remove('is-active');
      });
      document.documentElement.style.overflowY = '';
      document.body.style.overflowY = '';
      // Uncomment below if additional styles are needed for close state
      // this.drawer.style.visibility = 'hidden';
      // this.drawer.style.opacity = '0';
      // setTimeout(() => {
      //   this.drawer.style.display = 'none';
      // }, 300);
    }

    toggleDrawer() {
      if (this.hamburger.classList.contains('is-active')) {
        this.closeDrawer();
      } else {
        this.openDrawer();
      }
    }

    handleResize() {
      if (window.innerWidth >= this.breakpoint && this.hamburger.classList.contains('is-active')) {
        this.closeDrawer();
      }
    }
  }
  if (true) {
    const drawerToggle = new DrawerToggle('.js-header', '.js-hamburger', '.js-drawer', '.js-drawer-mask');
  }

  // ! TOPにスクロールするボタン ***********
  const toTopButton = document.querySelector('.js-to-up');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 600) {
      toTopButton.classList.add('is-active');
    } else {
      toTopButton.classList.remove('is-active');
    }
  });
  toTopButton.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  });

  // ! ヘッダーをスクロールで非表示にする ***********
  let lastScrollTop = 0;
  const header = document.querySelector('.js-header');
  window.addEventListener('scroll', () => {
    let scrollTop = document.documentElement.scrollTop;
    // * scrollTop > window.innerHeight * 0.2 はスマホのバウンディング対応
    if (scrollTop > lastScrollTop && scrollTop > window.innerHeight * 0.2) {
      header.classList.add('is-hidden');
    } else {
      header.classList.remove('is-hidden');
    }
    lastScrollTop = scrollTop;
  });

  // ! トリガー要素以下で見えなくなるターゲット ***********
  if (false) {
    let trigger = document.querySelector('.js-trigger');
    let target = document.querySelector('.js-target');
    if (trigger && target) {
      // ビューポート上の位置＋現在のスクロール量
      let triggerTop = trigger.getBoundingClientRect().top + window.scrollY;
      window.addEventListener('scroll', function () {
        // フォームの上端が画面内に入ったかどうか
        var scrolledIntoView = window.scrollY + window.innerHeight >= triggerTop;
        if (scrolledIntoView) {
          target.style.opacity = '0';
          target.style.visibility = 'hidden';
          target.style.pointerEvents = 'none';
          target.style.userSelect = 'none';
        } else {
          target.style.opacity = '1';
          target.style.visibility = 'visible';
          target.style.pointerEvents = '';
          target.style.userSelect = '';
        }
      });
    }
  }

  // ! 途中から追従するコンタクトボタン ***********
  if (false) {
    let tl = gsap.timeline({});
    tl.to('.js-floating-button', {
      scrollTrigger: {
        markers: true,
        id: 'stFloatingButton',
        trigger: '.js-floating-button',
        start: 'bottom bottom-=20',
        end: 'top-=50% bottom',
        endTrigger: '#footer',
        pin: true,
        pinSpacing: false,
        onLeave: () => {
          gsap.to('.js-floating-button', { autoAlpha: 0, duration: 0.3 });
        },
        onEnterBack: () => {
          gsap.to('.js-floating-button', { autoAlpha: 1, duration: 0.5 });
        },
      },
    });
  }

  // ! クリックアコーディオン 回転タイプ ***********
  class AccordionToggle {
    constructor(accordionElement, index) {
      this.accordionElement = accordionElement;
      this.parent = accordionElement.children[0];
      this.child = accordionElement.children[1];
      this.isOpen = this.accordionElement.classList.contains('is-open');
      this.index = index;
      this.init();
    }

    init() {
      // 一時的にアコーディオンを表示状態にする
      this.child.style.display = 'block';
      this.child.style.height = 'auto';
      this.child.style.transform = 'rotateX(0deg)';
      this.child.style.opacity = 1;
      this.child.style.visibility = 'visible';

      this.height = this.child.offsetHeight;
      this.paddingTop = window.getComputedStyle(this.child).paddingTop;
      this.paddingBottom = window.getComputedStyle(this.child).paddingBottom;

      this.reset();

      if (this.isOpen || this.index === 0) {
        this.open();
      } else {
        this.reset();
      }

      this.parent.addEventListener('click', () => this.toggle());
    }

    reset() {
      this.child.style.height = '0';
      this.child.style.paddingTop = '0';
      this.child.style.paddingBottom = '0';
      this.child.style.transform = 'rotateX(90deg)';
      this.child.style.opacity = 0;
      this.child.style.visibility = 'hidden';
    }

    open() {
      this.isOpen = true;
      this.accordionElement.classList.add('is-open');
      gsap.to(this.child, {
        duration: 0.3,
        height: this.height,
        paddingTop: this.paddingTop,
        paddingBottom: this.paddingBottom,
        rotateX: 0,
        autoAlpha: 1,
        ease: 'power2.inOut',
        onStart: () => {
          this.child.style.display = 'block';
        },
      });
    }

    close() {
      this.isOpen = false;
      this.accordionElement.classList.remove('is-open');
      gsap.to(this.child, {
        duration: 0.3,
        height: 0,
        paddingTop: 0,
        paddingBottom: 0,
        rotateX: 90,
        autoAlpha: 0,
        ease: 'power2.inOut',
        onComplete: () => {
          this.child.style.display = 'none';
        },
      });
    }

    toggle() {
      if (this.isOpen) {
        this.close();
      } else {
        this.open();
      }
    }
  }

  const accordions = document.querySelectorAll('.js-accordion');
  if (accordions.length > 0) {
    accordions.forEach((accordion, index) => {
      new AccordionToggle(accordion, index);
    });

    // ウィンドウ幅が変わったときに再度インスタンスを生成
    let lastWindowWidth = window.innerWidth;
    window.addEventListener('resize', () => {
      let currentWindowWidth = window.innerWidth;
      if (currentWindowWidth !== lastWindowWidth) {
        lastWindowWidth = currentWindowWidth;
        accordions.forEach((accordion, index) => {
          const isOpen = accordion.classList.contains('is-open');
          new AccordionToggle(accordion, index, isOpen);
        });
      }
    });
  }

  // ! クリックアコーディオン クリップタイプ ***********
  class AccordionClip {
    constructor(accordionElement, index) {
      this.accordionElement = accordionElement;
      this.parent = accordionElement.children[0];
      this.child = accordionElement.children[1];
      this.isOpen = this.accordionElement.classList.contains('is-open');
      this.index = index;
      this.init();
    }

    init() {
      this.reset();
      if (this.isOpen) this.open();
      this.parent.addEventListener('click', () => this.toggle());
    }

    reset() {
      Object.assign(this.child.style, {
        clipPath: 'polygon(0 0, 100% 0, 100% 0, 0 0)',
        opacity: 0,
        visibility: 'hidden',
        display: 'none',
      });
    }

    open() {
      this.isOpen = true;
      this.accordionElement.classList.add('is-open');
      this.child.style.display = 'block';
      gsap.to(this.child, {
        duration: 0.3,
        clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)',
        autoAlpha: 1,
        ease: 'power2.inOut',
      });
    }

    close() {
      this.isOpen = false;
      this.accordionElement.classList.remove('is-open');
      gsap.to(this.child, {
        duration: 0.2,
        clipPath: 'polygon(0 0, 100% 0, 100% 0, 0 0)',
        autoAlpha: 0,
        ease: 'power2.inOut',
        onComplete: () => {
          this.child.style.display = 'none';
        },
      });
    }

    toggle() {
      this.isOpen ? this.close() : this.open();
    }
  }
  const accordionClips = document.querySelectorAll('.js-accordion');
  if (accordionClips.length > 0) {
    accordionClips.forEach((accordion, index) => {
      new AccordionClip(accordion, index);
    });
  }

  // ! 横スクロールアニメーション ***********
  if (false) {
    let scrollHorizon = document.querySelector('.js-scroll-horizon');
    let scrollHorizonContainer = document.querySelector('.js-scroll-horizon__box');
    let scrollHorizonContainerWidth = scrollHorizonContainer.offsetWidth;
    let scrollHorizonSlides = document.querySelectorAll('.js-scroll-horizon__image-item');
    let scrollHorizonBox = document.querySelector('.js-scroll-horizon__image-items');
    gsap.to(scrollHorizonSlides, {
      xPercent: -105 * (scrollHorizonSlides.length - 1),
      ease: 'none',
      scrollTrigger: {
        // markers: true,
        id: 'stScrollHorizon',
        trigger: '.js-scroll-horizon',
        start: 'bottom bottom',
        pin: scrollHorizon,
        scrub: 0.5,
        end: '+=' + scrollHorizonContainerWidth,
        anticipatePin: 1,
        invalidateOnRefresh: true,
        snap: {
          snapTo: 1 / (scrollHorizonSlides.length - 1),
          duration: { min: 0.1, max: 0.2 },
        },
      },
    });
  }

  // ! ホバー時人物写真追従アニメーション（removeを考慮してグローバルスコープで関数を定義） ***********
  class MouseHover {
    constructor(target, imageSelector) {
      this.target = target;
      this.image = target.querySelector(imageSelector);
      this.mouseEnter = () => gsap.to(this.image, { autoAlpha: 1, duration: 0.3 });
      this.mouseLeave = () => gsap.to(this.image, { autoAlpha: 0, duration: 0.3 });
      this.mouseMove = (e) => {
        const rect = this.target.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        gsap.to(this.image, {
          duration: 0.1,
          x: x,
          y: y,
          xPercent: 75,
          yPercent: -50,
        });
      };
    }
    setupHandlers() {
      this.target.addEventListener('mouseenter', this.mouseEnter);
      this.target.addEventListener('mouseleave', this.mouseLeave);
      this.target.addEventListener('mousemove', this.mouseMove);
    }
    killHandlers() {
      this.target.removeEventListener('mouseenter', this.mouseEnter);
      this.target.removeEventListener('mouseleave', this.mouseLeave);
      this.target.removeEventListener('mousemove', this.mouseMove);
      resetInlineCssStyle(this.image);
      resetInlineCssStyle(this.target);
    }
  }
  function hoverImageFollowSwitch() {
    if (document.querySelector('main').classList.contains('top') && currentWindowWidth >= breakpoint) {
      constants.forEach((constant) => {
        constant.setupHandlers();
      });
    } else {
      constants.forEach((constant) => {
        constant.killHandlers();
      });
    }
  }
  // * ホバー時人物写真追従アニメーションの初期化
  if (false) {
    const constants = [];
    document.querySelectorAll('.js-hover-image').forEach((target, index) => {
      constants[index] = new MouseHover(target, '.js-hover-image__image');
    });
    if (document.querySelector('main').classList.contains('top') && currentWindowWidth >= breakpoint) {
      constants.forEach((constant) => {
        constant.setupHandlers();
      });
    }
    window.addEventListener('resize', () => {
      hoverImageFollowSwitch();
    });
  }

  // ! 2要素間の高さの差分を取得 ***********
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
  if (false) {
    const difference = new GetDifferenceOfTwoElements('first', 'end');
  }

  // ! URLのアンカーを抜き出す ***********
  if (false) {
    let anchors = document.querySelectorAll('a');
    anchors.addEventListener('click', function (anchor) {
      let targetHref = anchor.getAttribute('href');
      if (targetHref && targetHref.startsWith('#')) {
        let linkAnchor = targetHref.substring(1); // 「#」以下を変数として抜き出す
        console.log('linkAnchor: ' + linkAnchor);
      }
    });
  }

  // ! Swiper FV ***********
  let swiperFvs = document.querySelectorAll('.js-swiper-fv .swiper');
  if (swiperFvs.length > 0) {
    const swiperFv = new Swiper('.js-swiper-fv .swiper', {
      direction: 'horizontal',
      effect: 'fade', // 'fade', 'cube', 'coverflow', 'flip'
      loop: true,
      speed: 3000,
      allowTouchMove: true,
      slidesPerView: 1,
      spaceBetween: 16,
      centeredSlides: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      breakpoints: {
        768: {
          spaceBetween: 32,
        },
      },
    });
    // * TOPページで特定のSwiperを制御する
    if (window.location.pathname === '/' || window.location.pathname === '/index.html') {
      if (swiperFv) {
        swiperFv.autoplay.stop(); // 初回は自動再生を止めておく
        setTimeout(() => {
          swiperFv.autoplay.start(); // 1秒後に自動再生を開始
        }, 1000);
      }
    }
  }

  // ! Swiper Normal  ***********
  let swiperNews = document.querySelectorAll('.js-swiper .swiper');
  if (swiperNews.length > 0) {
    const swiperNews = new Swiper('.js-swiper .swiper', {
      // **1. 基本設定**
      direction: 'horizontal', // 'horizontal' (水平) または 'vertical' (垂直)
      loop: true, // 無限ループ
      speed: 500, // スライドの切り替え速度 (ミリ秒)
      initialSlide: 0, // 最初に表示するスライド (インデックス番号)

      // **2. スライド表示設定**
      slidesPerView: 1, // 画面に表示するスライド数 ('auto'も指定可)
      spaceBetween: 10, // スライド間の余白 (px)
      slidesPerGroup: 1, // 一度に移動するスライド数
      centeredSlides: false, // アクティブスライドを中央に表示

      // **3. 自動再生**
      autoplay: {
        delay: 3000, // スライドの間隔 (ミリ秒)
        disableOnInteraction: false, // ユーザー操作後も再生を続ける
        pauseOnMouseEnter: true, // マウスオーバーで一時停止
      },

      // **4. ページネーション (ドット)**
      pagination: {
        el: '.swiper-pagination', // ページネーション要素のセレクタ
        clickable: true, // ページネーションをクリック可能にする
        dynamicBullets: true, // 動的なドットサイズ
        type: 'bullets', // 'bullets', 'fraction', 'progressbar', 'custom'
        renderBullet: function (index, className) {
          return `<span class="${className}">${index + 1}</span>`;
        },
      },

      // **5. ナビゲーションボタン (前後ボタン)**
      navigation: {
        nextEl: '.swiper-button-next', // 次のボタンのセレクタ
        prevEl: '.swiper-button-prev', // 前のボタンのセレクタ
      },

      // **6. スクロールバー**
      scrollbar: {
        el: '.swiper-scrollbar', // スクロールバーのセレクタ
        draggable: true, // ドラッグ可能にする
      },

      // **7. グリッドレイアウト**
      grid: {
        rows: 1, // 行数
        fill: 'row', // 'row' (横方向) または 'column' (縦方向)
      },

      // **8. レスポンシブ設定**
      breakpoints: {
        640: {
          slidesPerView: 1,
          spaceBetween: 10,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      },

      // **9. エフェクト設定**
      effect: 'slide', // 'slide', 'fade', 'cube', 'coverflow', 'flip', 'creative'
      fadeEffect: {
        crossFade: true, // フェードエフェクト間のクロスフェード
      },
      cubeEffect: {
        shadow: true,
        slideShadows: true,
        shadowOffset: 20,
        shadowScale: 0.94,
      },
      coverflowEffect: {
        rotate: 50, // スライドの回転角度
        stretch: 0, // スライド間の距離
        depth: 100, // 奥行き
        modifier: 1, // 効果の強さ
        slideShadows: true,
      },

      // **10. 仮想スライド (動的生成)**
      virtual: {
        slides: (function () {
          const slides = [];
          for (let i = 0; i < 500; i++) {
            slides.push(`Slide ${i}`);
          }
          return slides;
        })(),
      },

      // **11. スライダー動作に関する設定**
      allowSlideNext: true, // 次スライドへの移動を許可
      allowSlidePrev: true, // 前スライドへの移動を許可
      allowTouchMove: true, // スワイプ操作を許可
      threshold: 20, // スワイプ感知の閾値 (px)
      touchRatio: 1, // スワイプ感度
      touchAngle: 45, // スワイプ開始方向の角度制限
      simulateTouch: true, // デスクトップでのドラッグを許可
      grabCursor: true, // スライダー操作中にカーソルを変更

      // **13. 監視設定**
      observer: false, // SwiperがDOM変更を監視する
      observeParents: false, // 親要素の変更も監視する

      // **13. イベントコールバック**
      on: {
        init: function () {
          console.log('Swiper initialized');
        },
        slideChange: function () {
          console.log('Slide changed to: ', this.activeIndex);
        },
        reachEnd: function () {
          console.log('Reached the last slide');
        },
      },
    });
  }

  // ! Swiper Creative  ***********
  // const swiperCreative = new Swiper('.js-swiper .swiper', {
  //   effect: 'creative', // 「creative」を指定
  //   creativeEffect: {
  //     perspective: false, // 遠近 boolean / number
  //     progressMultiplier: 1, // 進捗度による倍率 number
  //     shadowPerProgress: false, // 影 boolean
  //     limitProgress: 2, // 進行状態制限 number
  //     depth: 300, // 深度 number
  //     prev: {
  //       translate: ['-100%', 0, -1],
  //       rotate: [0, 0, -90],
  //       scale: 0.8,
  //       opacity: 1,
  //       origin: 'right bottom',
  //     },
  //     next: {
  //       translate: ['100%', 0, 1],
  //       rotate: [0, 0, 90],
  //       scale: 0.8,
  //       opacity: 1,
  //       origin: 'left top',
  //     },
  //   },
  // });

  // ! ギャラリーモーダル ***********
  if (false) {
    let modal = document.querySelector('.js-modal');
    let modalOriImages = document.querySelectorAll('.js-modal-ori-image');
    let modalAddImageBox = document.querySelector('.js-modal-add-image-box');
    let modalClose = document.querySelector('.js-modal-close');
    const addImageToModal = (image) => {
      let modalAddImage = document.createElement('img');
      modalAddImage.src = image.src;
      modalAddImage.alt = image.alt;
      modalAddImageBox.appendChild(modalAddImage);
      modal.classList.add('is-active');
      document.body.style.overflow = 'hidden';
    };
    const closeModal = (event) => {
      if (event.target === modal || event.target === modalClose) {
        modal.classList.remove('is-active');
        document.body.style.overflow = '';
        modalAddImageBox.innerHTML = '';
      }
    };
    modalOriImages.forEach((modalOriImage) => {
      modalOriImage.addEventListener('click', () => addImageToModal(modalOriImage));
    });
    [modal, modalClose].forEach((element) => {
      element.addEventListener('click', closeModal);
    });
  }

  // ! クリックで画像を切り替える
  if (false) {
    const targetImages = document.querySelectorAll('.js-target-image img');
    const thumbnailImage = document.querySelector('.js-thumbnail-image img');
    targetImages.forEach((targetImage) => {
      targetImage.addEventListener('click', () => {
        thumbnailImage.src = targetImage.src;
        thumbnailImage.alt = targetImage.alt;
      });
    });
  }

  // ! クエリパラメータでリンク先の表示要素を切り替える ***********
  if (false) {
    let tabSelectors = document.querySelectorAll('.js-tab');
    let tabContents = document.querySelectorAll('.js-tab-content');
    let linkId = new URL(pageUrl).searchParams.get('id');
    if (linkId) {
      tabSelectors.forEach((tab) => {
        tab.classList.remove('is-open');
      });
      tabContents.forEach((content) => {
        content.classList.remove('is-open');
      });
      let tabSelectorTarget = document.querySelector(`[data-target='${linkId}']`);
      let tabContentTarget = document.getElementById(linkId);
      tabSelectorTarget.classList.add('is-open');
      tabContentTarget.classList.add('is-open');
    }
  }

  // ! スムーズスクロール ***********
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (e) => {
      e.preventDefault();
      document.querySelector(anchor.getAttribute('href')).scrollIntoView({
        behavior: 'smooth',
      });
    });
  });

  // ! FVアニメーション（Lottie）（lottie.min.js の読み込みが必要） ***********
  if (false) {
    const pageUrlNonLocate = pageUrl.replace(/\/en/g, ''); // 多言語設定がある場合は「/en」を削除
    let ltAnimationFv = lottie.loadAnimation({
      container: document.getElementById('animation'),
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: `${pageUrlNonLocate}/wp-content/themes/yourTheme/assets/images/animation/animation.json`,
    });
  }

  // ! ScrollHint https://www.appleple.com/blog/oss/scroll-hint.html ***********
  if (false) {
    const currentLanguage = document.documentElement.lang;
    let scrollableText;
    // * 言語設定によるスクロールヒントの切り替え
    if (currentLanguage === 'ja') {
      scrollableText = 'スクロールできます';
    } else {
      scrollableText = 'Scroll';
    }
    new ScrollHint('.js-scrollable', {
      scrollHintIconAppendClass: 'scroll-hint-icon-white',
      remainingTime: 3000, //ms
      offset: -1, // スクロールできなくても表示する
      i18n: {
        scrollable: scrollableText,
      },
    });
  }

  // ! お問い合わせページ限定 ***********
  if (false) {
    if (window.location.pathname.match(/^\/contact/)) {
      // * 郵便番号から住所を取得
      const zipCodeField = document.querySelector('input[name="your-zip-code"]');
      const addressField = document.querySelector('input[name="your-address"]');
      zipCodeField.addEventListener('blur', function () {
        const zipCode = zipCodeField.value.replace('-', '');
        if (zipCode.length === 7) {
          fetch(`https://api.zipaddress.net/?zipcode=${zipCode}`)
            .then((response) => response.json())
            .then((data) => {
              if (data.code === 200) {
                addressField.value = data.data.fullAddress;
              } else {
                alert('郵便番号が正しいかご確認ください。');
              }
            });
        }
      });
    }
  }

  // ! Pin Animation ***********
  const pinTarget = document.querySelector('.js-pin-target');
  const pinTrigger = document.querySelector('.js-pin-trigger');
  if (pinTarget) {
    ScrollTrigger.create({
      trigger: pinTrigger,
      start: 'top 30%',
      end: 'bottom 70%',
      pin: pinTarget,
      scrub: true,
      // markers: true, // デバッグ用
    });
  }

  // ! Parallax Animation ***********
  const parallaxs = document.querySelectorAll('.js-parallax');
  if (parallaxs.length > 0) {
    parallaxs.forEach((element) => {
      gsap.fromTo(
        element,
        {
          y: '30%',
          filter: 'blur(0px)',
        },
        {
          y: '80%',
          ease: 'power1.inOut',
          scrollTrigger: {
            trigger: element,
            start: 'top bottom',
            end: 'bottom top',
            scrub: 2,
          },
        }
      );
    });
  }

  // ! Clip Path Animation ***********
  const clipDowns = document.querySelectorAll('.js-clip-down');
  if (clipDowns.length > 0) {
    clipDowns.forEach((element) => {
      gsap.fromTo(
        element,
        {
          clipPath: 'polygon(0 0, 100% 0, 100% 0, 0 0)',
        },
        {
          clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)',
          ease: 'power1.inOut',
          scrollTrigger: {
            trigger: element,
            start: 'top 90%',
            end: 'bottom 80%',
            // markers: true, // マーカー表示
            scrub: true,
            once: true,
          },
        }
      );
    });
  }

  const clipRights = document.querySelectorAll('.js-clip-right');
  if (clipRights.length > 0) {
    clipRights.forEach((element) => {
      gsap.fromTo(
        element,
        {
          clipPath: 'polygon(0 0, 0 0, 0 100%, 0 100%)',
        },
        {
          clipPath: 'polygon(100% 0, 0 0, 0 100%, 100% 100%)',
          ease: 'power1.inOut',
          scrollTrigger: {
            trigger: element,
            start: 'top 90%',
            end: 'bottom 80%',
            // markers: true, // マーカー表示
            scrub: true,
            once: true,
          },
        }
      );
    });
  }

  // ! FadeIN Animation ***********
  // * その場でフェードイン
  const fadeIns = document.querySelectorAll('.js-fadeIn');
  if (fadeIns.length > 0) {
    fadeIns.forEach((element) => {
      gsap.from(element, {
        scrollTrigger: {
          trigger: element,
          start: 'top bottom-=70',
          once: true,
        },
        duration: 0.5,
        autoAlpha: 0,
      });
    });
  }
  // * 右からフェードイン
  const fadeInRights = document.querySelectorAll('.js-fadeInRight');
  if (fadeInRights.length > 0) {
    fadeInRights.forEach((element) => {
      gsap.from(element, {
        scrollTrigger: {
          trigger: element,
          start: 'top bottom-=70',
          once: true,
        },
        duration: 0.5,
        autoAlpha: 0,
        x: 100,
      });
    });
  }
  // * 左からフェードイン
  const fadeInLefts = document.querySelectorAll('.js-fadeInLeft');
  if (fadeInLefts.length > 0) {
    fadeInLefts.forEach((element) => {
      gsap.from(element, {
        scrollTrigger: {
          trigger: element,
          start: 'top bottom-=70',
          once: true,
        },
        duration: 0.5,
        autoAlpha: 0,
        x: -100,
      });
    });
  }
  // * 下からフェードイン
  const fadeInBottoms = document.querySelectorAll('.js-fadeInBottom');
  if (fadeInBottoms.length > 0) {
    fadeInBottoms.forEach((element) => {
      gsap.from(element, {
        scrollTrigger: {
          trigger: element,
          start: 'top bottom-=70',
          once: true,
        },
        duration: 0.5,
        autoAlpha: 0,
        y: 100,
      });
    });
  }
  // * 右から順番にフェードイン
  const fadeInRightStaggers = document.querySelectorAll('.js-fadeInRightStagger');
  if (fadeInRightStaggers.length > 0) {
    gsap.from(fadeInRightStaggers, {
      scrollTrigger: {
        trigger: fadeInRightStaggers[0],
        start: 'top bottom-=70',
        once: true,
      },
      duration: 0.7,
      autoAlpha: 0,
      x: 100,
      stagger: 0.2,
    });
  }

  // ! Loading Animation ***********
  if (false) {
    const hasOnceVisited = localStorage.getItem('hasVisitedTopPage'); //初回がfalse ブラウザを閉じても有効
    const hasFirstVisited = sessionStorage.getItem('hasVisitedTopPage'); //初回がfalse タブを閉じるとリセット
    // console.log('hasOnceVisited: ' + hasOnceVisited);
    // console.log('hasFirstVisited: ' + hasFirstVisited);
    if (!hasFirstVisited) {
      const loading = document.querySelector('.js-loading');
      const tl = gsap.timeline();
      tl.set(loading, { display: 'block' });
    }
    localStorage.setItem('hasVisitedTopPage', 'true'); // ブラウザを閉じても有効
    sessionStorage.setItem('hasVisitedTopPage', 'true'); // タブを閉じるとリセット
  }
});
