<template>
    <div>
        <van-nav-bar
            title="我的钱包"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />

        <div class="wallet-box" :style="'height:'+clientHeight+'px'">
            <div class="price-box">
                <span>我的余额</span>
                <span class="price">￥{{amount}}</span>
            </div>

            <!-- div class="btn">
                <van-button style="margin-top: 25px;" type="danger" round block>提现</van-button>
            </div -->
        </div>

    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { Button } from 'vant';
    export default {
        name: 'Wallet',
        components: {
            [NavBar.name]: NavBar,
            [Button.name]: Button
        },
        data() {
            return {
                clientHeight: window.outerHeight - 50,
                amount:0.00
            };
        },
        created() {
            let users = this.$storage.get("users",true);
            this.amount = users.amount;
            this.$request.get("/ucenter/info").then((res)=>{
                if(res.status){
                    this.amount = res.data.amount;
                    this.$store.commit("UPDATEUSERS",res.data);
                }
            });
        },
        methods: {
            prev() {
                this.$tools.prev();
            }
        },
    }
</script>

<style lang="scss" scoped>
.wallet-box{
    background-color: #fff;
    .price-box {
        height: 165px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        color: #909399;
        .price{
            font-size: 20px;
            color: #ff6c6c;
            margin-top: 12px;
        }
    }
    .btn{
        width: 90%; margin: 0 5%;
    }
}

</style>
