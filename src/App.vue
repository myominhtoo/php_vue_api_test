<template>
  <!-- <Modal/> -->
  <h1 class="h4 w-100 bg p-3 fw-bold text">User Management Simple App</h1>
  <button class="btn btn-success btn-sm w-100 mt-3 mx-1 mb-3" @click="modals.addModal.showModal = true;"><i class="fa-solid fa-circle-plus"></i> Add User</button>
  <main>
    <!-- <button class="btn btn-success btn-sm float-end mt-3 mx-1 mb-3" @click="modals.addModal.showModal = true;"><i class="fa-solid fa-circle-plus"></i> Add User</button> -->
    <Status :msg="status.msg" :hasError="false" v-if="status.showStatus"/>
    <Table :users="users" :showUsers="showUsers" @delete:user="handleDelete"/>
  </main>

  <Modal @add:user="handleAddUser"  v-show="modals.addModal.showModal || modals.deleteModal.showModal" @close:modal="handleCloseModal">
    <template v-slot:addUser v-if="modals.addModal.showModal">
       <BaseInput type="text" label="Username"  @update:value="handleNameUpdate" holder="Enter username" :hasError="false"/>
       <p class="my-3"></p>
       <BaseInput type="email" label="Email" @update:value="handleEmailUpdate" holder="Enter email address" :hasError="false"/>
       <button type="submit" class="btn btn-success w-100 my-2">Add</button>
    </template>
    <template v-slot:deleteUser v-if="modals.deleteModal.showModal" >
      <h1 class="h5 fw-bold text-d">You are deleting <span class="text-danger">{{ temp.name  }}</span>!</h1>
      <p class="my-3 fw-bold">Are you sure??</p>
      <div class="d-flex justify-content-between">
         <button class="btn btn-warning btn-sm" @click="handleCloseModal">Cancel</button>
         <button class="btn btn-danger btn-sm" @click="handleDeleteUser(temp.id)">Confirm</button>
      </div>
    </template>
  </Modal>

  <!-- <h1>{{ user.name }} {{ user.email }}</h1> -->
  <footer class="w-100 bg text p-2">Copy Right @ {{ year }}</footer>
</template>

<script>
import Modal from './components/Modal.vue';
import Table from './components/Table.vue';
import Status from './components/Status.vue';
import BaseInput from './components/BaseInput.vue';

export default {
  components : { Modal  , Table  , Status , BaseInput },
  data(){
    return{
       user : {
        name : "",
        email : "",
      },
      temp : {
        id : "",
        name : "",
        email : "",
      },
      users : [],
      showUsers : false,
      year : "",
      modals : {
        addModal : {
          showModal : false,
        },
        updateModal : {
          showModal : false,
        },
        deleteModal : {
          showModal : false,
        },
      },
      status : {
        hasError : false,
        msg : "",
        showStatus : false,
      },
    }
  },
  methods : {
    handleNameUpdate(name){
      this.user.name = name;
    },
    handleEmailUpdate(email){
      this.user.email = email;
    },
    handleCloseModal(){
      const modals = this.modals;
      modals.addModal.showModal = false;
      modals.updateModal.showModal = false;
      modals.deleteModal.showModal = false;
    },
    handleAddUser(){
      this.modals.addModal.showModal = true;
      //this will do if true 
      if(this.modals.addModal.showModal){
        let data = new FormData();
        data.append("name",this.user.name);
        data.append("email",this.user.email);
        fetch(`http://localhost:8088/user_management_api/users.php?action=insert&name=${this.user.name}&email=${this.user.email}`).then(res => {
          if(!res.ok){
            this.status.showStatus = true;
            this.status.msg = "Error!";
            throw new Error("Error Occured");
          }
          this.user.name = "";
          this.user.email = "";
          this.modals.addModal.showModal = false;
          this.fetchUsers();
          return res.json();
        }).then(data => {
          this.status.showStatus = true;
          this.status.hasError = false;
          this.status.msg = data.msg;
        })
        .catch(err => console.error(err));

      }
    },
    handleDelete(id){
      let user = this.users.filter(user => {
        return user.id === id;
      });
      this.modals.deleteModal.
        showModal = true;
      this.temp.id = user[0].id;
      this.temp.name = user[0].name;
    },
    handleDeleteUser(id = ""){
      fetch(`http://localhost:8088/user_management_api/users.php?action=delete&id=${id}`)
      .then(res => {
        if(!res.ok){
          throw new Error("Error occured!");
        }
        this.handleCloseModal();
        return res.json();
      }).then(data => {
         this.status.showStatus = true;
         this.status.msg = data.msg;
      });
    },
    fetchUsers(){
      fetch('http://localhost:8088/user_management_api/users.php?action=readUsers')
      .then(res =>{
        if(!res.ok){
          throw new Error("Error happened!");
        }
        return res.json();
      })
      .then(data => {
        this.showUsers = true;
        this.users = data;
      }).catch(e => {
        console.log(e);
      })
    },
  },
  mounted(){
    this.year = new Date().getFullYear();
    this.fetchUsers();
  }
}
</script>

<style>
main{
  height:450px;
  max-height: 450px;
  overflow-y:scroll;
}

footer{
  position: absolute;
  bottom: 0;
  left:0;
}

</style>