<!-- Template -->
<template>
  <div class="mdc-dialog" ref="dialog">
    <div class="mdc-dialog__container">
      <div class="mdc-dialog__surface">
        <div class="mdc-dialog__content" id="my-dialog-content">
          <slot></slot>
        </div>
        <div class="mdc-dialog__actions">
          <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
            <div class="mdc-button__ripple"></div>
            <span class="mdc-button__label">{{ cancelButtonTranslatedString }}</span>
          </button>
          <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="discard">
            <div class="mdc-button__ripple"></div>
            <span class="mdc-button__label">{{ acceptButtonTranslatedString }}</span>
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
  props: {
    'cancelButtonTranslationString': {
      type     : String,
      required : false,
      default  : 'mainPageComponents.dialog.buttons.main.cancel.label',
    },
    'acceptButtonTranslationString': {
      type     : String,
      required : true
    }
  },
  data(){
    return {
      dialogInstance : null,
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
  }
}
</script>