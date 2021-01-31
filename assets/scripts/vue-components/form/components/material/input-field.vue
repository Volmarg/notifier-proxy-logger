<!-- Template -->
<template>
  <label class="mdc-text-field mdc-text-field--filled m-0" :class="{'d-block': displayBlock, ['mt-' + marginTop] : true}" ref="materialDesignTextField">
    <span class="mdc-text-field__ripple"></span>
    <span class="mdc-floating-label special-text-color" :class="{'mdc-floating-label--float-above': (textFieldValue.length > 0) }" id="my-label-id">{{label}}</span>
    <input class="mdc-text-field__input" :type="type" aria-labelledby="my-label-id" v-model="textFieldValue">
    <span class="mdc-line-ripple"></span>
  </label>

  <div class="mdc-text-field-helper-line" v-if="'' !== errorMessage">
    <div class="text-danger mdc-text-field-helper-text mdc-text-field-helper-text--persistent mdc-text-field-helper-text--validation-msg">{{ errorMessage }}</div>
  </div>

</template>

<!-- Script -->
<script>
import MaterialDesignTextField from '../../../../libs/material/TextField';

let materialDesignTextField = new MaterialDesignTextField();

export default {
  props: {
    "errorMessage": {
      type     : String,
      required : false,
      default  : "",
    },
    "label": {
      type     : String,
      required : false,
      default  : "",
    },
    "displayBlock": {
      type     : Boolean,
      required : false,
      default  : false,
    },
    "marginTop": {
      type     : Number,
      required : false,
      default  : 0,
    },
    "value": {
      default: "",
    },
    "type": {
      type     : String,
      required : false,
      default  : "text",
    }
  },
  data(){
    return {
      textFieldValue: "",
    }
  },
  mounted(){
    let materialDesignTextFieldDomElement = this.$refs.materialDesignTextField;
    materialDesignTextField.initForDomElement(materialDesignTextFieldDomElement);

    this.textFieldValue = this.value;
  },
}
</script>

<!-- Style -->
<style scoped>
.special-text-color:focus {
  color: #1c4e7f !important;
}
</style>
