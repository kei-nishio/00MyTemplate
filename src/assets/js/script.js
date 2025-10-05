document.addEventListener('DOMContentLoaded', () => {
  // ! 基本設定 ***********
  gsap.registerPlugin(ScrollTrigger); // ScrollTrigger
  gsap.registerPlugin(SplitText); // SplitText
  const breakpoint = 768;
  function isSP() {
    return window.innerWidth < breakpoint;
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
    constructor({ header, hamburger, drawer, mask, breakpoint = 768 }) {
      this.header = document.querySelector(header);
      this.hamburger = document.querySelector(hamburger);
      this.drawer = document.querySelector(drawer);
      this.mask = document.querySelector(mask);
      this.breakpoint = breakpoint;

      this.activeClass = 'is-active'; // 再利用しやすいクラス名
      this.init();
    }

    init() {
      // 各種イベントの登録
      this.addEventListeners();
    }

    addEventListeners() {
      this.hamburger.addEventListener('click', (e) => this.handleHamburgerClick(e));
      this.mask.addEventListener('click', () => this.closeDrawer());
      document.addEventListener('click', (e) => this.handleDocumentClick(e));
      window.addEventListener('resize', () => this.handleResize());

      // ドロワー内のリンククリック時にドロワーを閉じる
      const links = this.drawer.querySelectorAll('a[href]');
      links.forEach((link) => {
        link.addEventListener('click', () => this.closeDrawer());
      });
    }

    handleHamburgerClick(e) {
      e.stopPropagation(); // クリックイベントの伝播を防ぐ
      this.toggleDrawer();
    }

    handleDocumentClick(e) {
      // ドロワーが開いている状態で、ドロワー外をクリックした場合に閉じる
      if (this.isDrawerOpen() && !this.drawer.contains(e.target) && !this.hamburger.contains(e.target)) {
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
      return this.hamburger.classList.contains(this.activeClass);
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
    header: '.js-header',
    hamburger: '.js-hamburger',
    drawer: '.js-drawer',
    mask: '.js-drawer-mask',
    breakpoint: 768,
  });

  // ! TOPにスクロールするボタン ***********
  const toTopButton = document.querySelector('.js-to-up');
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
  const header = document.querySelector('.js-header');
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
  document.querySelectorAll('.js-slide-toggle').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const target = trigger.children[1];
      if (target) {
        slideToggle(target, 500);
        trigger.classList.toggle('is-open');
      }
    });
  });

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
        el: '.js-swiper .swiper-pagination', // ページネーション要素のセレクタ
        clickable: true, // ページネーションをクリック可能にする
        dynamicBullets: true, // 動的なドットサイズ
        type: 'bullets', // 'bullets', 'fraction', 'progressbar', 'custom'
        renderBullet: function (index, className) {
          return `<span class="${className}">${index + 1}</span>`;
        },
      },

      // **5. ナビゲーションボタン (前後ボタン)**
      navigation: {
        nextEl: '.js-swiper .swiper-button-next', // 次のボタンのセレクタ
        prevEl: '.js-swiper .swiper-button-prev', // 前のボタンのセレクタ
      },

      // **6. スクロールバー**
      scrollbar: {
        el: '.js-swiper .swiper-scrollbar', // スクロールバーのセレクタ
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
    document.querySelectorAll('.js-scrollable').forEach((scrollable) => {
      scrollable.style.overflowY = 'hidden';
    });
  }

  // ! お問い合わせページ限定 ***********
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

  // ! Parallax Image Animation ***********
  const parallaxImages = document.querySelectorAll('.js-parallax-image');
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
  // * sample CSS
  // .p-xxx__image {
  //   aspect-ratio: width / height;
  //   overflow: hidden;
  //   width: 100%;
  //   outline: 4px solid blue;
  //   img {
  //     width: auto;
  //     height: 120%;
  //     object-fit: cover;
  //     transform: translateY(-20%);
  //     outline: 4px solid red;
  //   }
  // }

  // ! Clip Path Animation ***********
  // * 左からClipPathでスライドイン
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

  // * 右からClipPathでスライドイン
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
  // * 右から順番にフェードイン（ul/ol要素にクラスをつける）
  document.querySelectorAll('.js-fadeInRightStaggers').forEach((group) => {
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
  document.querySelectorAll('.js-fadeInBottomStaggers').forEach((group) => {
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
    document.querySelectorAll('.js-splitBottoms').forEach((element) => {
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
  const loading = document.querySelector('.js-loading');
  if (loading) {
    const loadingTL = gsap.timeline({
      onComplete: function () {
        // Flash of Invisible Content（FOIC）対策
        loading.classList.remove('is-active');
        sessionStorage.setItem('hasVisited', 'true'); // 訪問を記録
      },
    });

    loadingTL
      .to('.js-loading-logo', { autoAlpha: 1, duration: 0.5 })
      .to('.js-loading-bg', { autoAlpha: 0, duration: 0.5, ease: 'power4.inOut' }, '<');
  }
});
