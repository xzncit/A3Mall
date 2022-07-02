<template>
	<view> 
		<navbar :scroll="scrollNum" :iSimmersive="true" title="" :onBack="onBack"></navbar>
		<view class="app" v-if="(platform.type=='app' || platform.type=='h5') && !platform.isWechat">
			<view class="top">
				<view>泰誉凡</view>
				<view>会员登录</view>
			</view>
			
			<view class="theform">
				<form @submit="onSubmit">
					<view class="fields-box">
						<view class="field-box iconfont">
							<input type="text" class="uni-input" name="phone" value="18026740326" placeholder="用户名/邮箱/手机号" />
						</view>
						<view class="field-box iconfont">
							<input type="password" class="uni-input" name="password" value="admin888" placeholder="密码" />
						</view>
					</view>
					
					<view class="btn">
						<button :disabled="isSubmit" form-type="submit">登录</button>
					</view>
				</form>
				<view class="tips-box clear">
					<view><navigator url="register" hover-class="none">用户注册</navigator></view>
					<view><navigator url="forget" hover-class="none">忘记密码</navigator></view>
				</view>
			</view>
		</view>
		
		<!-- #ifdef H5 -->
		<view class="login-wrap" v-if="platform.type=='h5' && platform.isWechat">
			<view class="logo"><image v-if="static" :src="static+'app/a3mall.png'"></image></view>
			<view class="wechat-title">微信授权登录</view>
			<view class="wechat-desc">获得您的公开信息（昵称、头像等），以便为您提供更好的服务</view>
			<view class="wechat-login-btn" @click="onWechatLogin">授权登录</view>
			<view class="wechat-go-home" @click="onGoHome">暂不登录</view>
		</view>
		<!-- #endif -->
		
		<loading v-if="isSubmit" :layer="true"></loading>
	</view>
</template>
 
<script>
	import loading from '../../components/tool/loading';
	import { checkPhone } from '../../common/check';
	import navbar from "@/components/navbar/navbar";
	// #ifdef H5
	import { login } from '../../common/wechat';
	// #endif
	export default {
		components: {
			loading,navbar
		},
		data() {
			return {
				static: '',
				scrollNum: 0,
				isSubmit: false,
				platform: "app"
			}
		},
		onLoad() {
			this.static = this.$static;
			this.platform = this.$utils.platformAgent();
		},
		onShow() {
			// 微信公众号授权成功后回调处理
			if(this.platform.type == "h5" && this.platform.isWechat){
				if(this.$route.query.code != undefined && this.$route.query.code.length){
					this.isSubmit = true;
					this.$http.wxLogin({
						source: 1,
						code: this.$route.query.code,
						state: this.$route.query.state
					}).then(result=>{
						if(result.status){
							this.$store.commit("UPDATEUSERS",result.data);
							this.$utils.switchTab('ucenter/index');
						}else{
							this.$utils.msg(result.info);
						}
						
						this.isSubmit = false;
					}).catch(error=>{
						this.isSubmit = false;
						this.$utils.msg("请求失败，请稍后在试");
					});
				}
			}
		},
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
		},
		methods: {
			onBack(){
				this.$utils.switchTab("index/index");
			},
			// #ifdef H5
			onWechatLogin(){
				login().catch(error=>{
					this.$utils.msg(error);
				});
			},
			// #endif
			onGoHome(){
				this.$utils.switchTab("index/index");
			},
			onSubmit(e){
				let formData = e.detail.value;
				this.isSubmit = true;
				if(formData.phone == ''){
					this.isSubmit = false;
					this.$utils.msg("请填写帐号！");
					return ;
				}else if(formData.password == ''){
					this.isSubmit = false;
					this.$utils.msg("请填写密码！");
					return ;
				}
				
				this.$http.sendLogin({
					username: formData.phone,
					password: formData.password
				}).then((result)=>{
					if(result.status){
						this.$store.commit("UPDATEUSERS",result.data);
						this.$utils.switchTab('ucenter/index');
					}else{
						this.$utils.msg(result.info);
					}
					
					this.isSubmit = false;
				}).catch((error)=>{
					this.isSubmit = false;
					this.$utils.msg("连接网络错误，请检查网络是否连接！");
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.login-wrap {
		.logo {
			text-align: center;
			padding-top: 180rpx;
			image {
				width: 180rpx;
				height: 180rpx;
			}
		}
		.wechat-title {
			font-size: 35rpx;
			font-weight: 500;
			color: #333;
			margin-top: 64rpx;
			text-align: center;
		}
		.wechat-desc {
			font-size: 28rpx;
			font-weight: 500;
			color: #999;
			margin-top: 24rpx;
			text-align: center;
			padding: 10rpx 50rpx;
		}
		.wechat-login-btn {
			height: 80rpx; line-height: 80rpx;
			color: #fff; background-color: #33A7FF;
			text-align: center; border-radius: 50rpx;
			margin: 50rpx; font-size: 32rpx;
		}
		.wechat-go-home { text-align: center; font-size: 30rpx; color:#666; }
	}
	.top {
		background-color: transparent;
		width: 100%;
		height: 386rpx;
		position: relative;
		z-index: 1;
		background-image: url(~@/static/images/login-bg.png);
		background-repeat: no-repeat;
		background-size: 100%;
		view {
			z-index: 2;
			position: absolute;
			&:nth-child(1) {
				top: 90rpx;
				font-size: 72rpx;
				color: #fff;
				width: 100%;
				text-align: center;
				&::after {
					position: absolute;
					content: " ";
					background-color: #7a91dc;
					height: 1px;
					width: 210rpx;
					top: 120rpx;
					left: 50%;
					transform: translateX(-50%);
				}
			}
			&:nth-child(2) {
				top: 225rpx;
				font-size: 49rpx;
				color: #fff000;
				text-align: center;
				width: 100%;
			}
		}
		image {
			width: 100%;
			height: 386rpx;
		}
	}
	
	.theform {
		width: 590rpx;
		margin: 70rpx auto 0 auto;
		.fields-box{
			width: 100%;
			border: 1px solid #d2cdcd;
			overflow: hidden;
			border-radius: 10rpx;
			.field-box{
				width: 100%;
				height: 100rpx;
				position: relative;
				font-size: 40rpx;
				&:first-child {
					border-bottom:1px solid #d2cdcd;;
				}
				input { 
					width: 100%; height: 100rpx; line-height: 100rpx; 
					text-indent: 100rpx; font-size:29rpx; color: #888;
				}
				&:nth-child(1):before { 
					content: "\e61b";
					color: #bfbfbf;
					position: absolute;
					left: 30rpx;
					top: 28rpx;
				}
				&:nth-child(2):before {
					content: "\e61a";
					color: #bfbfbf;
					position: absolute;
					left: 30rpx;
					top: 28rpx;
				}
			}
		}
		.btn{
			width: 100%;
			margin-top: 48rpx;
			button{
				color: #fff;
				background-color: #1b43c4;
				border: 1px solid #1b43c4;
				border-radius: 10rpx;
				font-size: 33rpx;
				height: 100rpx;
				line-height: 100rpx;
				text-align: center;
			}
		}
		.tips-box{
			width: 100%;
			font-size: 28rpx;
			color: #888;
			margin-top: 45rpx;
			view {
				width: 50%;
				float: left;
				&:last-child {
					text-align: right;
				}
			}
		}
	}
</style>