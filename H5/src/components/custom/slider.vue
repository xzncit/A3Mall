<template>
    <div>
        <div class="swiper-box" :style="swiperBox">
            <van-swipe class="my-swipe" :style="swipeStyle" :height="rdata.params.height" :autoplay="rdata.params.duration">
                <van-swipe-item v-for="(image, index) in rdata.params.list" :key="index">
                    <img :src="image.thumb_image" :style="{height: rdata.params.height+'px'}" />
                </van-swipe-item>
            </van-swipe>
        </div>
    </div>
</template>

<script>
    import Vue from "vue";
    import {Swipe, SwipeItem} from 'vant';
    export default {
        name: "slider",
        components:{
            [Swipe.name]: Swipe,
            [SwipeItem.name]: SwipeItem,
        },
        data(){
            return {
                swiperBox: {},
                swipeStyle: {}
            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        created() {
            this.swipeStyle = {
                borderRadius: this.rdata.params.border+"px"
            };

            this.swiperBox = {
                paddingTop: this.rdata.params.marginTop+"px",
                paddingBottom: this.rdata.params.marginBottom+"px",
                width: 100 - this.rdata.params.marginLeft - this.rdata.params.marginRight + "%"
            };
        },
        methods: {

        }
    }
</script>

<style lang="scss" scoped>
    .swiper-box{ width: 100%; margin: 0 auto; }
    .my-swipe {width: 100%;height: auto; border-radius: 6px;}
    .my-swipe .van-swipe-item img { display: block;width: 100%;height: 150px;background-color: #fff;pointer-events: none;}
    .my-swipe /deep/ .van-swipe__indicator--active { background-color: #b71c1c }
</style>