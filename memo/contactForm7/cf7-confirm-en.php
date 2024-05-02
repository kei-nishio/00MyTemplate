<!-- * Form Contact Start -->
<div class="confirm-form">
  <dl class="confirm-form__list">
    <!-- * Radio -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">Inquiry type</dt>
      <dd class="confirm-form__description">[multiform your-radio id:f-radio]</dd>
    </div>
    <!-- * Name -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">Name</dt>
      <dd class="confirm-form__description">[multiform your-name id:f-name]</dd>
    </div>
    <!-- * address -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">Address</dt>
      <dd class="confirm-form__description">[multiform your-address id:f-address]</dd>
    </div>
    <!-- * E-mail -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">E-mail</dt>
      <dd class="confirm-form__description">[multiform your-email id:f-mail]</dd>
    </div>
    <!-- * Telephone -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">Tel</dt>
      <dd class="confirm-form__description">[multiform your-tel id:f-tel]</dd>
    </div>
    <!-- * Textarea -->
    <div class="confirm-form__item">
      <dt class="confirm-form__term">Content of inquiry</dt>
      <dd class="confirm-form__description">[multiform your-message id:f-textarea]</dd>
    </div>
  </dl>
  <div class="confirm-form__button">
    <!-- * Previous -->
    <div class="confirm-form__previous">
      <div class="button-submit button-submit--white">[previous "correct"]</div>
    </div>
    <!-- * Submit -->
    <div class="confirm-form__submit">
      <div class="button-submit">[submit "submit"]</div>
      [multistep your-multistep02 last_step send_email "/en/contact/confirm/thanks/"]
    </div>
  </div>
</div>
<!-- * Form Contact End -->