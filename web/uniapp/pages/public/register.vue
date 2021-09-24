<template>
	<view>
		<navbar :scroll="scrollNum" :iSimmersive="true" title="" :onBack="onBack"></navbar>
		<view class="top">
			<view>A3Mall</view>
			<view>泰誉凡</view>
		</view>
		
		<view class="theform">
			<form @submit="onSubmit">
				<view class="fields-box">
					<view class="field-box iconfont">
						<input type="number" v-model="phone" class="uni-input" name="phone" placeholder="手机号" />
					</view>
					<view class="field-box iconfont">
						<input type="number" class="uni-input" name="code" placeholder="短信验证码" />
						<text class="send-sms" :class="{active: isSendCode}" @click="onSend">{{smsMsg}}</text>
					</view>
					<view class="field-box iconfont">
						<input type="password" class="uni-input" name="password" placeholder="密码" />
					</view>
				</view>
				
				<view class="btn">
					<button :disabled="isSubmit" form-type="submit">注 册</button>
				</view>
			</form>
			<view class="tips-box">
				<view><navigator url="login" hover-class="none">已有账号，<text>登录</text></navigator></view>
			</view>
		</view>
		
		<loading v-if="isSubmit" :layer="true"></loading>
	</view>
</template>
 
<script>
	import loading from '../../components/tool/loading'
	import { checkPhone } from '../../common/check';
	import navbar from "@/components/navbar/navbar";
	export default {
		components:{
			loading,navbar
		},
		data() {
			return {
				static: "",
				scrollNum: 0,
				smsMsg: "发送验证码",
				isSendCode: false,
				phone: '',
				isSubmit: false,
				timer: null
			}
		},
		onLoad() {
			this.static = this.$static;
		},
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
		},
		methods: {
			onBack(){
				this.$utils.switchTab("index/index");
			},
			onSend(){
				if(!checkPhone(this.phone)){
					this.$utils.msg("您填写的手机号码不正确");
					return false;
				}
				if(this.isSendCode){
					return false;
				}
				
				let time = 60;
				clearInterval(this.timer);
				this.timer = setInterval(() => {
				    time--;
				    this.isSendCode=true;
				    this.smsMsg=time + "秒后重发"
				    if (time <= 0) {
				        this.isSendCode=false;
				        this.smsMsg="重新获取";
				        clearInterval(this.timer);
				    }
				}, 1000);
				
				this.$http.sendSMS({
					username: this.phone,
					type: "register"
				}).then((result)=>{
					this.$utils.msg(result.info);
				}).catch((error)=>{
					this.$utils.msg("连接网络错误，请检查网络是否连接！");
				});
			},
			onSubmit(e){
				let formData = e.detail.value;
				this.isSubmit = true;
				if(formData.phone == ''){
					this.isSubmit = false;
					this.$utils.msg("请填写手机号码！");
					return ;
				}else if(!checkPhone(this.phone)){
					this.isSubmit = false;
					this.$utils.msg("您填写的手机号码不正确！");
					return ;
				}else if(formData.password == ''){
					this.isSubmit = false;
					this.$utils.msg("请填写密码！");
					return ;
				}else if(formData.code == ''){
					this.isSubmit = false;
					this.$utils.msg("请填写验证码！");
					return ;
				}
				
				this.$http.sendRegister({
					username: formData.phone,
					password: formData.password,
					code: formData.code
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
	.top {
		background-color: transparent;
		width: 100%;
		height: 386rpx;
		position: relative;
		z-index: 1;
		background-image: url(~@/static/images/register-bg.png);
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
				border-bottom:1px solid #d2cdcd;
				position: relative;
				font-size: 40rpx;
				&:last-child {
					border-bottom:0px solid #d2cdcd;
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
					content: "\e618";
					color: #bfbfbf;
					position: absolute;
					left: 30rpx;
					top: 28rpx;
				}
				&:nth-child(3):before {
					content: "\e61a";
					color: #bfbfbf;
					position: absolute;
					left: 30rpx;
					top: 28rpx;
				}
				.send-sms{
					position: absolute;
					top: 50%;
					transform: translateY(-50%);
					font-size: 29rpx;
					color: #fff;
					background-color: #1b43c4;
					display: block;
					width: 195rpx;
					height: 90rpx;
					line-height: 90rpx;
					text-align: center;
					right: 10rpx;
					border-radius: 5rpx;
					&.active { color: #333; background-color: #eee; }
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
				width: 100%;
				float: left;
				text-align: center;
				text { color: #1b43c4; }
			}
		}
	}
</style>