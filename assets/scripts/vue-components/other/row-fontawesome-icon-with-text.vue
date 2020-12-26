<!-- Template -->
<template>
  <div class="row align-items-center mb-2">
    <div class="col-auto">
      <span class="icon icon-md text-purple icon-size">
        <slot name="icon"></slot>
      </span>
    </div>
    <div class="col">
      <div class="progress-wrapper">
        <div class="progress-info mb-0">
          <div class="h6 mb-0">
            <span class="d-inline-block title">
              <slot name="title"></slot>
            </span>
            &nbsp;
            <span class="d-inline-block">
              <slot name="title-context"></slot>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Script -->
<script>
export default {

}
</script>

<!-- Style -->
<style scoped>
.icon-size {
  font-size: 20px;
}

.title {
  min-width: 100px;
  text-transform: capitalize;
}
</style>