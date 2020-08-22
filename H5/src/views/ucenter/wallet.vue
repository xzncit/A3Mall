<template>
    <div>
        <nav-bar
                left-arrow
                :fixed="true"
                :transparent="true"
                :z-index="9999"
                @click-left="prev"
        />
        <div class="header">
            <div class="title">我的钱包</div>
            <div class="rechange" @click="$router.push('/rechange/view')">充值 &gt;</div>
            <div class="info">
                <div>总资产(元)</div>
                <div>{{amount}}</div>
                <div>
                    <span>累计充值(元)：{{rechange_amount||"0.00"}}</span>
                    <span>|</span>
                    <span>累计消费(元)：{{consume_amount||"0.00"}}</span>
                </div>
            </div>
        </div>

        <div class="log">
            <div @click="$router.push('/ucenter/bill/cashlist')">
                <span><img src="../../assets/images/wallet/1.png"></span>
                <span>申请提现</span>
            </div>
            <div @click="$router.push('/ucenter/bill/fund')">
                <span><img src="../../assets/images/wallet/2.png"></span>
                <span>资金明细</span>
            </div>
            <div @click="$router.push('/ucenter/point')">
                <span><img src="../../assets/images/wallet/3.png"></span>
                <span>积分中心</span>
            </div>
        </div>

        <div class="receive">
            <div class="c" @click="$router.push('/point')">
                <div>
                    <span>积分商品兑换</span>
                    <span>赚积分抵现金</span>
                </div>
            </div>
            <div class="c" @click="$router.push('/coupon')">
                <div>
                    <span>领取优惠券</span>
                    <span>满减享优惠</span>
                </div>
            </div>
        </div>

        <div class="guide">
            <div @click="$router.push('/group')">
                <span><img src="../../assets/images/wallet/6.png"></span>
                <span>拼团</span>
            </div>
            <div @click="$router.push('/regiment')">
                <span><img src="../../assets/images/wallet/7.png"></span>
                <span>团购</span>
            </div>
            <div @click="$router.push('/second')">
                <span><img src="../../assets/images/wallet/8.png"></span>
                <span>秒杀</span>
            </div>
            <div @click="$router.push('/special')">
                <span><img src="../../assets/images/wallet/10.png"></span>
                <span>会员特价</span>
            </div>
        </div>
    </div>
</template>

<script>
    import NavBar from '../../components/nav-bar/nav-bar';
    export default {
        name: 'Wallet',
        components: {
            [NavBar.name]: NavBar
        },
        data() {
            return {
                clientHeight: window.outerHeight - 50,
                amount:0.00,
                rechange_amount:0.00,
                consume_amount:0.00,
            };
        },
        created() {
            let users = this.$storage.get("users",true);
            this.amount = users.amount;
            this.$http.getWallet().then((res)=>{
                if(res.status){
                    this.amount = res.data.amount;
                    this.rechange_amount = res.data.rechange_amount;
                    this.consume_amount = res.data.consume_amount;
                }
            });
        },
        methods: {
            prev() {
                this.$tools.prev();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .header{
        width: 100%;
        background-image: url(../../assets/images/wallet-bg.png);
        background-size: 100%;
        background-repeat: no-repeat;
        height: 210px;
        position: relative;
        z-index: 1;
        .title{
            width: 100%;
            color: #fff;
            font-size: 17px;
            position: absolute;
            top: 13px;
            left: 0;
            text-align: center;
        }
        .rechange{
            position: absolute;
            top: 75px;
            right: 0;
            width: 90px;
            height: 30px;
            line-height: 30px;
            background-color: #cb565c;
            color: #fff;
            border-top-left-radius: 50px;
            border-bottom-left-radius: 50px;
            text-align: center;
            z-index: 9999;
        }
        .info{
            position: absolute;
            top: 75px;
            left: 15px;
            color: #fff;
            div:nth-child(1){
                font-size: 16px;
            }
            div:nth-child(2){
                font-size: 29px;
                padding-top: 10px;
            }
            div:nth-child(3){
                font-size: 13px;
                padding-top: 15px;
                span:nth-child(2){
                    padding: 0 5px;
                    position: relative;
                    top: -1px;
                }
            }
        }
    }
    .log{
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
        background-color: #fff;
        div{
            width: 33.333%;
            height: 100px;
            span {
                display: block;
                text-align: center;
                &:first-child{
                    margin-top: 20px;
                }
                &:last-child {
                    margin-top: 10px;
                }
            }
            &:nth-child(1){
                img { width: 31px; height: 29px; }
            }
            &:nth-child(2){
                img { width: 27px; height: 31px; }
            }
            &:nth-child(3){
                img { width: 36px; height: 28px; }
            }
        }
    }
    .receive{
        width: 100%;
        height: 90px;
        margin-top: 10px;
        background-color: #fff;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        .c {
            width: 50%;
            height: 90px;
            div{
                position: relative;
                margin-top: 20px;
                margin-left: 60px;
                span:first-child { font-size: 16px; color: #b91922 }
                span:last-child { padding-top: 4px; font-size: 12px; color: #999999; }
            }
            &:first-child div:before {
                position: absolute;
                left: -40px;
                top: 6px;
                content: " ";
                width: 30px;
                height: 33px;
                background-size: 100%;
                background-repeat: no-repeat;
                background-image: url(../../assets/images/wallet/4.png);
            }
            &:last-child div:before {
                position: absolute;
                left: -32px;
                top: -1px;
                content: " ";
                width: 23px;
                height: 41px;
                background-size: 100%;
                background-repeat: no-repeat;
                background-image: url(../../assets/images/wallet/5.png);
            }
            span {
                display: block;
            }
        }
    }
    .guide{
        width: 100%;
        margin-top: 10px;
        background-color: #fff;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        margin-bottom: 10px;
        div{
            width: 50%;
            height: 45px;
            line-height: 45px;
            border-bottom: 2px solid #f9f9f9;
            padding: 20px 0;
            img { width: 45px; height: 45px; display: block; }
            span {
                float: left;
                &:first-child { margin-left: 35px; }
                &:last-child { margin-left: 15px; }
            }
        }
    }


</style>
