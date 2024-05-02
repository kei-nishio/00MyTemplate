<!-- //! Contact Form 7 の変換前後をメモしたものです -->

<!--
[1]cf7の挿入項目にはクラス名をつけないことにする
・text,email,url,tel,acceptance,submitはinputにクラス名が付く
・textareaはtextareにクラス名が付く
・selectはselectにクラス名が付く（構造はselect>option*n）
・checkbox,radioはspanにクラス名が付く（構造はspan>span>label>input+span）
⇒クラス名が付くタグが異なるため、CSSで統一するのが面倒なため。

[2]wpcf7-spinnerはCSSで打ち消す
⇒送信ボタンのローディングアイコンを非表示にするため。

 -->

<!--  -->
<!-- //! Contact Form 7 変換前 -->
<!--  -->

<!-- * Form Contact Start -->
<div class="contact-form">
  [text* text-001 id:form-id-text class:form-class placeholder "テキスト"]
  [email* email-001 id:form-id-email class:form-class placeholder "メールアドレス"]
  [url* url-001 id:form-id-url class:form-class placeholder "URL"]
  [tel* tel-001 id:form-id-tel class:form-class placeholder "電話番号"]
  [textarea* textarea-001 id:form-id-textarea class:form-class placeholder "テキストエリア"]
  [select* menu-001 id:form-id-select class:form-class first_as_label "選択してください" "オプション1" "オプション2" "オプション3"]
  [checkbox* checkbox-001 id:form-id-checkbox class:form-class use_label_element "オプション1" "オプション2" "オプション3"]
  [radio radio-001 id:form-id-radio class:form-class use_label_element default:1 "オプション1" "オプション2" "オプション3"]
  [acceptance acceptance-001 id:form-id-acceptance class:form-class optional] ○○に同意します [/acceptance]
  [submit "送信します"]
</div>
<!-- * Form Contact End -->

<!--  -->
<!-- //! Contact Form 7 変換後 -->
<!--  -->

<!-- * Form Contact Start -->
<div class="contact-form">

  <!-- [text* text-001 id:form-id-text class:form-class placeholder "テキスト"] -->
  <span class="wpcf7-form-control-wrap" data-name="text-001">
    <input size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-class" id="form-id-text" aria-required="true" aria-invalid="false" placeholder="テキスト" value="" type="text" name="text-001">
  </span>

  <!-- [email* email-001 id:form-id-email class:form-class placeholder "メールアドレス"] -->
  <span class="wpcf7-form-control-wrap" data-name="email-001">
    <input size="40" class="wpcf7-form-control wpcf7-email wpcf7-validates-as-required wpcf7-text wpcf7-validates-as-email form-class" id="form-id-email" aria-required="true" aria-invalid="false" placeholder="メールアドレス" value="" type="email" name="email-001">
  </span>

  <!-- [url* url-001 id:form-id-url class:form-class placeholder "URL"] -->
  <span class="wpcf7-form-control-wrap" data-name="url-001">
    <input size="40" class="wpcf7-form-control wpcf7-url wpcf7-validates-as-required wpcf7-text wpcf7-validates-as-url form-class" id="form-id-url" aria-required="true" aria-invalid="false" placeholder="URL" value="" type="url" name="url-001">
  </span>

  <!-- [tel* tel-001 id:form-id-tel class:form-class placeholder "電話番号"] -->
  <span class="wpcf7-form-control-wrap" data-name="tel-001">
    <input size="40" class="wpcf7-form-control wpcf7-tel wpcf7-validates-as-required wpcf7-text wpcf7-validates-as-tel form-class" id="form-id-tel" aria-required="true" aria-invalid="false" placeholder="電話番号" value="" type="tel" name="tel-001">
  </span>

  <!-- [textarea* textarea-001 id:form-id-textarea class:form-class placeholder "テキストエリア"] -->
  <span class="wpcf7-form-control-wrap" data-name="textarea-001">
    <textarea cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea wpcf7-validates-as-required form-class" id="form-id-textarea" aria-required="true" aria-invalid="false" placeholder="テキストエリア" name="textarea-001"></textarea>
  </span>

  <!-- [select* menu-001 id:form-id-select class:form-class first_as_label "選択してください" "オプション1" "オプション2" "オプション3"] -->
  <span class="wpcf7-form-control-wrap" data-name="menu-001">
    <select class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required form-class" id="form-id-select" aria-required="true" aria-invalid="false" name="menu-001">
      <option value="">選択してください</option>
      <option value="オプション1">オプション1</option>
      <option value="オプション2">オプション2</option>
      <option value="オプション3">オプション3</option>
    </select>
  </span>


  <!-- [checkbox* checkbox-001 id:form-id-checkbox class:form-class use_label_element "オプション1" "オプション2" "オプション3"] -->
  <span class="wpcf7-form-control-wrap" data-name="checkbox-001">
    <span class="wpcf7-form-control wpcf7-checkbox wpcf7-validates-as-required form-class" id="form-id-checkbox">
      <span class="wpcf7-list-item first">
        <label>
          <input type="checkbox" name="checkbox-001[]" value="オプション1">
          <span class="wpcf7-list-item-label">オプション1</span>
        </label>
      </span>
      <span class="wpcf7-list-item">
        <label>
          <input type="checkbox" name="checkbox-001[]" value="オプション2">
          <span class="wpcf7-list-item-label">オプション2</span>
        </label>
      </span>
      <span class="wpcf7-list-item last">
        <label>
          <input type="checkbox" name="checkbox-001[]" value="オプション3">
          <span class="wpcf7-list-item-label">オプション3</span>
        </label>
      </span>
    </span>
  </span>

  <!-- [radio radio-001 id:form-id-radio class:form-class use_label_element default:1 "オプション1" "オプション2" "オプション3"] -->
  <span class="wpcf7-form-control-wrap" data-name="radio-001">
    <span class="wpcf7-form-control wpcf7-radio form-class" id="form-id-radio">
      <span class="wpcf7-list-item first">
        <label>
          <input type="radio" name="radio-001" value="オプション1" checked="checked">
          <span class="wpcf7-list-item-label">オプション1</span>
        </label>
      </span>
      <span class="wpcf7-list-item">
        <label>
          <input type="radio" name="radio-001" value="オプション2">
          <span class="wpcf7-list-item-label">オプション2</span>
        </label>
      </span>
      <span class="wpcf7-list-item last">
        <label>
          <input type="radio" name="radio-001" value="オプション3">
          <span class="wpcf7-list-item-label">オプション3</span>
        </label>
      </span>
    </span>
  </span>

  <!-- [acceptance acceptance-001 id:form-id-acceptance class:form-class optional] ○○に同意します [/acceptance] -->
  <span class="wpcf7-form-control-wrap" data-name="acceptance-001">
    <span class="wpcf7-form-control wpcf7-acceptance optional">
      <span class="wpcf7-list-item">
        <label>
          <input type="checkbox" name="acceptance-001" value="1" class="form-class" id="form-id-acceptance" aria-invalid="false">
          <span class="wpcf7-list-item-label">○○に同意します</span>
        </label>
      </span>
    </span>
  </span>

  <!-- [submit "送信します"] -->
  <input class="wpcf7-form-control wpcf7-submit has-spinner" type="submit" value="送信します">
  <span class="wpcf7-spinner"></span>
</div>
<!-- * Form Contact End -->