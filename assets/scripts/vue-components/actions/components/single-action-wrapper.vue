<!-- Template -->
<template>
  <section class="button-wrapper btn btn-sm" :class="classes">
    <slot name="buttonIcon"></slot>
  </section>
</template>

<!-- Script -->
<script>
export default {
  props: {
    classes: {
      type     : Object,
      required : false,
      default  : {
        "btn-secondary" : true
      },
    }
  }
}
</script>
