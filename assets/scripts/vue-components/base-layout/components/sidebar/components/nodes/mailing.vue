<!-- Template -->
<template>
  <li class="nav-item">
    <router-link :to="{ name: 'modules_mailing_overview'}" class="nav-link">
      <span class="sidebar-icon">
            <span class="fas fa-envelope-open-text"></span>
        </span>
      <span>{{ mailingTranslations }}</span>
    </router-link>
  </li>
</template>

<!-- Script -->
<script>
import SymfonyRoutes                               from "../../../../../../core/symfony/SymfonyRoutes";
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING} from "../../../../../../env";
import GetTranslationsForIdsResponseDto            from "../../../../../../core/dto/api/internal/GetTranslationsForIdsResponseDto";
import LocalStorageService                         from "../../../../../../core/services/LocalStorageService";
import StringUtils                                 from "../../../../../../core/utils/StringUtils";

export default {
  data(){
    return {
      mailingTranslations: VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING
    }
  },
  beforeMount() {
    let localStorageService = new LocalStorageService();
    let mailingTranslation  = localStorageService.getTranslationForString('sidebar.menu.nodes.mailing.label');

    if( !StringUtils.isEmptyString(mailingTranslation) ){
      this.mailingTranslations = mailingTranslation;
    }else{

      let ajaxData = {
        translationsIds: [
          'sidebar.menu.nodes.mailing.label'
        ]
      };

      this.axios({
        method: "POST",
        url   : SymfonyRoutes.GET_TRANSLATIONS_FOR_IDS,
        params: ajaxData
      }).then( (response) => {

        let dto                  = GetTranslationsForIdsResponseDto.fromAxiosResponse(response);
        this.mailingTranslations = dto.translationsJsonForIds['sidebar.menu.nodes.mailing.label'];

        localStorageService.setTranslationForString('sidebar.menu.nodes.mailing.label', this.mailingTranslations);
      })
    }

  }
}
</script>