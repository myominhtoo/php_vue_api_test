<template>
    <div>
        <label for="" class="form-label fw-bold ">{{ label }}</label>
        <input :type="type" :placeholder="holder" :class="attr"   :value="value" @input="handleUpdate" required autofocus/>
    </div>
</template>

<script>
export default {
    props : ["label","type","holder","classes","hasError","value"],
    emits : ["update:value"],
    data(){
        return{
            attr : "",
        }
    },
    methods : {
        handleUpdate(e){
            this.$emit('update:value',e.target.value);
        }
    },
    mounted(){
        switch(this.type){
            case "radio":
            case "checkbox":
                this.attr = "form-check-input";break;
            default : this.attr = "form-control"
        }

    }

}
</script>

<style>

</style>