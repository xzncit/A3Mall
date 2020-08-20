<template>
    <div :class="{'wrap':placeholder}">
        <div class="nav-bar" :class="{'nav-bar-fixed': fixed,'nav-bar-transparent':transparent}" :style="obj">
            <div class="nav-bar-left" v-if="leftArrow" @click="left">
                <i class="icon iconfont" :class="{'nav-bar-icon':transparent}" style="font-size: 18px;">&#xe60d;</i>
            </div>
            <div class="nav-bar-middle" :class="{'nav-bar-title':transparent}">{{title}}</div>
            <div class="nav-bar-right" v-if="rightArrow" @click="right">
                <i v-if="rightIcon=='share'" class="icon iconfont" :class="{'nav-bar-icon':transparent}" style="font-size: 18px;">&#xe60e;</i>
                <i v-if="rightIcon=='delete'" class="icon iconfont" :class="{'nav-bar-icon':transparent}" style="font-size: 16px;">&#xe652;</i>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NavBar",
        props:{
            title: {
                type: String,
                default: ""
            },
            zIndex: {
                type: Number,
                default: 0
            },
            fixed: {
                type:Boolean,
                default: false
            },
            transparent: {
                type:Boolean,
                default: false
            },
            placeholder: {
                type:Boolean,
                default: false
            },
            leftArrow: {
                type: Boolean,
                default: false
            },
            rightArrow: {
                type: Boolean,
                default: false
            },
            rightIcon: {
                type: String,
                default: ""
            }
        },
        data(){
            return {
                obj:{}
            };
        },
        mounted() {
            if(this.zIndex > 0){
                this.obj = { "z-index": this.zIndex };
            }
        },
        methods: {
            left(){
                this.$emit("click-left");
            },
            right(){
                this.$emit('click-right');
            }
        }
    }
</script>

<style lang="scss" scoped>
    .wrap{
        height: 49px;
    }
    .nav-bar{
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        height: 49px;
        line-height: 1.5;
        text-align: center;
        background-color: #fff;
        user-select: none;
        border-bottom: 1px solid #ebedf0;
    }
    .nav-bar-fixed {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
    }
    .nav-bar-transparent{
        background-color: transparent;
        border-bottom: 0px solid #ebedf0;
    }
    .nav-bar-icon { color: #fff; }
    .nav-bar-left, .nav-bar-right {
        position: absolute;
        top: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        padding: 0 16px;
        font-size: 14px;
        cursor: pointer;
    }
    .nav-bar-middle{
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        max-width: 60%;
        margin: 0 auto;
        color: #323233;
        font-weight: 500;
        font-size: 16px;
    }
    .nav-bar-title {
        color: #fff;
    }
    .nav-bar-left {
        left: 0;
    }
    .nav-bar-right {
        right: 0;
    }
</style>