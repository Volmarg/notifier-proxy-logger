<!-- Template -->
<template>
  <input type="hidden" name="_token" :value="csrfToken">
</template>

<!-- Script -->
<script>
export default {
  props: {
    "csrfToken": {
      type     : String,
      required : true,
    }
  }
}
</script>