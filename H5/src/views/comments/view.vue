<template>
    <div>
        <nav-bar
            title="商品评论"
            left-arrow
            :fixed="true"
            :placeholder="true"
            :z-index="9999"
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

                    <div class="goods-comments clear">
                        <div class="goods-comments-list clear">
                            <div class="goods-comments-box clear" v-for="(item,index) in comments" :key="index">
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
        components:{
            [NavBar.name]: NavBar,
            [PullRefresh.name]: PullRefresh,
            [List.name]: List,
            [Empty.name]: Empty
        },
        data(){
            return {
                comments: [],
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "该商品下暂无评论",
                loading: false,
                finished: false,
                refreshing: false,
                page: 1
            };
        },
        created() {
            console.log(this.$route.params)
        },
        methods:{
            loadGoods(){
                this.isEmpty = false;
                if (this.refreshing) {
                    this.comments = [];
                    this.refreshing = false;
                    this.page = 1;
                }

                this.$http.getGoodsComments({
                    page: this.page,
                    type: this.$route.params.type,
                    id: this.$route.params.id
                }).then((result)=>{
                    if(result.data.list == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = "search";
                        this.emptyDescription = "该商品下暂无评论";
                    } else if(result.status == 1){
                        this.comments = this.comments.concat(result.data.list);
                        this.loading = false;
                        this.page++;
                    }else if(result.status == -1){
                        if(result.data == undefined && this.page == 1){
                            this.isEmpty = true;
                            this.emptyImage = "search";
                            this.emptyDescription = "该商品下暂无评论";
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
            prev(){
                this.$tools.prev();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .goods-comments{
        margin-top: 10px;
        background-color: #fff;
        .title {
            height: 40px;
            line-height: 40px;
            font-size: 16px;
            width: 100%;
            border-bottom: 1px solid #e8e8e8;
            span:first-child{
                float: left;
                color: #333;
                padding-left: 15px;
            }
            span:last-child{
                float: right;
                color: #999;
                padding-right: 15px;
            }
        }
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
</style>