<!-- Template -->
<template>
  <li class="nav-item dropdown">
    <a class="nav-link pt-1 px-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <div class="media d-flex align-items-center">
        <img class="user-avatar md-avatar rounded-circle" alt="Image placeholder" src="/assets/images/demo/avatar.jpeg">
        <div class="media-body ml-2 text-dark align-items-center d-none d-lg-block">
          <span class="mb-0 font-small font-weight-bold">{{ loggedInUserShownName }}</span>
        </div>
      </div>
    </a>
    <div class="dropdown-menu dashboard-dropdown dropdown-menu-right mt-2">
      <a class="dropdown-item font-weight-bold" href="#logoutHrefHere"><span class="fas fa-sign-out-alt text-danger"></span>{{ logoutTranslation }}</a>
    </div>
  </li>
</template>

<!-- Script -->
<script>
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING}  from "../../../../../env";
import SymfonyRoutes                                from "../../../../../core/symfony/SymfonyRoutes";
import GetTranslationsForIdsResponseDto             from "../../../../../core/dto/api/internal/GetTranslationsForIdsResponseDto";
import LoggedInUserDataDto                          from "../../../../../core/dto/api/internal/LoggedInUserDataDto";
import LocalStorageService                          from "../../../../../core/services/LocalStorageService";

let localStorageService = new LocalStorageService();

export default {
  methods: {
    handleTranslations(){
      if( localStorageService.areTranslationsForStrings(['topbar.menu.nodes.logout.label']) ){

        this.logoutTranslation = localStorageService.getTranslationForString('topbar.menu.nodes.logout.label');
      }else{

        let ajaxData = {
          translationsIds: [
            'topbar.menu.nodes.logout.label'
          ]
        };

        this.axios({
          method: "POST",
          url   : SymfonyRoutes.GET_TRANSLATIONS_FOR_IDS,
          params: ajaxData
        }).then( (response) => {
          let translationDto     = GetTranslationsForIdsResponseDto.fromAxiosResponse(response);
          this.logoutTranslation = translationDto.translationsJsonForIds['topbar.menu.nodes.logout.label'];

          localStorageService.setTranslationForString('topbar.menu.nodes.logout.label', this.logoutTranslation);
        });
      }

    },
    handleLoggedInUserData(){

      if( localStorageService.isLoggedInUserSet() ){
        let dto = localStorageService.getLoggedInUser();
        this.loggedInUserShownName = dto.shownName;
      }else{

        this.axios({
          method: "GET",
          url: SymfonyRoutes.GET_LOGGED_IN_USER_DATA,
          params: []
        }).then( (response) => {
          let loggedInUserDto = LoggedInUserDataDto.fromAxiosResponse(response);

          localStorageService.setLoggedInUser(loggedInUserDto);

          this.loggedInUserShownName = loggedInUserDto.shownName;
        });
      }
    }
  },
  data(){
    return {
      logoutTranslation     : VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING,
      loggedInUserShownName : VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING
    };
  },
  beforeMount() {
    this.handleTranslations();
    this.handleLoggedInUserData();
  }
}

</script>