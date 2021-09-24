<template>
	<view class="wrap">
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="退款详情"></navbar>
		<info v-if="isError"></info>
		<view v-if="!isError">
		
			<view class="goods">
				<view class="title">
					<text>共{{order.item.length}}件商品</text>
				</view>
				<view class="goods-box">
					<view
						class="goods-item clear"
						v-for="(value,index) in order.item"
						:key="index"
					>

						<view class="goods-img">
							<image :src="value.thumb_image">
						</view>

						<view class="goods-info">
							<view class="t">
								<text>{{value.title}}</text>
								<text>￥{{value.sell_price}}</text>
							</view>
							<view class="b">
								<text>{{value.spec}}</text>
								<text>× {{value.nums}}</text>
							</view>
						</view>

					</view>

				</view>
			</view>

			<view class="order">
				<view class="list clear">
					<view class="list-box clear">
						<view>商品金额：</view>
						<view>{{order.real_amount}}</view>
					</view>
					<view class="list-box clear">
						<view>运费金额：</view>
						<view>{{order.payable_freight}}</view>
					</view>
					<view class="list-box clear">
						<view>订单总额：</view>
						<view class="money">{{order.order_amount}}</view>
					</view>
					<view class="list-box clear">
						<textarea @blur="bindTextAreaBlur" placeholder="请填写退款说明" maxlength="200" style="width: 100%; height: 100rpx;"></textarea>
					</view>
				</view>
			</view>
			
			<view class="btn" v-if="!order.is_refund">
				<view @click="onSubmit">申请退款</view>
			</view>
		</view>
	</view>
</template>

<script>
	import MallInfo from '@/components/tool/info.vue'
	import navbar from "@/components/navbar/navbar";
	export default {
		components:{
			MallInfo,navbar
		},
		data(){
			return {
				isError: false,
				isSubmit: false,
				orderId: 0,
				message: "",
				order:{
					item: [],
					order_amount: "",
					order_no: "",
					payable_freight: '',
					payable_amount: "",
					promotions: "",
					real_amount: "",
					is_refund:false,
					order_status: ""
				}
			};
		},
		onLoad(options) {
			this.isError = false;
			this.orderId = options.id;
			this.$http.getOrderRefund({
				id: this.orderId
			}).then((res)=>{
				if(res.status){
					this.order = res.data;
				}else{
					this.$utils.msg(res.info);
				}
				
				this.isError = false;
			}).catch((err)=>{
				this.isError = true;
			});
			
		},
		methods: {
			bindTextAreaBlur: function (e) {
				this.message = e.detail.value;
			},
			onSubmit(){
				if(this.isSubmit){
					return false;
				}
				
				this.$utils.showLoading();
				this.isSubmit = true;
				this.$http.sendOrderRefund({
					id: this.orderId,
					message: this.message
				}).then(res=>{
					this.$utils.hideLoading();
					if(res.status){
						this.$utils.msg(res.info);
						this.order.is_refund = true;
						this.$utils.redirectTo('order/detail',{
							id: this.orderId
						});
					}else{
						this.$utils.msg(res.info);
					}
					
					this.isSubmit = false;
				}).catch(err=>{
					this.$utils.hideLoading();
					this.isSubmit = false;
					this.$utils.msg("网络出错，请检查网络是否连接");
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.money{ color: #fc4141; }
	.goods{
		background-color: #fff;
		margin-top: 30rpx;
		padding-bottom: 20rpx;
		.title{
			width: 100%;
			margin: 0 auto;
			color: #666;
			font-size: 28rpx;
			height: 80rpx;
			line-height: 80rpx;
			border-bottom: 1px solid #eee;
			text {
				padding-left: 20rpx;
			}
		}
		.goods-box{
			padding: 0 30rpx;
			.goods-item {
				padding-top: 10px;
				.goods-img {
					width: 150rpx;
					height: 150rpx;
					display: inline-block;
					float: left;
					image {
						width: 100%;
						height: 100%;
					}
				}
				.goods-info {
					display: inline-block;
					width: 72%;
					font-size: 28rpx;
					float: right;
					.t {
						width: 100%;
						height: 90rpx;
						text:first-child{
							float: left;
							display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;
							-webkit-box-orient: vertical;
							width: 70%;
						}
						text:last-child{
							width: 30%;
							float: right;
							text-align: right;
						}
					}
					.b{
						width: 100%;
						height: 80rpx;
						font-size: 26rpx;
						text:first-child{
							float: left;
							color: #999;
						}
						text:last-child{
							float: right;
							color: #666;
						}
					}
				}
			}
		}
	}
	.order{
		background-color: #fff;
		margin-top: 30rpx;
		padding-bottom: 20rpx;
		.list {
			width: 100%;
			.list-box{
				width: 92%;
				height: auto !important;
				height: 80rpx;
				min-height: 80rpx;
				line-height: 80rpx;
				margin: 0 auto;
				font-size: 28rpx; color: #333;
				border-bottom: 1px solid #ebedf0;
				padding: 10rpx 0;
				view{ display: inline-block; }
				view:first-child { float: left; }
				view:last-child { float: right; }
			}
		}
	}
	.btn{
		width: 90%;
		margin: 40rpx auto;
		margin-top: 40rpx;
		view {
			background-color: #ee0a24;
			border-radius: 30rpx;
			font-size: 28rpx;
			text-align: center;
			height: 80rpx;
			line-height: 80rpx;
			color: #fff;
		}
	}
</style>
