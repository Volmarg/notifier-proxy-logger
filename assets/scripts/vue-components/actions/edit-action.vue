<!-- Template -->
<template>
  <single-action @click="$emit('onEditActionClicked')" :tippy-content="editActionTranslatedString">
    <template #buttonIcon>
      <i class="far fa-edit action-icon"></i>
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
    'onEditActionClicked',
  ],
  components: {
    "single-action": SingleActionWrapper,
  },
  computed: {
    editActionTranslatedString: function(){
      return translationsService.getTranslationForString('actions.label.edit');
    }
  }
}
</script>

<!-- Style -->
<style>
.action-icon {
  margin-left: 3px;
}
</style>