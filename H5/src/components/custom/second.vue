<template>
    <div>
        <div class="activity-box">
            <div class="activity-head">
                <div>
                    <span class="l">{{rdata.params.title}}</span>
                    <span class="r" @click="$router.push('/second')">更多 &gt;</span>
                </div>
            </div>

            <div class="activity-list">
                <div class="activity-slider" ref="activity">
                    <div
                            class="activity-slider-list"
                            :style="'width: '+((335*rdata.params.list.length))+'px;'"
                    >
                        <div class="activity-slider-item"
                             v-for="(item,index) in rdata.params.list"
                             :key="index"
                             @click="$router.push('/second/view/'+item.id)"
                        >
                            <div class="activity-image">
                                <img :src="item.photo||a3mall">
                            </div>

                            <div class="activity-info">
                                <div class="activity-title">{{item.title}}</div>
                                <div class="activity-price">￥{{item.sell_price}}</div>
                                <div class="activity-time">
                                    <span class="time">
                                        <van-count-down :time="item.time" format="DD 天 HH 时 mm 分 ss 秒" />
                                    </span>
                                    <span class="cart"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Bscroll from 'better-scroll'
    import {CountDown} from "vant";
    export default {
        name: "second",
        components:{
            [CountDown.name]: CountDown
        },
        data(){
            return {
                sscroll:null,
                a3mall: require("@/assets/images/a3mall.png")
            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.sscroll = new Bscroll(this.$refs.activity, {
                    startX: 0,
                    click: true,
                    scrollX: true,
                    scrollY: false,
                    eventPassthrough: 'vertical'
                });
            });
        }
    }
</script>

<style lang="scss" scoped>
    .activity-box{
        width: 100%;
        .activity-head{
            width: 100%;
            height: 50px;
            line-height: 50px;
            border-bottom: 1px solid #e7e7e7;
            div{
                width: 92%;
                margin:0 auto;
                .l{
                    float: left;
                    font-size: 17px;
                    color: #333;
                }
                .r{
                    float: right;
                    font-size: 17px;
                    color: #888;
                }
            }
        }
        .activity-slider-list {
            display: flex; flex-wrap: nowrap; flex-direction: row; font-size: 14px;
        }
        .activity-slider-item{
            position: relative;
            width: 320px;
            height: 115px;
            background-color: #fff;
            float: left;
            margin-right: 10px;
            margin-top: 15px;
            margin-bottom: 15px;
            &:first-child {
                margin-left: 20px;
            }
            .activity-image{
                position: absolute;
                top: 0;
                left: 0;
                width: 110px;
                height: 115px;
                text-align: center;
                img { padding-top: 5%; width: 90%; height: 90%; }
            }
            .activity-info {
                float: left;
                padding-left: 110px;
                position: relative;
                width: 210px;
                height: 115px;
                .activity-title {
                    font-size: 13px;
                    color: #888;
                    padding: 5px 10px;
                    display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical;
                }
                .activity-price {
                    font-size: 14px;
                    color: #b91922;
                    padding-left: 10px;
                    padding-top: 15px;
                }
                .activity-time {
                    position: absolute;
                    left: 120px;
                    bottom: 5px;
                    font-size: 13px;
                    span {
                        float: left;
                    }
                    span:first-child {
                        font-size: 12px;
                        color: #b91922;
                        height: 22px;
                        line-height: 22px;
                        background-color: #f7d6d6;
                        color: #b91922;
                        display: inline-block;
                        padding: 0 5px;
                        /deep/ .van-count-down {
                            font-size: 12px;
                            color: #b91922;
                        }
                    }
                    span:last-child {
                        background-color: #b91922;
                        width: 30px;
                        height: 22px;
                        background-image: url(~@/assets/images/activity-cart.png);
                        background-repeat: no-repeat;
                        background-position: center;
                        display: inline-block;
                        margin-left: 5px;
                    }
                }
            }
        }
    }
</style>