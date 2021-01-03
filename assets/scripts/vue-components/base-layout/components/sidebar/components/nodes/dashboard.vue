<!-- Template -->
<template>
  <li class="nav-item" :class="{'active' : ( this.$route.name === 'modules_dashboard_overview' )}">
    <router-link :to="{name: 'modules_dashboard_overview'}" class="nav-link">
        <span class="sidebar-icon">
            <span class="fas fa-lg fa-chart-pie"></span>
        </span>
      <span>{{ dashboardTranslation }}</span>
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
      dashboardTranslation: translationService.getTranslationForString('sidebar.menu.nodes.dashboard.label'),
    }
  }
}
</script>