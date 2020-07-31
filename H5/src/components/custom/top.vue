<template>
    <div>
        <div class="header">
            <div class="logo">
                <span>{{rdata.params.title}}</span>
                <span>{{rdata.params.ctitle}}</span>
            </div>
            <div class="cart" @click="$router.push('/cart/index')"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "top",
        props: {
            rdata:{
                required: true,
            }
        }
    }
</script>

<style lang="scss" scoped>
    .header{
        width: 93%;
        height: 35px;
        line-height: 45px;
        margin: 0 auto;
        position: relative;
        .logo {
            float: left;
            span:first-child{
                color:#fff;
                font-size: 21px;
                padding-right: 8px;
                position: relative;
            }
            span:first-child:after {
                border-right: 1px solid #dc8c91;
                width: 1px;
                height: 18px;
                content: " ";
                position: absolute;
                right: -2px;
                top: 4px;
            }
            span:last-child{
                color: #fff000;
                font-size: 19px;
                padding-left: 8px;
            }
        }
        .cart {
            float: right;
            width: 24px;
            height: 20px;
            background-image: url("../../assets/images/cart.png");
            background-size: 100%;
            background-repeat: no-repeat;
            position: relative;
            top: 12px;
        }
    }
</style>