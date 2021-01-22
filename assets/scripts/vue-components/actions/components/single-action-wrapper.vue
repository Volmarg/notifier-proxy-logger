<!-- Template -->
<template>
  <section class="action-button-wrapper btn btn-sm" :class="classes" :data-tippy-content="tippyContent" ref="buttonWrapper">
    <slot name="buttonIcon"></slot>
  </section>
</template>

<!-- Script -->
<script>

import Tippy from "../../../libs/tippy/Tippy";

let tippy = new Tippy();

export default {
  props: {
    classes: {
      type     : Object,
      required : false,
      default  : {
      }
    },
    tippyContent: {
      type     : String,
      required : false,
      default  : "",
    }
  },
  mounted(){
    this.tippyInstance = tippy.applyForElement(this.$refs.buttonWrapper);
  },
}
</script>
