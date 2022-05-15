<template>
    <div id="layer">
        <div id="modal">
            <div id="modal-title">
                <i class="fa-solid fa-xmark" id="modal-close" @click="handleCloseModal"></i>
            </div>
            <div id="modal-body" class="text-start">
                <form action="" class="form" @submit.prevent="handleAddSubmit">
                    <slot name="addUser"></slot>
                </form>
                <form action="" class="form" @submit.prevent="handleUpdate">
                     <slot name="updateUser"></slot>
                </form>
                <slot name="deleteUser"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits : ["add:user","close:modal","update:user"],
    methods : {
        handleAddSubmit(){
            this.$emit("add:user");
        },
        handleCloseModal(){
            this.$emit("close:modal");
        },
        handleUpdate(){
            this.$emit("update:user");
        }
    },
    mounted(){
        this.data = this.temp;
    },
}
</script>

<style>

</style>