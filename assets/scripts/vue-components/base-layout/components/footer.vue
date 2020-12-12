<!-- Template -->
<template>
  <footer class="footer section py-5">
    <div class="row">
      <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <p class="mb-0 text-center text-xl-left">
          <span v-html="footerTranslation"></span>
        </p>
      </div>
    </div>
  </footer>
</template>

<!-- Script -->
<script>
import TranslationsService from "../../../core/services/TranslationsService";

let translationService = new TranslationsService();

export default {
  data(){
    return {
      footerTranslation: translationService.getTranslationForString('mainPageComponents.footer'),
    }
  }
}
</script>