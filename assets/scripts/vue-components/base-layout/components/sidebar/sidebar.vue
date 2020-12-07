<!-- Template -->
<template>
  <nav id="sidebarMenu" class="sidebar d-md-block bg-primary text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
      <ul class="nav flex-column">
          <DashboardMenuNode/>
          <MailingMenuNode/>
<!--          <MenuFooter/>-->
      </ul>
    </div>
  </nav>
</template>

<!-- Script -->
<script>
import DashboardMenuNode  from './components/nodes/dashboard';
import MailingMenuNode    from './components/nodes/mailing';
import MenuFooter         from './components/footer';

export default {
  components: {
    DashboardMenuNode,
    MailingMenuNode,
    MenuFooter
  }
}
</script>