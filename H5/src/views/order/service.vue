<template>
    <div>
        <van-nav-bar
                title="售后列表"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

        <div class="list-wrap">
            <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
            <van-list
                    v-if="!isEmpty"
                    v-model="loading"
                    :finished="finished"
                    finished-text="没有更多了"
                    @load="onLoad"
            >
                <div class="list-box">

                    <div class="list-item-box" v-for="(item,index) in list" :key="index">
                        <div class="top">
                            <span class="order-type">{{item.type}}</span>
                            <span class="time">{{item.create_time}}</span>
                            <span class="satus">{{item.pay_status}}</span>
                        </div>
                        <div class="goods-box" @click="$router.push('/order/detail/'+item.order_id)">
                            <div class="goods-item clear" v-for="(value,j) in item.item" :key="j">

                                <div class="goods-img">
                                    <img :src="value.thumb_image">
                                </div>

                                <div class="goods-info">
                                    <div class="t">
                                        <span>{{value.title}}</span>
                                        <span>￥{{value.price}}</span>
                                    </div>
                                    <div class="b">
                                        <span>{{value.spec}}</span>
                                        <span>× {{value.nums}}</span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="order">
                            <div class="total">
                                共{{item.item.length}}件商品，总金额
                                <span>￥<i>{{item.order_amount}}</i></span>
                            </div>
                        </div>

                        <div class="botttom">
                            <span class="pay" @click="$router.push('/order/detail/'+item.order_id)">订单详情</span>
                        </div>
                    </div>

                </div>
            </van-list>
        </div>

    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { List,Empty,Toast } from 'vant';
    export default {
        name: 'OrderService',
        components: {
            [NavBar.name]: NavBar,
            [List.name]: List,
            [Empty.name]: Empty
        },
        data() {
            return {
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "该订单暂无内容",
                list: [],
                loading: false,
                finished: false,
                clientHeight: window.outerHeight - 100,
                page: 1
            };
        },
        created() {
        },
        mounted() {

        },
        watch:{
            "$route": function (to,form) {
                this.list = [];
                this.page = 1;
                this.onLoad();
            }
        },
        methods: {
            onLoad() {
                this.isEmpty = false;
                let emptyImage = this.$request.domain() + 'static/images/order-empty.png';
                this.$http.getOrderService({
                    page: this.page
                }).then(result=>{
                    if(result.data.list == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = emptyImage;
                        this.emptyDescription = "暂无订单信息";
                    } else if(result.status == 1){
                        this.list = this.list.concat(result.data.list);
                        this.loading = false;
                        this.page++;
                    }else if(result.status == -1){
                        if(result.data == undefined && this.page == 1){
                            this.isEmpty = true;
                            this.emptyImage = emptyImage;
                            this.emptyDescription = "暂无订单信息";
                        }else{
                            this.loading = false;
                            this.finished = true;
                        }
                    }
                }).catch((error)=>{
                    this.isEmpty = true;
                    this.emptyImage = "network";
                    this.emptyDescription = "网络出错，请检查网络是否连接";
                });
            },
            prev(){
                this.$tools.prev();
            },
            go(path){
                if(path == this.$route.params.id){
                    return ;
                }

                this.$router.replace({
                    name: "OrderList",
                    params:{
                        id: path
                    }
                });
            }
        },
    }
</script>

<style lang="scss" scoped>
    .list-wrap{
        margin-top: 10px;
    }
    .list-box{
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        .list-item-box {
            width: 95%;
            margin: 10px 2.5%;
            background-color: #fff;
            border-radius: 6px;
            .top{
                height: 45px;
                line-height: 45px;
                font-size: 15px;
                border-bottom: 1px solid #eee;
                .order-type{
                    font-size: 14px;
                    margin-right: 5px;
                    color: #666;
                }
                span:first-child{
                    float: left;
                    padding-left: 10px;
                }
                span:last-child{
                    font-size: 14px;
                    float: right;
                    padding-right: 10px;
                }
            }
            .goods-box{
                padding: 0 10px;
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
            .order{
                width: 100%;
                height: 45px;
                line-height: 45px;
                border-bottom: 1px solid #eee;
                .total {
                    text-align: right;
                    font-size: 14px;
                    padding-right: 10px;
                    span{
                        color: red;
                        i{
                            font-style: normal;
                            font-size: 16px;
                        }
                    }
                }
            }
            .botttom{
                width: 100%;
                height: 55px;
                line-height: 55px;
                text-align: right;
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
            }
        }
    }

</style>
