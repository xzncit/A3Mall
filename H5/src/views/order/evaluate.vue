<template>
    <div>
        <van-nav-bar
                title="商品评价"
                left-arrow
                :fixed="true"
                :placeholder="true"
                :z-index="99999"
                @click-left="prev"
        />

        <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
        <div v-if="!isEmpty">

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

            <div class="comment">
                <div class="rate-box">
                    <van-rate v-model="rate" @change="onChange" />
                </div>
                <van-field
                    v-model="message"
                    rows="2"
                    autosize
                    label="留言"
                    type="textarea"
                    maxlength="150"
                    placeholder="请输入留言"
                    show-word-limit
                />
            </div>

            <div class="btn">
                <van-button type="danger" @click="onSubmit" :loading="btn" loading-text="提交中..." round block>发表评价</van-button>
            </div>
        </div>


    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { Step, Steps } from 'vant';
    import { Cell, CellGroup } from 'vant';
    import { Empty,Toast,Popup,Button,Rate,Field } from 'vant';
    export default {
        name: 'OrderEvaluate',
        components: {
            [NavBar.name]: NavBar,
            [Step.name]: Step,
            [Steps.name]: Steps,
            [Cell.name]: Cell,
            [CellGroup.name]: CellGroup,
            [Popup.name]: Popup,
            [Empty.name]: Empty,
            [Button.name]: Button,
            [Rate.name]: Rate,
            [Field.name]: Field
        },
        data() {
            return {
                btn:false,
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "订单不存在！",
                rate: 5,
                message: '',
                order:{
                    item: []
                }
            };
        },
        created() {
            this.onLoadOrder();
        },
        mounted() {

        },
        methods: {
            onChange(value) {
                this.rate = value;
            },
            onSubmit(){
                if(this.btn){
                    return false;
                }
                this.btn = true;
                this.isEmpty = false;
                this.$http.sendOrderEvaluate({
                    id: this.$route.params.id,
                    message: this.message,
                    rate: this.rate
                }).then(res=>{
                    if(res.status){
                        this.$router.replace('/order/detail/' + this.$route.params.id);
                    }else{
                        Toast(res.info);
                    }

                    this.btn = false;
                }).catch(err=>{
                    this.btn = false;
                    this.isEmpty = true;
                    this.emptyImage = "network";
                    this.emptyDescription = "网络出错，请检查网络是否连接";
                });
            },
            onLoadOrder(){
                let id = this.$route.params.id;
                this.isEmpty = false;
                this.$http.getOrderEvaluate({
                    id: id
                }).then((res)=>{
                    if(res.status == 1){
                        this.order = res.data;
                    }else if(res.status == 2){
                        this.$router.replace('/order/detail/' + this.$route.params.id);
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
                this.$tools.prev();
            }
        },
    }
</script>

<style lang="scss" scoped>
    .money{ color: #fc4141; }
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
    .btn{
        width: 90%;
        margin: 20px auto;
        margin-top: 20px;
    }
    .comment{
        margin: 0 auto;
        margin-top: 20px;
        background-color: #fff;
        padding: 20px 0;
        .rate-box{
            margin-left: 18px;
        }
    }
</style>
