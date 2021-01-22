<!-- Template -->
<template>
  <nav id="sidebarMenu" class="sidebar d-md-block bg-primary text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
      <ul class="nav flex-column">
          <logo/>
          <dashboard-menu-node/>
          <mailing-menu-node/>
          <discord-menu-node/>
<!--          <MenuFooter/>-->
      </ul>
    </div>
  </nav>
</template>

<!-- Script -->
<script>
import DashboardMenuNode  from './components/nodes/dashboard';
import MailingMenuNode    from './components/nodes/mailing';
import DiscordMenuNode    from './components/nodes/discord';
import MenuFooter         from './components/footer';
import LogoComponent      from './components/logo';

export default {
  components: {
    "dashboard-menu-node" : DashboardMenuNode,
    "mailing-menu-node"   : MailingMenuNode,
    "discord-menu-node"   : DiscordMenuNode,
    "menu-footer"         : MenuFooter,
    "logo"                : LogoComponent
  }
}
</script>