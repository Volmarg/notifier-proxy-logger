<!-- Template -->
<template>
  <li class="nav-item" :class="{'active': isParentNodeActive}">
    <span class="nav-link d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#submenu-discord" aria-expanded="false" ref="parentNodeArrowSpan">
      <span>
        <span class="sidebar-icon">
          <span class="fab fa-lg fa-discord"></span>
        </span>
        {{ discordMenuLabelTranslation }}
      </span>
      <span class="link-arrow">
        <span class="fas fa-chevron-right"></span>
      </span>
    </span>

    <div class="multi-level collapse" role="list" id="submenu-discord" aria-expanded="false" ref="childNodesListWrapper">
      <ul class="flex-column nav">
        <li class="nav-item" :class="{'active' : ( this.$route.name === 'modules_discord_test_sending' )}">
          <router-link :to="{ name: 'modules_discord_test_sending' }" class="nav-link">
            <span>{{ discordSubmenuTestSendingLabelTranslation }}</span>
          </router-link>
        </li>
        <li class="nav-item" :class="{'active' : ( this.$route.name === 'modules_discord_webhooks_manage' )}">
          <router-link :to="{ name: 'modules_discord_webhooks_manage'}" class="nav-link">
            <span>{{ discordSubmenuManageWebhooksLabelTranslation }}</span>
          </router-link>
        </li>
        <li class="nav-item" :class="{'active' : ( this.$route.name === 'modules_discord_history' )}">
          <router-link :to="{ name: 'modules_discord_history'}" class="nav-link">
            <span>
              {{ discordSubmenuHistoryLabelTranslation }}
            </span>
          </router-link>
        </li>
      </ul>
    </div>
  </li>
</template>

<!-- Script -->
<script>
import TranslationsService from "../../../../../../core/services/TranslationsService";
import SidebarNavMixing    from "../../../../../../core/mixins/sidebar-nav-mixin";

let translationService = new TranslationsService();

export default {
  computed: {
    discordMenuLabelTranslation : {
      get: function(){
        return translationService.getTranslationForString('sidebar.menu.nodes.discord.label');
      }
    },
    discordSubmenuTestSendingLabelTranslation : {
      get: function() {
        return translationService.getTranslationForString('sidebar.menu.nodes.discord.testSending.label');
      }
    },
    discordSubmenuManageWebhooksLabelTranslation : {
      get: function() {
        return translationService.getTranslationForString('sidebar.menu.nodes.discord.manageWebhooks.label');
      }
    },
    discordSubmenuHistoryLabelTranslation : {
      get: function(){
        return translationService.getTranslationForString('sidebar.menu.nodes.discord.history.label');
      }
    },
    nodesNames: function(){
      return [
        'modules_discord_test_sending',
        'modules_discord_webhooks_manage',
        'modules_discord_history',
      ];
    }
  },
  mixins: [
    SidebarNavMixing
  ]
}
</script>