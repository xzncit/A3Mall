<template>
    <view class="goods-action-icon" :class="'goods-action-icon-'+icon" @click="btn">
        <view class="goods-action-btn">
            <view v-if="icon == 'home'" class="icon iconfont" style="font-size: 18px">&#xe657;</view>
			<view v-if="icon == 'online'" class="icon iconfont" style="font-size: 18px">&#xe605;</view>
            <view v-if="icon == 'cart'" class="icon iconfont" style="font-size: 18px;">&#xe60f;</view>
            <view v-if="icon == 'collect'" class="icon iconfont" :class="{active:active}" style="font-size: 18px">&#xe635;</view>
            <text v-if="count > 0">{{count}}</text>
        </view>
        <view><text :class="{active:active}">{{text}}</text></view>
    </view>
</template>

<script>
    export default {
        props: {
            icon: {
                type: String,
                default: ""
            },
            text: {
                type: String,
                default: ""
            },
            count: {
                type: [String,Number],
                default: "0"
            },
            active: {
                type: Boolean,
                default: false
            }
        },
        methods:{
            btn(){
                this.$emit("click");
            }
        }
    }
</script>

<style lang="scss" scoped>
.goods-action-icon{
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 108rpx;
    height: 99rpx;
    color: #646566;
    font-size: 24rpx;
    text-align: center;
    background-color: #fff;
    cursor: pointer;
    .goods-action-btn {
        display: block; text-align: center;
        font-size: 26rpx; color: #666;
        position: relative;
        &:first-child {
            position: relative;
            view {
                display: inline-block;
                width: 36rpx; height: 36rpx;
                padding: 6rpx 0;
            }
            .active {
                color: #ff5000;
            }
            text {
                position: absolute;
                top: 10rpx;
                right: 24rpx;
                box-sizing: border-box;
                min-width: 36rpx;
                padding: 0 6rpx;
                color: #fff;
                font-weight: 500;
                font-size: 24rpx;
                line-height: 28rpx;
                text-align: center;
                background-color: #ee0a24;
                border: 2rpx solid #fff;
                border-radius: 64rpx;
                transform: translate(50%,-50%);
                transform-origin: 100%;
            }
        }
        &:last-child{
            font-size: 48rpx;
            -webkit-transform:scale(0.90);
            .active {
                color: #ff5000;
            }
        }
    }
}

</style>