<!-- Template -->
<template>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ translationHeaderSendTestMail }}  </h2>

        <information-text-for-block :displayed-text="formInformationTranslatedString"/>

        <section class="">
            <send-test-mail/>
          </section>
      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import SendTestMailForm    from '../../../form/modules/mailing/send-test-mail-form';
import TranslationsService from "../../../../core/services/TranslationsService";

import InformationTextForBlockComponent from '../../../../vue-components/pages/components/information-text-for-block';

let translationService = new TranslationsService();

export default {
  computed: {
    formInformationTranslatedString: function(){
      return translationService.getTranslationForString('pages.mailing.sendTestMail.texts.general');
    },
    translationHeaderSendTestMail: function(){
      return translationService.getTranslationForString('pages.mailing.overview.headers.sendTestMail');
    }
  },
  components: {
    "send-test-mail"             : SendTestMailForm,
    "information-text-for-block" : InformationTextForBlockComponent,
  }
}
</script>