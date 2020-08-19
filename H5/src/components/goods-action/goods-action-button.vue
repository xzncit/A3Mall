<template>
    <button class="goods-action-button" :class="'button-'+type" @click="btn">
        {{text}}
    </button>
</template>

<script>
    export default {
        name: "GoodsActionButton",
        props: {
            type: {
                type: String,
                default: ""
            },
            text: {
                type: String,
                default: "按钮"
            }
        },
        methods: {
            btn(){
                this.$emit("click");
            }
        }
    }
</script>

<style lang="scss" scoped>
.goods-action-button{
    position: relative;
    display: inline-block;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    line-height: 1.2;
    text-align: center;
    cursor: pointer;
    transition: opacity .2s;
    -webkit-appearance: none;
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    flex: 1;
    height: 50px;
    font-weight: 500;
    font-size: 14px;
    border: none;
    color: #fff;
}
.button-cart {
    background-color: #ffc03a;
}
.button-buy {
    background-color: #b91922;
}
</style>