<template>
	<view class="max-empty" :style="{backgroundColor: backgroundColor}">
		<view v-if="type==='cart'" class="cart column center">
			<image class="icon" :src="static+'/empty/cart.png'"></image>
			<text class="title">空空如也</text>
			<text class="text">别忘了买点什么犒赏一下自己哦</text>
			<view class="btn center" @click="jump">
				<text>随便逛逛</text>
			</view>
		</view>
		<view v-else-if="type==='address'" class="address column center">
			<image class="icon" :src="static+'/empty/address.png'"></image>
			<text class="text">您还没有收货地址哦~</text>
			<view class="btn center" @click="jump">
				<text>添加地址</text>
			</view>
		</view>
		<view v-else-if="type==='favorite'" class="favorite column center">
			<image class="icon" :src="static+'/empty/favorite.png'"></image>
			<text class="text">收藏夹空空的，先去逛逛吧~</text>
			<view class="btn center" @click="jump">
				<text>随便逛逛</text>
			</view>
		</view>
		<view v-else-if="type==='order'" class="order column center">
			<image class="icon" :src="static+'/empty/order.png'"></image>
			<text class="text">加载订单失败，请稍后在试</text>
			<view class="btn center" @click="jump">
				<text>返回</text>
			</view>
		</view>
		<view v-else-if="type==='service'" class="service column center">
			<image class="icon" :src="static+'/empty/service.png'"></image>
			<text class="text">请求出错，请稍在试</text>
			<view class="btn center" @click="jump">
				<text>刷新</text>
			</view>
		</view>
		<view v-else class="default column center">
			<image class="icon" :src="static+'/empty/default.png'"></image>
			<text class="text" @click="jump">{{ text }}</text>
		</view>
	</view>
</template>

<script>
	/**
	 * 缺省显示
	 * @prop text 缺省文字提示
	 * @prop type 缺省类型
	 * @prop backgroundColor 缺省页面背景色
	 */
	export default {
		props: {
			text: {
				type: String,
				default: '暂时没有数据'
			},
			type: {
				type: String,
				default: ''
			},
			backgroundColor: {
				type: String,
				default: 'rgba(0,0,0,0)'
			}
		},
		data(){
			return {
				static: ""
			};
		},
		mounted() {
			this.static = this.$static;
		},
		methods: {
			jump(){
				this.$emit("onEvents",{});
			},
			onCartBtnClick(){
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			switchTab(url){
				uni.switchTab({
					url
				})
			}
		}
	}
</script>

<style scoped lang="scss">
	.column{
		/* #ifndef APP-PLUS-NVUE */
		display:flex;
		/* #endif */
		flex-direction: column;
	}
	.center{
		/* #ifndef APP-PLUS-NVUE */
		display:flex;
		/* #endif */
		align-items: center;
		justify-content: center;
	}
	.max-empty{
		position: fixed;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
		display: flex;
		flex-direction: column;
		align-items: center;
		animation: show .5s 1;
	}
	@keyframes show{
		from {
			opacity: 0;
		}
		to {
			opacity: 1;
		}
	}
	.default{
		padding-top: 26vh;
		/* #ifdef H5 */
		padding-top: 30vh;
		/* #endif */
		
		.icon{
			width: 350rpx;
			height: 350rpx;
		}
		.text{
			margin-top: 10rpx;
			font-size: 28rpx;
			color: #999;
		}
	}
	.cart{
		padding-top: 14vh;
		/* #ifdef H5 */
		padding-top: 18vh;
		/* #endif */
		
		.icon{
			width: 350rpx;
			height: 350rpx;
		}
		.title{
			margin: 50rpx 0 26rpx;
			font-size: 34rpx;
			color: #333;
		}
		.text{
			font-size: 28rpx;
			color: #aaa;
		}
		.btn{
			width: 320rpx;
			height: 80rpx;
			margin-top: 80rpx;
			text-indent: 2rpx;
			letter-spacing: 2rpx;
			font-size: 32rpx;
			color: #fff;
			border-radius: 100rpx;
			background: linear-gradient(to bottom right, #1b43c4, #1b43c4);
		}
	}
	.address{
		padding-top: 6vh;
		/* #ifdef H5 */
		padding-top: 10vh;
		/* #endif */
		
		.icon{
			width: 350rpx;
			height: 350rpx;
		}
		.text{
			width: 400rpx;
			margin-top: 40rpx;
			font-size: 30rpx;
			color: #999;
			text-align: center;
			line-height: 1.6;
		}
		.btn{
			width: 320rpx;
			height: 80rpx;
			margin-top: 80rpx;
			text-indent: 2rpx;
			letter-spacing: 2rpx;
			font-size: 32rpx;
			color: #fff;
			border-radius: 100rpx;
			background: linear-gradient(to bottom right, #1b43c4, #1b43c4);
		}
	}
	.favorite{
		padding-top: 6vh;
		/* #ifdef H5 */
		padding-top: 10vh;
		/* #endif */
		
		.icon{
			width: 360rpx;
			height: 360rpx;
		}
		.text{
			width: 400rpx;
			margin-top: 40rpx;
			font-size: 30rpx;
			color: #999;
			text-align: center;
			line-height: 1.6;
		}
		.btn{
			width: 320rpx;
			height: 80rpx;
			margin-top: 80rpx;
			text-indent: 2rpx;
			letter-spacing: 2rpx;
			font-size: 32rpx;
			color: #fff;
			border-radius: 100rpx;
			background: linear-gradient(to bottom right, #1b43c4, #1b43c4);
		}
	}
	.order{
		padding-top: 6vh;
		/* #ifdef H5 */
		padding-top: 10vh;
		/* #endif */
		
		.icon{
			width: 360rpx;
			height: 360rpx;
		}
		.text{
			width: 400rpx;
			margin-top: 40rpx;
			font-size: 30rpx;
			color: #999;
			text-align: center;
			line-height: 1.6;
		}
		.btn{
			width: 320rpx;
			height: 80rpx;
			margin-top: 80rpx;
			text-indent: 2rpx;
			letter-spacing: 2rpx;
			font-size: 32rpx;
			color: #fff;
			border-radius: 100rpx;
			background: linear-gradient(to bottom right, #1b43c4, #1b43c4);
		}
	}
	.service{
		padding-top: 6vh;
		/* #ifdef H5 */
		padding-top: 10vh;
		/* #endif */
		
		.icon{
			width: 360rpx;
			height: 360rpx;
		}
		.text{
			width: 400rpx;
			margin-top: 40rpx;
			font-size: 30rpx;
			color: #999;
			text-align: center;
			line-height: 1.6;
		}
		.btn{
			width: 320rpx;
			height: 80rpx;
			margin-top: 80rpx;
			text-indent: 2rpx;
			letter-spacing: 2rpx;
			font-size: 32rpx;
			color: #fff;
			border-radius: 100rpx;
			background: linear-gradient(to bottom right, #1b43c4, #1b43c4);
		}
	}
</style>
