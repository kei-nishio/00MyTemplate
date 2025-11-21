document.addEventListener('DOMContentLoaded', () => {
  // ! デバッグ用 ***********
  document.querySelectorAll('*').forEach((el) => {
    if (getComputedStyle(el).position === 'absolute') {
      el.classList.add('is-absolute');
    }
    el.style.outline = '2px solid red';
  });

  // ! 基本設定 ***********
  gsap.registerPlugin(ScrollTrigger); // ScrollTrigger
  gsap.registerPlugin(SplitText); // SplitText
  const breakpoint = 768;
  function isSP() {
    return window.innerWidth < breakpoint;
  }

  // ! タブ・クラス名トグル ***********
  class ToggleClass {
    constructor(tabDataAttr, contentDataAttr, openClass = 'is-open', exclusive = false, outsideClose = false) {
      // data-tab="xxx" のような形で要素を取得
      this.tabs = document.querySelectorAll(`[${tabDataAttr}]`);
      this.contents = document.querySelectorAll(`[${contentDataAttr}]`);
      this.openClass = openClass; // クラス名
      this.exclusive = exclusive;
      this.outsideClose = outsideClose;
      this.init();
    }

    init() {
      this.tabs.forEach((tab, index) => {
        tab.addEventListener('click', (event) => {
          event.stopPropagation();
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
  if (false) {
    let tabSwitcher;
    tabSwitcher = new ToggleClass('data-tab', 'data-content', 'is-open', true, true);
    /*
    参考HTML構造
    <button data-tab="menu" data-open="false" class="is-open">メニュー</button>
    <button data-tab="search" data-open="false">検索</button>
    <div data-content="menu" data-open="false" class="is-open">メニューの内容</div>
    <div data-content="search" data-open="false">検索の内容</div>
    */
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
    constructor({ headerAttr, hamburgerAttr, drawerAttr, maskAttr, activeClass = 'is-active', breakpoint = 768 }) {
      this.header = document.querySelector(`[${headerAttr}]`);
      this.hamburger = document.querySelector(`[${hamburgerAttr}]`);
      this.drawer = document.querySelector(`[${drawerAttr}]`);
      this.mask = document.querySelector(`[${maskAttr}]`);
      this.breakpoint = breakpoint;
      this.activeClass = activeClass; // トグルするクラス名（変数で指定可能）

      this.init();
    }

    init() {
      // 必須要素が存在しない場合は処理を中断
      if (!this.hamburger || !this.drawer) {
        console.warn('DrawerToggle: 必須要素が見つかりません');
        return;
      }

      // 各種イベントの登録
      this.addEventListeners();
    }

    addEventListeners() {
      if (this.hamburger) {
        this.hamburger.addEventListener('click', (e) => this.handleHamburgerClick(e));
      }

      if (this.mask) {
        this.mask.addEventListener('click', () => this.closeDrawer());
      }

      document.addEventListener('click', (e) => this.handleDocumentClick(e));
      window.addEventListener('resize', () => this.handleResize());

      // ドロワー内のリンククリック時にドロワーを閉じる
      if (this.drawer) {
        const links = this.drawer.querySelectorAll('a[href]');
        links.forEach((link) => {
          link.addEventListener('click', () => this.closeDrawer());
        });
      }
    }

    handleHamburgerClick(e) {
      e.stopPropagation(); // クリックイベントの伝播を防ぐ
      this.toggleDrawer();
    }

    handleDocumentClick(e) {
      // ドロワーが開いている状態で、ドロワー外をクリックした場合に閉じる
      if (
        this.isDrawerOpen() &&
        this.drawer &&
        !this.drawer.contains(e.target) &&
        this.hamburger &&
        !this.hamburger.contains(e.target)
      ) {
        this.closeDrawer();
      }
    }

    handleResize() {
      // ブレイクポイント以上でドロワーが開いていたら閉じる
      if (window.innerWidth >= this.breakpoint && this.isDrawerOpen()) {
        this.closeDrawer();
      }
    }

    toggleDrawer() {
      if (this.isDrawerOpen()) {
        this.closeDrawer();
      } else {
        this.openDrawer();
      }
    }

    openDrawer() {
      this.applyClass([this.header, this.hamburger, this.drawer, this.mask], true);
      this.lockScroll(true);
    }

    closeDrawer() {
      this.applyClass([this.header, this.hamburger, this.drawer, this.mask], false);
      this.lockScroll(false);
    }

    isDrawerOpen() {
      return this.hamburger && this.hamburger.classList.contains(this.activeClass);
    }

    applyClass(elements, add) {
      elements.forEach((el) => {
        if (el) {
          el.classList.toggle(this.activeClass, add);
        }
      });
    }

    lockScroll(lock) {
      const value = lock ? 'hidden' : '';
      document.documentElement.style.overflowY = value;
      document.body.style.overflowY = value;
    }
  }
  const drawerToggle = new DrawerToggle({
    headerAttr: 'data-header',
    hamburgerAttr: 'data-hamburger',
    drawerAttr: 'data-drawer',
    maskAttr: 'data-drawer-mask',
    activeClass: 'is-active',
    breakpoint: 768,
  });

  // ! TOPにスクロールするボタン ***********
  const toTopButton = document.querySelector('[data-to-top]');
  if (toTopButton) {
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
  }

  // ! ヘッダーをスクロールで非表示にする ***********
  let lastScrollTop = 0;
  const header = document.querySelector('[data-header]');
  if (header) {
    window.addEventListener('scroll', () => {
      let scrollTop = document.documentElement.scrollTop;
      // * scrollTop > window.innerHeight * 0.2 はスマホのバウンディング対応
      if (scrollTop > lastScrollTop && scrollTop > window.innerHeight * 0.2) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }
      lastScrollTop = scrollTop;
    });
  }

  // ! トリガー要素以下で見えなくなるターゲット ***********
  if (false) {
    let trigger = document.querySelector('[data-trigger]');
    let target = document.querySelector('[data-target]');
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
    /*
    <div data-animation="floating-button">
      <button>お問い合わせ</button>
    </div>
    */
    const floatingButton = document.querySelector('[data-animation="floating-button"]');
    if (floatingButton) {
      let tl = gsap.timeline({});
      tl.to(floatingButton, {
        scrollTrigger: {
          markers: true,
          id: 'stFloatingButton',
          trigger: floatingButton,
          start: 'bottom bottom-=20',
          end: 'top-=50% bottom',
          endTrigger: '#footer',
          pin: true,
          pinSpacing: false,
          onLeave: () => {
            gsap.to(floatingButton, { autoAlpha: 0, duration: 0.3 });
          },
          onEnterBack: () => {
            gsap.to(floatingButton, { autoAlpha: 1, duration: 0.5 });
          },
        },
      });
    }
  }

  // ! クリックアコーディオン jsタイプ ***********
  // * slideUp
  const slideUp = (el, duration = 300) => {
    el.style.height = el.offsetHeight + 'px';
    el.offsetHeight;
    el.style.transitionProperty = 'height, margin, padding, opacity';
    el.style.transitionDuration = duration + 'ms';
    el.style.transitionTimingFunction = 'ease';
    el.style.overflow = 'hidden';
    el.style.height = 0;
    el.style.paddingTop = 0;
    el.style.paddingBottom = 0;
    el.style.marginTop = 0;
    el.style.marginBottom = 0;
    el.style.opacity = 0;
    setTimeout(() => {
      el.style.removeProperty('opacity');
    }, duration * 0.8);
    setTimeout(() => {
      el.style.display = 'none';
      el.style.removeProperty('height');
      el.style.removeProperty('padding-top');
      el.style.removeProperty('padding-bottom');
      el.style.removeProperty('margin-top');
      el.style.removeProperty('margin-bottom');
      el.style.removeProperty('overflow');
      el.style.removeProperty('transition-duration');
      el.style.removeProperty('transition-property');
      el.style.removeProperty('transition-timing-function');
    }, duration);
  };
  // * slideDown
  const slideDown = (el, duration = 300) => {
    el.style.removeProperty('display');
    let display = window.getComputedStyle(el).display;
    if (display === 'none') {
      display = 'block';
      el.style.opacity = 0;
    }
    el.style.display = display;
    let height = el.offsetHeight;
    el.style.overflow = 'hidden';
    el.style.height = 0;
    el.style.paddingTop = 0;
    el.style.paddingBottom = 0;
    el.style.marginTop = 0;
    el.style.marginBottom = 0;
    el.offsetHeight;
    el.style.transitionProperty = 'height, margin, padding, opacity';
    el.style.transitionDuration = duration + 'ms';
    el.style.transitionTimingFunction = 'ease';
    el.style.height = height + 'px';
    el.style.removeProperty('padding-top');
    el.style.removeProperty('padding-bottom');
    el.style.removeProperty('margin-top');
    el.style.removeProperty('margin-bottom');
    setTimeout(() => {
      el.style.removeProperty('opacity');
    }, duration * 0.6);
    setTimeout(() => {
      el.style.removeProperty('height');
      el.style.removeProperty('overflow');
      el.style.removeProperty('transition-duration');
      el.style.removeProperty('transition-property');
      el.style.removeProperty('transition-timing-function');
    }, duration);
  };
  // * slideToggle
  const slideToggle = (el, duration = 300) => {
    if (window.getComputedStyle(el).display === 'none') {
      return slideDown(el, duration);
    } else {
      return slideUp(el, duration);
    }
  };
  // * event listeners
  /*
  <button data-animation="slide-toggle" aria-expanded="false">
    <span>タイトル</span>
    <div>スライドする内容</div>
  </button>
  */
  document.querySelectorAll('[data-animation="slide-toggle"]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const target = trigger.children[1];
      const expanded = trigger.getAttribute('aria-expanded') === 'true';
      if (target) {
        slideToggle(target, 500);
        trigger.classList.toggle('is-open');
        trigger.setAttribute('aria-expanded', !expanded);
      }
    });
  });

  // ! Swiper FV ***********
  /*
  <div data-animation="slide-fv">
    <div class="swiper">
      <ul class="swiper-wrapper">
        <li class="swiper-slide">Slide 1</li>
        <li class="swiper-slide">Slide 2</li>
        <li class="swiper-slide">Slide 3</li>
      </ul>
    </div>
  </div>
  */
  let swiperFvs = document.querySelectorAll('[data-animation="slide-fv"] .swiper');
  if (swiperFvs.length > 0) {
    const swiperFv = new Swiper('[data-animation="slide-fv"] .swiper', {
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

  // ! Swiper ***********
  /*
  <div data-animation="slide-swiper">
    <div class="swiper">
      <ul class="swiper-wrapper">
        <li class="swiper-slide">Slide 1</li>
        <li class="swiper-slide">Slide 2</li>
        <li class="swiper-slide">Slide 3</li>
      </ul>
    </div>
  </div>
  */
  const swiperEl = document.querySelectorAll('[data-animation="slide-swiper"] .swiper');
  if (swiperEl.length > 0) {
    const swiper = new Swiper('[data-animation="slide-swiper"] .swiper', {
      loop: true,
      speed: 600,
      slidesPerView: 1,
      spaceBetween: 20,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      pagination: {
        el: '[data-animation="slide-swiper"] .swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '[data-animation="slide-swiper"] .swiper-button-next',
        prevEl: '[data-animation="slide-swiper"] .swiper-button-prev',
      },
      breakpoints: {
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
      },
      on: {
        init() {
          console.log('Swiper initialized');
        },
        slideChange() {
          console.log('Active slide:', this.activeIndex);
        },
      },
    });
  }

  // ! クエリパラメータでリンク先の表示要素を切り替える ***********
  /*
  <!-- タブボタン -->
  <button data-tab="tab1">タブ1</button>
  <button data-tab="tab2">タブ2</button>
  <button data-tab="tab3">タブ3</button>

  <!-- タブコンテンツ -->
  <div data-tab-content="tab1" id="tab1">コンテンツ1</div>
  <div data-tab-content="tab2" id="tab2">コンテンツ2</div>
  <div data-tab-content="tab3" id="tab3">コンテンツ3</div>

  <!-- URL例 -->
  https://example.com/page.html?id=tab2
  */
  if (false) {
    const urlParams = new URLSearchParams(window.location.search);
    const linkId = urlParams.get('id');

    if (linkId) {
      // すべてのタブとコンテンツから is-open を削除
      const removeActiveClass = (selector) => {
        document.querySelectorAll(selector).forEach((element) => {
          element.classList.remove('is-open');
        });
      };
      removeActiveClass('[data-tab]');
      removeActiveClass('[data-tab-content]');

      // 対象のタブとコンテンツに is-open を追加
      const tabSelectorTarget = document.querySelector(`[data-tab='${linkId}']`);
      const tabContentTarget = document.getElementById(linkId);

      if (tabSelectorTarget && tabContentTarget) {
        tabSelectorTarget.classList.add('is-open');
        tabContentTarget.classList.add('is-open');
      }
    }
  }

  // ! スムーズスクロール ***********
  // * 同一ページ内のアンカーリンク
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (e) => {
      const targetId = anchor.getAttribute('href');
      if (targetId.length > 1 && document.querySelector(targetId)) {
        e.preventDefault();
        const header = document.querySelector('[data-header]');
        const headerHeight = header ? header.offsetHeight : 0;
        const targetElement = document.querySelector(targetId);
        const rect = targetElement.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const targetPosition = rect.top + scrollTop - headerHeight * 1.5;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth',
        });
      }
    });
  });
  // * ページ読み込み時にURLのハッシュをチェック（下層ページから遷移した場合）
  window.addEventListener('load', () => {
    if (window.location.hash) {
      const targetId = window.location.hash;
      if (document.querySelector(targetId)) {
        // 少し遅延させてからスクロール（レイアウトが確定するまで待つ）
        setTimeout(() => {
          smoothScrollToTarget(targetId);
        }, 100);
      }
    }
  });
  // * スムーズスクロールの共通関数
  function smoothScrollToTarget(targetId) {
    const header = document.querySelector('[data-header]');
    const headerHeight = header ? header.offsetHeight : 0;
    const targetElement = document.querySelector(targetId);

    if (targetElement) {
      const rect = targetElement.getBoundingClientRect();
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      const targetPosition = rect.top + scrollTop - headerHeight * 1.5;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth',
      });
    }
  }

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
  /*
  <div data-scrollable>
    <table></table>
  </div>
  */
  if (false) {
    const currentLanguage = document.documentElement.lang;
    let scrollableText;
    if (currentLanguage === 'ja') {
      scrollableText = 'スクロールできます';
    } else {
      scrollableText = 'Scroll';
    }
    new ScrollHint('[data-scrollable]', {
      scrollHintIconAppendClass: 'scroll-hint-icon-white',
      remainingTime: 3000, //ms
      offset: -1, // スクロールできなくても表示する
      i18n: {
        scrollable: scrollableText,
      },
    });
    document.querySelectorAll('[data-scrollable]').forEach((scrollable) => {
      scrollable.style.overflowY = 'hidden';
    });
  }

  // ! お問い合わせページで郵便番号から住所を自動入力 ***********
  if (false) {
    const zipCodeField = document.querySelector('input[name="your-zip-code"]');
    if (zipCodeField) {
      const addressField = document.querySelector('input[name="your-address"]');
      zipCodeField.addEventListener('blur', function () {
        const zipCode = zipCodeField.value.replace('-', '');
        if (zipCode.length === 7) {
          fetch(`https://api.zipaddress.net/?zipcode=${zipCode}`)
            .then((response) => response.json())
            .then((data) => {
              if (data.code === 200) {
                addressField.value = data.data.fullAddress;
                addressField.dispatchEvent(new Event('input')); // 住所入力イベントを検知できるように手動発火する
                const confirmElement = document.getElementById('your-address-confirm');
                if (confirmElement) {
                  confirmElement.textContent = data.data.fullAddress;
                }
              } else {
                alert('郵便番号が正しいかご確認ください。');
              }
            });
        }
      });
    }
  }

  // ! Pin Animation ***********
  const pinTarget = document.querySelector('[data-animation="pin-target"]');
  const pinTrigger = document.querySelector('[data-animation="pin-trigger"]');
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

  // ! Parallax Image Animation ***********
  /*
  .p-xxx__image {
    aspect-ratio: width / height;
    overflow: hidden;
    width: 100%;
    img {
      width: 100%;
      height: 120%;
      object-fit: cover;
      transform: translateY(-10%);
    }
  }
  */
  const parallaxImages = document.querySelectorAll('[data-animation="parallax-image"]');
  if (parallaxImages.length > 0) {
    parallaxImages.forEach((element) => {
      gsap.to(element, {
        yPercent: 20,
        ease: 'power1.inOut',
        scrollTrigger: {
          trigger: element,
          start: 'top bottom',
          end: 'bottom top',
          scrub: 1.5,
          // markers: true,
        },
      });
    });
  }

  // ! Clip Path Animation ***********
  // * 左からClipPathでスライドイン
  const clipDowns = document.querySelectorAll('[data-animation="clip-down"]');
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

  // * 右からClipPathでスライドイン
  const clipRights = document.querySelectorAll('[data-animation="clip-right"]');
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
  const fadeIns = document.querySelectorAll('[data-animation="fade-in"]');
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
  const fadeInRights = document.querySelectorAll('[data-animation="fade-in-right"]');
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
  const fadeInLefts = document.querySelectorAll('[data-animation="fade-in-left"]');
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
  const fadeInBottoms = document.querySelectorAll('[data-animation="fade-in-bottom"]');
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
  // * 右から順番にフェードイン（ul/ol要素にクラスをつける）
  document.querySelectorAll('[data-animation="fade-in-right-staggers"]').forEach((group) => {
    const targets = group.querySelectorAll(':scope > *');
    if (targets.length > 0) {
      gsap.from(targets, {
        scrollTrigger: {
          trigger: group,
          start: 'top bottom-=70',
          once: true,
        },
        autoAlpha: 0,
        x: 100,
        duration: 0.7,
        stagger: 0.1,
      });
    }
  });
  // * 下から順番にフェードイン(ul/ol要素にクラスをつける)
  document.querySelectorAll('[data-animation="fade-in-bottom-staggers"]').forEach((group) => {
    const targets = group.querySelectorAll(':scope > *');
    if (targets.length > 0) {
      gsap.from(targets, {
        scrollTrigger: {
          trigger: group,
          start: 'top bottom-=70',
          once: true,
        },
        autoAlpha: 0,
        y: 100,
        duration: 0.7,
        stagger: 0.1,
      });
    }
  });

  // ! Split Text Animation ***********
  // * 1文字ずつ下からフェードイン
  document.fonts.ready.then(() => {
    document.querySelectorAll('[data-animation="split-bottoms"]').forEach((element) => {
      const split = new SplitText(element, { type: 'chars' });
      if (split.chars && split.chars.length > 0 && isSP()) {
        gsap.from(split.chars, {
          duration: 0.3,
          autoAlpha: 0,
          y: 20,
          ease: 'power2.out',
          stagger: 0.03,
          scrollTrigger: {
            trigger: element,
            start: 'top bottom',
            once: true,
            // markers: true, // マーカー表示
          },
        });
      }
    });
  });

  // ! ローディングアニメーション ***********
  /*
  <div data-animation="loading" class="is-active">
    <div data-animation="loading-bg"></div>
    <div data-animation="loading-logo">
      <img src="logo.svg" alt="Logo">
    </div>
  </div>
  */
  const loading = document.querySelector('[data-animation="loading"]');
  if (loading) {
    const loadingTL = gsap.timeline({
      onComplete: function () {
        // Flash of Invisible Content（FOIC）対策
        loading.classList.remove('is-active');
        sessionStorage.setItem('hasVisited', 'true'); // 訪問を記録
      },
    });

    loadingTL.to('[data-animation="loading-title"]', { autoAlpha: 0, duration: 0.5, ease: 'power4.inOut' }, '<');
  }
});
