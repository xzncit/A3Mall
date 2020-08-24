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
        <div>
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

                <div class="goods-comments clear">
                    <div class="title">
                        <span>商品评价</span>
                        <span v-if="comments.length > 0" @click="$router.push(`/comments/goods/${$route.params.id}`)">更多 &gt;</span>
                    </div>
                    <div class="comments-empty" v-if="comments.length <= 0">该商品还没有评论哦！</div>
                    <div
                            class="goods-comments-list clear"
                            v-if="comments.length > 0"
                    >
                        <div
                                class="goods-comments-box clear"
                                v-for="(item,index) in comments"
                                :key="index"
                        >
                            <div class="t">
                                <div class="u">
                                    <span><img :src="item.avatar"></span>
                                    <span>{{item.username}}</span>
                                </div>
                                <div class="time">{{item.time}}</div>
                            </div>
                            <div class="c">{{item.content}}</div>
                        </div>
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
            <goods-action-icon icon="cart" @click="$router.replace('/cart/index')" text="购物车" :count="cartCount"></goods-action-icon>
            <goods-action-icon icon="collect" @click="favorite" text="收藏" :active="collect"></goods-action-icon>
            <goods-action-button type="cart" @click="onAddCartClicked" text="加入购物车"></goods-action-button>
            <goods-action-button type="buy" @click="onBuyClicked" text="立即购买"></goods-action-button>
        </goods-action>

    </div>
</template>

<script>
    import Vue from 'vue';
    import { PullRefresh, Swipe, SwipeItem } from 'vant';
    import { Lazyload,Toast } from 'vant';
    import SkuAction from '../../components/sku-action/sku-action';
    import GoodsAction from "../../components/goods-action/goods-action";
    import GoodsActionButton from "../../components/goods-action/goods-action-button";
    import GoodsActionIcon from "../../components/goods-action/goods-action-icon";
    import NavBar from '../../components/nav-bar/nav-bar';
    Toast.setDefaultOptions({ duration: 5000 });
    Vue.use(Lazyload);
    export default {
        name: 'GoodsView',
        components: {
            GoodsActionIcon,
            GoodsActionButton,
            GoodsAction,
            [PullRefresh.name]: PullRefresh,
            [NavBar.name]: NavBar,
            [Swipe.name]: Swipe,
            [SwipeItem.name]: SwipeItem,
            [SkuAction.name]: SkuAction,
            [GoodsAction.name]: GoodsAction,
            [GoodsActionButton.name]: GoodsActionButton,
            [GoodsActionIcon.name]: GoodsActionIcon,
        },
        data() {
            return {
                fields:["id"],
                isSkuStatus: false,
                images: [],
                collect: false,
                cartCount: 0,
                current: 0,
                isRefresh: false,
                selectedGoodsInfo: {},
                products: {},
                attribute: [],
                comments: [],
                item:{}
            };
        },
        created() {
            let users = this.$storage.get("users",true);
            this.cartCount = users != null ? users.shop_count : 0;
            this.onLoad();
        },
        methods: {
            onLoad(){
                this.$http.getGoodsDetail({
                    id: this.$route.params.id
                }).then((result)=>{
                    this.collect = result.data.collect ? true : false;
                    this.products = result.data.goods;
                    this.attribute = result.data.attr;
                    this.comments = result.data.comments;
                    this.item = result.data.item;
                    this.images = result.data.photo;
                }).catch(err=>{
                    this.$router.replace("/404");
                });
            },
            favorite(){
                this.$store.dispatch("isUsers").then(()=>{
                    this.$http.goodsDetailFavorite({
                        id: this.$route.params.id
                    }).then((result)=>{
                        if(result.status){
                            this.collect = result.data == 1 ? true : false;
                        }else{
                            Toast(result.info);
                        }
                    });
                }).catch(()=>{
                    this.$storage.set("VUE_REFERER","/goods/view/"+this.$route.params.id);
                    this.$router.push("/public/login");
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
                            type: "buy"
                        }});
                }).catch(()=>{
                    this.$storage.set("VUE_REFERER","/goods/view/"+this.$route.params.id);
                    this.$router.push("/public/login");
                });
            },
            onAddCartClicked(){
                if(this.isSkuStatus == false){
                    this.isSkuStatus = true;
                    return ;
                }

                if(!this.selectedGoodsInfo.isSubmit){
                    Toast("请选择规格！");
                    return false;
                }

                this.$store.dispatch("isUsers").then(()=>{
                    this.$http.goodsDetailAddCart({
                        id: this.selectedGoodsInfo.id,
                        sku_id: this.selectedGoodsInfo.selectedSku.id,
                        num: this.selectedGoodsInfo.num,
                    }).then((result)=>{
                        this.isShow = false;
                        if(result.status){
                            this.cartCount = result.data.count;
                            this.$store.commit("UPDATECART",result.data.count);
                            Toast(result.info);
                        }else{
                            Toast(result.info);
                        }
                    }).catch((error)=>{
                        Toast("网络出错，请检查网络是否连接");
                    });
                }).catch(()=>{
                    this.$storage.set("VUE_REFERER","/goods/view/"+this.$route.params.id);
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
        background-image: url(../../assets/images/bg/goods-bg.png);
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
    .goods-comments{
        margin-top: 10px;
        background-color: #fff;
        .title {
            height: 40px;
            line-height: 40px;
            font-size: 16px;
            width: 100%;
            border-bottom: 1px solid #e8e8e8;
            span:nth-child(1){
                float: left;
                color: #333;
                padding-left: 15px;
            }
            span:nth-child(2){
                float: right;
                color: #999;
                padding-right: 15px;
            }
        }
        .comments-empty { padding: 50px 15px; text-align: center; font-size: 16px; color: #666; }
        .goods-comments-list{
            .goods-comments-box{
                border-bottom: 1px solid #e8e8e8;
                min-height: 120px;
                .t {
                    padding: 0 15px;
                    height: 85px;
                    line-height: 80px;
                    color: #666;
                    .u{
                        float: left;
                        font-size: 15px;
                        span:first-child{
                            width: 48px; height: 48px;
                            overflow: hidden; border-radius: 50%;
                            background-color: #eee; display: inline-block;
                            position: relative; top: 15px;
                            img {
                                width: 48px; height: 48px;
                            }
                        }
                        span:last-child { position: relative; left: 10px; }
                    }
                    .time{
                        float: right;
                        font-size: 14px;
                    }
                }
                .c{
                    padding: 0 15px 25px 15px;
                    font-size: 15px; color: #333;
                }
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
