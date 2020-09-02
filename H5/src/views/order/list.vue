<template>
    <div>
        <van-nav-bar
            title="订单列表"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />
        <div class="">
            <div class="menu">
                <div class="menu-wrap">
                    <span @click="go('1')" :class="{active: $route.path == '/order/list/1'}">待付款</span>
                    <span @click="go('2')" :class="{active: $route.path == '/order/list/2'}">待发货</span>
                    <span @click="go('3')" :class="{active: $route.path == '/order/list/3'}">待收货</span>
                    <span @click="go('4')" :class="{active: $route.path == '/order/list/4'}">待评价</span>
                    <span @click="go('5')" :class="{active: $route.path == '/order/list/5'}">己完成</span>
                </div>
            </div>
            <div class="placeholder-box"></div>

        </div>

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
                            <span class="satus">{{item.order_status}}</span>
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
                        <div class="order" :class="{addBorder:item.active==6}">
                            <div class="total">
                                共{{item.item.length}}件商品，总金额
                                <span>￥<i>{{item.order_amount}}</i></span>
                            </div>
                        </div>
                        <div class="botttom" v-if="item.active!=6">
                            <span class="cancel" v-if="item.active == 1" @click="cancel(item.order_id)">取消订单</span>
                            <span class="pay" v-if="item.active == 1" @click="$router.push('/order/detail/'+item.order_id)">立即付款</span>

                            <span class="cancel" v-if="item.active == 2 || item.active==3 || item.active==4" @click="$router.push('/order/confirm_delivery/' + item.order_id)">确认收货</span>
                            <span class="pay" v-if="item.active == 2 || item.active==3 || item.active==4" @click="$router.push('/order/refund/' + item.order_id)">申请退款</span>

                            <span class="pay" v-if="item.active==5" @click="$router.push('/order/evaluate/' + item.order_id)">待评价</span>
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
    name: 'OrderList',
    components: {
        [NavBar.name]: NavBar,
        [List.name]: List,
        [Empty.name]: Empty
    },
    data() {
        return {
            isEmpty: false,
            emptyImage: "search",
            emptyDescription: "您搜索的关键字暂无内容",
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
            this.$http.getOrderList({
                type: this.$route.params.id,
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
        cancel(order_id){
            Toast.loading({
                message: '加载中...',
                forbidClick: true,
                loadingType: 'spinner',
                duration: 0
            });
            this.$http.getOrderListCancel({
                order_id: order_id
            }).then(res=>{
                Toast.clear();
                if(res.status){
                    let index = this.list.findIndex((value)=>{
                        return value.order_id == order_id;
                    });

                    this.list.splice(index,1);
                    if(this.list.length <= 0){
                        this.isEmpty = true;
                        this.emptyImage = this.$request.domain() + 'static/images/order-empty.png';
                        this.emptyDescription = "暂无订单信息";
                    }
                    Toast(res.info);
                }else{
                    Toast(res.info);
                }
            }).catch(err=>{
                Toast.clear();
                Toast("网络出错，请检查网络是否连接");
            });
        },
        prev(){
            this.$router.replace('/ucenter/index');
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
.placeholder-box{
    width: 100%;
    height: 50px;
}
.addBorder{
    border-top: 1px solid #eee;
}
.menu{
    background-color: #fff;
    height: 50px;
    line-height: 50px;
    position: fixed;
    width: 100%;
    .menu-wrap{
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
        span {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .active:before {
            content: " ";
            position: absolute;
            bottom: 0px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #e93323;
        }
    }
}
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
