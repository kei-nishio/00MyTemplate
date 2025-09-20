<?php get_header(); ?>
<main class="main">
  <div class="l-lower-heading">
    <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "contact", "title" => "お問い合わせ"]); ?>
  </div>
  <?php echo do_shortcode('[aioseo_breadcrumbs]'); ?>
  <?php get_template_part('/parts/c-breadcrumb');  ?>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="l-lower-top p-sub-contact">
        <div class="p-sub-contact__inner l-inner">
          <div class="p-sub-contact__form">
            <?php if (true): ?>
              <?php echo do_shortcode('[contact-form-7 id="41009c7" title="inquiry"]'); ?>
            <?php else: ?>
              <p style="text-align: center; margin-top: 20px; ">ここにショートコードが入ります。</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else : ?>
    <p style="text-align: center; margin-top: 20px; ">記事が投稿されていません。</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 入力値を確認画面に反映する関数
    function updateConfirmationScreen(id, value, append = false) {
      const confirmElement = document.getElementById(`${id}-confirm`);
      if (confirmElement) {
        // 追加の場合は既存のテキストに値を追加し、そうでない場合は新しい値で上書き
        confirmElement.textContent = append ? confirmElement.textContent + value + " / " : value;
      }
    }

    // ラジオボタンの選択値を確認画面に初期反映
    document.querySelectorAll('[type="radio"]:checked').forEach(radio => {
      const id = radio.closest('[id]') ? radio.closest('[id]').id : null;
      if (id) {
        updateConfirmationScreen(id, radio.value);
      }
    });

    // チェックボックスの選択値を確認画面に初期反映
    document.querySelectorAll('[type="checkbox"]:checked').forEach(checkbox => {
      const id = checkbox.closest('[id]') ? checkbox.closest('[id]').id : null;
      if (id) {
        updateConfirmationScreen(id, checkbox.value, true);
      }
    });

    // 入力フィールドの変更イベントを処理
    document.querySelectorAll('.p-form__item input, .p-form__item select, .p-form__item textarea').forEach(input => {
      input.addEventListener('change', function() {
        const id = this.id || (this.closest("[id]") ? this.closest("[id]").id : null);
        const type = this.getAttribute("type");
        if (id) {
          if (type === "radio") {
            updateConfirmationScreen(id, this.value);
          } else if (type === "checkbox") {
            updateConfirmationScreen(id, this.value, this.checked);
          } else {
            updateConfirmationScreen(id, this.value);
          }
        }
      });
    });

    // ページ内の表示領域切替を行う関数
    function navigateAreas(showArea, hideArea) {
      if (showArea && hideArea) {
        showArea.style.display = 'block';
        hideArea.style.display = 'none';
        window.scrollTo(0, showArea.getBoundingClientRect().top);
      }
    }

    const formArea = document.querySelector(".js-formArea");
    const confirmArea = document.querySelector(".js-confirmArea");
    const confirmButton = document.querySelector(".js-confirmButton");
    const backButton = document.querySelector(".js-backButton");

    // 確認ボタンと戻るボタンのイベントハンドラ設定
    confirmButton?.addEventListener('click', () => navigateAreas(confirmArea, formArea));
    backButton?.addEventListener('click', () => navigateAreas(formArea, confirmArea));

    // 必須項目とプライバシーポリシーの同意の状態に基づく確認ボタンの有効化/無効化
    const requiredInputs = document.querySelectorAll('[aria-required="true"]');
    const privacyCheckbox = document.querySelector('input[name="your-acceptance"]');

    requiredInputs.forEach(input => {
      input.addEventListener('input', updateButtonState);
    });
    privacyCheckbox.addEventListener('change', updateButtonState);

    function updateButtonState() {
      // 全ての必須項目が表示されており、かつ入力されているか確認
      const allFilled = Array.from(requiredInputs).every(input => {
        // 入力フィールドが 'wpcf7cf-hidden' クラスを持つ親要素に属していないか確認
        const isHidden = input.closest('.wpcf7cf-hidden');
        return !isHidden && input.value.trim() !== "";
      });

      // 確認ボタンの有効/無効状態を更新
      const isPrivacyAccepted = privacyCheckbox.checked;
      confirmButton.disabled = !(allFilled && isPrivacyAccepted);
    }

    // 送信完了時のリダイレクト処理
    document.addEventListener('wpcf7mailsent', () => {
      window.location.href = '/inquiry/thanks/';
    }, false);

    // エラー時は入力画面を表示して確認画面を隠す
    document.addEventListener('wpcf7invalid', function(event) {
      const formArea = document.querySelector(".js-formArea");
      const confirmArea = document.querySelector(".js-confirmArea");
      if (formArea && confirmArea) {
        formArea.style.display = 'block';
        confirmArea.style.display = 'none';
        // 入力エリアの一番上にスクロールする
        window.scrollTo({
          top: formArea.getBoundingClientRect().top + window.scrollY,
          behavior: 'smooth'
        });
      }
    });

    // 入力モード制御（スマホ対応）
    // <input type="email, tel, number">は自動で入力制御が切り替わるため不要
    // <input type="text">で郵便番号やなど数字入力欄に対して設定する
    document.getElementById('your-email-confirmation')?.setAttribute('inputmode', 'email');
    document.getElementById('your-zip')?.setAttribute('inputmode', 'numeric');

  });
</script>