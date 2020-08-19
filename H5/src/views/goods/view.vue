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

                <div class="goods-info clear">
                    <div class="price">
                        <span>￥<i>{{ products.sell_price }}</i></span>
                    </div>
                    <div class="title">
                        {{ products.title }}
                    </div>
                    <div class="goods-info-box">
                        <span>￥<i>{{ products.market_price }}</i></span>
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
            <goods-action-icon icon="cart" @click="$router.replace('/cart/index')" text="购物车" :count="cartCount"></goods-action-icon>
            <goods-action-icon icon="collect" text="收藏" @click="favorite" :active="collect"></goods-action-icon>
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
            [PullRefresh.name]: PullRefresh,
            [NavBar.name]: NavBar,
            [Swipe.name]: Swipe,
            [SwipeItem.name]: SwipeItem,
            [GoodsAction.name]: GoodsAction,
            [GoodsActionIcon.name]: GoodsActionIcon,
            [GoodsActionButton.name]: GoodsActionButton,
            [SkuAction.name]: SkuAction
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
                item:{},
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
                this.$http.getGoodsDetail({
                    id: this.$route.params.id
                }).then((result)=>{
                    this.collect = result.data.collect ? true : false;
                    this.products = result.data.goods;
                    this.attribute = result.data.attr;
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
    .goods-info{
        background-color: #fff;
        i {
            font-style: normal;
        }
        .price{
            display: block;
            padding: 15px 15px 5px 15px;
            color: red;
            font-size: 14px;
            i {
                font-size: 18px;
            }
        }
        .title{
            display: block;
            padding: 0 15px;
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
                width: 33.33%;
                height: 40px;
                line-height: 40px;
                text-align: center;
                font-size: 13px;
                color: #555;
            }
            span:nth-child(1){
                text-align: left;
            }
            span:nth-child(3){
                text-align: right;
            }
        }
    }
    .goods-content{
        background-color: #fff;
        width: 100%;
        margin-top: 15px;
        margin-bottom: 0px;
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
