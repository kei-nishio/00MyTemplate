<!-- * Form Contact Start -->
<div class="contact-form">
  <dl class="contact-form__list">
    <!-- * Radio -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-radio">
          <span class="contact-form__required">必須</span>
          お問い合わせ種別
        </label>
      </dt>
      <dd class="contact-form__description">
        [radio your-radio id:f-radio use_label_element default:1 "お問い合わせ種別01" "お問い合わせ種別02"]
      </dd>
    </div>
    <!-- * Name -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-name">
          <span class="contact-form__required">必須</span>
          お名前
        </label>
      </dt>
      <dd class="contact-form__description">
        [text* your-name id:f-name placeholder "例）　山田　太郎"]
      </dd>
    </div>
    <!-- * kana -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-kana">
          <span class="contact-form__required">必須</span>
          お名前（フリガナ）
        </label>
      </dt>
      <dd class="contact-form__description">
        [text* your-kana id:f-kana placeholder "例）　ヤマダ　タロウ"]
      </dd>
    </div>
    <!-- * address -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-address">
          <span class="contact-form__required contact-form__required--not">任意</span>
          ご住所
        </label>
      </dt>
      <dd class="contact-form__description">
        [text your-address id:f-address placeholder "例）　北海道札幌市xxxyyy123456"]
      </dd>
    </div>
    <!-- * E-mail -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-mail">
          <span class="contact-form__required">必須</span>
          メールアドレス
        </label>
      </dt>
      <dd class="contact-form__description">
        [email* your-email id:f-mail placeholder "例）　prdtheapeutics@example.jp"]
      </dd>
    </div>
    <!-- * Telephone -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-tel">
          <span class="contact-form__required">必須</span>
          電話番号
        </label>
      </dt>
      <dd class="contact-form__description">
        [tel* your-tel id:f-tel placeholder "例）　012-3456-7890"]
      </dd>
    </div>
    <!-- * Textarea -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-textarea">
          <span class="contact-form__required">必須</span>
          お問い合わせ内容
        </label>
      </dt>
      <dd class="contact-form__description">
        [textarea* your-message id:f-textarea placeholder "こちらにお問い合わせに内容をご入力お願いいたします。"]
      </dd>
    </div>
  </dl>
  <!-- * Submit -->
  <div class="contact-form__confirm">
    <div class="button-submit">[submit "入力内容のご確認"]</div>
    [multistep your-multistep01 first_step "http://localhost:3000/contact/confirm/"]
    <!-- [multistep your-multistep01 first_step "/contact/confirm/"] -->
  </div>
</div>
<!-- * Form Contact End -->