<!-- Template -->
<template>
  <li class="nav-item">
    <router-link :to="{name: 'modules_dashboard_overview'}" class="nav-link">
        <span class="sidebar-icon">
            <span class="fas fa-chart-pie"></span>
        </span>
      <span>{{ dashboardTranslation }}</span>
    </router-link>
  </li>
</template>

<!-- Script -->
<script>
import SymfonyRoutes                               from "../../../../../../core/symfony/SymfonyRoutes";
import GetTranslationsForIdsResponseDto            from "../../../../../../core/dto/api/internal/GetTranslationsForIdsResponseDto";
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING} from "../../../../../../env";
import LocalStorageService                         from "../../../../../../core/services/LocalStorageService";
import StringUtils                                 from "../../../../../../core/utils/StringUtils";

export default {
  data(){
    return {
      dashboardTranslation: VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING
    }
  },
  beforeMount(){
    let localStorageService        = new LocalStorageService();
    let dashboardTranslationString = 'sidebar.menu.nodes.dashboard.label';
    let dashboardTranslation       = localStorageService.getTranslationForString(dashboardTranslationString);

    if( !StringUtils.isEmptyString(dashboardTranslation) ){
      this.dashboardTranslation = dashboardTranslation;
    }else{

      let ajaxData = {
        translationsIds: [
          dashboardTranslationString
        ]
      }

      this.axios({
        method: "POST",
        url: SymfonyRoutes.GET_TRANSLATIONS_FOR_IDS,
        params: ajaxData
      }).then( (response) => {
        let dto                   = GetTranslationsForIdsResponseDto.fromAxiosResponse(response);
        this.dashboardTranslation = dto.translationsJsonForIds[dashboardTranslationString];

        localStorageService.setTranslationForString(dashboardTranslationString, this.dashboardTranslation);
      })
    }
  }
}
</script>