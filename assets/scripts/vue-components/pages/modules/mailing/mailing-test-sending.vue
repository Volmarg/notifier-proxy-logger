<!-- Template -->
<template>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ translationHeaderSendTestMail }}  </h2>

        <information-text-for-block :displayed-text="formInformationText"/>

        <section class="">
            <send-test-mail/>
          </section>
      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import SendTestMailForm                                    from '../../../form/modules/mailing/send-test-mail-form';
import TranslationsService                                 from "../../../../core/services/TranslationsService";
import {isDemo, VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING} from "../../../../env";

import InformationTextForBlockComponent from '../../../../vue-components/pages/components/information-text-for-block';

let translationService = new TranslationsService();

export default {
  data() {
    return {
      formInformationText: VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING,
    }
  },
  computed: {
    translationHeaderSendTestMail: function(){
      return translationService.getTranslationForString('pages.mailing.overview.headers.sendTestMail');
    }
  },
  components: {
    "send-test-mail"             : SendTestMailForm,
    "information-text-for-block" : InformationTextForBlockComponent,
  },
  methods: {
    getFormInformationTranslatedString(){
      isDemo().then( (isDemoMode) => {
        if(isDemoMode){
          this.formInformationText = translationService.getTranslationForString('pages.mailing.sendTestMail.texts.demo');
          return
        }
        this.formInformationText = translationService.getTranslationForString('pages.mailing.sendTestMail.texts.general');
      });
    }
  },
  created(){
    this.getFormInformationTranslatedString();
  }
}
</script>