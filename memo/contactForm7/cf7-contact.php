<!-- ! 確認画面の遷移は案件に合わせて選択すること -->

<!-- * Form Contact Start -->
<div class="p-form">
  <dl class="p-form__list">
    <!-- * Company -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-company">
          会社名
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [text* your-company id:f-company placeholder "例）株式会社○○"]
        <p class="p-form__example">例）株式会社○○</p>
      </dd>
    </div>
    <!-- * Name -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-name">
          ご担当者名
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [text* your-name id:f-name placeholder "例）田中太郎"]
        <p class="p-form__example">例）田中太郎</p>
      </dd>
    </div>
    <!-- * URL -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-url">
          URL
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [url* your-url id:f-url placeholder "例）https://www.hogehoge.com"]
        <p class="p-form__example">例）https://www.hogehoge.com</p>
      </dd>
    </div>
    <!-- * Telephone -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-tel">
          電話番号
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [tel* your-tel id:f-tel placeholder "例）000-0000-0000"]
        <p class="p-form__example">例）000-0000-0000</p>
      </dd>
    </div>
    <!-- * E-mail -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-mail">
          メールアドレス
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [email* your-email id:f-email placeholder "例）hoge@hogehoge.com"]
        <p class="p-form__example">例）hoge@hogehoge.com</p>
      </dd>
    </div>
    <!-- * Select -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-select">
          お問い合わせの種類（セレクト）（通常）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [select* your-select id:f-select first_as_label "選択してください" "セレクト1" "セレクト2" "セレクト3"]
      </dd>
    </div>
    <!-- * Select カスタムする場合 -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-select">
          お問い合わせの種類（セレクト）（カスタム）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [select* your-select1 id:f-select1 data:custom-select]
      </dd>
    </div>
    <!-- * Select 条件分岐がある場合 -->
    [group quotation1 clear_on_hide]
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-select">
          お問い合わせの種類（セレクト）（条件分岐）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [select* your-select2 id:f-select2 first_as_label "選択してください" "セレクト1" "セレクト2" "セレクト3"]
      </dd>
    </div>
    [/group]
    <!-- * Select 条件分岐とカスタムがある場合 -->
    [group quotation2 clear_on_hide]
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-select">
          お問い合わせの種類（セレクト）（条件分岐＋カスタム）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [select* your-select3 id:f-select3 data:custom-select]
      </dd>
    </div>
    [/group]
    <!-- * Checkbox -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-checkbox">
          お問い合わせの種類（チェックボックス）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [checkbox your-checkbox id:f-checkbox use_label_element "チェックボックス1" "チェックボックス2"
        "チェックボックス3"]
      </dd>
    </div>
    <!-- * Radio -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-radio">
          お問い合わせの種類（ラジオボタン）
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [radio your-radio id:f-radio use_label_element default:1 "ラジオ1" "ラジオ2" "ラジオ3"]
      </dd>
    </div>
    <!-- * Textarea -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-textarea">
          お問い合わせ内容
          <sup class="p-form__required">*</sup>
          <span class="p-form__required">必須</span>
        </label>
      </dt>
      <dd class="p-form__description">
        [textarea* your-message id:f-textarea minlength:10 maxlength:300 placeholder "お気軽にご相談ください。"]
        <p class="p-form__note">現在[count your-message]字 / 残り[count your-message down]字</p>
      </dd>
    </div>
    <!-- * zip-code -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-zip-code">
          <span class="p-form__required">必須</span>
          郵便番号
        </label>
      </dt>
      <dd class="p-form__description">
        [text* your-zip-code id:f-zip-code placeholder "例）123-4567"]
      </dd>
    </div>
    <!-- * address -->
    <div class="p-form__item">
      <dt class="p-form__term">
        <label for="f-address">
          <span class="p-form__required">必須</span>
          ご住所
        </label>
      </dt>
      <dd class="p-form__description">
        [text* your-address id:f-address placeholder "例）京都府京都市中京区○○"]
      </dd>
    </div>
  </dl>
  <!-- * Acceptance -->
  <div class="p-form__policy">
    [acceptance your-acceptance]
    <a href="/privacy-policy/" target="_blank" rel="noreferrer noopener">「個人情報の取り扱いについて」(プライバシーポリシー)</a>に同意する。
    [/acceptance]
  </div>

  <!-- * Submit そのまま送信する場合 -->
  <p class="p-form__submit-cation">
    ご入力内容をご確認の上、送信ボタンを押してください。<br />確認画面は表示されず、送信が完了しますのでご了承ください。
  </p>
  <div class="p-form__submit">
    <div class="c-button-normal">[submit "送信する"]</div>
  </div>
  <!-- * To Confirm Multi Step で確認画面に遷移する場合 -->
  <div class="p-form__submit">
    <div class="c-button-normal">[submit "入力内容のご確認"]</div>
    [multistep your-multistep01 first_step "http://localhost:3000/contact/confirm"]
    <!-- [multistep your-multistep01 first_step "hogehoge/contact/confirm"] -->
  </div>
</div>
<!-- * Form Contact End -->