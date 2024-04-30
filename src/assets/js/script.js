jQuery(function ($) {
  // ! 「jQuery(function ($) {}」内であればWordpressでも「$」が使用可能になる
  // ***********
  // ** 定数管理
  // ***********

  // ***********
  // ** vanillaJS
  // ***********
  // ** 初期設定
  // ***********
  // ** 強制リロード
  // ***********
  function forceReload() {
    window.location.href = window.location.href; // 現在のURLにリダイレクト
  }

  // ***********
  // ** jQuery
  // ***********
  // ** 初期設定
  // ***********

  // ***********
  // ** GSAP
  // ***********
  // ** 初期設定
  // ***********
  gsap.registerPlugin(ScrollTrigger);
  // ** z-indexの設定
  // ***********
  function zIndexAdd() {
    gsap.utils.toArray(".js-scroll-switch-items__image-item").forEach((item, index, array) => {
      item.style.zIndex = `${array.length - index}`;
    });
  }

  // ***********
  // ** Swiper
  // ***********
  // ** 初期設定
  // ***********

  // *********** 以下、未整理 ***********

  // ***********
  // * ヘッダー高さ分を考慮した遷移
  // ***********
  function fncHeaderDown(linkAnchor, durationTime, easeType, a) {
    $("html, body").animate(
      {
        scrollTop: $("#" + linkAnchor).offset().top - headerHeightDefault * a,
      },
      durationTime,
      easeType
    );
  }

  // ***********
  // * Promise用 wait関数
  // ***********
  function wait(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }

  // ! ***********
  // ! 共通
  // ! ***********
  // ***********
  // * 画面幅を取得する
  // ***********
  const windowWidthDefault = $(window).width();
  let windowWidth = $(window).width();
  $(window).on("resize", function () {
    windowWidth = $(this).width();
  });

  // ***********
  // * ヘッダー高さを取得する
  // ***********
  const headerHeightDefault = $("header").height();

  // ***********
  // * URLにアンカーがある場合は読込後にヘッダー高さ分を下げる
  // ***********
  $("a").on("click", function () {
    let targetHref = $(this).attr("href");
    if (targetHref && targetHref.startsWith("#")) {
      // console.log("targetHref: " + targetHref);
      let linkAnchor = targetHref.substring(1); // 「#」以下を変数として抜き出す
      fncHeaderDown(linkAnchor, 100, "linear", 1.1);
    }
    // リンク先が#で始まっていない場合は何もしない
  });

  // ***********
  // * 遷移先にアンカーがあるときは読み込み後にヘッダー高さを考慮して移動する;
  // ***********
  $(window).on("load", function () {
    let targetHref = window.location.href;
    if (targetHref && targetHref.includes("#")) {
      // console.log("targetHref: " + targetHref);
      let linkAnchor = window.location.href.split("#")[1];
      fncHeaderDown(linkAnchor, 100, "linear", 1.1);
    }
  });

  // ! ***********
  // ! 個別動作
  // ! ***********
  // ***********
  // * ローディングアニメーション
  // ***********
  if (window.location.pathname === "/") {
    // 初回のみローディングアニメーションを実行
    if (!sessionStorage.getItem("visited")) {
      // console.log("loading");
      $("body").css({ "overflow-y": "scroll", position: "fixed" });
      const loadingTop = $(".js-load");
      const loadingFlex = $(".js-loading-flex");
      const loadingLeft = $(".js-loading-left");
      const loadingRight = $(".js-loading-right");

      loadingTop.css({ display: "block" });
      loadingLeft.css({ top: "100%" });
      loadingRight.css({ top: "calc(100% + 80px)", right: "-20%" });
      // ローディング画面前に背景を写さないようにするため
      loadingFlex.css({ display: "flex" });

      const loadingStartTime = 1000;
      const imageAnimationTime = 1500;
      const loadingFadeOutTime = 750;

      wait(loadingStartTime)
        .then(function () {
          loadingLeft.animate(
            {
              top: 0,
            },
            imageAnimationTime
          );
          loadingRight.animate(
            {
              top: 0,
              right: 0,
            },
            imageAnimationTime
          );

          return wait(imageAnimationTime);
        })
        .then(function () {
          loadingTop.fadeOut(loadingFadeOutTime);
          $("body").css({ "overflow-y": "", position: "" });
        });
      sessionStorage.setItem("visited", "true");
    }
  }

  // ***********
  // * ハンバーガーメニューとドロワーメニュー
  // ***********
  $(".js-hamburger , .js-drawer-menu").click(function () {
    // console.log("hamburger");
    $(".js-hamburger").toggleClass("is-active");
    $(".js-drawer-menu").fadeToggle();

    if ($(".js-hamburger").hasClass("is-active")) {
      $("html, body").css({ overflow: "hidden" });
    } else {
      $("html, body").css({ overflow: "auto" });
    }
  });

  // ***********
  // * ファーストビューのスライダー
  // ***********
  const mainViewSwiper = new Swiper(".js-main-view-swiper .swiper", {
    direction: "horizontal",
    loop: true,
    slidesPerView: 1,
    effect: "fade",
    speed: 3000,
    allowTouchMove: false,
    autoplay: {
      delay: 6000,
    },
  });

  // ***********
  // * キャンペーンのスライダー
  // ***********
  const campaignSwiper = new Swiper(".js-campaign-swiper .swiper", {
    loop: true,
    slidesPerView: "auto",
    spaceBetween: 24,
    grabCursor: true,
    breakpoints: {
      768: {
        spaceBetween: 16,
        slidesPerView: 3,
      },
      1024: {
        spaceBetween: 40,
        slidesPerView: 3,
      },
    },

    // Navigation arrows
    navigation: {
      nextEl: ".js-campaign-swiper .swiper-button-next",
      prevEl: ".js-campaign-swiper .swiper-button-prev",
    },
  });

  // ***********
  // * 横から背景画像→imageとなるアニメーション
  // * 参考：https://design-remarks.com/image-after-color/
  // ***********
  $(".js-colorbox").each(function () {
    $(this).append('<div class="is-color"></div>');
    let color = $(this).find($(".is-color"));
    let image = $(this).find("img");
    let counter = 0;
    let speed = 400;

    color.css("width", "0%");
    image.css("opacity", "0");
    //inviewを使って背景色が画面に現れたら処理をする
    color.on("inview", function () {
      if (counter == 0) {
        $(this)
          .delay(50)
          .animate({ width: "100%" }, speed, function () {
            image.css("opacity", "1");
            $(this).css({ left: "0", right: "auto" });
            $(this).animate({ width: "0%" }, speed);
          });
        counter = 1;
      }
    });
  });

  // ***********
  // * スクロールトップにアニメーションで移動する
  // ***********
  $(".js-scroll-top-button").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "normal");
    return false;
  });

  // ***********
  // * ページネーションをクリックしたときにクリックしたものだけをactiveにする
  // ***********
  $(".js-pagination-number").click(function () {
    $(".js-pagination-number").removeClass("is-active");
    $(this).addClass("is-active");
  });

  // ***********
  // * About us のギャラリーモーダル
  // ***********
  const modal = $(".js-modal");
  const open = $(".js-modal-open");
  const close = $(".js-modal-close");
  const specifiedWidth = 768;
  // 768px以上で開くボタンをクリックしたらモーダルを表示する
  open.on("click", function () {
    if (windowWidth >= specifiedWidth) {
      // console.log("open modal");
      let imageSrc = $(this).children("img").attr("src");
      let imageAlt = $(this).children("img").attr("alt");
      let newImg = $("<img>", {
        class: "modal__img js-modal-img",
        src: imageSrc,
        alt: imageAlt,
      });
      $(".js-modal-imgBox").append(newImg);
      modal.fadeIn(500, function () {
        $("body").css({ overflow: "hidden" });
      });
    }
  });
  // 閉じるボタンをクリックしたらモーダルを閉じる
  close.add(modal).on("click", function () {
    // console.log("close modal");
    modal.fadeOut(500, function () {
      $(".js-modal-img").remove();
      $("body").css({ overflow: "" });
    });
  });

  // ***********
  // * カテゴリータグをactiveにする & Information記事を切り替える
  // ***********
  const categoryButton = $(".js-category-button");
  const categoryContent = $(".js-category-content");
  // 初期表示
  categoryButton.eq(0).addClass("is-active");
  categoryContent.eq(0).addClass("is-active");
  // ボタンをクリックしたらactiveにする
  categoryButton.on("click", function () {
    // console.log("categoryButton");
    let index = categoryButton.index(this);
    categoryButton.removeClass("is-active");
    categoryContent.removeClass("is-active");
    $(this).addClass("is-active");
    categoryContent.eq(index).addClass("is-active");
  });

  // ***********
  // * target-id付リンクを踏んだ時にリンク先のInformation記事を切り替える
  // ***********
  let linkId = new URL(window.location.href).searchParams.get("id");
  if (linkId) {
    // console.log(new URL(window.location.href));
    // console.log(linkId);
    categoryButton.removeClass("is-active");
    categoryContent.removeClass("is-active");
    let button = $("[data-target='" + linkId + "']");
    let content = $("#" + linkId);
    button.addClass("is-active");
    content.addClass("is-active");
  }

  // ***********
  // * アーカイブ年をクリックしたときにアコーディオンする
  // ***********
  // 初期表示
  const archiveButton = $(".js-archive-button");
  archiveButton.eq(0).addClass("is-active");
  // 初期表示　is-activeの次の要素を表示する
  const archiveButtonActive = $(".js-archive-button.is-active");
  archiveButtonActive.next().css({ display: "block" });
  // ボタンをクリックしたらアコーディオンする
  archiveButton.on("click", function () {
    $(this).toggleClass("is-active");
    $(this).next().slideToggle(300);
  });

  // ***********
  // * FAQのQをクリックしたときにアコーディオンする
  // ***********
  const faqQuestion = $(".page-faq__question");
  const faqAnswer = $(".page-faq__answer");
  faqQuestion.addClass("is-open");
  faqAnswer.css("display", "block");
  faqQuestion.on("click", function () {
    $(this).toggleClass("is-open");
    $(this).next().slideToggle(300);
  });

  // ***********
  // * cf7フォームのバリデーション
  // ***********
  $(document).on("wpcf7invalid", function (event) {
    $(".js-form-error").addClass("is-active");
    window.scrollTo({
      top: $(".js-form-error").offset().top - headerHeightDefault,
    });
  });

  // ! ***********
  // ! 今回は利用しない
  // ! ***********

  // ***********
  // * ナビゲーションクリック時にスーッと移動する
  // ***********
  // const headerHeight = $(".js-header").height();
  // $('a[href^="#"]').click(function () {
  //   const speed = 600;
  //   let href = $(this).attr("href");
  //   let target = $(href == "#" || href == "" ? "html" : href);

  //   //移動先のpadding-topを取得
  //   let paddingValue = parseInt(target.css("padding-top"), 10);

  //   // ヘッダーの高さ分下げる
  //   let position = target.offset().top - headerHeight + paddingValue;
  //   $("body,html").animate({ scrollTop: position }, speed, "swing");
  //   return false;
  // });

  // ***********
  // * ～px or 100vh後にscroll-top-buttonを表示させる
  // ***********
  // $(window).scroll(function () {
  //   // let scrollThreshold = $(this).height(); //=100vh
  //   let scrollThreshold = 700;
  //   let scrollPosition = $(this).scrollTop();
  //   if (scrollPosition > scrollThreshold) {
  //     $(".js-scroll-top-button").fadeIn();
  //   } else {
  //     $(".js-scroll-top-button").fadeOut();
  //   }
  // });
});
