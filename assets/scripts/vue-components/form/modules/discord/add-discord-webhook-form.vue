<!-- Template -->
<!-- todo: split the inputs to components with given styling (?)-->
<template>
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderAddDiscordWebhookTranslation }}  </h2>
        <section class="">

          <form ref="form" method="POST" @submit.prevent="submitNewWebhook">
            <div class="row">
              <div class="col-md-10 mb-3">
                <div>
                  <label for="add_discord_webhook_url">{{ webhookUrlLabelTranslation }}</label>
                  <input class="form-control" id="add_discord_webhook_url" type="text" required=""
                         :placeholder="webhookUrlPlaceholderTranslation"
                         ref="webhookUrl"
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
                         ref="webhookName"
                  >
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div class="form-group">
                  <label for="username">{{ usernameLabelTranslation }}</label>
                  <input id="username"  class="form-control" required=""
                         type="text"
                         :placeholder="usernamePlaceholderTranslation"
                         ref="username"
                  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div class="form-group">
                  <label for="description">{{ descriptionLabelTranslation }}</label>
                  <textarea id="description"  class="form-control" required=""
                            :placeholder="descriptionPlaceholderTranslation"
                            ref="description"
                  >
          </textarea>
                </div>
              </div>
            </div>

            <div class="mt-3">
              <button type="submit" class="btn btn-primary"
              >{{ submitButtonTranslation }}</button>
            </div>

            <csrf-token :csrf-token="csrfToken"/>
          </form>

        </section>
      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import TranslationsService        from '../../../../core/services/TranslationsService';
import SymfonyRoutes              from "../../../../core/symfony/SymfonyRoutes";
import Notification               from "../../../../libs/mdb5/Notification";

import BaseInternalApiResponseDto from "../../../../core/dto/api/internal/BaseInternalApiResponseDto";
import CsrfTokenComponent         from "../../../../vue-components/form/components/csrf-token-input";
import SymfonyForms               from "../../../../core/symfony/SymfonyForms";

import CsrfTokenResponseDto       from "../../../../core/dto/api/internal/CsrfTokenResponseDto";

let translationsService = new TranslationsService();
let notification        = new Notification();

export default {
  data(){
    return {
      csrfToken: "",
    }
  },
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
    mainHeaderAddDiscordWebhookTranslation: function(){
      return translationsService.getTranslationForString('pages.discord.addDiscordWebhook.header.main');
    },
  },
  methods: {
    submitNewWebhook(){
      let ajaxData = {
        "username"    : this.$refs.username.value,
        "webhookUrl"  : this.$refs.webhookUrl.value,
        "description" : this.$refs.description.value,
        "webhookName" : this.$refs.webhookName.value,
        "_token"      : this.csrfToken,
      };

      this.axios.post(SymfonyRoutes.ADD_DISCORD_WEBHOOK, ajaxData).then( (response) => {
        let baseApiResponseDto = BaseInternalApiResponseDto.fromAxiosResponse(response);

        if( baseApiResponseDto.success ){
          notification.showGreenNotification(baseApiResponseDto.message);
        }else{
          notification.showRedNotification(baseApiResponseDto.message);
        }

      })

    },
    getCsrfToken(){
      let urlReplacementParams = {
        [SymfonyRoutes.GET_CSRF_TOKEN_PARAM_FORM_NAME]: SymfonyForms.ADD_DISCORD_WEBHOOK_FORM_BLOCK_NAME
      }

      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_CSRF_TOKEN, urlReplacementParams)

      this.axios.get(url).then( (response) => {
        let csrfTokenResponse = CsrfTokenResponseDto.fromAxiosResponse(response);
        this.csrfToken = csrfTokenResponse.csrToken;
      })
    }
  },
  beforeMount(){
    this.getCsrfToken();
  },
  components: {
    "csrf-token": CsrfTokenComponent
  }
}
</script>