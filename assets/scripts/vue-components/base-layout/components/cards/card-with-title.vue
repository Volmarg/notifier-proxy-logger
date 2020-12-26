<!-- Template -->
<template>
  <div class="col-12 col-lg-4 mb-4 position-relative">
    <semipolar-spinner v-show="isSpinnerVisible"/>

    <div class="card border-light shadow-sm">
      <div class="card-header border-bottom border-light">
        <h2 class="h5 mb-0">{{ cardTitle }}</h2>
      </div>
      <div class="card-body">
        <slot name="card-body"></slot>
      </div>
    </div>
  </div>
</template>

<!-- Script -->
<script>
import SemipolarSpinnerComponent from "../../../../vue-components/libs/epic-spinners/semipolar-spinner";

export default {
  props: {
    "cardTitle": {
      type     : String,
      required : true,
    },
    "isSpinnerVisible": {
      type     : Boolean,
      required : true
    }
  },
  components: {
    "semipolar-spinner": SemipolarSpinnerComponent
  }
}
</script>