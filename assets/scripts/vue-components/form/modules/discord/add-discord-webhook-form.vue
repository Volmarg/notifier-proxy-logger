<!-- Template -->
<!-- todo: split the inputs to components with given styling (?)-->
<template>
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordWebhooksTranslation }}  </h2>
        <section class="">

          <form ref="form" method="POST">
            <div class="row">
              <div class="col-md-10 mb-3">
                <div>
                  <label for="add_discord_webhook_url">{{ webhookUrlLabelTranslation }}</label>
                  <input class="form-control" id="add_discord_webhook_url" type="text" required=""
                         :placeholder="webhookUrlPlaceholderTranslation"
                  >
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div>
                  <label for="add_discord_webhook_name">{{ webhookNameLabelTranslation }}</label>
                  <input class="form-control" id="add_discord_webhook_name" type="text" required=""
                         :placeholder="webhookNamePlaceholderTranslation"
                  >
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div class="form-group">
                  <label for="description">{{ descriptionLabelTranslation }}</label>
                  <textarea id="description"  class="form-control" required=""
                            :placeholder="descriptionPlaceholderTranslation"
                  >
          </textarea>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div class="form-group">
                  <label for="username">{{ usernameLabelTranslation }}</label>
                  <textarea id="username"  class="form-control" required=""
                            :placeholder="usernamePlaceholderTranslation"
                  >
          </textarea>
                </div>
              </div>
            </div>

            <div class="mt-3">
              <button type="submit" class="btn btn-primary"
              >{{ submitButtonTranslation }}</button>
            </div>

            <!--    <CsrfTokenInputComponent :csrf-token="csrfToken"/>-->
          </form>

        </section>
      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import TranslationsService from '../../../../core/services/TranslationsService';

let translationsService = new TranslationsService();

export default {
  computed: {
    webhookUrlLabelTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.webhookUrl.label');
    },
    webhookUrlPlaceholderTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.webhookUrl.placeholder');
    },
    webhookNameLabelTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.webhookName.label');
    },
    webhookNamePlaceholderTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.webhookName.placeholder');
    },
    descriptionLabelTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.description.label');
    },
    descriptionPlaceholderTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.description.placeholder');
    },
    usernameLabelTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.username.label');
    },
    usernamePlaceholderTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.username.placeholder');
    },
    submitButtonTranslation: function(){
      return translationsService.getTranslationForString('forms.addDiscordWebhookForm.submit.label');
    },
  }
}
</script>