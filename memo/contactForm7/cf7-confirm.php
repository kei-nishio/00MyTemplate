<!-- * Form Contact Start -->
<div class="confirm-form">
  <dl class="confirm-form__list">
    <!-- * Radio -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">お問い合わせ種別</dt>
      <dd class="confirm-form__description">[multiform your-radio id:f-radio]</dd>
    </div>
    <!-- * Name -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">お名前</dt>
      <dd class="confirm-form__description">[multiform your-name id:f-name]</dd>
    </div>
    <!-- * Kana -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">お名前（フリガナ）</dt>
      <dd class="confirm-form__description">[multiform your-kana id:f-kana]</dd>
    </div>
    <!-- * address -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">ご住所</dt>
      <dd class="confirm-form__description">[multiform your-address id:f-address]</dd>
    </div>
    <!-- * E-mail -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">メールアドレス</dt>
      <dd class="confirm-form__description">[multiform your-email id:f-mail]</dd>
    </div>
    <!-- * Telephone -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">電話番号</dt>
      <dd class="confirm-form__description">[multiform your-tel id:f-tel]</dd>
    </div>
    <!-- * Textarea -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">お問い合わせ内容</dt>
      <dd class="confirm-form__description">[multiform your-message id:f-textarea]</dd>
    </div>
  </dl>
  <div class="confirm-form__button">
    <!-- * Previous -->
    <div class="confirm-form__previous">
      <div class="button-submit button-submit--white">[previous "訂正する"]</div>
    </div>
    <!-- * Submit -->
    <div class="confirm-form__submit">
      <div class="button-submit">[submit "送信する"]</div>
      [multistep your-multistep02 last_step send_email "http://localhost:3000/contact/confirm/thanks/"]
      <!-- [multistep your-multistep02 last_step send_email "/contact/confirm/"] -->
    </div>
  </div>
</div>
<!-- * Form Contact End -->