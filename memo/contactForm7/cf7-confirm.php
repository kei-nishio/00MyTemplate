<!-- * Form Contact Start -->
<div class="p-form">
  <dl class="p-form__list">
    <!-- * Company -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          会社名
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [multiform your-company id:f-company]
      </dd>
    </div>
    <!-- * Name -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          ご担当者名
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [multiform your-name id:f-name]
      </dd>
    </div>
    <!-- * URL -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          URL
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [multiform your-url id:f-url]
      </dd>
    </div>
    <!-- * Telephone -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          電話番号
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [multiform your-tel id:f-tel]
      </dd>
    </div>
    <!-- * E-mail -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          メールアドレス
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [multiform your-email id:f-mail]
      </dd>
    </div>
    <!-- * Select -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          お問い合わせの種類（セレクト）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">[multiform your-select id:f-select]</dd>
    </div>
    <!-- * Checkbox -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          お問い合わせの種類（チェックボックス）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">[multiform your-checkbox id:f-checkbox]</dd>
    </div>
    <!-- * Radio -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          お問い合わせの種類（ラジオボタン）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">[multiform your-radio id:f-radio]</dd>
    </div>
    <!-- * Textarea -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label>
          お問い合わせ内容
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">[multiform your-message id:f-textarea]</dd>
    </div>
    <!-- * zip-code -->
    <div class="p-form__item">
      <dt class="p-form__term">郵便番号</dt>
      <dd class="p-form__description">[multiform your-zip-code id:f-zip-code]</dd>
    </div>
    <!-- * address -->
    <div class="p-form__item">
      <dt class="p-form__term">ご住所</dt>
      <dd class="p-form__description">[multiform your-address id:f-address]</dd>
    </div>
  </dl>

  <div class="p-form__button">
    <!-- * Previous -->
    <div class="p-form__previous">
      <div class="c-button-normal">[previous "訂正する"]</div>
    </div>
    <!-- * Submit -->
    <div class="p-form__submit">
      <div class="c-button-normal">[submit "送信する"]</div>
      [multistep your-multistep02 last_step send_email "http://localhost:3000/contact/thanks"]
      <!-- [multistep your-multistep02 last_step send_email "hogehoge/contact/thanks"] -->
    </div>
  </div>
</div>
<!-- * Form Contact End -->