<!-- Template -->
<template>
  <table class="table table-centered table-nowrap mb-0 rounded" ref="table">
    <slot></slot>
  </table>
</template>

<!-- Script -->
<script>
import DataTables from '../../../libs/datatable/DataTables';

let dataTables = new DataTables();

export default {
  updated(){
    let domElement = this.$refs.table;
    dataTables.initForDomElement(domElement);
  }
}

</script>