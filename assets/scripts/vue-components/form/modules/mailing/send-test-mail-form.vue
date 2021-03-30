<!-- Template -->
<template>
  <form @submit.prevent="submitTestMailForm" v-bind:action="formActionUrl" ref="form" method="POST">

    <div class="row">
      <div class="col-md-10 mb-3">
        <label for="account_select">{{ selectMailAccountLabelTranslation }}</label>
        <select class="form-control" id="account_select" ref="webhookSelect" v-model="mailAccountSelect">
          <option value="">{{ selectMailAccountPlaceholderTranslation }}</option>
          <option v-for="emailAccountDto in allEmailsAccounts" :value="emailAccountDto.id" >{{ emailAccountDto.name }} ( {{ emailAccountDto.client }} )</option>
        </select>
      </div>
    </div>

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
<script type="ts">
import TranslationsService             from "../../../../core/services/TranslationsService";
import SymfonyRoutes                   from "../../../../core/symfony/SymfonyRoutes";
import SymfonyForms                    from "../../../../core/symfony/SymfonyForms";
import CsrfTokenResponseDto            from "../../../../core/dto/api/internal/CsrfTokenResponseDto";
import CsrfTokenInputComponent         from "../../components/csrf-token-input";
import Notification                    from '../../../../libs/mdb5/Notification';
import BaseInternalApiResponseDto      from "../../../../core/dto/api/internal/BaseInternalApiResponseDto";
import GetAllEmailsAccountsResponseDto from "../../../../core/dto/api/internal/GetAllEmailsAccountsResponseDto";
import MailAccountDto from "../../../../core/dto/modules/mailing/MailAccountDto";

let translationService = new TranslationsService();
let notification       = new Notification();

export default {
  data(){
    return {
      receiverLabelTranslation                : translationService.getTranslationForString('forms.sendTestMailForm.receiver.label'),
      receiverPlaceholderTranslation          : translationService.getTranslationForString('forms.sendTestMailForm.receiver.placeholder'),
      messageTitleLabelTranslation            : translationService.getTranslationForString('forms.sendTestMailForm.messageTitle.label'),
      messageTitleLabelPlaceholder            : translationService.getTranslationForString('forms.sendTestMailForm.messageTitle.placeholder'),
      messageBodyLabelTranslation             : translationService.getTranslationForString('forms.sendTestMailForm.messageBody.label'),
      messageBodyPlaceholderTranslation       : translationService.getTranslationForString('forms.sendTestMailForm.messageBody.placeholder'),
      submitButtonTranslation                 : translationService.getTranslationForString('forms.sendTestMailForm.submit.label'),
      selectMailAccountLabelTranslation       : translationService.getTranslationForString('forms.sendTestMailForm.account.label'),
      selectMailAccountPlaceholderTranslation : translationService.getTranslationForString('forms.sendTestMailForm.account.placeholder'),
      formActionUrl                           : SymfonyRoutes.SEND_TEST_MAIL,
      mailAccountSelect                       : null,
      emailInput                              : "",
      titleInput                              : "",
      bodyTextArea                            : "",
      csrfToken                               : "",
      allEmailsAccounts                       : []
    }
  },
  methods: {
    /**
     * @description will handle submission of the form
     */
    submitTestMailForm(){
      let csrfRequestPromise = this.getCsrfToken();

      csrfRequestPromise.then( (response) => {
        let csrfTokenResponseDto = CsrfTokenResponseDto.fromAxiosResponse(response);
        this.csrfToken           = csrfTokenResponseDto.csrToken;

        if( !csrfTokenResponseDto.success ){
          notification.showRedNotification(csrfTokenResponseDto.message);
          return;
        }

        let ajaxFormData = {
          receiver     : this.emailInput,
          messageTitle : this.titleInput,
          messageBody  : this.bodyTextArea,
          account      : this.mailAccountSelect,
          _token       : this.csrfToken,
        };

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
    },
    /**
     * @description will fetch all the mails accounts
     */
    getAllEmailsAccounts(){
      this.axios.get(SymfonyRoutes.GET_ALL_MAIL_ACCOUNTS).then( (response) => {

        let emailsAccountsJsons = GetAllEmailsAccountsResponseDto.fromAxiosResponse(response).emailsAccountsJsons;
        this.allEmailsAccounts  = emailsAccountsJsons.map( (json) => {
          return MailAccountDto.fromJson(json);
        })
      })
    }
  },
  beforeMount(){
    this.getAllEmailsAccounts();
  },
  components: {
    CsrfTokenInputComponent
  }
}
</script>