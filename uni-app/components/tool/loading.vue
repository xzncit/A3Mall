<template>
	<view>
		<view v-if="isShowLoading" class="iconfont loading">&#xe84b;</view>
		<view class="loading-text" v-if="text != ''">
			<view>{{text}}<text class="dotting"></text></view>
		</view>
		<view  
		v-if="layer"
		class="layer-box"
		:style="'background-color:'+color"
		></view>
	</view>
</template>

<script>
	export default {
		name: "loading",
		props:{
			text:{
				type: String,
				default: ""
			},
			isShowLoading: {
				type:Boolean,
				default:true
			},
			layer:{
				type:Boolean,
				default:false
			},
			color:{
				type:String,
				default: "rgba(255,255,255,0.1)"
			}
		},
		mounted() {
			
		}
	}
</script>

<style lang="scss" scoped>
	:root .dotting { margin-right: 8px; }
	.loading {
		font-size: 50rpx;
		position: fixed;
		left: 47%;
		top: 40%;
		z-index: 100002;
		background-size: 100%; 
		transform: translateX(-47%);
		transform: translateY(-40%);
		animation: aaa-spin 2s infinite linear;
		display: inline-block;
	}
	.loading-text { 
		width: 100%;
		font-size: 29rpx;
		text-align: center;
		position: fixed;
		top: 47%;
		color: #333;
		z-index: 100002;
		background-size: 100%;
		transform: translateY(-40%);
		view {
			width: 80%;
			margin: 0 auto;
			.dotting {
				display: inline-block; min-width: 2px; min-height: 2px;
				animation: dot 4s infinite step-start both;
				font-size: 29rpx;
			}
		}
	}
	.layer-box{
		width: 100%;
		height: 100%;
		position: fixed;
		top: 0;
		left: 0;
		background-color: rgba(255,255,255,0.1);
		z-index: 100001;
	}
	
	@keyframes aaa-spin {
		0% {
			transform: rotate(0deg)
		}
	
		100% {
			transform: rotate(359deg)
		}
	}
	@keyframes dot {
	    25% { box-shadow: none; }
	    50% { box-shadow: 2px 0 #666; }
	    75% { box-shadow: 2px 0 #666, 6px 0 #666; }
	}
</style>
