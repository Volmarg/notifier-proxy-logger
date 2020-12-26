<!-- Template -->
<template>

  <div class="row">
    <div class="col-12 col-xl-6">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ translationHeaderSendTestMail }}  </h2>
          <section class="">
            <SendTestMailForm/>
          </section>
      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import SendTestMailForm    from '../../../form/modules/mailing/send-test-mail-form';
import TranslationsService from "../../../../core/services/TranslationsService";

let translationService = new TranslationsService();

export default {
  components: {
    SendTestMailForm,
  },
  data(){
    return {
      translationHeaderSendTestMail: translationService.getTranslationForString('pages.mailing.overview.headers.sendTestMail'),
    }
  }
}
</script>