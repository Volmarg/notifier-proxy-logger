<!-- Template -->
<template>
  <tr class="volt-table-row">
    <table-cell v-for="(value, key, index) in rowData" :key="index" :cell-value="value"/>
  </tr>
</template>

<!-- Script -->
<script>
import TableCell from './table-cell';

export default {
  props: {
    "rowData": {
      type     : Object,
      required : true
    }
  },
  components: {
    "table-cell": TableCell
  }
}
</script>

<!-- Style -->
<style>
  .volt-table-row {
    border-bottom-color:  rgb(234, 237, 242);
    border-bottom-style:  solid;
    border-bottom-width:  1px;
    border-collapse:  collapse;
    border-left-color:  rgb(234, 237, 242);
    border-left-style:  solid;
    border-left-width:  0;
    border-right-color: rgb(234, 237, 242);
    border-right-style: solid;
    border-right-width: 0;
    border-top-color: rgb(234, 237, 242);
    border-top-style: solid;
    border-top-width: 0;
  }
</style>