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
import TranslationsService from "../../../../../../core/services/TranslationsService";

let translationService  = new TranslationsService();

export default {
  data(){
    return {
      mailingTranslations: translationService.getTranslationForString('sidebar.menu.nodes.mailing.label')
    }
  }
}
</script>