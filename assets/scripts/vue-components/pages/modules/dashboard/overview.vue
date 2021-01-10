<!-- Template -->
<template>
  <section class="d-flex justify-content-around">
    <last-processed-emails/>
    <last-processed-discord-messages/>
  </section>
</template>

<!-- Script -->
<script>
import LastProcessedEmailsComponent          from '../../../pages/modules/dashboard/widgets/last-processed-emails'
import LastProcessedDiscordMessagesComponent from '../../../pages/modules/dashboard/widgets/last-processed-discord-messages'

export default {
  components: {
    'last-processed-emails'           : LastProcessedEmailsComponent,
    'last-processed-discord-messages' : LastProcessedDiscordMessagesComponent
  }
}
</script>