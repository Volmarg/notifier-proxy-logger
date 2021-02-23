<!-- Template -->
<template>
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderAddMailAccountTranslated }} </h2>

        <information-text-for-block :displayed-text="translatedInformationTextString"/>

        <section class="">

          <form ref="form" method="POST" @submit.prevent="submitNewMailAccount">
            <div class="row">
              <div class="col-md-10 mb-3">
                <div>
                  <label for="add_mail_account_client">{{ clientLabelTranslated }}</label>
                  <input class="form-control"
                         id="add_mail_account_client"
                         type="text"
                         required=""
                         :placeholder="clientPlaceholderTranslated"
                         ref="client"
                  >
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div>
                  <label for="add_mail_account_name">{{ nameLabelTranslated }}</label>
                  <input class="form-control"
                         id="add_mail_account_name"
                         type="text"
                         required=""
                         :placeholder="namePlaceholderTranslated"
                         ref="name"
                  >
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div class="form-group">
                  <label for="add_mail_account_login">{{ loginLabelTranslated }}</label>
                  <input id="add_mail_account_login"
                         class="form-control"
                         required=""
                         type="text"
                         :placeholder="loginPlaceholderTranslated"
                         ref="login"
                  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-10 mb-3">
                <div class="form-group">
                  <label for="add_mail_account_password">{{ passwordLabelTranslated }}</label>
                  <input type="password"
                         id="add_mail_account_password"
                         class="form-control"
                         required=""
                         :placeholder="passwordPlaceholderTranslated"
                         ref="password"
                  />
                </div>
              </div>
            </div>

            <div class="mt-3">
              <button type="submit" class="btn btn-primary"
              >{{ submitButtonTranslated }}</button>
            </div>

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
import SymfonyForms               from "../../../../core/symfony/SymfonyForms";

import CsrfTokenResponseDto       from "../../../../core/dto/api/internal/CsrfTokenResponseDto";
import BaseInternalApiResponseDto from "../../../../core/dto/api/internal/BaseInternalApiResponseDto";

import InformationTextForBlockComponent from "../../../../vue-components/pages/components/information-text-for-block";

let translationsService = new TranslationsService();
let notification        = new Notification();

export default {
  data(){
    return {
      csrfToken: "",
    }
  },
  computed: {
    clientLabelTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.client.label');
    },
    clientPlaceholderTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.client.placeholder');
    },
    nameLabelTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.name.label');
    },
    namePlaceholderTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.name.placeholder');
    },
    loginLabelTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.login.label');
    },
    loginPlaceholderTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.login.placeholder');
    },
    passwordLabelTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.password.label');
    },
    passwordPlaceholderTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.password.placeholder');
    },
    submitButtonTranslated: function(){
      return translationsService.getTranslationForString('forms.addMailAccountForm.submit.label');
    },
    mainHeaderAddMailAccountTranslated: function(){
      return translationsService.getTranslationForString('pages.mailing.addMailAccount.header.main');
    },
    translatedInformationTextString: function(){
      return translationsService.getTranslationForString('pages.mailing.manageMailAccounts.texts.general');
    }
  },
  components: {
    'information-text-for-block' : InformationTextForBlockComponent,
  },
  emits: [
      'addMailAccountFormSubmitted'
  ],
  methods: {
    submitNewMailAccount(){
      let ajaxData = {
        "client"   : this.$refs.client.value,
        "name"     : this.$refs.name.value,
        "login"    : this.$refs.login.value,
        "password" : this.$refs.password.value,
        "_token"   : this.csrfToken,
      };

      this.axios.post(SymfonyRoutes.ADD_MAIL_ACCOUNT, ajaxData).then( (response) => {
        let baseApiResponseDto = BaseInternalApiResponseDto.fromAxiosResponse(response);

        if( baseApiResponseDto.success ){
          notification.showGreenNotification(baseApiResponseDto.message);
        }else{
          notification.showRedNotification(baseApiResponseDto.message);
        }

      })

      // tell the parent to fetch new state of hooks to update the table
      this.$emit('addMailAccountFormSubmitted')
    },
    getCsrfToken(){
      let urlReplacementParams = {
        [SymfonyRoutes.GET_CSRF_TOKEN_PARAM_FORM_NAME]: SymfonyForms.MAIL_ACCOUNT_FORM_BLOCK_NAME
      }

      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_CSRF_TOKEN, urlReplacementParams)

      this.axios.get(url).then( (response) => {
        let csrfTokenResponse = CsrfTokenResponseDto.fromAxiosResponse(response);
        this.csrfToken        = csrfTokenResponse.csrToken;
      })
    }
  },
  beforeMount(){
    this.getCsrfToken();
  },
}
</script>