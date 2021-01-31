<!-- Template -->
<template>
  <section class="d-flex justify-content-center row">
    <section class="col-8 text-center">
      <raw-content :content="displayedText"/>
    </section>
  </section>
</template>

<!-- Script -->
<script>
import RawContentComponent from '../../../vue-components/other/raw-content';

export default {
  props: {
    "displayedText" : {
      type     : String,
      required : true,
    }
  },
  components: {
    "raw-content" : RawContentComponent,
  }
}
</script>