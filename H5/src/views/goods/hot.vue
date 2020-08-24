<template>
    <div>
        <nav-bar
                title="商品列表"
                left-arrow
                :fixed="true"
                :z-index="9999"
                :transparent="true"
                background-color="#b91922"
                @click-left="prev"
        />

        <div class="navbar">
            <div class="nav-item" :class="{current: filterIndex === 0}" @click="tabClick(0)">
                综合排序
            </div>
            <div class="nav-item" :class="{current: filterIndex === 1}" @click="tabClick(1)">
                销量优先
            </div>
            <div class="nav-item" :class="{current: filterIndex === 2}" @click="tabClick(2)">
                <span>价格</span>
                <div class="arrow-box">
                    <span :class="{active: priceOrder === 1 && filterIndex === 2,'icon-arrow-up-active':priceOrder === 1 && filterIndex === 2}" class="icon iconfont icon-arrow-up">&#xe61c;</span>
                    <span :class="{active: priceOrder === 2 && filterIndex === 2,'icon-arrow-down-active':priceOrder === 2 && filterIndex === 2}" class="icon iconfont icon-arrow-down">&#xe61c;</span>
                </div>
            </div>
        </div>

        <div style="height: 100px; background-color: #b91922"></div>

        <div class="pull-refresh-box">
            <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
            <van-pull-refresh
                    v-if="!isEmpty"
                    v-model="refreshing"
                    @refresh="onRefresh"
            >

                <van-list
                        v-model="loading"
                        :finished="finished"
                        finished-text="没有更多了"
                        @load="loadGoods "
                >

                    <div class="goods-list-box">
                        <div
                                class="goods-list-item-box"
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
            </van-pull-refresh>
        </div>

    </div>
</template>

<script>
    import NavBar from '../../components/nav-bar/nav-bar';
    import { PullRefresh,List } from 'vant';
    import { Empty } from 'vant';
    export default {
        name: 'GoodsHotList',
        components: {
            [NavBar.name]: NavBar,
            [PullRefresh.name]: PullRefresh,
            [List.name]: List,
            [Empty.name]: Empty
        },
        data() {
            return {
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "您搜索的关键字暂无内容",
                filterIndex: 0,
                priceOrder: 1,
                list: [],
                loading: false,
                finished: false,
                refreshing: false,
                page: 1,
            };
        },
        methods: {
            tabClick(index){
                if(this.filterIndex === index && index !== 2){
                    return;
                }
                this.filterIndex = index;
                if(index === 2){
                    this.priceOrder = this.priceOrder === 1 ? 2: 1;
                }else{
                    this.priceOrder = 0;
                }

                // 清空列表数据
                this.finished = false;
                this.refreshing = true;
                // 重新加载数据
                // 将 loading 设置为 true，表示处于加载状态
                this.loading = true;
                this.loadGoods();
            },
            loadGoods(){
                this.isEmpty = false;
                if (this.refreshing) {
                    this.list = [];
                    this.refreshing = false;
                    this.page = 1;
                }

                this.$http.getGoodsHot({
                    page: this.page,
                    type: this.filterIndex,
                    sort: this.priceOrder
                }).then((result)=>{
                    if(result.data.list == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = "search";
                        this.emptyDescription = "该分类下暂无内容";
                    } else if(result.status == 1){
                        this.list = this.list.concat(result.data.list);
                        this.loading = false;
                        this.page++;
                    }else if(result.status == -1){
                        if(result.data == undefined && this.page == 1){
                            this.isEmpty = true;
                            this.emptyImage = "search";
                            this.emptyDescription = "该分类下暂无内容";
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
            onRefresh() {
                // 清空列表数据
                this.finished = false;

                // 重新加载数据
                // 将 loading 设置为 true，表示处于加载状态
                this.loading = true;
                setTimeout(()=>{
                    this.loadGoods();
                },1500);
            },
            prev() {
                this.$tools.prev();
            }
        },
    }
</script>

<style lang="scss" scoped>
    .navbar{
        position: fixed;
        left: 0;
        top: 49px;
        display: flex;
        width: 100%;
        height: 50px;
        background: #b91922;
        z-index: 10;
        .nav-item{
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            font-size: 14px;
            color: #fff;
            position: relative;
            &.current{
                color: #fff000;
            }
            .arrow-box{
                display: flex;
                flex-direction: column;
                .icon{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 19px;
                    height: 5px;
                    line-height: 5px;
                    margin-left: 0px;
                    font-size: 15px;
                    color: #fff;
                    text-align: center;
                    &.active{
                        color: #fff000;
                    }
                }
                .icon-arrow-up {

                }
                .icon-arrow-down {
                    transform:rotate(-180deg);
                }
            }
        }
    }

    .pull-refresh-box{ margin-top: 10px; }
    .goods-list-box{ width: 100%;display: flex; flex-direction: row;flex-wrap: wrap; }
    .goods-list-item-box{ width: 50%; margin-bottom: 10px; }
    .goods-list-item-box:nth-child(2n+1) .goods-list-item-wrap { margin-left: 10px; margin-right: 5px; }
    .goods-list-item-box:nth-child(2n) .goods-list-item-wrap { margin-left: 5px; margin-right: 10px; }
    .goods-list-item-wrap{ height: 260px; background: #fff; overflow: hidden; border-radius: 8px; }
    .goods-list-item-wrap span { display: block; }
    .goods-list-item-wrap span:nth-child(1) { height: 185px; }
    .goods-list-item-wrap span:nth-child(1) img { padding: 10px 5%; width: 90%; height: 165px; }
    .goods-list-item-wrap span:nth-child(2) { height: 40px; font-size: 14px; padding: 0 10px; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
    .goods-list-item-wrap span:nth-child(3){ font-size: 15px; padding: 5px; color: red; }
</style>
