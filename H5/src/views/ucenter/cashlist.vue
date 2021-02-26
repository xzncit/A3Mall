<template>
    <div>
        <nav-bar
            title="提现记录"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />

        <div class="top">
            <span>总余额：￥{{amount}}</span>
            <span @click="$router.push('/ucenter/withdraw')">去提现</span>
        </div>

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
                        <div class="t">
                            <span>转帐</span>
                            <span>-￥{{item.amount}}</span>
                        </div>
                        <div class="box">
                            <div>
                                <span><i class="icon iconfont">&#xe619;</i>申请时间</span>
                                <span>{{item.time}}</span>
                            </div>
                            <div>
                                <span><i class="icon iconfont">&#xe610;</i>申请状态</span>
                                <span :class="{'c-1': item.status==0,'c-2': item.status==1,'c-3': item.status==2}">{{item.text}}</span>
                            </div>
                            <div class="description clear" v-if="item.description">{{item.description}}</div>
                        </div>
                    </div>
                </div>

            </van-list>
        </div>

    </div>
</template>

<script>
    import NavBar from '../../components/nav-bar/nav-bar';
    import { List,Empty,Toast } from 'vant';
    export default {
        components: {
            [NavBar.name]: NavBar,
            [List.name]: List,
            [Empty.name]: Empty
        },
        data() {
            return {
                amount:'',
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
            let users = this.$storage.get("users",true);
            this.amount = users.amount;
            this.$http.getUcenter().then((res)=>{
                if(res.status){
                    this.amount = users.amount = res.data.amount;
                    this.$store.commit("UPDATEUSERS",users);
                }
            });
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
    .top{
        height: 70px;
        line-height: 70px;
        background-color: #b91922;
        color: #fff;
        padding: 0 15px;font-size: 16px;
        span:first-child { float: left; }
        span:last-child { font-size: 15px; margin-top: 20px; float: right; width: 100px; height: 30px; line-height: 30px; text-align: center; border-radius: 30px; border:1px solid #fff; }
    }
    .list-wrap{
        width: 100%;
        margin-top: 10px;
        .list-item{
            width: 100%;
            height: auto !important;
            height: 110px;
            background-color: #fff;
            font-size: 13px;
            margin-bottom: 10px;
            .t {
                height: 40px;
                line-height: 40px;
                border-bottom: 1px solid #ebebeb;
                span { font-size: 16px; color: #333; }
                span:first-child {
                    padding-left: 16px; float: left;
                }
                span:last-child {
                    padding-right: 16px; float: right;
                }
            }
            .box {
                height: 68px;
                width: 100%;
                div {
                    width: 100%;
                    height: 16px;
                    float: left;
                    font-size: 14px; color: #888;
                    padding-top: 10px;
                    span {
                        i { padding-right: 5px; position: relative; top: 1px; }
                    }
                    span:first-child { padding-left: 16px; float: left; }
                    span:last-child { padding-right: 16px; float: right; }
                    .c-1 { color: #888; }
                    .c-3 { color: #b91922; }
                    .c-2 { color: green; }

                    &:nth-child(3) {
                        width: 90%;
                        padding: 10px 16px;
                        height: auto !important;
                        height: 30px;
                        min-height: 30px;
                        font-size: 13px;
                    }
                }
            }
        }

    }
</style>
