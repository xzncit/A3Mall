<template>
    <div>
        <van-nav-bar
                title="确认订单"
                left-arrow
                :fixed="true"
                :placeholder="true"
                :z-index="99999"
                @click-left="prev"
        />

        <div class="top">
            <span class="top-map">
                <van-icon name="location-o" size="18px" color="#888" />
            </span>
            <div class="address" @click="isAddressStatus = true">
                <div class="info" v-if="address.tel">
                    <span v-if="address.name">收件人：{{ address.name }}</span>
                    <span v-if="address.tel">手机号：{{ address.tel }}</span>
                </div>
                <div class="info" v-if="!address.tel">
                    <span style="position: relative; top: -5px;">请选择地址</span>
                </div>
                <div class="address-info" v-if="address.address">{{ address.address }}</div>
            </div>
            <span class="arrow-right">
                <van-icon name="arrow" size="18px" color="#888" />
            </span>
        </div>

        <div class="goods">
            <div class="title">
                <span>共{{orderData.item.length}}件商品</span>
            </div>
            <div class="goods-box">
                <div class="goods-item clear" v-for="(item,index) in orderData.item" :key="index">

                    <div class="goods-img">
                        <img :src="item.thumb_image">
                    </div>

                    <div class="goods-info">
                        <div class="t">
                            <span>{{item.title}}</span>
                            <span>￥{{item.sell_price}}</span>
                        </div>
                        <div class="b">
                            <span v-if="item.goods_array">
                                <i v-for="(value,j) in item.goods_array" :key="j">
                                    {{value.name}}：{{value.value}}&nbsp;&nbsp;
                                </i>
                            </span>
                            <span>× {{item.goods_nums}}</span>
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
                <van-cell @click="isCouponStatus = !isCouponStatus" title="优惠劵：" :value="bonusText" />
                <van-cell title="商品金额：" :value="'￥'+orderData.real_amount+''" />
                <van-cell title="运费金额：" :value="'￥'+orderData.real_freight+''" />
                <van-cell v-if="orderData.real_point > 0" title="需要积分：" :value="''+orderData.real_point+'积分'" value-class="money" />
                <van-cell title="订单总额：" :value="'￥'+orderData.payable_amount+''" value-class="money" />
            </van-cell-group>
        </div>

        <div class="order">
            <div class="title">
                <span>备注内容</span>
            </div>
            <van-field
                    v-model="remarks"
                    rows="2"
                    autosize
                    label="留言"
                    type="textarea"
                    maxlength="100"
                    placeholder="请输入留言"
                    show-word-limit
            />
        </div>

        <div class="order">
            <div class="title">
                <span>支付方式</span>
            </div>
            <div class="payment-box">
                <div class="payment-item" @click="selectPayment('wechat')">
                    <span><i class="fa fa-weixin" style="color: #fff;background-color: #41b035;"></i></span>
                    <span :class="{activeColor:payment == 'wechat'}">微信支付</span>
                    <span :class="{active:payment == 'wechat'}"><i class="fa fa-check"></i></span>
                </div>
                <div class="payment-item" @click="selectPayment('balance')">
                    <span><i class="fa fa-jpy" style="color: #fff;background-color: #fe960f;"></i></span>
                    <span :class="{activeColor:payment == 'balance'}">余额支付<i>可用余额: ￥{{orderData.users_price}}元</i></span>
                    <span :class="{active:payment == 'balance'}"><i class="fa fa-check"></i></span>
                </div>
            </div>
        </div>

        <coupon
                v-model="isCouponStatus"
                :coupons="coupons"
                @coupon-event="onCoupons"
        ></coupon>

        <address-list
                v-model="isAddressStatus"
                :array="addressList"
                @onAdd="onAdd"
                @address-event="onSelectedAddress"
        >
        </address-list>

        <div class="operation-placeholder"></div>
        <div class="operation">
            <span class="amount">
                <i>合计：</i>
                <i v-if="orderData.order_amount">￥{{orderData.order_amount}}</i>
                <i v-else>￥{{orderData.payable_amount}}</i>
            </span>
            <span class="pay" @click="onOrderSubmit">提交订单</span>
        </div>

    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { Cell, CellGroup,Toast } from 'vant';
    import { Icon } from 'vant';
    import { Field } from 'vant';
    import Coupon from "../../components/coupon/coupon";
    import Address from "../../components/address/address";
    export default {
        name: 'CartConfirm',
        components: {
            [NavBar.name]: NavBar,
            [Cell.name]: Cell,
            [CellGroup.name]: CellGroup,
            [Icon.name]: Icon,
            [Field.name]: Field,
            [Address.name]: Address,
            [Coupon.name]: Coupon
        },
        data() {
            return {
                isCouponStatus: false,
                isAddressStatus: false,
                bonusText: "请选择",
                address:{
                    id: "",
                    name: "",
                    tel: "",
                    address: ""
                },
                chosenAddressId: '0',
                bonusId: '0',
                addressList: [],
                orderData: {
                    item:{},
                    real_amount: 0.00,
                    real_freight: 0.00,
                    payable_amount: 0.00,
                    order_amount: 0.00,
                    users_price:0.00,
                    real_point: 0,
                    type: 0
                },
                remarks: "",
                payment: "wechat",
                showCoupon: false,
                coupons: [],
                params:null,
                orderBtnFlag:false
            };
        },
        created() {
            let type = this.$route.query.type;
            let id = this.$route.query.id;
            let params = {
                id: id,type: type
            };

            if(type == undefined){
                this.$tools.prev();
            }

            if(this.$tools.in_array(type,["buy","point","second","regiment","special"])){
                params.sku_id = this.$route.query.sku_id;
                params.num = this.$route.query.num;
            }

            this.params = params;
            this.onLoadOrder();
        },
        methods: {
            onLoadOrder(){
                Toast.loading({
                    message: '加载中...',
                    forbidClick: true,
                    loadingType: 'spinner',
                    duration: 0
                });
                this.$http.getCartConfirm(this.params).then((res)=>{
                    Toast.clear();
                    if(res.status){
                        this.orderData = res.data;
                        let order_address_id = this.$storage.get("ORDER_ADDRESS_ID");
                        if(order_address_id){
                            for(let obj in res.data.address.list){
                                if(res.data.address.list[obj].id == order_address_id){
                                    this.chosenAddressId = res.data.address.list[obj].id;
                                    this.address = res.data.address.list[obj];
                                    break;
                                }
                            }
                        } else if(res.data.address.default){
                            this.chosenAddressId = res.data.address.default.id;
                            this.address = res.data.address.default;
                        }

                        if(res.data.address.list.length){
                            this.addressList = res.data.address.list;
                        }

                        this.coupons = res.data.bonus;
                        if(this.bonusText == '请选择'){
                            this.bonusText = res.data.bonus.length <= 0 ? "暂无优惠劵" : res.data.bonus.length + "张可用"
                        }
                    }else{
                        this.$router.push({
                            path: "/cart/msg",
                            query: {
                                msg: res.info
                            }
                        });
                    }
                });
            },
            prev(){
                this.$tools.prev();
            },
            onOrderSubmit(){
                if(this.orderBtnFlag){
                    return false;
                }

                this.orderBtnFlag = true;
                Toast.loading({
                    message: '加载中...',
                    forbidClick: true,
                    loadingType: 'spinner',
                    duration: 0
                });

                let params = {};
                Object.assign(params,{
                    id: this.params.id,
                    type: this.params.type,
                    address_id: this.chosenAddressId,
                    bonus_id: this.bonusId,
                    payment: this.payment,
                    remarks: this.remarks,
                    source: this.$tools.isWeiXin() ? 2 : 1,
                    url: document.location.href
                },this.params);
                this.$http.createOrder(params).then((res)=>{
                    Toast.clear();
                    if(res.status){
                        this.resultOrderData(res.data);
                    }else{
                        Toast(res.info);
                    }
                    this.orderBtnFlag = false;
                }).catch((err)=>{
                    Toast.clear();
                    Toast("网络连接错误，请检查网络是否可用");
                    this.orderBtnFlag = false;
                });
            },
            resultOrderData(data){
                this.$store.commit("UPDATECART",data.shop_count);
                switch (data.pay+"") {
                    case "0":
                        this.$router.replace('/order/detail/'+data.order_id);
                        break;
                    case "1":
                        this.$wx.config(data.result.config);
                        var options = data.result.options;
                        var that = this;
                        options.success = function(){
                            Toast("支付成功");
                            setTimeout(()=>{
                                that.$router.replace('/order/detail/'+data.order_id);
                            },1500);
                        }
                        this.$wx.chooseWXPay(options);
                        break;
                    case "2":
                        location.href = data.result.url+"&redirect_url="+location.origin+'/order/detail/'+data.order_id;
                        break;
                    case "99":
                        this.$storage.set("order_msg",data.msg);
                        this.$storage.set("order_id",data.order_id);
                        this.$router.replace('/cart/info');
                        break;

                }
            },
            selectPayment(value){
                this.payment = value;
            },
            onCoupons(value) {
                this.isCouponStatus = false;
                this.params.bonus_id = value.id;
                this.bonusText = value.value;
                this.bonusId = value.id;
                this.onLoadOrder();
            },
            onSelectedAddress(value){
                this.isAddressStatus = false;
                this.chosenAddressId = value.id;
                this.params.address_id = this.chosenAddressId;
                this.address = value;
                this.onLoadOrder();
            },
            onAdd() {
                this.$router.push({ path: '/ucenter/address/add' });
            }
        },
    }
</script>

<style lang="scss" scoped>
    .money{ color: #fc4141; }
    .van-address-item__edit { display: none; }
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
        .top-map{
            width: 30px;
            height: 30px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 10px;
        }
        .arrow-right{
            width: 30px;
            height: 30px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 0px;
        }
        .address{
            font-size: 14px;
            width: 85%;
            margin: 0 auto;
            padding: 10px 0px;
            padding-left: 20px;
            position: relative;
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
                    i{
                        font-style: normal;
                    }
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
        .amount{
            float: left;
            padding-top: 0;
            i{
                font-style: normal;
                font-size: 16px;
                color: #555;
            }
            i:last-child{
                color: #db1111;
                font-size: 17px;
                position: relative;
                top: 1px;
            }
        }
        span.cancel{
            color: #333;
            border: 1px solid #ddd;
        }
        span.pay {
            background-color: #e93323;
            color: #fff;
        }
    }
</style>
