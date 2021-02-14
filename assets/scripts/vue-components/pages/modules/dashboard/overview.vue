<!-- Template -->
<template>
  <section class="d-flex justify-content-around">
    <last-processed-emails/>
    <last-processed-discord-messages/>
    <material-dialog :show-cancel-button="false" v-show="isDemo" ref="demoWelcomeDialog">
      <raw-content :content="demoModeWelcomeDialogTranslatedText"/>
    </material-dialog>
  </section>
</template>

<!-- Script -->
<script>
import LastProcessedEmailsComponent                        from '../../../pages/modules/dashboard/widgets/last-processed-emails'
import LastProcessedDiscordMessagesComponent               from '../../../pages/modules/dashboard/widgets/last-processed-discord-messages'
import MaterialDialogComponent                             from '../../../../vue-components/dialog-modal/material/dialog';
import RawContentComponent                                 from '../../../../vue-components/other/raw-content';

import {isDemo, VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING} from "../../../../env";
import TranslationsService                                 from "../../../../core/services/TranslationsService";
import LocalStorageService                                 from "../../../../core/services/LocalStorageService";

let translationsService = new TranslationsService();
let localStorageService = new LocalStorageService();

export default {
  data(){
    return {
      isDemo                              : false,
      isRendered                          : false,
      demoModeWelcomeDialogTranslatedText : VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING,
    }
  },
  components: {
    'material-dialog'                 : MaterialDialogComponent,
    'last-processed-emails'           : LastProcessedEmailsComponent,
    'last-processed-discord-messages' : LastProcessedDiscordMessagesComponent,
    'raw-content'                     : RawContentComponent,
  },
  methods: {
    /**
     * @description will check if the demo mode is active
     **/
    async checkDemoMode(){
      isDemo().then( (isDemoMode) => {
        this.isDemo = isDemoMode;
      });
    },
    /**
     * @description will get the translation text for demo mode welcome dialog if the demo mode is active
     */
    getDemoModeWelcomeDialogTranslatedText(){
      this.demoModeWelcomeDialogTranslatedText = translationsService.getTranslationForString('mainPageComponents.dialog.texts.demoText');
    },
    /**
     * @description will show the demo welcome dialog
     */
    showDemoWelcomeDialog(){
      this.$refs.demoWelcomeDialog.showDialog();
    }
  },
  created(){
    this.checkDemoMode();
  },
  mounted() {
    this.isRendered = true;
  },
  watch: {
    isDemo(){
      if(
              this.isDemo
          &&  this.isRendered
          &&  localStorageService.isFirstLogin()
      ){
        this.getDemoModeWelcomeDialogTranslatedText()
        this.showDemoWelcomeDialog();
        localStorageService.setFirstLogin();
      }
    }
  }
}
</script>