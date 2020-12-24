<!-- Template -->
<template >
  <td class="border-0" v-if="cellValue">
    {{ cellValue }}
  </td>
</template>


<!-- Script -->
<script>
export default {
  props: [
      'cellValue'
  ]
}
</script>