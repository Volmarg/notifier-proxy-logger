<!-- Template -->
<template>
  <form>
    <div class="row">
      <div class="col-md-6 mb-3">
        <div>
          <label for="first_name">{{ receiverLabelTranslation }}</label>
          <input class="form-control" id="first_name" type="text" v-bind:placeholder="receiverPlaceholderTranslation" required="">
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div>
          <label for="last_name">{{ messageTitleLabelTranslation }}</label>
          <input class="form-control" id="last_name" type="text" v-bind:placeholder="messageTitleLabelPlaceholder" required="">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="phone">{{ messageBodyLabelTranslation }}</label>
          <input class="form-control" id="phone" type="number" v-bind:placeholder="messageBodyPlaceholderTranslation" required="">
        </div>
      </div>
    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-primary">{{ submitButtonTranslation }}</button>
    </div>
  </form>
</template>

<!-- Script -->
<script>
import TranslationsService from "../../../../core/services/TranslationsService";

let translationService  = new TranslationsService();

export default {
  data(){
    return {
      receiverLabelTranslation          : translationService.getTranslationForString('forms.sendTestMailForm.receiver.label'),
      receiverPlaceholderTranslation    : translationService.getTranslationForString('forms.sendTestMailForm.receiver.placeholder'),
      messageTitleLabelTranslation      : translationService.getTranslationForString('forms.sendTestMailForm.messageTitle.label'),
      messageTitleLabelPlaceholder      : translationService.getTranslationForString('forms.sendTestMailForm.messageTitle.placeholder'),
      messageBodyLabelTranslation       : translationService.getTranslationForString('forms.sendTestMailForm.messageBody.label'),
      messageBodyPlaceholderTranslation : translationService.getTranslationForString('forms.sendTestMailForm.messageBody.placeholder'),
      submitButtonTranslation           : translationService.getTranslationForString('forms.sendTestMailForm.submit.label'),
    }
  }
}
</script>