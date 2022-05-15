<template>
    <div id="layer">
        <div id="modal">
            <div id="modal-title">
                <i class="fa-solid fa-xmark" id="modal-close" @click="handleCloseModal"></i>
            </div>
            <div id="modal-body" class="text-start">
                <form action="" class="form" @submit.prevent="handleSubmit">
                    <slot name="addUser"></slot>
                    <slot name="updateUser"></slot>
                </form>
                <slot name="deleteUser"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits : ["add:user","close:modal"],
    props : ['temp'],
    data(){
        return{
    
        }
    },
    methods : {
        handleSubmit(){
            this.$emit("add:user");
        },
        handleCloseModal(){
            this.$emit("close:modal");
        }
    },
    mounted(){
        this.data = this.temp;
    },
}
</script>

<style>
#layer{
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.8);
    display:flex;
    justify-content:center;
    align-items:center;
    transition: 0.8s all;
}

#modal{
    width:70%;
    height:auto;
    padding:0.5rem;
    background: rgba(255,255,255,1);
    padding:10px 30px;
    border-radius:3px;
    box-shadow: 0 0 2.5px rgba(255,255,255,0.75);
}

#modal #modal-title{
    display: flex;
    justify-content: flex-end;
}

#modal #modal-close{
    color:rgba(0,0,0,0.75);
    font-size:25px;
    font-weight: bold;
    transition:0.5s all;
}

#modal #modal-close:hover{
    transform: rotate(100deg);
}


</style>