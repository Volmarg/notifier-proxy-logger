<!-- Template -->
<template>
  <form @submit.prevent="submitTestMailForm" v-bind:action="formActionUrl" ref="form" method="POST">
    <div class="row">
      <div class="col-md-10 mb-3">
        <div>
          <label for="test_message_receiver">{{ receiverLabelTranslation }}</label>
          <input class="form-control" id="test_message_receiver" type="email" required=""
                 v-bind:placeholder="receiverPlaceholderTranslation"
                 v-model="emailInput"
                 >
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-10 mb-3">
        <div>
          <label for="test_message_title">{{ messageTitleLabelTranslation }}</label>
          <input class="form-control" id="test_message_title" type="text" required=""
                 v-bind:placeholder="messageTitleLabelPlaceholder"
                 v-model="titleInput"
          >
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-10 mb-3">
        <div class="form-group">
          <label for="test_message_body">{{ messageBodyLabelTranslation }}</label>
          <textarea id="test_message_body"  class="form-control" required=""
                    v-bind:placeholder="messageBodyPlaceholderTranslation"
                    v-model="bodyTextArea"
          >
          </textarea>
        </div>
      </div>
    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-primary"
      >{{ submitButtonTranslation }}</button>
    </div>

    <CsrfTokenInputComponent :csrf-token="csrfToken"/>
  </form>
</template>

<!-- Script -->
<script>
import TranslationsService        from "../../../../core/services/TranslationsService";
import SymfonyRoutes              from "../../../../core/symfony/SymfonyRoutes";
import SymfonyForms               from "../../../../core/symfony/SymfonyForms";
import CsrfTokenResponseDto       from "../../../../core/dto/api/internal/CsrfTokenResponseDto";
import CsrfTokenInputComponent    from "../../components/csrf-token-input";
import Notification               from '../../../../libs/mdb5/Notification';
import BaseInternalApiResponseDto from "../../../../core/dto/api/internal/BaseInternalApiResponseDto";

let translationService = new TranslationsService();
let notification       = new Notification();

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
      formActionUrl                     : SymfonyRoutes.SEND_TEST_MAIL,
      emailInput                        : "",
      titleInput                        : "",
      bodyTextArea                      : "",
      csrfToken                         : "",
    }
  },
  methods: {
    /**
     * @description will handle submission of the form
     */
    submitTestMailForm(){
      let csrfRequestPromise = this.getCsrfToken();

      let ajaxFormData = {
        receiver     : this.emailInput,
        messageTitle : this.titleInput,
        messageBody  : this.bodyTextArea,
        _token       : this.csrfToken,
      };

      csrfRequestPromise.then( (response) => {
        let csrfTokenResponseDto = CsrfTokenResponseDto.fromAxiosResponse(response);
        this.csrfToken           = csrfTokenResponseDto.csrToken;

        if( !csrfTokenResponseDto.success ){
          notification.showRedNotification(csrfTokenResponseDto.message);
          return;
        }

        this.axios({
          method : "POST",
          url    : SymfonyRoutes.SEND_TEST_MAIL,
          data   : ajaxFormData,
        }).then( (response) => {

          let baseApiResponseDto = BaseInternalApiResponseDto.fromAxiosResponse(response);
          if( baseApiResponseDto.success ){
            notification.showGreenNotification(baseApiResponseDto.message);
          }else {
            notification.showRedNotification(baseApiResponseDto.message);
          }
        })
      });
    },
    /**
     * @description will return the csrf token which is required upon submitting the form (Internal Symfony Validation Logic)
     */
    getCsrfToken(){
      let urlReplacementParams = {
        [SymfonyRoutes.GET_CSRF_TOKEN_PARAM_FORM_NAME]: SymfonyForms.SEND_TEST_MAIL_FORM_BLOCK_NAME
      }

      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_CSRF_TOKEN, urlReplacementParams);

      let promise = this.axios({
        method : "GET",
        url    : url,
      });

      return promise;
    }
  },
  components: {
    CsrfTokenInputComponent
  }
}
</script>