<template>
    <div>
        <van-nav-bar
                title="团购列表"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

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
                            @click="$router.push({ path: '/group/view/' + item.id })"
                        >
                            <div class="goods-list-item-wrap">
                                <span><img :src="item.photo"></span>
                                <span>{{ item.title }}</span>
                                <span>￥{{ item.price }}</span>
                                <span>￥{{ item.sell_price }}</span>
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
    import { DropdownMenu, DropdownItem } from 'vant';
    import { Empty } from 'vant';
    export default {
        name: 'Group',
        components: {
            [NavBar.name]: NavBar,
            [PullRefresh.name]: PullRefresh,
            [List.name]: List,
            [DropdownMenu.name]: DropdownMenu,
            [DropdownItem.name]: DropdownItem,
            [Empty.name]: Empty,
        },
        data() {
            return {
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "您搜索的关键字暂无内容",
                list: [],
                loading: false,
                finished: false,
                refreshing: false,
                page: 1
            };
        },
        created() {
        },
        methods: {
            loadGoods(){
                this.isEmpty = false;
                if (this.refreshing) {
                    this.list = [];
                    this.refreshing = false;
                    this.page = 1;
                }

                this.$request.get("/group",{
                    page: this.page
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
    .nav-placeholder{ width: 100%; height: 46px; }
    .pull-refresh-box{ margin-top: 10px; }
    .goods-list-box{ width: 100%;display: flex; flex-direction: row;flex-wrap: wrap; }
    .goods-list-item-box{width: 50%; margin-bottom: 10px; }
    .goods-list-item-box:nth-child(2n+1) .goods-list-item-wrap { margin-left: 10px; margin-right: 5px; }
    .goods-list-item-box:nth-child(2n) .goods-list-item-wrap { margin-left: 5px; margin-right: 10px; }
    .goods-list-item-wrap{ height: 260px; background: #fff; }
    .goods-list-item-wrap span { display: block; }
    .goods-list-item-wrap span:nth-child(1) { height: 185px; }
    .goods-list-item-wrap span:nth-child(1) img { padding: 10px 5%; width: 90%; height: 165px; }
    .goods-list-item-wrap span:nth-child(2) { font-size: 14px; padding: 0 10px; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
    .goods-list-item-wrap span:nth-child(3){ float: left; font-size: 15px; padding: 5px; color: red; }
    .goods-list-item-wrap span:nth-child(4){ text-decoration:line-through; float: right; font-size: 15px; padding: 5px; color: #666; }
</style>
