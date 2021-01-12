<!-- Template -->
<template>
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordTestSendingTranslation }}  </h2>
        <section class="">
          <test-sending-form />
        </section>
      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import TranslationsService      from "../../../../core/services/TranslationsService";
import TestSendingFormComponent from '../../../form/modules/discord/send-test-message-form'

let translationsService = new TranslationsService();

export default {
  data(){
    return {
      discordWebhooksDtos : [],
    }
  },
  computed: {
    mainHeaderDiscordTestSendingTranslation: function(){
      return translationsService.getTranslationForString('pages.discord.testMessageSending.header.main');
    }
  },
  components: {
    'test-sending-form' : TestSendingFormComponent,
  },
}
</script>