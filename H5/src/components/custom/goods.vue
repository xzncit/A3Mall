<template>
    <div>
        <div class="goods-wrap">
            <div class="goods-head">
                <div>
                    <span class="l">{{rdata.params.title}}</span>
                    <span
                        class="r"
                        v-if="rdata.params.lookMore=='true' && rdata.params.type=='auto'"
                        @click="$router.push('/goods/list/'+rdata.params.category_id)"
                    >更多 &gt;</span>
                </div>
            </div>
            <div v-if="rdata.params.display=='list'">
                <div class="goods-list" :class="'goods-'+rdata.params.column">
                    <div
                        class="goods-box clear"
                        v-for="(item,index) in rdata.params.list"
                        :key="index"
                        :class="'column-'+rdata.params.column"
                        @click="$router.push('/goods/view/'+item.id)"
                    >
                        <div class="goods-item-box clear">
                            <div class="goods-image">
                                <img :src="item.photo||a3mall" alt="">
                            </div>
                            <div class="goods-detail clear">
                            <span class="goods-name">
                                {{item.title||'此处显示商品名称'}}
                            </span>
                                <span class="goods-price">￥{{item.sell_price||'0.00'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="rdata.params.display=='slide'">
                <div class="goods-slider" ref="goods">
                    <div
                        class="goods-slider-list"
                        :style="'width: '+((140*rdata.params.list.length))+'px;'"
                    >
                        <div class="goods-slider-item"
                             v-for="(item,index) in rdata.params.list"
                             :key="index"
                             @click="$router.push('/goods/view/'+item.id)"
                        >
                            <span><img :src="item.photo||a3mall"></span>
                            <span>{{item.title||'此处显示商品名称'}}</span>
                            <span>￥{{item.sell_price}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Bscroll from 'better-scroll'
    export default {
        name: "goods",
        data(){
            return {
                scroll:null,
                a3mall: require("@/assets/images/a3mall.png")
            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        mounted() {
            if(this.rdata.params.display=='slide'){
                this.$nextTick(() => {
                    this.scroll = new Bscroll(this.$refs.goods, {
                        startX: 0,
                        click: true,
                        scrollX: true,
                        scrollY: false,
                        eventPassthrough: 'vertical'
                    });
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .goods-wrap{
        width: 100%;
        .goods-head{
            width: 100%;
            height: 50px;
            line-height: 50px;
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
        .goods-list{
            width: 92%;
            margin: 0 auto;
            display: flex; flex-direction: row;flex-wrap: wrap;
            .goods-box { float: left; }
            .goods-image img { width: 100%; height: 100%; }
            .goods-box.column-1 {
                width: 100%;
                height: 115px;
                position: relative;
                background-color: #fff;
                margin-bottom: 10px;
                .goods-image {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 110px;
                    height: 115px;
                    text-align: center;
                    img { padding-top: 5%; width: 90%; height: 90%; }
                }
                .goods-detail{
                    padding-left: 115px;
                    .goods-name{
                        padding: 0 10px;
                        position: relative;
                        top: 20px;
                        color: #888;
                        font-size: 13px;
                        display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical;
                    }
                    .goods-price{
                        padding: 0 10px;
                        position: relative;
                        top: 40px;
                        font-size: 13px;
                        color: #b91922;
                    }
                }
            }
            .goods-box.column-2 {
                width: 50%;
                margin-bottom: 10px;
                .goods-image {
                    height: 180px;
                }
                .goods-item-box{ background-color: #fff; height: auto; }
                &:nth-child(2n+1) .goods-item-box {
                    margin-left: 0px; margin-right: 5px;
                }
                &:nth-child(2n) .goods-item-box {
                    margin-left: 5px; margin-right: 0px;
                }
                span{ display: block }
                .goods-name{
                    margin: 5px 0;
                    padding: 0 5px;
                    font-size: 13px;
                    float: left;
                    display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical;
                }
                .goods-price{
                    margin: 5px 0 10px 0;
                    padding: 0 10px;
                    font-size: 14px; color: #b91922;
                    float: left;
                }
            }
            .goods-box.column-3 {
                width: 33.333%;
                margin-bottom: 10px;
                .goods-image {
                    height: 110px;
                }
                .goods-item-box{ background-color: #fff; height: auto; }
                &:nth-child(2n+1) .goods-item-box {
                    margin-left: 5px; margin-right: 5px;
                }
                &:nth-child(2n) .goods-item-box {
                    margin-left: 5px; margin-right: 5px;
                }
                span{ display: block }
                .goods-name{
                    margin: 5px 0;
                    padding: 0 5px;
                    font-size: 13px;
                    float: left;
                    display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical;
                }
                .goods-price{
                    margin: 5px 0 10px 0;
                    padding: 0 10px;
                    font-size: 14px; color: #b91922;
                    float: left;
                }
            }
            &.goods-3 { width: 96%; }
        }
        .goods-slider-list {
            background-color: #fff;
            display: flex; flex-wrap: nowrap; flex-direction: row; font-size: 14px;
        }
        .goods-slider-item { float: left; width: 130px; margin-right: 10px; }
        .goods-slider-item:first-child { margin-left: 10px; }
        .goods-slider-item span { display: block; text-align: center; }
        .goods-slider-item span:nth-child(1) { height: 130px; }
        .goods-slider-item span:nth-child(1) img { width: 100%; height: 130px; display: block; }
        .goods-slider-item span:nth-child(2) { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .goods-slider-item span:nth-child(3) { color: red; }

    }
</style>