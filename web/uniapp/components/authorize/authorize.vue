<template>
	<view>
		<view class='popup-box' v-if="value">
		   <view class='title'>申请授权</view>
		   <!-- #ifdef H5 || APP-PLUS -->
		   <view class='tip' v-if="platformAgent != null && platformAgent.isWechat">获得你的公开信息（昵称、头像等）,以便为您提供更好的服务</view>
		   <view class='tip' v-if="platformAgent != null && !platformAgent.isWechat">您还没有登录,请登录后在继续操作。</view>
		   <!-- #endif -->
		   
		   <!-- #ifdef MP -->
		   <view class='tip'>获得你的公开信息（昵称、头像等）,以便为您提供更好的服务</view>
		   <!-- #endif -->
		   <view class='bottom flex'>
		      <view class='item' @click="close">随便逛逛</view>
		      <!-- #ifdef H5 || APP-PLUS -->
			  <button class='item grant' v-if="platformAgent != null && platformAgent.isWechat" type="primary" @click="login">去授权</button>
			  <button class='item grant' v-if="platformAgent != null && !platformAgent.isWechat" type="primary" @click="login">去登录</button>
		      <!-- #endif -->
			  
			  <!-- #ifdef MP -->
			  <button class='item grant' type="primary" @click="login">去授权</button>
			  <!-- #endif -->
		   </view>
		</view>
		<view class='mask' v-if="value" @click="close"></view>
	</view>
</template>

<script>
	export default {
		props: {
			value: {
				type: [Boolean],
				default: true
			},
			isGoHome: {
				type: [Boolean],
				default: false
			},
			isBack: {
				type: [Boolean],
				default: false
			}
		},
		data(){
			return {
				platformAgent: null
			};
		},
		mounted() {
			this.platformAgent = this.$utils.platformAgent();
		},
		methods: {
			login(){
				uni.navigateTo({
					url: "/pages/public/login"
				})
			},
			close(){
				this.$emit("input",!this.value);
				if(this.isGoHome){
					uni.switchTab({ url: "/pages/index/index" })
				}else{
					if(!this.isBack){
						return ;
					}
					
					let pages = getCurrentPages();
					let currPage  = pages[pages.length - 1];
					
					if(currPage.route == "pages/ucenter/index"){
						return ;
					}
					
					if(pages.length <= 1){
						uni.switchTab({ url: "/pages/index/index" });
						return ;
					}
					
					uni.navigateBack();
				}
				
			}
		}
	}
</script>

<style lang="scss">
	.popup-box{
	  width:500rpx;
	  background-color:#fff;
	  position:fixed;
	  top:50%;
	  left:50%;
	  margin-left:-250rpx;
	  transform:translateY(-50%);
	  z-index:311220;
	}
	.popup-box .title{
	  font-size:28rpx;
	  color:#000;
	  text-align:center;
	  margin-top: 30rpx;
	}
	.popup-box .tip{
	  font-size:22rpx;
	  color:#555;
	  padding:0 24rpx;
	  margin-top:25rpx;
	}
	.popup-box .bottom .item{
	  width:50%;
	  height:80rpx;
	  background-color:#eeeeee;
	  text-align:center;
	  line-height:80rpx;
	  font-size:24rpx;
	  color:#666;
	  margin-top:54rpx;
	}
	.popup-box .bottom .item.on{
	  width: 100%;
	}
	.flex{
	  display:flex;
	}
	.popup-box .bottom .item.grant{
	  font-size:28rpx;
	  color:#fff;
	  font-weight:bold;
	  background-color:#1b43c4;
	  border-radius:0;
	  padding:0;
	}
	.mask{
	  position:fixed;
	  top:0;
	  right:0;
	  left:0;
	  bottom:0;
	  background-color:rgba(0,0,0,0.3);
	  z-index:311110;
	}
</style>
