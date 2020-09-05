<template>
    <div>
        <van-nav-bar
                title="物流信息"
                left-arrow
                :fixed="true"
                :placeholder="true"
                :z-index="99999"
                @click-left="prev"
        />

        <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
        <div v-if="!isEmpty">
            <div class="top">
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

            <div class="order">
                <div class="title">
                    <span>订单信息</span>
                </div>
                <van-cell-group>
                    <van-cell title="订单编号：" :value="order.order_no" />
                    <van-cell v-if="order.express.expName" title="快递名称：" :value="order.express.expName||''" />
                    <van-cell v-if="order.express.number" title="快递单号：" :value="order.express.number||''" />
                    <van-cell v-if="order.express.takeTime" title="物流耗时：" :value="order.express.takeTime||''" />
                    <van-cell v-if="order.express.updateTime" title="更新时间：" :value="order.express.updateTime||''" />
                </van-cell-group>

                <van-steps direction="vertical" :active="0" v-if="order.express.list">
                    <van-step v-for="(item,index) in order.express.list" :key="index">
                        <p>{{item.status}}</p>
                        <p>{{item.time}}</p>
                    </van-step>
                </van-steps>
            </div>

        </div>

    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { Step, Steps } from 'vant';
    import { Cell, CellGroup } from 'vant';
    import { Empty,Toast } from 'vant';
    export default {
        name: 'OrderExpress',
        components: {
            [NavBar.name]: NavBar,
            [Step.name]: Step,
            [Steps.name]: Steps,
            [Cell.name]: Cell,
            [CellGroup.name]: CellGroup,
            [Empty.name]: Empty
        },
        data() {
            return {
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "订单不存在！",
                active: 0,
                order:{
                    express:{
                        expName: "",
                        number: "",
                        takeTime: "",
                        updateTime: ""
                    },
                    accept_name: "",
                    address: "",
                    create_time: "",
                    mobile: "",
                    order_no: "",
                    region: ""
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
                this.$http.getOrderExpress({ id: id }).then((res)=>{
                    if(res.status){
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
            prev(){
                this.$router.replace('/order/list/1');
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
