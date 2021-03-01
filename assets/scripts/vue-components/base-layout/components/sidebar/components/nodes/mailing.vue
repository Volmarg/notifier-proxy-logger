<!-- Template -->
<template>
  <li class="nav-item" :class="{'active': isParentNodeActive}">
    <span class="nav-link d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#submenu-mailing" aria-expanded="false" ref="parentNodeArrowSpan">
      <span>
        <span class="sidebar-icon">
          <span class="fas fa-lg fa-envelope-open-text"></span>
        </span>
        {{ mailingTranslations }}
      </span>
      <span class="link-arrow">
        <span class="fas fa-chevron-right"></span>
      </span>
    </span>

    <div class="multi-level collapse" role="list" id="submenu-mailing" aria-expanded="false" style="" ref="childNodesListWrapper">
      <ul class="flex-column nav">
        <li class="nav-item" :class="{'active' : ( this.$route.name === 'modules_mailing_overview' )}">
          <router-link :to="{ name: 'modules_mailing_overview' }" class="nav-link">
            <span>{{ testSendingTranslation }}</span>
          </router-link>
        </li>
        <li class="nav-item" :class="{'active': ( this.$route.name === 'modules_mailing_history' )}">
          <router-link :to="{name: 'modules_mailing_history'}" class="nav-link">
            <span>{{ historyTranslation }}</span>
          </router-link>
        </li>
        <li class="nav-item" :class="{'active': ( this.$route.name === 'modules_mailing_settings' )}">
          <router-link :to="{name: 'modules_mailing_settings'}" class="nav-link">
            <span>{{ settings }}</span>
          </router-link>
        </li>
      </ul>
    </div>
  </li>
</template>

<!-- Script -->
<script>
import TranslationsService from "../../../../../../core/services/TranslationsService";
import SidebarNavMixin from '../../../../../../core/mixins/sidebar-nav-mixin';

let translationService = new TranslationsService();

export default {
  computed: {
    mailingTranslations : {
      get: function(){
        return translationService.getTranslationForString('sidebar.menu.nodes.mailing.label');
      }
    },
    testSendingTranslation : {
      get: function() {
        return translationService.getTranslationForString('sidebar.menu.nodes.mailing.testSending.label');
      }
    },
    historyTranslation : {
      get: function(){
        return translationService.getTranslationForString('sidebar.menu.nodes.mailing.history.label');
      }
    },
    settings : {
      get: function(){
        return translationService.getTranslationForString('sidebar.menu.nodes.discord.settings.label');
      }
    },
    nodesNames: function(){
      return [
          'modules_mailing_overview',
          'modules_mailing_history',
          'modules_mailing_settings',
      ];
    },
  },
  mixins: [
    SidebarNavMixin,
  ],
}
</script>