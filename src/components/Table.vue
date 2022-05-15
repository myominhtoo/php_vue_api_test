<template>
  <table class="table table-striped table-dark" v-if="showUsers">
      <thead class="sticky-top">
          <tr class="text-danger fw-bold text-start">
             <td>ID</td>
             <td>Name</td>
             <td>Email</td>
             <td>Update</td>
             <td>Delete</td>
          </tr>
      </thead>
      <tbody>
          <tr v-if="users.length == 0">
              <td colspan="5" class="text-center text-danger fw-bold">There is no user to show!</td>
          </tr>
          <tr v-for="(user,idx) in users" :key="idx">
              <td>{{ idx + 1 }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td id="update"><i class="fa-solid fa-pen-to-square" id="update-btn" @click="handleUpdate(user.id)"></i></td>
              <td id="delete"><i class="fa-solid fa-trash-can" id="delete-btn" @click="handleDelete(user.id,user.name)"></i></td>
          </tr>
      </tbody>
  </table>
</template>

<script>
export default {
    props : ['users','showUsers'],
    emits : ['delete:user','update:user'],
    methods : {
        handleDelete(id){
            this.$emit("delete:user",id);
        },
        handleUpdate(id){
            this.$emit("update:user",id);
        }
    }
}
</script>

<style>
.table{
    width:95%;
    margin:0 auto;

}

#update-btn{
    color:rgba(255,255,255,0.75);
    box-shadow:0 0 0.5px rgba(0,0,0,0.15);
    padding:10px 20px;
    background: rgba(161, 94, 13,0.35);
}

#delete-btn{
    color:rgba(255,255,255,0.75);
    box-shadow:0 0 0.5px rgba(0,0,0,0.15);
    padding:10px 20px;
    background: rgba(196, 14, 32 , 0.35);
}

tbody tr{
    text-align: left;
}
</style>