<template>
    <div>
        <nav-bar
                title="资讯列表"
                left-arrow
                :fixed="true"
                :z-index="9999"
                :transparent="true"
                :placeholder="true"
                background-color="#b91922"
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
                        @load="loadGoods"
                >

                    <div class="news-list-box">
                        <div class="news-wrap">
                            <div
                                    v-for="(item,index) in list"
                                    :key="index"
                                    class="news-item-box clear"
                                    @click="$router.push('/news/view/'+item.id)"
                            >
                                <div class="news-box">
                                    <span>{{item.title}}</span>
                                    <span>{{item.create_time}}</span>
                                </div>
                                <div class="pic">
                                    <img :src="item.photo">
                                </div>
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
        name: 'News',
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
                list: [],
                loading: false,
                finished: false,
                refreshing: false,
                page: 1,
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

                this.$http.getNewsList({
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
    .pull-refresh-box{ margin-top: 10px; }
    .news-list-box{
        width: 100%;display: flex; flex-direction: row;flex-wrap: wrap;
        .news-wrap{
            width: 95%;
            margin: 0 auto;
            position: relative;
        }
        .news-item-box{
            border-radius: 10px;
            background-color: #fff;
            height: 120px;
            .news-box{
                padding-right: 135px;
                position: relative;
                height: 120px;
                span:first-child{
                    display: block;
                    margin-left: 15px;
                    line-height: 25px;
                    padding-top: 5px;
                    font-size: 14px;
                    color: #666;
                    display: -webkit-box;
                    overflow: hidden;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                }
                span:last-child{
                    width: 100%;
                    position: absolute;
                    bottom: 0px;
                    left: 0;
                    height: 30px;
                    line-height: 30px;
                    text-indent: 15px;
                    font-size: 12px;
                    color: #999;
                }
            }
            .pic {
                position: absolute;
                top: 10px;
                right: 10px;
                img {
                    width: 115px;
                    height: 100px;
                }
            }
        }
    }

</style>
