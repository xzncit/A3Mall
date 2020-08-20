<template>
    <div>
        <nav-bar
                title="商品详情"
                left-arrow
                :fixed="true"
                :placeholder="true"
                :z-index="9999"
                @click-left="prev"
        />
        <div :style="'height:'+clientHeight+'px'">
            <van-pull-refresh v-model="isRefresh" @refresh="onRefresh">
                <van-swipe class="swiper-box" :autoplay="3000" @change="onChange">
                    <van-swipe-item v-for="(image, index) in images" :key="index">
                        <img v-lazy="image" />
                    </van-swipe-item>
                    <template #indicator>
                        <div class="custom-indicator">
                            {{ current + 1 }} / {{ images.length }}
                        </div>
                    </template>
                </van-swipe>

                <div class="goods-price">
                    <div class="price">
                        <span>￥<i>{{ products.sell_price }}</i></span>
                        <span>原价格<i>￥{{ products.market_price }}</i></span>
                    </div>
                    <div class="count-down-box">
                        <count-down
                                :now-time="products.now_time"
                                :start-time="products.start_time"
                                :end-time="products.end_time"
                                :status.sync="isActivityStatus"
                        ></count-down>
                    </div>
                </div>

                <div class="goods-info clear">
                    <div class="title">
                        {{ products.title }}
                    </div>
                    <div class="goods-info-box">
                        <span>库存: {{ products.store_nums }}件</span>
                        <span>销量: {{ products.sale }}件</span>
                    </div>
                </div>

                <div class="goods-content clear">
                    <div class="title">图文详情</div>
                    <div class="clear" v-html="products.content">
                        {{ products.content }}
                    </div>
                </div>

            </van-pull-refresh>

        </div>

        <sku-action
                v-model="isSkuStatus"
                :goods="products"
                :attribute="attribute"
                :item="item"
                :goods-info.sync="selectedGoodsInfo"
                :fields="fields"
        ></sku-action>

        <goods-action>
            <goods-action-icon icon="home" @click="$router.replace('/')" text="首页"></goods-action-icon>
            <goods-action-button type="buy" @click="onBuyClicked" text="立即购买"></goods-action-button>
        </goods-action>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { PullRefresh, Swipe, SwipeItem } from 'vant';
    import { Lazyload,Toast } from 'vant';
    import SkuAction from '../../components/sku-action/sku-action';
    import NavBar from '../../components/nav-bar/nav-bar';
    import CountDown from '../../components/count-down/count-down';
    import GoodsAction from "../../components/goods-action/goods-action";
    import GoodsActionButton from "../../components/goods-action/goods-action-button";
    import GoodsActionIcon from "../../components/goods-action/goods-action-icon";
    Toast.setDefaultOptions({ duration: 5000 });
    Vue.use(Lazyload);
    export default {
        name: 'SecondView',
        components: {
            [PullRefresh.name]: PullRefresh,
            [NavBar.name]: NavBar,
            [Swipe.name]: Swipe,
            [SwipeItem.name]: SwipeItem,
            [GoodsAction.name]: GoodsAction,
            [GoodsActionIcon.name]: GoodsActionIcon,
            [GoodsActionButton.name]: GoodsActionButton,
            [SkuAction.name]: SkuAction,
            [CountDown.name]: CountDown,
        },
        data() {
            return {
                fields:["id","goods_id"],
                isSkuStatus: false,
                selectedGoodsInfo: {},
                products: {},
                attribute: [],
                item:{},
                images: [],
                cartCount: 0,
                current: 0,
                isRefresh: false,
                isActivityStatus: false,
                clientHeight: window.outerHeight - 50
            };
        },
        created() {
            let users = this.$storage.get("users",true);
            this.cartCount = users != null ? users.shop_count : 0;
            this.onLoad();
        },
        methods: {
            onLoad(){
                this.$http.getSecondDetail({
                    id: this.$route.params.id
                }).then((result)=>{
                    if(result.status){
                        this.products = result.data.goods;
                        this.attribute = result.data.attr;
                        this.item = result.data.item;
                        this.images = result.data.photo;
                    }
                });
            },
            onChange(index) {
                this.current = index;
            },
            onRefresh(){
                setTimeout(()=>{
                    this.isRefresh = false;
                    this.onLoad();
                },1500);
            },
            onBuyClicked(){
                if(this.isActivityStatus == false){
                    Toast("活动己结束！");
                    return false;
                }

                if(this.isSkuStatus == false){
                    this.isSkuStatus = true;
                    return ;
                }

                if(!this.selectedGoodsInfo.isSubmit){
                    Toast("请选择规格！");
                    return false;
                }

                this.$store.dispatch("isUsers").then(()=>{
                    this.$router.push({ path: "/cart/confirm", query: {
                            id: this.selectedGoodsInfo.id,
                            sku_id: this.selectedGoodsInfo.selectedSku.id,
                            num: this.selectedGoodsInfo.num,
                            type: "second"
                        }});
                }).catch(()=>{
                    this.$storage.set("VUE_REFERER","/second/view/"+this.$route.params.id);
                    this.$router.push("/public/login");
                });
            },
            prev(){
                this.$tools.prev();
            }
        },
    }
