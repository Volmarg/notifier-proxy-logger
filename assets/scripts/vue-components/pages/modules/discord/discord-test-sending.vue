<!-- Template -->
<template>
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordTestSendingTranslation }}  </h2>
        <section class="">

          <form ref="form" method="POST" @submit.prevent="submitSendDiscordMessage">
            <div class="row">
              <div class="col-md-10 mb-3">
                <div>

                  <label for="webhook_select">{{ webhookSelectLabelTranslation }}</label>
                  <select class="form-control" id="webhook_select" required ref="webhookSelect">
                    <option value=""></option>
                    <template v-if="discordWebhooksDtos.length">
                      <option
                          v-for="(discordWebhookDto, index) in discordWebhooksDtos"
                          :key="index"
                          :value="discordWebhookDto.id">{{ discordWebhookDto.webhookName }}</option>
                    </template>
                  </select>

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div>
                  <label for="message">{{ webhookMessageLabelTranslation }}</label>
                  <textarea class="form-control" id="message" required ref="message" :placeholder="webhookMessagePlaceholderTranslation"></textarea>
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
import SemipolarSpinnerComponent        from '../../../../vue-components/libs/epic-spinners/semipolar-spinner'


import GetAllDiscordWebhooksResponseDto from "../../../../core/dto/api/internal/GetAllDiscordWebhooksResponseDto";
import DiscordWebhookDto                from "../../../../core/dto/modules/discord/DiscordWebhookDto";
import CsrfTokenResponseDto             from "../../../../core/dto/api/internal/CsrfTokenResponseDto";
import BaseInternalApiResponseDto       from "../../../../core/dto/api/internal/BaseInternalApiResponseDto";

import SymfonyRoutes                    from "../../../../core/symfony/SymfonyRoutes";
import TranslationsService              from "../../../../core/services/TranslationsService";
import Notification                     from "../../../../libs/mdb5/Notification";
import SymfonyForms from "../../../../core/symfony/SymfonyForms";

let translationsService = new TranslationsService();
let notification        = new Notification();

export default {
  data(){
    return {
      isSpinnerVisible    : true,
      discordWebhooksDtos : [],
    }
  },
  computed: {
    webhookSelectLabelTranslation: function(){
      return translationsService.getTranslationForString('forms.testDiscordMessageForm.webhooks.label');
    },
    webhookMessageLabelTranslation: function(){
      return translationsService.getTranslationForString('forms.testDiscordMessageForm.message.label');
    },
    webhookMessagePlaceholderTranslation: function(){
      return translationsService.getTranslationForString('forms.testDiscordMessageForm.message.placeholder');
    },
    mainHeaderDiscordTestSendingTranslation: function(){
      return translationsService.getTranslationForString('pages.discord.testMessageSending.header.main');
    },
    submitButtonTranslation: function(){
      return translationsService.getTranslationForString('forms.testDiscordMessageForm.submit.label');
    },
  },
  components: {
    'semipolar-spinner': SemipolarSpinnerComponent
  },
  methods: {
    /**
     * @description will fetch the webhooks definitions from the backend
     */
    getAllDiscordWebhooks(){
      this.axios.get(SymfonyRoutes.GET_ALL_DISCORD_WEBHOOKS).then( (response) => {

        let getAllDiscordWebhooksResponseDto = GetAllDiscordWebhooksResponseDto.fromAxiosResponse(response);
        let discordWebhooksJsons             = getAllDiscordWebhooksResponseDto.discordWebhooksJsons;

        this.isSpinnerVisible    = false;
        this.discordWebhooksDtos = discordWebhooksJsons.map( (json) => {
          return DiscordWebhookDto.fromJson(json);
        });

      });
    },
    submitSendDiscordMessage(){

      let urlReplacementParams = {
        [SymfonyRoutes.GET_CSRF_TOKEN_PARAM_FORM_NAME]: SymfonyForms.SEND_TEST_DISCORD_MESSAGE_FORM
      }
      let url                 = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_CSRF_TOKEN, urlReplacementParams)
      let getCsrfTokenPromise = this.axios.get(url);

      getCsrfTokenPromise.then( (response) => {
        let csrfTokenResponse = CsrfTokenResponseDto.fromAxiosResponse(response);
        this.csrfToken = csrfTokenResponse.csrToken;

        let dataBag = {
          "webhookId" : this.$refs.webhookSelect.value,
          "message"   : this.$refs.message.value,
          '_token'    : this.csrfToken,
        }

        this.axios.post(SymfonyRoutes.SEND_DISCORD_TEST_MESSAGE, dataBag).then( (response) => {

          let baseApiResponseDto = BaseInternalApiResponseDto.fromAxiosResponse(response);
          if( baseApiResponseDto.success ){
            notification.showGreenNotification(baseApiResponseDto.message);
          }else{
            notification.showRedNotification(baseApiResponseDto.message);
          }
        })
      })

    }
  },
  beforeMount() {
    this.getAllDiscordWebhooks();
  }
}
</script>