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
                    <span>{{order.order_no||""}}</span>
                </div>

                <div class="m">
                    <span>下单时间</span>
                    <span>{{order.create_time||""}}</span>
                </div>

                <div class="m">
                    <span>支付方式</span>
                    <span>{{order.payment_type||""}}</span>
                </div>

                <div class="m">
                    <span>支付金额</span>
                    <span>{{order.order_amount||""}}</span>
                </div>

                <div class="m">
                    <span>支付状态</span>
                    <span class="err">{{order.order_status||""}}</span>
                </div>
            </div>

            <div class="btn">
                <span class="success" @click="$router.replace('/order/detail/'+order.order_id)">查看订单</span>
                <span class="err" @click="popupShow=true">重新发起支付</span>
            </div>
        </div>

        <van-popup
                v-model="popupShow"
                closeable
                position="bottom"
                :style="{ height: '40%' }"
        >
            <div class="popup-payment">
                <div class="payment-box">
                    <div class="payment-item" @click="selectPayment('wechat')">
                        <span><i class="fa fa-weixin" style="color: #fff;background-color: #41b035;"></i></span>
                        <span :class="{activeColor:payment == 'wechat'}">微信支付</span>
                        <span :class="{active:payment == 'wechat'}"><i class="fa fa-check"></i></span>
                    </div>
                    <div class="payment-item" @click="selectPayment('balance')">
                        <span><i class="fa fa-jpy" style="color: #fff;background-color: #fe960f;"></i></span>
                        <span :class="{activeColor:payment == 'balance'}">余额支付<i>可用余额: ￥{{order.users_price||"0.00"}}元</i></span>
                        <span :class="{active:payment == 'balance'}"><i class="fa fa-check"></i></span>
                    </div>
                </div>
            </div>

            <div class="payment-button">
                <van-button type="danger" @click="goPay" block>去支付</van-button>
            </div>
        </van-popup>
    </div>
</template>

<script>
    import {Button, NavBar, Popup, Toast} from 'vant';
    export default {
        name: 'CartInfo',
        components: {
            [Popup.name]: Popup,
            [NavBar.name]: NavBar,
            [Button.name]: Button
        },
        data() {
            return {
                popupShow: false,
                payment: "wechat",
                order: {}
            };
        },
        created() {
            let order_id = this.$storage.get("order_id",false,0);
            if(order_id <= 0){
                this.$router.replace('/');
            }

            let msg = this.$storage.get("order_msg",false,"");
            if(msg != ""){
                Toast(msg);
            }

            this.$request.get("/order/info",{
                order_id: order_id
            }).then(res=>{
                if(res.status){
                    this.order = res.data;
                }else{
                    this.$router.replace('/');
                }

                this.$storage.delete("order_id");
                this.$storage.delete("order_msg");
            }).catch(err=>{
                this.$router.replace('/');
            });
        },
        methods: {
            selectPayment(value){
                this.payment = value;
            },
            goPay(){
                Toast.loading({
                    message: '加载中...',
                    forbidClick: true,
                    loadingType: 'spinner',
                    duration: 0
                });

                this.$http.getOrderDetailPayment({
                    order_id: this.order.order_id,
                    payment_id: this.payment,
                    source: this.$tools.isWeiXin() ? 2 : 1
                }).then(res=>{
                    Toast.clear();
                    if(res.status){
                        this.resultOrderData(res.data);
                    }else{
                        Toast(res.info);
                    }
                }).catch(err=>{
                    Toast.clear();
                    Toast("网络出错，请检查网络是否连接");
                });
            },
            resultOrderData(data){
                this.popupShow = false;
                switch (data.pay+"") {
                    case "0":
                        Toast("支付成功");
                        this.$router.replace('/order/detail/'+data.order_id);
                        break;
                    case "1":
                        this.$wx.config(data.result.config);
                        var options = data.result.options;
                        options.success = function(){
                            Toast("支付成功");
                            this.$router.replace('/order/detail/'+data.order_id);
                        }
                        this.$wx.chooseWXPay(options);
                        break;
                    case "2":
                        location.href = data.result.url+"&redirect_url="+location.origin+'/order/detail/'+data.order_id;
                        break;
                    case "99":
                        Toast(data.msg);
                        break;
                }
            },
            prev() {
                this.$router.replace('/cart/index');
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
                    background-color: #e93323;
                    color: #fff;
                }
                &.err{
                    background-color: #fff;
                    color: #e93323;
                    border:1px solid #e93323;
                }
            }

        }
    }
    .payment-button{
        position: absolute;
        bottom: 0;
        width: 100%;
    }
    .popup-payment{
        position: relative;
        top: 40px;
        .payment-box{
            .payment-item{
                padding: 10px 16px;
                border-bottom: 1px solid #eee;
                span:first-child{
                    font-size: 15px;
                    i{
                        width: 20px;
                        height: 20px;
                        line-height:20px;
                        text-align: center;
                        border-radius:50%;
                        padding: 2px;
                    }
                }
                span:nth-child(2){
                    font-size: 15px;
                    padding-left: 10px;
                    i{
                        font-size: 12px;
                        font-style: normal;
                        color: #999;
                        padding-left: 10px;
                    }
                }
                span:nth-child(3){
                    float: right;
                    display: none;
                    color: #999;
                }
                span.active{
                    display: block;
                }
                span.activeColor{
                    color: red;
                }
            }
        }
    }
</style>