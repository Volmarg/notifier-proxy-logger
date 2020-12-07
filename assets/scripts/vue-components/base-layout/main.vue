<!-- Template -->
<template>
  <main class="content">
      <TopBar/>
      <PageContentWrapper/>
      <Footer/>
  </main>
</template>

<!-- Script -->
<script>
import TopBar             from './components/topbar/topbar';
import PageContentWrapper from './components/page-content-wrapper/page-content-wrapper';
import Footer             from './components/footer';

export default {
  components: {
    TopBar,
    PageContentWrapper,
    Footer
  }
}
</script>