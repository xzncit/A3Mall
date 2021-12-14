<template>
    <view>
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="订单信息"></navbar>
        <view class="order-info clear">
            <view class="title">订单创建成功</view>
            <view class="list">
                <view class="m">
                    <text>订单编号</text>
                    <text>{{order.order_no||""}}</text>
                </view>

                <view class="m">
                    <text>下单时间</text>
                    <text>{{order.create_time||""}}</text>
                </view>

                <view class="m">
                    <text>支付方式</text>
                    <text>{{order.payment_type||""}}</text>
                </view>

                <view class="m">
                    <text>支付金额</text>
                    <text>{{order.order_amount||""}}</text>
                </view>

                <view class="m">
                    <text>支付状态</text>
                    <text class="err">{{order.order_status||""}}</text>
                </view>
            </view>

            <view class="btn">
                <text class="success" @click="$utils.redirectTo('order/detail',{ id: order.order_id })">查看订单</text>
            </view>
        </view>

    </view>
</template>

<script>
	import navbar from "@/components/navbar/navbar";
    export default {
		components: {
			navbar
		},
        data() {
            return {
                order: {}
            };
        },
        onLoad() {
            let order_id = this.$storage.get("order_id");
            if(!order_id){
                this.$utils.switchTab("index/index");
            }

            let msg = this.$storage.get("order_msg");
            if(!msg){
                this.$utils.msg(msg);
            }
			
			this.$http.getCartInfo({
                order_id: order_id
            }).then(res=>{
                if(res.status){
                    this.order = res.data;
                }else{
                    this.$utils.switchTab("index/index");
                }

                this.$storage.remove("order_id");
                this.$storage.remove("order_msg");
            }).catch(err=>{
                this.$utils.switchTab("index/index");
            });
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
            text:first-child {
                float: left;
            }
            text:last-child{
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
        text {
            border-radius: 15px;
            text-align: center;
            width: 95%;
            height: 50px;
            line-height: 50px;
            display: block;
            font-size: 16px;
            margin: 0 2.5%;
            margin-top: 10px;
            &.success {
                background-color: #1b43c4;
                color: #fff;
            }
            &.err{
                background-color: #fff;
                color: #e93323;
                border: 1px solid #e93323;
            }
        }
    }
}
</style>