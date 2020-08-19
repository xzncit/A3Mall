<template>
    <div>

        <div class="header">
            <div class="my">我的</div>
            <div class="info">
                <span class="avatar">
                    <img :src="avatar">
                </span>
                <span>{{username}}</span>
            </div>

            <div class="amount">
                <div>
                    <span>￥{{amount}}</span>
                    <span>我的余额</span>
                </div>
                <div>
                    <span>{{coupon}}</span>
                    <span>优惠券</span>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="content-box">
                <div class="title">我的订单</div>
                <div class="list-box">
                    <div class="box" @click="go('/order/list/1')">
                        <span>
                            <img src="../../assets/images/ucenter/1.png">
                            <i v-if="order_count.a">{{order_count.a}}</i>
                        </span>
                        <span>待付款</span>
                    </div>
                    <div class="box" @click="go('/order/list/2')">
                        <span>
                            <img src="../../assets/images/ucenter/2.png">
                            <i v-if="order_count.b">{{order_count.b}}</i>
                        </span>
                        <span>待发货</span>
                    </div>
                    <div class="box" @click="go('/order/list/3')">
                        <span>
                            <img src="../../assets/images/ucenter/3.png">
                            <i v-if="order_count.c">{{order_count.c}}</i>
                        </span>
                        <span>待收货</span>
                    </div>
                    <div class="box" @click="go('/order/list/4')">
                        <span>
                            <img src="../../assets/images/ucenter/4.png">
                            <i v-if="order_count.d">{{order_count.d}}</i>
                        </span>
                        <span>待评价</span>
                    </div>
                    <div class="box" @click="go('/order/service')">
                        <span><img src="../../assets/images/ucenter/5.png"></span>
                        <span>退换货</span>
                    </div>
                </div>
            </div>

            <div class="content-box">
                <div class="title">我的服务</div>
                <div class="list-box service-box">
                    <div class="box" @click="go('/ucenter/wallet')">
                        <span><img src="../../assets/images/ucenter/7.png"></span>
                        <span>我的钱包</span>
                    </div>
                    <div class="box" @click="go('/ucenter/collect')">
                        <span><img src="../../assets/images/ucenter/8.png"></span>
                        <span>我的收藏</span>
                    </div>
                    <div class="box" @click="go('/ucenter/address')">
                        <span><img src="../../assets/images/ucenter/9.png"></span>
                        <span>收货地址</span>
                    </div>
                    <div class="box" @click="go('/ucenter/coupon')">
                        <span><img src="../../assets/images/ucenter/10.png"></span>
                        <span>优惠券</span>
                    </div>
                    <div class="box" @click="go('/ucenter/point')">
                        <span><img src="../../assets/images/ucenter/11.png"></span>
                        <span>我的积分</span>
                    </div>
                    <div class="box" @click="go('/ucenter/setting')">
                        <span><img src="../../assets/images/ucenter/12.png"></span>
                        <span>会员设置</span>
                    </div>
                    <div class="box" @click="go('/ucenter/help')">
                        <span><img src="../../assets/images/ucenter/13.png"></span>
                        <span>帮助中心</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: 'Ucenter',
        data() {
            return {
                username:'',
                amount:0.00,
                coupon:0,
                avatar: "",
                order_count: {}
            };
        },
        created() {
            let users = this.$storage.get("users",true);
            this.username = users.nickname || users.username || users.mobile;
            this.amount = users.amount;
            this.coupon = users.coupon_count;
            this.avatar = users.avatar;
            this.$http.getUcenter().then((res)=>{
                if(res.status){
                    this.username = res.data.nickname || res.data.username || res.data.mobile;
                    this.amount = users.amount = res.data.amount;
                    this.coupon = users.coupon_count = res.data.coupon_count;
                    this.avatar = users.avatar = res.data.avatar;
                    this.order_count = res.data.order_count;
                    this.$store.commit("UPDATEUSERS",users);
                }
            });
        },
        methods: {
            go(value,param){
                let params = param || "";
                if(params == ""){
                    this.$router.push({
                        path: value
                    });
                }else{
                    this.$router.push({
                        path: value,
                        query: params
                    });
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
    .header{
        width: 100%;
        height: 295px;
        background-image: url(../../assets/images/ucenter-bg.png);
        background-repeat: no-repeat;
        background-size: 100%;
        position: relative;
        .my{
            width: 100%;
            font-size: 17px;
            color: #fff;
            text-align: center;
            display: block;
            position: absolute;
            top: 20px;
        }
        .info{
            position: absolute;
            top: 65px;
            left: 20px;
            span {
                float: left;
                img { width: 70px; height: 70px; display: block; border-radius: 50%; }
                &:last-child {
                    height: 70px; line-height: 70px; padding-left: 12px; font-size: 18px; color: #fff;
                }
            }
        }
        .amount{
            position: absolute;
            top: 155px;
            width: 100%;
            div {
                width: 50%; float: left;
                span { display: block; color: #fff; text-align: center }
                span:first-child { font-size: 17px; }
                span:last-child { font-size: 13px; padding-top: 5px; }
            }
        }
    }
    .content {
        margin: -70px 20px 0 20px;
        position: relative;
        .content-box{
            width: 100%;
            float: left;
            border-radius: 5px;
            background-color: #fff;
            &:last-child { margin-top: 15px; margin-bottom: 15px; }
            .title{
                font-size: 16px;
                color: #666;
                width: 100%;
                float: left;
                border-bottom: 1px solid #ebebeb;
                height: 50px;
                line-height: 50px;
                text-indent: 15px;
            }
            .list-box{
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                flex-direction: row;
                .box{
                    width: 20%;
                    padding: 20px 0;
                    span {
                        display: block; text-align: center; font-size: 13px; color: #666;
                        &:first-child {
                            position: relative;
                            i {
                                position: absolute;
                                top: 5px;
                                right: 19px;
                                box-sizing: border-box;
                                min-width: 16px;
                                padding: 0 3px;
                                color: #fff;
                                font-weight: 500;
                                font-size: 12px;
                                line-height: 14px;
                                text-align: center;
                                background-color: #ee0a24;
                                border: 1px solid #fff;
                                border-radius: 16px;
                                transform: translate(50%,-50%);
                                transform-origin: 100%;
                            }
                        }
                    }
                    img { width: 40px; height: 40px; display: inline-block; }
                }
            }
            .service-box{
                .box{
                    width: 25%;
                    padding: 20px 0;
                    span { display: block; text-align: center; font-size: 13px; color: #666; }
                    img { width: 40px; height: 40px; display: inline-block; }
                }
            }
        }
    }
</style>
