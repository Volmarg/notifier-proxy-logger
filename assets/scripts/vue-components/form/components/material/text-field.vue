<!-- Template -->
<template>
  <label class="mdc-text-field mdc-text-field--filled" :class="{'d-block': displayBlock}" ref="materialDesignTextField">
    <span class="mdc-text-field__ripple"></span>
    <span class="mdc-floating-label" :class="{'mdc-floating-label--float-above': (textFieldValue.length > 0) }" id="my-label-id">{{label}}</span>
    <input class="mdc-text-field__input" type="text" aria-labelledby="my-label-id" v-model="textFieldValue">
    <span class="mdc-line-ripple"></span>
  </label>
</template>

<!-- Script -->
<script>
import MaterialDesignTextField from '../../../../libs/material/TextField';

let materialDesignTextField = new MaterialDesignTextField();

export default {
  props: {
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
    "value": {
      default: "",
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
