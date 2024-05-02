<!-- * Form Contact Start -->
<div class="contact-form">
  <dl class="contact-form__list">
    <!-- * Radio -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-radio">
          <span class="contact-form__required">Required</span>
          Inquiry type
        </label>
      </dt>
      <dd class="contact-form__description">
        [radio your-radio id:f-radio use_label_element default:1 "Inquiry type 01" "Inquiry type 02"]
      </dd>
    </div>
    <!-- * Name -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-name">
          <span class="contact-form__required">Required</span>
          Name
        </label>
      </dt>
      <dd class="contact-form__description">
        [text* your-name id:f-name placeholder "ex) John Smith"]
      </dd>
    </div>
    <!-- * address -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-address">
          <span class="contact-form__required contact-form__required--not">Any</span>
          Address
        </label>
      </dt>
      <dd class="contact-form__description">
        [text your-address id:f-address placeholder "ex) 123456, xxxxyyy, Sapporo city, Hokkaido Japan"]
      </dd>
    </div>
    <!-- * E-mail -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-mail">
          <span class="contact-form__required">Required</span>
          E-mail
        </label>
      </dt>
      <dd class="contact-form__description">
        [email* your-email id:f-mail placeholder "ex) prdtheapeutics@example.jp"]
      </dd>
    </div>
    <!-- * Telephone -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-tel">
          <span class="contact-form__required">Required</span>
          Tel
        </label>
      </dt>
      <dd class="contact-form__description">
        [tel* your-tel id:f-tel placeholder "ex) 012-3456-7890"]
      </dd>
    </div>
    <!-- * Textarea -->
    <div class="contact-form__item">
      <dt class="contact-form__term">
        <label for="f-textarea">
          <span class="contact-form__required">Required</span>
          Content of inquiry
        </label>
      </dt>
      <dd class="contact-form__description">
        [textarea* your-message id:f-textarea placeholder "Please enter the details of your inquiry here."]
      </dd>
    </div>
  </dl>
  <!-- * Submit -->
  <div class="contact-form__confirm">
    <div class="button-submit">[submit "Confirm"]</div>
    [multistep your-multistep01 first_step "/en/contact/confirm/"]
  </div>
</div>
<!-- * Form Contact End -->