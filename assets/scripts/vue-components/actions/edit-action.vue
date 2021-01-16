<!-- Template -->
<template>
  <single-action @click="$emit('onEditActionClicked')">
    <template #buttonIcon>
      <i class="far fa-edit action-icon"></i>
    </template>
  </single-action>
</template>

<!-- Script -->
<script>

import SingleActionWrapper from '../actions/components/single-action-wrapper';

export default {
  components: {
    "single-action": SingleActionWrapper,
  }
}
</script>

<!-- Style -->
<style>
.action-icon {
  margin-left: 3px;
}
</style>