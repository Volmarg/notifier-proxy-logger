<!-- Template -->
<template>
  <thead class="thead-light">
  <tr>
    <th v-for="header in tableHeaders" class="border-0">{{ header }}</th>
  </tr>
  </thead>
</template>

<!-- Script -->
<script>
export default {
  props: [
      'tableHeaders'
  ]
}
</script>