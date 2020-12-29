<!-- Template -->
<template >
  <td class="border-0" v-if="cellValue" v-html="cellValue"></td>
</template>


<!-- Script -->
<script>
export default {
  props: {
    "cellValue": {
      types    : [String, Number],
      required : true
    }
  }
}
</script>