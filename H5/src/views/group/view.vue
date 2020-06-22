<template>
    <div>
        <van-nav-bar
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

            <div class="count-down-time">
                <count-down
                    :currentTime="products.now_time"
                    :startTime="products.start_time"
                    :endTime="products.end_time"
                    :tipText="'离团购开始:'"
                    :tipTextEnd="'离团购结束'"
                    :endText="'团购己结束'"
                    :dayTxt="'天'"
                    :hourTxt="'小时'"
                    :minutesTxt="'分钟'"
                    :secondsTxt="'秒'">
                </count-down>
            </div>

            <div class="goods-info clear">
                <div class="price">
                    <span>￥<i>{{ products.sell_price }}</i><strong>团购价</strong></span>
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

        <van-sku
            v-model="isShow"
            :sku="sku"
            :goods="goods"
            :goods-id="goodsId"
            :quota="quota"
            :quota-used="quotaUsed"
            :hide-stock="sku.hide_stock"
            :show-add-cart-btn="false"
            @buy-clicked="onBuyClicked"
        />

        <van-goods-action>
            <van-goods-action-icon replace to="/home" icon="wap-home-o" text="首页" />
            <van-goods-action-icon replace to="/cart/index" icon="cart-o" text="购物车" :badge="cartCount" />
            <van-goods-action-icon icon="star" @click="favorite" text="收藏" :color="collect" />
            <van-goods-action-button @click="skuShow" type="danger" text="立即购买" />
        </van-goods-action>
    </div>
</template>

<script>
import Vue from 'vue';
import { PullRefresh,NavBar, Swipe, SwipeItem } from 'vant';
import { GoodsAction, GoodsActionIcon, GoodsActionButton } from 'vant';
import { Sku } from 'vant';
import CountDown from '../../components/count-down/countdown'
import { Lazyload,Toast } from 'vant';
Toast.setDefaultOptions({ duration: 5000 });
Vue.use(Lazyload);
export default {
    name: 'GrouplView',
    components: {
        [PullRefresh.name]: PullRefresh,
        [NavBar.name]: NavBar,
        [Swipe.name]: Swipe,
        [SwipeItem.name]: SwipeItem,
        [GoodsAction.name]: GoodsAction,
        [GoodsActionIcon.name]: GoodsActionIcon,
        [GoodsActionButton.name]: GoodsActionButton,
        [Sku.name]: Sku,
        [CountDown.name]: CountDown,
    },
    data() {
        return {
            images: [],
            isShow: false,
            collect: '#ccc',
            cartCount: 0,
            goodsId: 1,
            quota: 0, // 限购数，0 表示不限购
            quotaUsed: 0, // 已经购买过的数量
            sku: {
                // 所有sku规格类目与其值的从属关系，比如商品有颜色和尺码两大类规格，颜色下面又有红色和蓝色两个规格值。
                // 可以理解为一个商品可以有多个规格类目，一个规格类目下可以有多个规格值。
                tree: [],
                // 所有 sku 的组合列表，比如红色、M 码为一个 sku 组合，红色、S 码为另一个组合
                list: [],
                price: '0.00', // 默认价格（单位元）
                stock_num: 0, // 商品总库存
                collection_id: 0, // 无规格商品 skuId 取 collection_id，否则取所选 sku 组合对应的 id
                none_sku: false, // 是否无规格商品
                hide_stock: false // 是否隐藏剩余库存
            },
            goods: { // 默认商品 sku 缩略图
                picture: ''
            },
            current: 0,
            isRefresh: false,
            activityId: 0,
            products: {
                "title": "",
                "sell_price": "",
                "market_price": "",
                "store_nums": "",
                "sale": "",
                "content": "",
                "start_time": 0,
                "end_time": 0,
                "now_time": 0
            },
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
            this.$request.get("/group/view",{
                id: this.$route.params.id
            }).then((result)=>{
                if(result.status){
                    this.collect = result.data.collect ? "#ff5000" : "#ccc";
                    this.products = result.data.goods;
                    this.images = result.data.photo;
                    this.goods.picture = result.data.goods.photo;
                    this.goodsId = this.$route.params.id;
                    this.sku = result.data.sku;
                    this.activityId = result.data.activityId;
                }
            });
        },
        favorite(){
            this.$store.dispatch("isUsers").then(()=>{
                this.$request.get("/group/favorite",{
                    id: this.$route.params.id
                }).then((result)=>{
                    if(result.status){
                        this.collect = result.data == 1 ? "#ff5000" : "#ccc";
                    }else{
                        Toast(result.info);
                    }
                });
            }).catch(()=>{
                let path = this.$storage.set("VUE_REFERER","/goods/view");
                this.$router.push("/public/login");
            });
        },
        skuShow(){
          this.isShow = true;
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
        onBuyClicked(data){
            this.$store.dispatch("isUsers").then(()=>{
                this.$router.push({ path: "/cart/confirm", query: {
                    id: this.activityId,
                    sku_id: data.selectedSkuComb.id,
                    num: data.selectedNum,
                    type: "group"
                }});
            }).catch(()=>{
                let path = this.$storage.set("VUE_REFERER","/goods/view");
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
.count-down-time{
    padding: 5px 15px 5px 15px;
    background-color: #fff;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    div{
        color: #c21313;
    }
    font-size: 14px;
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
        strong{
            position: relative;
            top: -1px;
            font-size: 15px;
            left: 3px;
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
