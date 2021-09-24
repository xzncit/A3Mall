<template>
	<view>
		<navbar :scroll="scrollNum" :iSimmersive="false" :placeholder="true" title="申请提现"></navbar>
		<view class="notice-box">
			当前可提现金额: ￥{{money}}
		</view>
		<form>
			<view class="form-box">
				<view class="form-fields clear">
					<view class="title">转帐方式</view>
					<view class="form-field-box">
						<picker @change="bindPickerChange" :value="index" :range="array">
							<view class="form-field-text">{{array[index]}}</view>
						</picker>
					</view>
				</view>
				<view class="form-fields clear">
					<view class="title">持卡人</view>
					<view class="form-field-box">
						<input class="form-field-input" name="name" v-model="name" placeholder="请填写持卡人" />
					</view>
				</view>
				<view class="form-fields clear">
					<view class="title">卡号</view>
					<view class="form-field-box">
						<input class="form-field-input" name="code" v-model="code" placeholder="请填写卡号" />
					</view>
				</view>
				 <view class="form-fields clear">
					<view class="title">提现金额</view>
					<view class="form-field-box">
						<input type="number" class="form-field-input" name="price" v-model="price" placeholder="0.00" />
					</view>
				</view>
				<view class="form-submit">
					<view class="btn" :class="{ active: isActive }" @click="formSubmit">提交</view>
				</view>
			</view>
		</form>
	</view>
</template>

<script>
	import navbar from "@/components/navbar/navbar";
	export default {
		components: {
			navbar
		},
		data(){
			return {
				scrollNum: 0,
				array: ["请选择"],
				index: 0,
				name: "",
				code: "",
				price: "",
				money: "",
				isActive: false
			};
		},
		onShow(){
			this.$http.getWalletSettlement().then(res=>{
				if(res.status){
					this.array = res.data.bank;
					this.money = res.data.money;
				}
			});
		},
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
		},
		methods: { 
			bindPickerChange: function(e) {
				this.index = e.target.value
			},
			formSubmit(){
				if(this.isActive){
					return ;
				}
				
				if(this.price.length <= 0){
					this.$utils.msg("请输入要提现金额");
					return ;
				}
				
				if(this.name.length == 0){
					this.$utils.msg("请填写持卡人");
					return false;
				}

				if(this.code.length == 0){
					this.$utils.msg("请填写卡号");
					return false;
				}

				if(!/^([1-9]{1})(\d{15}|\d{18})$/.test(this.code)){
					this.$utils.msg("您填写的银行卡号不正确");
					return false;
				}
				
				this.isActive = true;
				
				this.$http.editWalletSettlement({
					bank_type: this.array[this.index],
					name: this.name,
					code: this.code,
					price: this.price
				}).then(res=>{
					if(res.status){
						this.$utils.msg(res.info);
						setTimeout(()=>{
							this.$utils.navigateBack();
						},2000);
					}else{
						this.$utils.msg(res.info);
					}
					
					this.isActive = false;
				}).catch(err=>{
					this.$utils.msg("连接网络出错，请稍后在试。");
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.notice-box { 
		color: #1989FA;
		background: #ECF9FF;
		font-size: 28rpx;
		padding: 0 30rpx;
		height: 80rpx;
		line-height: 80rpx;
	}
	.form-fields {
		width: 100%; float: left;
		height: 100rpx;
		line-height: 100rpx; 
		border-bottom: 1px solid #ebedf0;
		.title { 
			float: left; font-size: 30rpx;
			width: 200rpx; text-indent: 30rpx;
		}
		.form-field-box { 
			float: right; font-size: 30rpx; 
			width: 550rpx;
			.form-field-input {
				height: 100rpx;
				line-height: 100rpx; 
				font-size: 30rpx;
			}
		}
		
	}
	.form-submit {
		float: left;
		width: 100%;
		margin-top: 50rpx;
		.btn { 
			margin: 0 auto;
			width: 92%;
			height: 80rpx;
			line-height: 80rpx;
			display: block;
			text-align: center;
			font-size: 30rpx; 
			background-color: #b91922;
			border: 1px solid #b91922;
			border-radius: 10rpx;
			color: #fff;
		}
		.active { 
			background-color: #ffffff;
			border: 1px solid #d6d6d6;
			color: #a1a1a1;
		}
	}
</style>
