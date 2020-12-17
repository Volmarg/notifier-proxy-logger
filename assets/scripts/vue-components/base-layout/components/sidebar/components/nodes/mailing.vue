<!-- Template -->
<template>
  <li class="nav-item">
    <span class="nav-link d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#submenu-app" aria-expanded="true">
      <span>
        <span class="sidebar-icon">
          <span class="fas fa-envelope-open-text"></span>
        </span>
        {{ mailingTranslations }}
      </span>
      <span class="link-arrow">
        <span class="fas fa-chevron-right"></span>
      </span>
    </span>

    <div class="multi-level collapse show" role="list" id="submenu-app" aria-expanded="false" style="">
      <ul class="flex-column nav">
        <li class="nav-item active">
          <router-link :to="{ name: 'modules_mailing_overview' }" class="nav-link">
            <span>Test sending</span> <!-- todo: add translation -->
          </router-link>
          <router-link :to="{name: 'modules_mailing_history'}" class="nav-link">
            <span>History</span> <!-- todo: add label -->
          </router-link>
        </li>
      </ul>
    </div>
  </li>


</template>

<!-- Script -->
<script>
import TranslationsService from "../../../../../../core/services/TranslationsService";

let translationService = new TranslationsService();

export default {
  data() {
    return {
      mailingTranslations: translationService.getTranslationForString('sidebar.menu.nodes.mailing.label')
    }
  }
}
</script>