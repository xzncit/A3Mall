<template>
    <div>
        <van-nav-bar
                title="优惠劵"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

        <div class="top">
            <span :class="{active: isActive == 1}" @click="changeCoupon(1)">未使用</span>
            <span :class="{active: isActive == 2}" @click="changeCoupon(2)">己使用/己过期</span>
        </div>
        <div class="top-placeholder"></div>

        <div class="list-wrap">
            <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
            <van-list
                    v-if="!isEmpty"
                    v-model="loading"
                    :finished="finished"
                    finished-text="没有更多了"
                    @load="onLoad"
            >

                <div class="list-box">
                    <div class="list-item" v-for="(item, index) in list" :key="index">
                        <div class="l">
                            <span>￥<i>{{item.amount}}</i></span>
                            <span v-if="item.price > 0">满{{item.price}}可用</span>
                            <span v-else>无门槛</span>
                        </div>
                        <div class="m">
                            <span>{{item.name}}</span>
                            <span>到期：{{ item.end_time }}</span>
                        </div>
                        <div class="r" :class="{'disable': isActive == 2}">
                            <span @click="go">使用</span>
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
        name: 'UCoupon',
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
                emptyDescription: "您还没有优惠劵哦",
            };
        },
        created() {

        },
        methods: {
            go(){
                if(this.isActive == 2){
                    return ;
                }

                this.$router.push('/ucenter/goods');
            },
            prev() {
                this.$tools.prev();
            },
            changeCoupon(index){
                this.page = 1;
                this.isActive = index;
                this.list = [];
                this.loading = true;
                this.onLoad();
            },
            onLoad() {
                this.isEmpty = false;
                let emptyImage = this.$request.domain() + 'static/images/coupon-empty.png';
                this.$http.getCoupon({
                    type: this.isActive,
                    page: this.page
                }).then(result=>{
                    if(result.data.list == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = emptyImage;
                        this.emptyDescription = "您还没有优惠劵哦";
                    } else if(result.status == 1){
                        this.list = this.list.concat(result.data.list);
                        this.loading = false;
                        this.page++;
                    }else if(result.status == -1){
                        if(result.data == undefined && this.page == 1){
                            this.isEmpty = true;
                            this.emptyImage = emptyImage;
                            this.emptyDescription = "您还没有优惠劵哦";
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
    .top-placeholder { height: 45px; width: 100% }
    .top{
        width: 100%;
        position: fixed;
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
        height: 45px;
        line-height: 45px;
        text-align: center;
        background-color: #fff;
        font-size: 14px;
        color: #666;
        border-bottom: 1px solid #eee;
        span{
            width: 50%;
        }
        span:first-child{
            width: 49%;
            border-right: 1px solid #eee;
        }
        .active{
            color: #e72a2a;
        }
    }
    .list-wrap{
        width: 100%;
        margin-top: 10px;
        .list-item{
            width: 92%;
            height: 100px;
            border-radius: 5px;
            background-color: #fff;
            margin: 0 auto 10px auto;
            font-size: 13px;
            position: relative;
            .l{
                position: absolute;
                width: 110px;
                height: 80px;
                top: 10px;
                left: 0;
                border-right: 1px dashed #cccccc;
                span{
                    color: red;
                    display: block;
                    text-align: center;
                    height: 30px;
                    line-height: 20px;
                }
                span:first-child{
                    font-size: 16px;
                    height: 50px;
                    line-height: 60px;
                    color: #ce3232;
                    i{
                        font-size: 25px;
                        font-style: normal;
                        font-weight: bold;
                    }
                }

            }
            .m{
                padding: 0 55px 0 110px;
                height: 80px;
                text-align: center;
                span{
                    display: block;
                }
                span:first-child{
                    padding-top: 25px;
                    line-height: 25px;
                    font-size: 15px;
                    color: #333;
                }
                span:last-child{
                    height: 25px;
                    line-height: 25px;
                    font-size: 13px; color: #999;
                }
            }
            .r {
                &:before {
                    z-index: 11;
                    content: " ";
                    position: absolute;
                    top: -8px;
                    left: -8px;
                    width: 16px;
                    height: 12px;
                    background-color: #f6f6f6;
                    border-radius: 50px;
                }
                &:after {
                    z-index: 11;
                    content: " ";
                    position: absolute;
                    bottom: -8px;
                    left: -8px;
                    width: 16px;
                    height: 12px;
                    background-color: #f6f6f6;
                    border-radius: 50px;
                }
                z-index: 1;
                position: absolute;
                right: 0;
                top: 0;
                width: 55px;
                height: 100px;
                line-height: 100px;
                float: right;
                text-align: center;
                background-color: #b91922;
                background-image: url(../../assets/images/coupon-circle.png);
                background-repeat: repeat-y;
                background-position: -2px center;
                background-size: 6px;
                span{
                    font-size: 15px;
                    color: #fff;
                    display: block;
                    text-align: center;
                }
            }
            .active{
                background-color: #e55f67;
            }
            .disable{
                background-color: #dbdadd;
            }
        }

    }
</style>
