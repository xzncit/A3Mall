<template>
    <div>
        <van-nav-bar
                title="提现记录"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

        <div class="list-wrap">
            <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
            <van-list
                    v-if="!isEmpty"
                    v-model="loading"
                    :finished="finished"
                    finished-text="没有更多了"
                    @load="onLoad"
            >

                <div class="list-box clear">
                    <div class="list-item clear" v-for="(item, index) in list" :key="index">
                        <div class="item-box clear">
                            <div class="ll clear">
                                <span>{{item.description}}</span>
                                <span>{{item.time}}</span>
                            </div>
                            <div class="rr">
                                -{{item.amount}}
                            </div>
                        </div>
                    </div>
                </div>

            </van-list>
        </div>

    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { List,Empty,Toast } from 'vant';
    export default {
        components: {
            [NavBar.name]: NavBar,
            [List.name]: List,
            [Empty.name]: Empty
        },
        data() {
            return {
                list: [],
                loading: false,
                finished: false,
                isActive:1,
                page: 1,
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "暂无内容",
            };
        },
        created() {

        },
        methods: {
            changeData(index){
                this.page = 1;
                this.isActive = index;
                this.list = [];
                this.loading = true;
                this.onLoad();
            },
            prev() {
                this.$tools.prev();
            },
            onLoad() {
                this.isEmpty = false;
                this.$http.getWalletCashlist({
                    type: this.isActive,
                    page: this.page
                }).then(result=>{
                    if(result.data.list == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = "search";
                        this.emptyDescription = "暂无内容";
                    } else if(result.status == 1){
                        this.list = this.list.concat(result.data.list);
                        this.loading = false;
                        this.page++;
                    }else if(result.status == -1){
                        if(result.data == undefined && this.page == 1){
                            this.isEmpty = true;
                            this.emptyImage = "search";
                            this.emptyDescription = "暂无内容";
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
        },
    }
</script>

<style lang="scss" scoped>
    .list-wrap{
        width: 100%;
        margin-top: 10px;
        .list-item{
            width: 92%;
            height: auto !important;
            height: 80px;
            min-height: 80px;
            border-radius: 5px;
            background-color: #fff;
            margin:  0 auto;
            margin-bottom: 10px;
            font-size: 13px;
            .item-box{
                height: auto !important;
                height: 80px;
                min-height: 80px;
                position: relative;
                .ll {
                    float: left;
                    width: 55%;
                    span{
                        display: block;
                        padding-left: 5%;
                        float: left;
                    }
                    span:first-child{
                        font-size: 13px;
                        color: #333;
                        margin-top: 15px;
                    }
                    span:last-child{
                        font-size: 12px;
                        color: #999;
                        margin-top: 5px;
                    }
                }
                .rr {
                    float: right;
                    width: 35%;
                    height: 80px;
                    line-height: 80px;
                    text-align: right;
                    padding-right: 5%;
                    color:#fc4141;
                    font-size: 16px;
                }
            }
        }

    }
</style>
