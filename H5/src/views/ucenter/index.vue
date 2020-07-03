<template>
    <div>
        <div class="header">
            <img class="bg" src="../../assets/images/bg.png">
            <div class="avatar">
                <img src="../../assets/images/avatar.png">
            </div>
            <div class="userinfo">
                <span>{{username}}</span>
            </div>
        </div>

        <div class="money">
            <div class="m">
                <span>我的余额</span>
                <span>{{amount}}</span>
            </div>
            <div class="m">
                <span>优惠劵</span>
                <span>{{coupon}}</span>
            </div>
        </div>

        <div class="wrap-box clear">
            <div class="title">
                <span>我的订单</span>
            </div>
            <div class="list-box">
                <div class="list-item-box" @click="go('/order/list/1')">
                    <span><van-icon name="balance-pay" /></span>
                    <span>待付款</span>
                </div>
                <div class="list-item-box" @click="go('/order/list/2')">
                    <span><van-icon name="todo-list-o" /></span>
                    <span>待发货</span>
                </div>
                <div class="list-item-box" @click="go('/order/list/3')">
                    <span><van-icon name="logistics" /></span>
                    <span>待收货</span>
                </div>
                <div class="list-item-box" @click="go('/order/list/4')">
                    <span><van-icon name="like-o" /></span>
                    <span>待评价</span>
                </div>
                <div class="list-item-box" @click="go('/order/service')">
                    <span><van-icon name="gold-coin-o" /></span>
                    <span>退换货</span>
                </div>
            </div>
        </div>

        <div class="wrap-box clear">
            <div class="title">
                <span>我的服务</span>
            </div>
            <div class="list-box">
                <div class="list-item-i-box" @click="go('/ucenter/wallet')">
                    <span><van-icon name="balance-o" /></span>
                    <span>我的钱包</span>
                </div>
                <div class="list-item-i-box" @click="go('/ucenter/collect')">
                    <span><van-icon name="star-o" /></span>
                    <span>我的收藏</span>
                </div>
                <div class="list-item-i-box" @click="go('/ucenter/address')">
                    <span><van-icon name="location-o" /></span>
                    <span>收货地址</span>
                </div>
                <div class="list-item-i-box" @click="go('/ucenter/coupon')">
                    <span><van-icon name="cash-on-deliver" /></span>
                    <span>优惠劵</span>
                </div>
                <div class="list-item-i-box" @click="go('/ucenter/point')">
                    <span><van-icon name="points" /></span>
                    <span>我的积分</span>
                </div>
                <div class="list-item-i-box" @click="go('/ucenter/setting')">
                    <span><van-icon name="setting-o" /></span>
                    <span>会员设置</span>
                </div>
                <div class="list-item-i-box" @click="go('/ucenter/help')">
                    <span><van-icon name="warning-o" /></span>
                    <span>帮助中心</span>
                </div>
                <div class="list-item-i-box" @click="go('/ucenter/online')">
                    <span><van-icon name="user-o" /></span>
                    <span>联系客服</span>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { Icon } from 'vant';
export default {
    name: 'Ucenter',
    components: {
        [Icon.name]: Icon
    },
    data() {
        return {
            username:'',
            amount:0.00,
            coupon:0
        };
    },
    created() {
        let users = this.$storage.get("users",true);
        this.username = users.nickname || users.username || users.mobile;
        this.amount = users.amount;
        this.coupon = users.coupon_count;
        this.$http.getUcenter().then((res)=>{
            if(res.status){
                this.username = res.data.nickname || res.data.username || res.data.mobile;
                this.amount = res.data.amount;
                this.coupon = res.data.coupon_count;
                this.$store.commit("UPDATEUSERS",res.data);
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
    width: 100%;height: 185px;background: #f2f2f2;
    position: relative;overflow: hidden;
    position: relative; z-index: 1;
    .bg {
        width: 100%;height: 375px;display: block;
    }
    .avatar{
        position: absolute; z-index: 100; top: 50px; left: 50%;
        width: 80px;height: 80px;
        overflow: hidden;border: 2px solid #fff;
        border-radius: 100%;
        box-shadow: 1px 0px 5px rgba(50, 50, 50, 0.3);
        transform: translateX(-50%);
    }
    .avatar img { width: 100%; height: 100%; }
    .userinfo{
        position: absolute; z-index: 100; top: 145px; left: 50%;
        transform: translateX(-50%);
        span {
            font-size: 15px; color: #fff; font-weight: bold;
        }
    }
}
.money{
    width: 100%;
    height: 65px;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    background-color: #fff;
    align-items: center;
    .m {
        width: 50%;
        text-align: center;
        &:first-child {
            width: 49%;
            border-right: 1px solid #ddd;
        }
        span {
            width: 100%;
            display: block;
        }
        span:first-child{
            color: #aaa;
            font-size: 14px;
        }
        span:last-child{
            color: #666;
            font-size: 16px;
            padding-top: 5px;
        }
    }
}
.wrap-box{
    width: 100%;
    height: auto !important;
    height: 50px;
    min-height: 50px;
    margin: 10px 0;
    background-color: #fff;
    .title{
        width: 100%;
        height: 45px;
        line-height: 45px;
        border-bottom: 1px solid #eee;
        span {
            display: block;
            position: relative;
        }
        span:first-child {
            float: left;
            color: #333;
            font-size: 16px;
            margin-left: 10px;
        }
    }
    .list-box{
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
        .list-item-box{
            width: 20%;
            height: 70px;
        }
        .list-item-i-box{
            width: 25%;
            height: 70px;
        }
        span {
            display: block;
            text-align: center;
            color: #e93323;
        }
        span:first-child {
            padding-top: 8px;
            font-size: 27px;
        }
        span:last-child {
            font-size: 14px;
            color: #555;
            padding-top: 0px;
        }
    }
}
</style>
