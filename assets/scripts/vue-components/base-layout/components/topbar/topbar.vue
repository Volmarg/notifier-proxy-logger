<!-- Template -->
<template>
  <nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark pl-0 pr-2 pb-0">
    <div class="container-fluid px-0">
      <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
        <div class="d-flex"></div>
        <ul class="navbar-nav align-items-center">
          <Navigation/>
        </ul>
      </div>
    </div>
  </nav>
</template>

<!-- Script -->
<script>
import Navigation from './components/navigation';

export default {
  components: {
    Navigation
  }
}
</script>