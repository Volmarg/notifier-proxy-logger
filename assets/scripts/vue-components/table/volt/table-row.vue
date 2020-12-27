<!-- Template -->
<template>
  <tr class="volt-table-row" :data-tippy-content="tippyRowBodyContent" ref="tableRow">
    <table-cell v-for="(value, key, index) in rowData" :key="index" :cell-value="value"/>
  </tr>
</template>

<!-- Script -->
<script>
import TableCellComponent from './table-cell';

/**
 * @description tippy js is handled in the DataTables.ts file, as  the logic for tippy MUST be
 *              applied alongside with DataTables
 */
export default {
  props: {
    "rowData": {
      type     : Object,
      required : true
    },
    "tippyRowBodyContent": {
      type     : String,
      required : false,
      default  : ""
    }
  },
  components: {
    "table-cell": TableCellComponent
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