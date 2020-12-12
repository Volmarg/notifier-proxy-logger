<!-- Template -->
<template>
  <footer class="footer section py-5">
    <div class="row">
      <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <p class="mb-0 text-center text-xl-left">
          <span v-html="footerTranslation"></span>
        </p>
      </div>
    </div>
  </footer>
</template>

<!-- Script -->
<script>
import SymfonyRoutes                               from "../../../core/symfony/SymfonyRoutes";
import GetTranslationsForIdsResponseDto            from "../../../core/dto/api/internal/GetTranslationsForIdsResponseDto";
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING} from "../../../env";
import LocalStorageService                         from "../../../core/services/LocalStorageService";

let localStorageService = new LocalStorageService;

export default {

  methods: {
    handleTranslations(){
      let footerTranslationId = 'mainPageComponents.footer';

      if( localStorageService.isTranslationForString(footerTranslationId) ){
        this.footerTranslation = localStorageService.getTranslationForString(footerTranslationId);
      }else{

        let ajaxData = {
          translationsIds: [
            footerTranslationId
          ]
        };

        this.axios({
          method: "POST",
          url: SymfonyRoutes.GET_TRANSLATIONS_FOR_IDS,
          params: ajaxData
        }).then( (response) => {
          let translationsDto    = GetTranslationsForIdsResponseDto.fromAxiosResponse(response);
          this.footerTranslation = translationsDto.translationsJsonForIds[footerTranslationId];

          localStorageService.setTranslationForString(footerTranslationId, this.footerTranslation);
        })

      }
    }
  },
  data(){
    return {
      footerTranslation: VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING
    }
  },
  beforeMount() {
    this.handleTranslations();
  }

}
</script>