</script>

<style lang="scss" scoped>
    .swiper-box{
        width: 100%;
        height: 400px;
        img {
            width: 100%;
            height: 400px;
        }
        .custom-indicator {
            position: absolute;
            right: 20px;
            bottom: 20px;
            padding: 6px 15px;
            font-size: 14px;
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            border-radius: 6px;
        }
    }
    .goods-price{
        width: 100%;
        height: 70px;
        background-image: url(../../assets/images/bg/group-bg.png);
        background-repeat: no-repeat;
        background-size: 100%;
        .price {
            height: 70px;
            float: left;
            margin-left: 16px;
            span {
                display: block;
                color: #fff;
                &:first-child {
                    font-size: 21px;
                    padding-top: 12px;
                    font-style: normal;
                }
                &:last-child {
                    font-size: 12px;
                    padding-top: 0px;
                    i {
                        font-style: normal;
                        font-size: 13px;
                        position: relative;
                        top: 1px;
                        text-decoration:line-through;
                        padding-left: 2px;
                    }
                }
            }
        }
    }
    .count-down-box {
        float: right;
        height: 70px;
        /deep/.simple{
            .wrap-simple{
                color: #fff;
                height: 70px;
                font-size: 13px;
                padding-right: 16px;
                padding-top: 10px;
                .before {
                    color: #a0171f;
                    display: block;
                }
                .day,.hour,.minute,.second{
                    i {
                        display: inline-block;
                    }
                    i:first-child{
                        background-color: #000;
                        color: #fff;
                        border-radius: 3px;
                        min-width: 20px;
                        height: 18px;
                        text-align: center;
                    }
                    i:last-child{
                        color: #000;
                        padding: 0 2px;
                    }
                }
            }
            .tips-simple{ color: #000; padding-right: 16px; height: 70px; line-height: 70px }
        }
    }
    .goods-info{
        background-color: #fff;
        .title{
            display: block;
            padding: 12px 15px 3px 15px;
            color: #333;
            font-size: 16px;
        }
        .goods-info-box{
            display: block;
            padding: 0 15px;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
            span {
                width: 50%;
                height: 40px;
                line-height: 40px;
                text-align: left;
                font-size: 15px;
                color: #888;
            }
        }
    }
    .goods-content{
        background-color: #fff;
        width: 100%;
        margin-top: 15px;
        margin-bottom: 50px;
        .title {
            font-size: 16px;
            color: #282828;
            height: 48px;
            width: 100%;
            background-color: #fff;
            text-align: center;
            line-height: 48px;
        }
        img {
            width: 100%;
            height: auto;
            float: left;
        }
    }
</style>
