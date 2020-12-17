<!-- Template -->
<template>

  <div class="row">
    <div class="col-12 col-xl-8">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> todo  </h2>
          <section class="">
            <VoltTable>
              <VoltTableHead>
                <!-- todo: add headers as prop -> array -->
              </VoltTableHead>
              <VoltTableBody>
                <VoltTableRow/>
                <VoltTableRow/>
                <VoltTableRow/>
                <!-- tod some foreach (vue based - print rows) --->
              </VoltTableBody>
            </VoltTable>
          </section>
      </div>
    </div>
  </div>


</template>

<!-- Script -->
<script>
import VoltTable     from '../../../table/volt/table';
import VoltTableHead from '../../../table/volt/table-head';
import VoltTableBody from '../../../table/volt/table-body';
import VoltTableRow  from '../../../table/volt/table-row';

export default {
  components: {
    VoltTableBody,
    VoltTableHead,
    VoltTableRow,
    VoltTable
  }
}
</script>