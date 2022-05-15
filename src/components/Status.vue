<template>
  <div id="status-container" class="d-flex align-items-center " :class="{error : hasError}">
      <h1 class="h6 status p-0 m-0 fw-bold text-dark text-start">
          {{  msg }}
      </h1>
  </div>
</template>

<script>
export default {
    props : ["msg","hasError"],
}
</script>
<style>
</style>