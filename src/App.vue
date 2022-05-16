<template>
  <!-- <Modal/> -->
  <h1 class="h4 w-100 bg p-3 fw-bold text">User Management Simple App</h1>
  <div class="text-end px-2" id="bar"><button class="btn btn-success btn-sm mt-3 mx-1 mb-3 p-2" @click="modals.addModal.showModal = true;"><i class="fa-solid fa-circle-plus"></i> Add User</button></div>
  <main>
    <!-- <button class="btn btn-success btn-sm float-end mt-3 mx-1 mb-3" @click="modals.addModal.showModal = true;"><i class="fa-solid fa-circle-plus"></i> Add User</button> -->
    <Status :msg="status.msg" :hasError="status.hasError" v-if="status.showStatus"/>
    <Table :users="users" :showUsers="showUsers" @delete:user="handleDelete" @update:user="handleUpdate"/>
  </main>

  <Modal @add:user="handleAddUser" @update:user="handleUpdateUser(temp.id)"  v-show="modals.addModal.showModal || modals.deleteModal.showModal || modals.updateModal.showModal" @close:modal="handleCloseModal">
    <!-- for add user -->
    <template v-slot:addUser v-if="modals.addModal.showModal">
       <BaseInput type="text" label="Username"  @update:value="handleNameUpdate" holder="Enter username" :hasError="false"/>
       <p class="my-3"></p>
       <BaseInput type="email" label="Email" @update:value="handleEmailUpdate" holder="Enter email address" :hasError="false"/>
       <button type="submit" class="btn btn-success w-100 my-2">Add</button>
    </template>
    <!-- for delete -->
    <template v-slot:deleteUser v-if="modals.deleteModal.showModal" >
      <h1 class="h5 fw-bold text-d">You are deleting <span class="text-danger">{{ temp.name  }}</span>!</h1>
      <p class="my-3 fw-bold">Are you sure??</p>
      <div class="d-flex justify-content-between">
         <button class="btn btn-warning btn-sm" @click="handleCloseModal">Cancel</button>
         <button class="btn btn-danger btn-sm" @click="handleDeleteUser(temp.id)">Confirm</button>
      </div>
    </template>
    <template v-slot:updateUser v-if="modals.updateModal.showModal">
        <BaseInput type="hidden" :value="temp.id"/>
        <BaseInput type="text" label="Username"  @update:value="handleNameUpdate" :value="temp.name" holder="Enter username" :hasError="false"/>
       <p class="my-3"></p>
       <BaseInput type="email" label="Email" @update:value="handleEmailUpdate" :value="temp.email" holder="Enter email address" :hasError="false"/>
       <button type="submit" class="btn btn-success w-100 my-2">Update</button>
    </template>
  </Modal>

  <!-- <h1 class="text-daner">{{ user.name }}</h1> -->
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
        // let data = new FormData();
        // data.append("name",this.user.name);
        // data.append("email",this.user.email);
        fetch(`http://localhost:8088/user_management_api/users.php?action=insert&name=${this.user.name}&email=${this.user.email}`).then(res => {
          if(!res.ok){
            this.status.showStatus = true;
            this.status.msg = "Error!";
            throw new Error("Error Occured");
          }
          return res.json();
        }).then(data => {
          this.fetchUsers();
          this.status.showStatus = true;
          this.status.hasError = data.error;
          this.status.msg = data.msg;
          this.user.name = "";
          this.user.email = "";
          this.handleCloseModal();
        })
        .catch(err => console.error(err));

      }
    },
    handleDelete(id){
      let user = this.users.filter(user => {
        return user.id === id;
      });
      this.modals.deleteModal.showModal = true;
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
         this.fetchUsers();
      }).catch(e => {
        console.log(e);
      });
    },
    handleUpdate(id){
      let user = this.users.filter( user => {
        return id === user.id;
      });
      this.modals.updateModal.showModal = true;
      this.temp.id = user[0].id;
      this.temp.name = user[0].name;
      this.temp.email = user[0].email;
      //for submit
      this.user.name = user[0].name;
      this.user.email = user[0].email;
    },
   handleUpdateUser(id){
      let name = this.user.name;
      let email = this.user.email;

      fetch(`http://localhost:8088/user_management_api/users.php?action=update&id=${id}&name=${name}&email=${email}`)
      .then(res => res.json())
      .then(data => {
        this.fetchUsers();
        this.handleCloseModal();
        this.status.showStatus = true;
        this.status.hasError = data.error;
        this.status.msg = data.msg;
        this.user.name = "";
        this.user.email = "";
      });
    },
    fetchUsers(){
      fetch('http://localhost:8088/user_management_api/users.php?action=readUsers')
      .then(res =>{
        if(!res.ok){
          throw new Error("Error happened!");
        }
        return res.json();
      }).then(data => {
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

</style>