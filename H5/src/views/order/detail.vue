<template>
    <div>
        <van-nav-bar
                title="订单详情"
                left-arrow
                :fixed="true"
                :placeholder="true"
                :z-index="99999"
                @click-left="prev"
        />

        <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
        <div v-if="!isEmpty">
            <div class="top">
                <div class="status">
                    <van-steps :active="active">
                        <van-step>待付款</van-step>
                        <van-step>待发货</van-step>
                        <van-step>待收货</van-step>
                        <van-step>待评价</van-step>
                        <van-step>己完成</van-step>
                    </van-steps>
                </div>

                <div class="address">
                    <div class="info">
                        <span>收件人：{{order.accept_name}}</span>
                        <span>手机号：{{order.mobile}}</span>
                    </div>
                    <div class="address-info">
                        {{order.region}} {{order.address}}
                    </div>
                </div>
            </div>

            <div class="goods">
                <div class="title">
                    <span>共{{order.item.length}}件商品</span>
                </div>
                <div class="goods-box">
                    <div
                        class="goods-item clear"
                        v-for="(value,index) in order.item"
                        :key="index"
                        @click="$router.push('/goods/view/'+value.goods_id)"
                    >

                        <div class="goods-img">
                            <img :src="value.thumb_image">
                        </div>

                        <div class="goods-info">
                            <div class="t">
                                <span>{{value.title}}</span>
                                <span>￥{{value.sell_price}}</span>
                            </div>
                            <div class="b">
                                <span>{{value.spec}}</span>
                                <span>× {{value.nums}}</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="order">
                <div class="title">
                    <span>订单信息</span>
                </div>
                <van-cell-group>
                    <van-cell title="订单编号：" :value="order.order_no" />
                    <van-cell title="下单时间：" :value="order.create_time" />
                    <van-cell title="订单类型：" :value="order.type" />
                    <van-cell title="支付状态：" :value="order.pay_status" />
                    <van-cell title="支付方式：" :value="order.pay_type" />
                </van-cell-group>
            </div>

            <div class="order">
                <van-cell-group>
                    <van-cell title="商品金额：" :value="order.real_amount" />
                    <van-cell title="运费金额：" :value="order.payable_freight" />
                    <van-cell title="订单总额：" :value="order.order_amount" value-class="money" />
                </van-cell-group>
            </div>

            <div v-if="active != 4">
                <div class="operation-placeholder"></div>
                <div class="operation">
                    <span class="cancel" v-if="order.order_status==1" @click="cancel">取消订单</span>
                    <span class="pay" v-if="order.order_status==1" @click="popupShow=true">立即付款</span>

                    <span class="confirm" v-if="order.order_status == 2 || order.order_status==3 || order.order_status==4" @click="confirm">确认收货</span>
                    <span class="refund" v-if="order.order_status == 2 || order.order_status==3 || order.order_status==4" @click="refund">申请退款</span>

                    <span class="evaluate" v-if="order.order_status==5" @click="evaluate">待评价</span>
                </div>
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
                        <span :class="{activeColor:payment == 'balance'}">余额支付<i>可用余额: ￥{{order.users_price}}元</i></span>
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
import { NavBar } from 'vant';
import { Step, Steps } from 'vant';
import { Cell, CellGroup } from 'vant';
import { Empty,Toast,Popup,Button } from 'vant';
export default {
    name: 'OrderDetail',
    components: {
        [NavBar.name]: NavBar,
        [Step.name]: Step,
        [Steps.name]: Steps,
        [Cell.name]: Cell,
        [CellGroup.name]: CellGroup,
        [Popup.name]: Popup,
        [Empty.name]: Empty,
        [Button.name]: Button
    },
    data() {
        return {
            popupShow: false,
            payment: "wechat",
            isEmpty: false,
            emptyImage: "search",
            emptyDescription: "订单不存在！",
            active: 0,
            order:{
                accept_name: "",
                address: "",
                create_time: "",
                item: [],
                mobile: "",
                order_amount: "",
                order_no: "",
                pay_status: "",
                pay_type: "",
                payable_freight: '',
                payable_amount: "",
                promotions: "",
                real_amount: "",
                region: "",
                type: "",
                users_price:"0.00",
                order_status: 1
            }
        };
    },
    created() {
        this.onLoadOrder();
    },
    mounted() {

    },
    methods: {
        onLoadOrder(){
            let id = this.$route.params.id;
            this.isEmpty = false;
            this.$http.getOrderDetail({
                id: id
            }).then((res)=>{
                if(res.status){
                    this.active = res.data.active;
                    this.order = res.data;
                }else{
                    Toast(res.info);
                }
            }).catch((err)=>{
                this.isEmpty = true;
                this.emptyImage = "network";
                this.emptyDescription = "网络出错，请检查网络是否连接";
            });
        },
        selectPayment(value){
            this.payment = value;
        },
        prev(){
            this.$tools.prev();
        },
        goPay(){
            Toast.loading({
                message: '加载中...',
                forbidClick: true,
                loadingType: 'spinner',
                duration: 0
            });

            this.$http.getOrderDetailPayment({
                order_id: this.$route.params.id
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
            switch (data.pay+"") {
                case "0":
                    this.$router.replace('/order/detail/'+data.order_id);
                    break;
                case "1":
                    this.$wx.config(data.result.config);
                    var options = data.result.options;
                    options.success = function(){
                        Toast("支付成功");
                        setTimeout(()=>{
                            this.$router.replace('/order/detail/'+data.order_id);
                        },1500);
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
        confirm(){
            this.$router.push('/order/confirm_delivery/' + this.$route.params.id);
        },
        refund(){
            this.$router.push('/order/refund/' + this.$route.params.id);
        },
        evaluate(){
            this.$router.push('/order/evaluate/' + this.$route.params.id);
        },
        cancel(){
            Toast.loading({
                message: '加载中...',
                forbidClick: true,
                loadingType: 'spinner',
                duration: 0
            });

            let order_id = this.$route.params.id;
            this.$http.getOrderDetailCancel({
                order_id: order_id
            }).then(res=>{
                Toast.clear();
                if(res.status){
                    Toast(res.info);
                    this.$router.replace('/order/list/1');
                }else{
                    Toast(res.info);
                }
            }).catch(err=>{
                Toast.clear();
                Toast("网络出错，请检查网络是否连接");
            });
        }
    },
}
</script>

<style lang="scss" scoped>
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
.money{ color: #fc4141; }
.top{
    background-color: #fff;
    position: relative;
    &:before{
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 2px;
        background: -webkit-repeating-linear-gradient(135deg,#ff6c6c 0,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);
        background: repeating-linear-gradient(-45deg,#ff6c6c 0,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);
        background-size: 80px;
        content: '';
    }
    .status{
        width: 95%;
        margin: 0 auto;
    }
    .address{
        font-size: 14px;
        width: 92%;
        margin: 0 auto;
        .info{
            height: 30px;
            line-height: 30px;
            span:first-child{
                padding-right: 10px;
            }
            span:last-child{

            }
        }
        .address-info{
            height: 30px;
            line-height: 20px;
        }
    }
}
.goods{
    background-color: #fff;
    margin-top: 15px;
    padding-bottom: 10px;
    .title{
        width: 100%;
        margin: 0 auto;
        color: #666;
        font-size: 14px;
        height: 40px;
        line-height: 40px;
        border-bottom: 1px solid #eee;
        span {
            padding-left: 10px;
        }
    }
    .goods-box{
        padding: 0 16px;
        .goods-item {
            padding-top: 10px;
            .goods-img {
                width: 77px;
                height: 77px;
                display: inline-block;
                float: left;
                img{
                    width: 100%;
                    height: 100%;
                }
            }
            .goods-info {
                display: inline-block;
                width: 72%;
                font-size: 14px;
                float: right;
                .t {
                    width: 100%;
                    height: 45px;
                    span:first-child{
                        float: left;
                        display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        width: 70%;
                    }
                    span:last-child{
                        width: 30%;
                        float: right;
                        text-align: right;
                    }
                }
                .b{
                    width: 100%;
                    height: 40px;
                    font-size: 13px;
                    span:first-child{
                        float: left;
                        color: #999;
                    }
                    span:last-child{
                        float: right;
                        color: #666;
                    }
                }
            }
        }
    }
}
.order{
    background-color: #fff;
    margin-top: 15px;
    padding-bottom: 10px;
    .title{
        width: 100%;
        margin: 0 auto;
        color: #666;
        font-size: 14px;
        height: 40px;
        line-height: 40px;
        border-bottom: 1px solid #eee;
        span {
            padding-left: 10px;
        }
    }

}
.operation-placeholder{
    width: 100%;
    height: 70px;
    line-height: 70px;
}
.operation{
    width: 100%;
    height: 55px;
    line-height: 55px;
    text-align: right;
    background-color: #fff;
    position: fixed;
    left: 0;
    bottom: 0;
    border-top: 1px solid #eee;
    span{
        font-size: 14px;
        text-align: center;
        border-radius: 15px;
        background-color: #fff;
        padding: 8px 15px;
        margin-right: 10px;
    }
    span.cancel{
        color: #333;
        border: 1px solid #ddd;
    }
    span.pay {
        background-color: #e93323;
        color: #fff;
    }
    .confirm{
        color: #fff;
        background-color: #01AAED;
    }
    .refund{
        color: #fff;
        background-color: #FF5722;
    }
    .evaluate{
        color: #fff;
        background-color: #009688;
    }
}
</style>
