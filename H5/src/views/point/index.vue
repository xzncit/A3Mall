<template>
    <div>
        <nav-bar
                title="积分兑换"
                left-arrow
                :fixed="true"
                :z-index="9999"
                :transparent="true"
                background-color="#b91922"
                @click-left="prev"
        />

        <div style="height: 50px; background-color: #b91922"></div>

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

                    <div class="coupon clear">
                        <div class="coupon-wrap clear">
                            <div class="coupon-list clear">

                                <div v-for="(item,index) in list"
                                     :key="index"
                                     class="coupon-item"
                                     @click="onCoupon(item.id,index)"
                                >
                                    <div>{{item.name}}</div>
                                    <div>
                                        <span>{{item.point}}积分</span>
                                        <span :class="{ 'active': item.active }">点击兑换</span>
                                    </div>
                                    <div>{{item.start_time}} ~ {{item.end_time}}</div>
                                    <div class="coupon-img"><img src="../../assets/images/coupon-img.png"></div>
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
    import { Toast } from 'vant';
    export default {
        name: 'Point',
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
            onCoupon(id,index){
                if(this.list[index].active){
                    return false;
                }

                this.$http.receivePointCoupon({
                    id: id
                }).then(res=>{
                    if(res.status){
                        this.list[index].active = 1;
                        Toast(res.info);
                    }else{
                        Toast(res.info);
                    }
                });
            },
            loadGoods(){
                this.isEmpty = false;
                if (this.refreshing) {
                    this.list = [];
                    this.refreshing = false;
                    this.page = 1;
                }

                this.$http.getPointList({
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
                },900);
            },
            prev() {
                this.$tools.prev();
            }
        },
    }
</script>

<style lang="scss" scoped>
    .coupon {
        margin: 15px auto 20px auto;
        position: relative;
        width: 100%;
        border-radius: 15px;
        .coupon-wrap {
            padding: 0 15px;
            .coupon-title {
                font-size: 18px;
                color: #000;
                float: left;
                width: 100%;
                margin-top: 20px;
                margin-bottom: 24px;
            }
            .coupon-item {
                width: 100%; height: 94px;
                border: 1px solid #eec5af;
                border-radius: 10px;
                float: left;
                position: relative;
                margin-bottom: 15px;
                background-color: #fff;
                div {
                    &:nth-child(1){
                        float: left; width: 100%;
                        margin-top: 16px;
                        font-size: 17px; color: #393939;
                        text-indent: 17px;
                    }
                    &:nth-child(2){
                        float: left; width: 100%;
                        margin-top: 5px;
                        span {
                            float: left;
                            margin-left: 17px;
                            &:first-child {
                                color: #b91922;
                                font-size: 14px;
                            }
                            &:last-child {
                                background-color: #b91922;
                                color: #fff;
                                width: 65px; height: 20px; line-height: 20px;
                                font-size: 12px; text-align: center;
                                border-radius: 15px;
                                &.active {
                                    background-color: #999;
                                }
                            }
                        }
                    }
                    &:nth-child(3){
                        float: left; width: 100%;
                        font-size: 12px; text-indent: 17px;
                        margin-top: 5px;
                    }
                    &:nth-child(4){
                        position: absolute;
                        right: 10px; bottom: 0;
                    }
                }
            }
        }
    }
</style>
