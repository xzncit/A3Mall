<template>
    <div>
        <van-nav-bar
            :title="keywords"
            left-arrow
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
                    <span :class="{active: priceOrder === 1 && filterIndex === 2,'icon-arrow-up-active':priceOrder === 1 && filterIndex === 2}" class="icon icon-arrow-up"></span>
                    <span :class="{active: priceOrder === 2 && filterIndex === 2,'icon-arrow-down-active':priceOrder === 2 && filterIndex === 2}" class="icon icon-arrow-down"></span>
                </div>
            </div>
        </div>
        <div style="width: 100%; height: 50px"></div>

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
                            @click="go(keywords,item.id)"
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
import { NavBar } from 'vant';
import { PullRefresh,List } from 'vant';
import { Empty,Toast } from 'vant';
export default {
    name: 'SearchList',
    components: {
        [NavBar.name]: NavBar,
        [PullRefresh.name]: PullRefresh,
        [List.name]: List,
        [Empty.name]: Empty,
    },
    data() {
        return {
            isEmpty: false,
            emptyImage: "search",
            emptyDescription: "您搜索的关键字暂无内容",
            keywords: "标题",
            filterIndex: 0,
            priceOrder: 1,
            list: [],
            loading: false,
            finished: false,
            refreshing: false,
            page: 1
        };
    },
    created() {
        this.keywords = this.$route.query.keywords;
        if(this.keywords == ""){
            this.prev();
        }

    },
    methods: {
        go(keywords,goods_id){
            this.$http.searchKeywords({
                keywords:keywords,
                goods_id:goods_id,
                client_type: this.$tools.isWeiXin() ? "3" : "1"
            }).then(res=>{
                this.$router.push({ path: '/goods/view/' + goods_id });
            }).catch(err=>{
                Toast("请求失败，请检查网络是否连接");
            })
        },
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

            this.$http.getSearchList({
                page: this.page,
                keywords: this.keywords,
                type: this.filterIndex,
                sort: this.priceOrder
            }).then((result)=>{
                if(result.data.list == undefined && this.page == 1){
                    this.isEmpty = true;
                    this.emptyImage = "search";
                    this.emptyDescription = "您搜索的关键字暂无内容";
                } else if(result.status == 1){
                    this.list = this.list.concat(result.data.list);
                    this.loading = false;
                    this.page++;
                }else if(result.status == -1){
                    if(result.data == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = "search";
                        this.emptyDescription = "您搜索的关键字暂无内容";
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
            },800);
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
    top: 46px;
    display: flex;
    width: 100%;
    height: 40px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    z-index: 10;
    .nav-item{
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        font-size: 14px;
        color: #303133;
        position: relative;
        &.current{
            color: #fa436a;
            &:after{
                content: '';
                position: absolute;
                left: 50%;
                bottom: 0;
                transform: translateX(-50%);
                width: 60px;
                height: 0;
                border-bottom: 2px solid #fa436a;
            }
        }
        .arrow-box{
            display: flex;
            flex-direction: column;
            .icon{
                display: flex;
                align-items: center;
                justify-content: center;
                width: 30px;
                height: 10px;
                line-height: 10px;
                margin-left: 4px;
                font-size: 26px;
                color: #888;
                background-repeat: no-repeat;
                background-size: 94px 56px;
                background-position: center;
                background-size: 30%;
                &.active{
                    color: #fa436a;
                }
            }
            .icon-arrow-up {
                background-image: url(../../assets/images/arrow.png);
            }

            .icon-arrow-up-active {
                background-image: url(../../assets/images/arrow-active.png);
            }

            .icon-arrow-down {
                transform:rotate(-180deg);
                background-image: url(../../assets/images/arrow.png);
            }

            .icon-arrow-down-active {
                transform:rotate(-180deg);
                background-image: url(../../assets/images/arrow-active.png);
            }

        }
    }
}

.goods-list-box{
    width: 100%;display: flex; flex-direction: row;flex-wrap: wrap;
    .goods-list-item-box{
        width: 50%; margin-bottom: 10px;
        &:nth-child(2n+1) .goods-list-item-wrap { margin-left: 10px; margin-right: 5px; }
        &:nth-child(2n) .goods-list-item-wrap { margin-left: 5px; margin-right: 10px; }
        .goods-list-item-wrap{
            height: 260px; background: #fff;
            span { display: block; }
            span:nth-child(1) { height: 185px; }
            span:nth-child(1) img { padding: 10px 5%; width: 90%; height: 165px; }
            span:nth-child(2) { height:40px; font-size: 14px; padding: 0 10px; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
            span:nth-child(3){ font-size: 15px; padding: 5px; color: red; }
        }
    }
}
</style>
