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
      <a class="dropdown-item font-weight-bold" href="/logout"><span class="fas fa-sign-out-alt text-danger"></span>{{ logoutTranslation }}</a>
    </div>
  </li>
</template>

<!-- Script -->
<script>
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING}  from "../../../../../env";
import SymfonyRoutes                                from "../../../../../core/symfony/SymfonyRoutes";
import LoggedInUserDataDto                          from "../../../../../core/dto/api/internal/LoggedInUserDataDto";
import LocalStorageService                          from "../../../../../core/services/LocalStorageService";
import TranslationsService                          from "../../../../../core/services/TranslationsService";

let localStorageService = new LocalStorageService();
let translationService  = new TranslationsService();

export default {
  methods: {
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
      logoutTranslation     : translationService.getTranslationForString('topbar.menu.nodes.logout.label'),
      loggedInUserShownName : VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING
    };
  },
  beforeMount() {
    this.handleLoggedInUserData();
  }
}

</script>