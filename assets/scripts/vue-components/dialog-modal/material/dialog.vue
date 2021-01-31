<!-- Template -->
<template>
  <div class="mdc-dialog" ref="dialog">
    <div class="mdc-dialog__container">
      <div class="mdc-dialog__surface" :style="{'min-width': minWidth}">
        <div class="mdc-dialog__content" id="my-dialog-content">
          <slot></slot>
        </div>
        <div class="mdc-dialog__actions">
          <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
            <div class="mdc-button__ripple"></div>
            <span class="mdc-button__label modal-action-button special-text-color">{{ cancelButtonTranslatedString }}</span>
          </button>
          <button type="button" class="mdc-button mdc-dialog__button" :data-mdc-dialog-action="dialogDiscardAction" @click="$emit('materialModalConfirmButtonClick')">
            <div class="mdc-button__ripple"></div>
            <span class="mdc-button__label modal-action-button special-text-color">{{ acceptButtonTranslatedString }}</span>
          </button>
        </div>
      </div>
    </div>
    <div class="mdc-dialog__scrim"></div>
  </div>
</template>


<!-- Script -->
<script>
import Dialog               from "../../../libs/material/Dialog";
import TranslationsService  from "../../../core/services/TranslationsService";

let translationService = new TranslationsService();
let dialog             = new Dialog();

export default {
  emits: [
    'materialModalConfirmButtonClick',
  ],
  props: {
    'cancelButtonTranslationString': {
      type     : String,
      required : false,
      default  : 'mainPageComponents.dialog.buttons.main.cancel.label',
    },
    'acceptButtonTranslationString': {
      type     : String,
      required : true
    },
    'minWidth': {
      type     : String,
      required : false,
      default  : '300px',
    },
    'hideOnError' : {
      type     : Boolean,
      required : false,
      default  : true,
    }
  },
  data(){
    return {
      dialogInstance      : null,
      dialogDiscardAction : '',
    }
  },
  computed: {
    cancelButtonTranslatedString: function(){
      return translationService.getTranslationForString(this.cancelButtonTranslationString);
    },
    acceptButtonTranslatedString: function(){
      return translationService.getTranslationForString(this.acceptButtonTranslationString);
    }
  },
  mounted(){
    let editActionDialogDomElement = this.$refs.dialog;
    this.dialogInstance            = dialog.initForDomElement(editActionDialogDomElement);

    if(this.hideOnError){
      this.dialogDiscardAction = "discard";
    }
  }
}
</script>

<!-- Style -->
<style scoped lang="scss">
.modal-action-button {
  font-weight: bold;
}

.special-text-color {
  color: #1c4e7f;
}
</style>