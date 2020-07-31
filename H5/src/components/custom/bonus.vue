<template>
    <div>
        <div class="bonus-wrap">
            <div class="bonus-list">
                <div
                    class="bonus-box"
                    v-for="(item,index) in rdata.params.list"
                    :key="index"
                >
                    <div class="l">
                        <span><i>￥</i>{{item.amount}}</span>
                    </div>
                    <div class="m">
                        <div class="mm">
                            <span>{{item.name}}</span>
                            <span v-if="item.order_amount > 0">满{{item.order_amount}}可用</span>
                            <span v-else>无门槛</span>
                            <div>
                                <span>{{item.start_time}} 至</span>
                                <span>{{item.end_time}}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="r"
                        @click="onBuyBonus(item.id,index)"
                    ><span>立即领取</span></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Toast} from "vant";

    export default {
        name: "bonus",
        data(){
            return {

            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        methods:{
            onBuyBonus(id,index){
                this.$http.getCouponList({
                    id: id
                }).then(res=>{
                    if(res.status == 1){
                        Toast(res.info);
                    }else if(res.status == -999){
                        this.$router.push("/public/login");
                    }else{
                        Toast(res.info);
                    }
                }).catch(err=>{
                    Toast("网络出错，请检查是否己连接");
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
.bonus-wrap{
    width: 100%;
    height: auto;
    background-color: #fff;
    padding: 10px 0;
    .bonus-list{
        width: 92%;
        margin: 0 auto;
        .bonus-box{
            width: 100%;
            height: 100px;
            background-color: #b91922;
            border:1px solid #b91922;
            border-top-right-radius: 50px;
            border-bottom-right-radius: 50px;
            position: relative;
            margin-bottom: 10px;
            &:last-child {
                margin-bottom: 0;
            }
            &::before {
                position: absolute;
                left: -11px;
                top: 50%;
                transform: translateY(-50%);
                content: " ";
                width: 22px;
                height: 22px;
                background-color: #fff;
                border-radius: 50%;
            }
            .l{
                position: absolute;
                left: 0;
                top:0;
                width: 108px;
                height: 100px;
                border-right: 1px dashed #fff;
                overflow: hidden;
                span {
                    font-size: 63px;
                    color: #fff;
                    margin-left: 10px;
                    margin-top: 15px;
                    float: left;
                    i {
                        font-style: normal;
                        font-size: 18px;
                    }
                }
            }
            .m {
                padding: 0px 95px 0 108px;
                color: #fff;
                font-size: 14px;
                span {
                    display: block;
                }
                .mm {
                    padding: 15px 10px 10px 15px;
                }
            }
            .active{
                background-color: #fff;
                color: #666;
            }
            .r{
                width: 95px;
                height: 95px;
                position: absolute;
                top: 2px;
                right: 2px;
                color: #b91922;
                font-size: 26px;
                background-color: #fff;
                border-radius: 50px;
                text-align: center;
                span {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%,-50%);
                    width: 60px;
                    display: block;
                }
            }
        }
    }
}
</style>