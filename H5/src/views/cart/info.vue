<template>
    <div>
        <van-nav-bar
                title="订单信息"
                left-arrow
                :fixed="true"
                :placeholder="true"
                :z-index="99999"
                @click-left="prev"
        />

        <div class="order-info clear">
            <div class="title">订单创建成功</div>
            <div class="list">
                <div class="m">
                    <span>订单编号</span>
                    <span>wx159034757880255724</span>
                </div>

                <div class="m">
                    <span>下单时间</span>
                    <span>1590347578</span>
                </div>

                <div class="m">
                    <span>支付方式</span>
                    <span>H5微信支付</span>
                </div>

                <div class="m">
                    <span>支付金额</span>
                    <span>178.20</span>
                </div>

                <div class="m">
                    <span>支付状态</span>
                    <span class="err">失败</span>
                </div>
            </div>

            <div class="btn">
                <span>查看订单</span>
                <span class="err">重新发起支付</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { NavBar } from 'vant';
    export default {
        name: 'CartConfirm',
        components: {
            [NavBar.name]: NavBar
        },
        data() {
            return {}
        },
        methods: {
            prev() {
                this.$tools.prev();
            },
        }
    }
</script>

<style lang="scss" scoped>
.order-info{
    width: 92%;
    height: auto !important;
    height: 100px;
    background-color: #fff;
    margin: 0 auto;
    border-radius: 10px;
    min-height: 100px;
    position: relative;
    top: 50px;
    color: #555;
    .title{
        width: 95%;
        margin: 0 2.5%;
        float: left;
        height: 25px;
        padding: 20px 0;
        font-size: 16px;
        font-weight: 700;
        text-align: center;
        color: #333;
        border-bottom: 1px solid #eee;
    }
    .list{
        width: 95%;
        margin: 0 2.5%;
        float: left;
        font-size: 14px;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
        .m{
            width: 100%;
            height: 30px;
            line-height: 30px;
            span:first-child {
                float: left;
            }
            span:last-child{
                float: right;
            }
        }
    }
    .err {
        color: red;
    }
    .success {
        color: #029902;
    }
    .btn{
        float: left;
        width: 100%;
        padding: 10px 0 20px 0;
        span {
            background-color: #e93323;
            color: #fff;
            border-radius: 15px;
            text-align: center;
            width: 95%;
            height: 50px;
            line-height: 50px;
            display: block;
            font-size: 16px;
            margin: 0 2.5%;
            margin-top: 10px;
        }
        span:last-child{
            background-color: #fff;
            color: #e93323;
            border:1px solid #e93323;
        }
    }
}

</style>