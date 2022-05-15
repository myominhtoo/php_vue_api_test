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
#status-container{
    width:98%;
    margin:5px auto;
    padding:10px 5px;
    border-radius:3px;
    box-shadow:0 0 1.5px rgba(0,0,0,0.75);
    background: rgba(46, 230, 92 , 0.75);
}

#status-container.error{
   background: rgba(255, 97, 117,0.75);
}

.status{
    padding:0;
    margin:0;
}
</style>