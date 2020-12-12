<!-- Not used so far !-->
<!-- Template -->
<template>
  <section data-page-content-wrapper-top-bar>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
      <div class="btn-toolbar dropdown">
        <button class="btn btn-primary btn-sm mr-2 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
          <span class="fas fa-plus mr-2"></span>
          New Task
        </button>
        <div class="dropdown-menu dashboard-dropdown dropdown-menu-left mt-2">
          <a class="dropdown-item font-weight-bold" href="#">
            <span class="fas fa-tasks"></span>
            New Task
          </a>
          <a class="dropdown-item font-weight-bold" href="#">
            <span class="fas fa-cloud-upload-alt"></span>
            Upload Files
          </a>
          <a class="dropdown-item font-weight-bold" href="#">
            <span class="fas fa-user-shield"></span>
            Preview Security
          </a>
          <div role="separator" class="dropdown-divider"></div>
          <a class="dropdown-item font-weight-bold" href="#">
            <span class="fas fa-rocket text-danger"></span>
            Upgrade to Pro
          </a>
        </div>
      </div>
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-outline-primary">Share</button>
        <button type="button" class="btn btn-sm btn-outline-primary">Export</button>
      </div>
    </div>

  </section>
</template>

<!-- Script -->
<script>
export default {

}
</script>