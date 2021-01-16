<!--
 This component can be used to render html from provided string
-->

<!-- Template -->
<template>
  <span v-html="content"></span>
</template>

<!-- Script -->
<script>
export default{
  props: {
    content: {
      required : true,
    }
  },
}
</script>