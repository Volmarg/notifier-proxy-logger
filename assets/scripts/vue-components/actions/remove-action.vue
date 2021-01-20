<!-- Template -->
<template>
  <single-action @click="$emit('onRemoveActionClicked')" :classes="actionWrapperClasses">
    <template #buttonIcon>
        <i class="fas fa-trash-alt action-icon"></i>
    </template>
  </single-action>
</template>

<!-- Script -->
<script>

import SingleActionWrapper from '../actions/components/single-action-wrapper';

export default {
  components: {
    "single-action": SingleActionWrapper,
  },
  data(){
    return {
      actionWrapperClasses: {
        'btn-danger-blackened' : true
      }
    }
  },
}
</script>

<!-- Style -->
<style>
.action-icon {
  padding-left: 1px;
  padding-right: 2px;
}
</style>