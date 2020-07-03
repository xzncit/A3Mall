<template>
    <div>
        <van-pull-refresh v-model="refreshing" @refresh="onRefresh">
            <div class="top-wrap">
                <div class="header">
                    <div class="logo">
                        <span>A3Mall</span>
                        <span>素烟姿</span>
                    </div>
                    <div class="cart" @click="$router.push('/cart/index')"></div>
                </div>
                <div class="search-box" @click="onSearch">
                    <van-icon color="search-icon" name="search" />
                    <span class="search-text">请输入搜索关键词</span>
                </div>
                <div class="swiper-box">
                    <van-swipe class="my-swipe" :autoplay="3000">
                        <van-swipe-item v-for="(image, index) in images" :key="index">
                            <img v-lazy="image" />
                        </van-swipe-item>
                    </van-swipe>
                </div>

                <div class="notice-box">
                    <van-notice-bar @click="noticeEvent" color="#b91922" background="transparent" left-icon="volume-o" :text="noticeText" />
                </div>
            </div>

            <div class="grid-box">
                <div class="grid-box-item" v-for="(value,i) in category" :key="i" @click="$router.push(value.url)">
                    <span class="grid-box-item-img"><img :src="value.image"></span>
                    <span class="grid-box-item-text">{{ value.name }}</span>
                </div>
            </div>

            <div class="m-1" v-if="img_1.image">
                <img :src="img_1.image" @click="url(img_1.url)">
            </div>

            <div class="host-box">
                <div class="host-title">
                    <span>热销排行</span>
                    <router-link to="/goods/hot" tag="span">更多</router-link>
                </div>
                <div class="host-middle">
                    <div v-for="(item,index) in hot" :key="index" class="host-middle-box" @click="url(item.url)" active-class="host-active-link">
                        <span><img :src="item.image"></span>
                        <span>{{item.name}}</span>
                        <span>￥{{item.price}}</span>
                    </div>
                </div>
            </div>

            <div class="prop-box" v-if="img_2[0]">
                <div class="l">
                    <img :src="img_2[0].image" @click="url(img_2[0].url)" />
                </div>
                <div class="r">
                    <div>
                        <img :src="img_2[1].image" @click="url(img_2[1].url)" />
                    </div>
                    <div>
                        <img :src="img_2[2].image" @click="url(img_2[2].url)" />
                        <img :src="img_2[3].image" @click="url(img_2[3].url)" />
                    </div>
                </div>
            </div>

            <div class="recommend-wrap">
                <div class="recommend-title">
                    <span>精品推荐</span>
                    <span @click="$router.push('/goods/recommend')">更多</span>
                </div>

                <div class="recommend" ref="recommend">
                    <div class="recommend-list" :style="'width: '+((130*recommend.length) + 50)+'px;'">
                        <div class="recommend-item" v-for="(item,index) in recommend" :key="index" @click="url(item.url)">
                            <span><img :src="item.image"></span>
                            <span>{{item.name}}</span>
                            <span>￥{{item.price}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="goods-list" v-if="!isEmpty">
                <div class="goods-title">
                    猜你喜欢
                </div>
                <van-list
                    v-model="loading"
                    :finished="finished"
                    finished-text="没有更多了"
                    @load="onLoad"
                >

                    <div class="goods-list-box">
                        <div class="goods-list-item-box"
                             v-for="(item,index) in list"
                             :key="index"
                             @click="$router.push({ path: '/goods/view/' + item.id })"
                        >
                            <div class="goods-list-item-wrap">
                                <span><img :src="item.photo"></span>
                                <span>{{ item.title }}</span>
                                <span>￥{{ item.price }}</span>
                            </div>
                        </div>
                    </div>

                </van-list>

            </div>
        </van-pull-refresh>

    </div>
</template>

<script>
    import Vue from 'vue';
    import { PullRefresh,List,Cell } from 'vant';
    import { Search,NoticeBar,Icon } from 'vant';
    import { Swipe, SwipeItem } from 'vant';
    import { Toast,Lazyload,Empty } from 'vant';
    import Bscroll from 'better-scroll'
    Vue.use(Lazyload);
    export default {
        name: 'Home',
        components: {
            [PullRefresh.name]: PullRefresh,
            [List.name]: List,
            [Cell.name]: Cell,
            [Search.name]: Search,
            [NoticeBar.name]: NoticeBar,
            [Icon.name]: Icon,
            [Swipe.name]: Swipe,
            [SwipeItem.name]: SwipeItem
        },
        data() {
            return {
                searchValue: '',
                noticeText: "A3Mall v1.0 即将上线",
                images: [],
                category:[],
                img_1: {},
                img_2: [],
                hot: [],
                recommend: [],
                list: [],
                loading: false,
                finished: false,
                refreshing: false,
                page: 1,
                isEmpty: false
            };
        },
        created() {
            this.onLoadData();
        },
        mounted() {
            this.$nextTick(() => {
                this.scroll = new Bscroll(this.$refs.recommend, {
                    startX: 0,
                    click: true,
                    scrollX: true,
                    scrollY: false,
                    eventPassthrough: 'vertical'
                });
            })
        },
        methods: {
            onLoadData(){
                this.$http.getHomeCommon().then(res=>{
                    this.images = res.data.banner;
                    this.category = res.data.nav;
                    this.img_1 = res.data.img_1;
                    this.img_2 = res.data.img_2;
                    this.hot = res.data.hot;
                    this.recommend = res.data.recommend;
                }).catch(err=>{
                    Toast("网络出错，请检查网络是否连接");
                });
            },
            onLoad() {
                this.isEmpty = false;
                if (this.refreshing) {
                    this.list = [];
                    this.refreshing = false;
                    this.page = 1;
                }

                this.$http.getHomeList({
                    page: this.page
                }).then((result)=>{
                    if(result.data.list == undefined && this.page == 1){
                        this.isEmpty = true;
                    } else if(result.status == 1){
                        this.list = this.list.concat(result.data.list);
                        this.loading = false;
                        this.page++;
                    }else if(result.status == -1){
                        if(result.data == undefined && this.page == 1){
                            this.isEmpty = true;
                        }else{
                            this.loading = false;
                            this.finished = true;
                        }
                    }
                });
            },
            onRefresh() {
                // 清空列表数据
                this.finished = false;
                this.loading = true;
                setTimeout(()=>{
                    this.onLoad();
                },1000);
            },
            onSearch() {
                this.$router.push('/search/index');
            },
            noticeEvent(){
                Toast('公告');
            },
            url(string){
                if(string == ''){
                    return ;
                }

                this.$router.push(string);
            }
        },
    }
</script>

<style lang="scss" scoped>
.top-wrap { background: linear-gradient(to bottom,#b91922,#fefdfd); }
.swiper-box{ width: 93%; margin: 0 auto; }
.header{
    width: 93%;
    height: 35px;
    line-height: 45px;
    margin: 0 auto;
    position: relative;
    .logo {
        float: left;
        span:first-child{
            color:#fff;
            font-size: 21px;
            padding-right: 8px;
            position: relative;
        }
        span:first-child:after {
            border-right: 1px solid #dc8c91;
            width: 1px;
            height: 18px;
            content: " ";
            position: absolute;
            right: -2px;
            top: 4px;
        }
        span:last-child{
            color: #fff000;
            font-size: 19px;
            padding-left: 8px;
        }
    }
    .cart {
        float: right;
        width: 24px;
        height: 20px;
        background-image: url("../../assets/images/cart.png");
        background-size: 100%;
        background-repeat: no-repeat;
        position: relative;
        top: 12px;
    }
}
.search-box {
    width: 93%; height: 35px;
    background-color: #fff;
    margin: 15px auto;
    border-radius: 50px;
    position: relative;
    .van-icon-search {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666666;
        font-size: 25px;
    }
    .search-text {
        position: absolute;
        left: 40px;
        color: #666;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
    }
}
.my-swipe {width: 100%;height: 150px; border-radius: 6px;}
.my-swipe .van-swipe-item img {display: block;width: 100%;height: 150px;background-color: #fff;pointer-events: none;}
.my-swipe /deep/ .van-swipe__indicator--active { background-color: #b71c1c }
.notice-box /deep/ .van-notice-bar { background: transparent; }
.grid-box{background: #fff;display: flex;flex-direction: row;flex-wrap: wrap;padding-top: 10px;}
.grid-box-item {width: 25%;height: 80px;text-align: center;}
.grid-box-item-img {display: block;}
.grid-box-item-img img {width: 50px;height: 50px;border-radius: 50%;}
.grid-box-item-text {display: block;font-size: 13px;color: #666;width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
.host-box { margin: 10px 0; width: 100%; height: 260px; background-color: #b91922; }
.host-box .host-title { color: #fff; width: 92%; height: 45px; line-height: 45px; margin: 0 auto; }
.host-box .host-title span:first-child { font-size: 16px; float: left; font-weight: bold; }
.host-box .host-title span:last-child { position: relative; font-size: 13px; float: right; padding-right: 15px; }
.host-box .host-title span:last-child:after { position: absolute; right: 0; top: -1px; content: '>'; }
.host-middle { padding-bottom: 10px; width: 92%; margin: 0 auto; background: #fff; border-radius: 5px; display: flex; justify-content: center; flex-wrap: nowrap; flex-direction: row; }
.host-middle-box { width: 31%; padding: 0 1%; padding-top: 10px; text-align: center; }
.host-middle-box span { display: block; width: 100%; font-size: 14px; }
.host-middle-box span:nth-child(1) img { width: 110px; height: 110px; border-radius: 5px; }
.host-middle-box span:nth-child(2) { height: 40px; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
.host-middle-box span:nth-child(3) { color: red; text-align: left; }
.m-1{height: 100px;margin: 10px 0;}
.m-1 img{display: block;width: 100%;height: 100%;}
.prop-box { width: 100%; height: 175px; margin-bottom: 10px; }
.prop-box .l { width: 40%; display: inline-block; float: left; }
.prop-box .l img { width: 100%; height: 175px; }
.prop-box .r { width: 60%; display: inline-block; }
.prop-box .r div { float: left; }
.prop-box .r div:first-child { display: inline-block; height: 87px; }
.prop-box .r div:first-child img { width: 100%; height: 87px; }
.prop-box .r div:last-child { display: inline-block; height: 87px; }
.prop-box .r div:last-child img { width: 50%; height: 88px; }
.recommend-wrap { width: 100%; height: 240px; background: #fff; }
.recommend-title { width: 92%; margin: 0 auto; height: 45px; line-height: 45px; }
.recommend-title span { color: #555; }
.recommend-title span:first-child { float: left; font-size: 16px; font-weight: bold; }
.recommend-title span:last-child { position: relative; float: right; font-size: 13px; padding-right: 15px; }
.recommend-title span:last-child:after { position: absolute; right: 0; top: -1px; content: '>'; }
.recommend-list {
    display: flex; flex-wrap: nowrap; flex-direction: row; font-size: 14px;
}
.recommend-item { float: left; width: 130px; margin-right: 10px; }
.recommend-item:first-child { margin-left: 10px; }
.recommend-item span { display: block; text-align: center; }
.recommend-item span:nth-child(1) { height: 130px; }
.recommend-item span:nth-child(1) img { width: 100%; height: 130px; display: block; }
.recommend-item span:nth-child(2) { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.recommend-item span:nth-child(3) { color: red; }
.goods-list-box{ width: 100%;display: flex; flex-direction: row;flex-wrap: wrap; }
.goods-title{ color: #666; width: 100%; height: 50px; line-height: 50px; text-align: center; font-size: 16px; font-weight: bold; }
.goods-title:before { content: "::"; position: relative; top: -1px; left: -2px; font-size: 18px; }
.goods-title:after { content: "::"; position: relative; top: -1px; right: -2px; font-size: 18px; }
.goods-list-item-box{width: 50%; margin-bottom: 10px; }
.goods-list-item-box:nth-child(2n+1) .goods-list-item-wrap { margin-left: 10px; margin-right: 5px; }
.goods-list-item-box:nth-child(2n) .goods-list-item-wrap { margin-left: 5px; margin-right: 10px; }
.goods-list-item-wrap{ height: 260px; background: #fff; }
.goods-list-item-wrap span { display: block; }
.goods-list-item-wrap span:nth-child(1) { height: 185px; }
.goods-list-item-wrap span:nth-child(1) img { padding: 10px 5%; width: 90%; height: 165px; }
.goods-list-item-wrap span:nth-child(2) { font-size: 14px; padding: 0 10px; height: 40px; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
.goods-list-item-wrap span:nth-child(3){ font-size: 15px; padding: 5px; color: red; }
</style>
