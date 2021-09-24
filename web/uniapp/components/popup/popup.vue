<template>
    <div class="mask" @click.stop="onClose" :class="{'hide':value==false,'show':value==true}">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props:{
            value:{
                type:Boolean,
                default: false
            }
        },
        methods:{
            onClose(){
                //this.$emit("input",!this.value);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .mask{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 8999;
        background-color: #000;
        opacity: 0.5;
    }
    .hide {
        display: none;
    }
    .show{
        display: block;
    }
</style>