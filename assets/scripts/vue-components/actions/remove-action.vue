<!-- Template -->
<template>
  <single-action @click="$emit('onRemoveActionClicked')" :tippy-content="removeActionTranslatedString">
    <template #buttonIcon>
        <i class="fas fa-trash-alt action-icon"></i>
    </template>
  </single-action>
</template>

<!-- Script -->
<script>
import TranslationsService from "../../core/services/TranslationsService";

import SingleActionWrapper from '../actions/components/single-action-wrapper';

let translationsService = new TranslationsService();

export default {
  emits: [
    'onRemoveActionClicked',
  ],
  components: {
    "single-action": SingleActionWrapper,
  },
  computed: {
    removeActionTranslatedString: function(){
      return translationsService.getTranslationForString('actions.label.remove');
    }
  }
}
</script>

<!-- Style -->
<style>
.action-icon {
  padding-left: 1px;
  padding-right: 2px;
}
</style>