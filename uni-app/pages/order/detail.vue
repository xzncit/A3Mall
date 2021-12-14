<template>
	<view class="wrap">
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="订单详情"></navbar>
		<info v-if="isError"></info>
		<view v-if="!isError">
			<view class="top" v-if="order.shipping_type == 1">
				<view class="status">
					<uni-steps :options="stepsOptions" active-color="#07c160" :active="active" />
				</view>

				<view class="address">
					<view class="info">
						<text>收件人：{{order.accept_name}}</text>
						<text>手机号：{{order.mobile}}</text>
					</view>
					<view class="address-info">
						{{order.region}} {{order.address}}
					</view>
				</view>
			</view>
			
			<view class="top" v-if="order.shipping_type == 2">
				<view class="status">
					<uni-steps :options="stepsShopOptions" active-color="#07c160" :active="active" />
				</view>
			</view>
			
			<view class="order" v-if="order.shipping_type == 2">
				<view class="title">
					<text>核销信息</text>
				</view>
				<view class="shop-box">
					<view class="qrcode">
						<image :src="order.qrcode"></image>
					</view>
					<view class="code">{{order.code}}</view>
				</view>
				<view class="shop-address">
					<view>门店名称：{{order.shop_name}}</view>
					<view>营业时间：{{order.day_time}}</view>
					<view>门店电话：{{order.phone}}</view>
				</view>
				<view class="shop-address">
					<view>取件地址：</view>
					<view>
						<text>{{order.area_name}}</text>
						<text>{{order.shop_address}}</text>
					</view>
				</view>
			</view>
			
			<view class="goods">
				<view class="title">
					<text>共{{order.item.length}}件商品</text>
				</view>
				<view class="goods-box">
					<view
						class="goods-item clear"
						v-for="(value,index) in order.item"
						:key="index"
						@click="goGoods(value.goods_id)"
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
			
			<view class="order" v-if="order.goods_info" v-for="(value,index) in order.goods_info" :key="index">
				<view class="title">
					<text>商品信息</text>
				</view>
				<view class="shop-address">
					<view>商品名称：</view>
					<view>
						<text style="word-wrap: break-word;">{{value.title}}</text>
					</view>
				</view>
				
				<view class="shop-address" v-if="value.type==1">
					<view>商品内容：</view>
					<view>
						<text style="word-wrap: break-word;">{{value.content}}</text>
					</view>
					<view style="padding-top: 10rpx;">
						<button style="background-color: #1b43c4;color: #fff;" @click="scopy(value.content)">复制内容</button>
					</view>
				</view>
				<view class="shop-address" v-if="value.type==2">
					<view>商品内容：</view>
					<view>
						<text style="word-wrap: break-word;">{{value.content}}</text>
					</view>
					<view style="padding-top: 10rpx;">
						<button style="background-color: #1b43c4;color: #fff;" @click="scopy(value.content)">复制内容</button>
					</view>
				</view>
				<view class="shop-address" v-if="value.type==3">
					<view>
						<button style="background-color: #1b43c4;color: #fff;" @click="downLoad(value.content)">下载内容</button>
					</view>
				</view>
			</view>
			
			<view class="order">
				<view class="title">
					<text>订单信息</text>
				</view>
				<view class="list clear">
					<view class="list-box clear">
						<view>订单编号：</view>
						<view class="money">{{order.order_no}}</view>
					</view>
					<view class="list-box clear">
						<view>下单时间：</view>
						<view>{{order.create_time}}</view>
					</view>
					<view class="list-box clear">
						<view>订单类型：</view>
						<view>{{order.type}}</view>
					</view>
					<view class="list-box clear">
						<view>支付状态：</view>
						<view class="money">{{order.pay_status}}</view>
					</view>
					<view class="list-box clear">
						<view>支付方式：</view>
						<view class="money">{{order.pay_type}}</view>
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
				</view>
			</view>
			
			<view class="order" v-if="order.order_status==1">
				<view class="title">
					<text>支付方式</text>
				</view>
				
				<view class="payment-box">
					<view 
						v-for="(item,index) in providerList" 
						:key="index"
						class="payment-item" 
						@click="selectPayment(item.id)"
					>
						<view><text class="iconfont pay" :id="item.id" :class="item.class"></text></view>
						<view :class="{activeColor:payment == item.id}">{{item.name}}<text v-if="item.id == 'balance'" style="padding-left: 15rpx; padding-top: 2rpx; font-size: 24rpx;">可用余额: ￥{{order.users_price}}元</text></view>
						<view :class="{active:payment == item.id}"><text class="iconfont">&#xe631;</text></view>
					</view>
				</view>
				
			</view>
			
			<view v-if="active != 4">
				<view class="operation-placeholder"></view>
				<view class="operation">
					<text class="cancel" v-if="order.order_status==1" @click="cancel">取消订单</text>
					<text class="pay" v-if="order.order_status==1" @click="goPay">立即付款</text>

					<text class="confirm" v-if="order.order_status == 2 || order.order_status==3 || order.order_status==4" @click="confirm">确认收货</text>
					<text class="refund" v-if="order.order_status == 2 || order.order_status==3 || order.order_status==4" @click="refund">申请退款</text>

					<text class="evaluate" v-if="order.order_status==5" @click="evaluate">待评价</text>
				</view>
			</view>
			
		</view>
		
	</view>
</template>

<script>
	import info from '@/components/tool/info.vue'
	import uniSteps from '@/components/uni-steps/uni-steps.vue'
	import subscribe from "@/common/subscribe";
	import payment from '@/common/payment';
	export default {
		components:{
			info,uniSteps
		},
		data(){
			return {
				isError: false,
				orderId: 0,
				active: 0,
				stepsOptions:[
					{
						title: '待付款'
					},{
						title: '待发货'
					},{
						title: '待收货'
					},{
						title: '待评价'
					},{
						title: '己完成'
					}
				],
				stepsShopOptions:[
					{
						title: '待付款'
					},{
						title: '待发货'
					},{
						title: '待取货'
					},{
						title: '待评价'
					},{
						title: '己完成'
					}
				],
				payment: "wechat",
				order:{
					accept_name: "",
					address: "",
					create_time: "",
					item: [],
					mobile: "",
					order_amount: "",
					order_no: "",
					pay_status: "",
					pay_type: "",
					payable_freight: '',
					payable_amount: "",
					promotions: "",
					real_amount: "",
					region: "",
					type: "",
					users_price:"0.00",
					order_status: 1,
					shipping_type: 0,
					qrcode: "",
					code: "",
					area_name: "",
					shop_name: "",
					phone: "",
					shop_address: "",
					day_time: "",
					goods_info: []
				},
				providerList: []
			};
		},
		onLoad(options) {
			this.isError = false;
			this.orderId = options.id;
			this.$http.getOrderDetail({
				id: this.orderId
			}).then((res)=>{
				this.isError = false;
				if(res.status){
					this.active = res.data.active;
					this.order = res.data;
				}else{
					this.isError = true;
				}
			}).catch((err)=>{
				this.isError = true;
			});
			
			payment.getPaymentList().then(res=>{
				this.providerList = res;
			})
			
			subscribe.order();
		},
		onBackPress(e) {
			this.$utils.navigateTo('order/list',{ id: 1 });
			return true;
		},
		methods: {
			scopy(content){
				this.$utils.iCopy(content).then(res=>{
					this.$utils.msg(res);
				}).catch(err=>{
					this.$utils.msg(err);
				});
			},
			downLoad(saveUrl){
				let that = this;
				uni.downloadFile({
				    url: saveUrl,
				    success: (res) => {
				        if (res.statusCode === 200) {
				            console.log('下载成功');
				        }
						// #ifdef H5
						that.$utils.msg("己将文件保存在："+res.tempFilePath);
						// #endif
						// #ifndef H5
						uni.saveFile({
							tempFilePath: res.tempFilePath,
							success: function(file) {
								that.$utils.msg("己将文件保存在："+file.savedFilePath);
							}
						});
						// #endif
				    }
				});
			},
			goGoods(goods_id){
				this.$utils.navigateTo("goods/view",{
					id: goods_id
				});
			},
			selectPayment(value){
				this.payment = value;
			},
			goPay(){
				this.$utils.showLoading();

				this.$http.getOrderDetailPayment({
					order_id: this.orderId,
					payment_id: this.payment,
					source: payment.getPaymentType(),
					// #ifdef H5
					url: document.location.href
					// #endif
				}).then(res=>{
					this.$utils.hideLoading();
					if(res.status){
						payment.setPayType("order").crreateOrder(res.data);
					}else{
						this.$utils.msg(res.info);
					}
				}).catch(err=>{
					this.$utils.hideLoading();
					this.$utils.msg("网络出错，请检查网络是否连接");
				});
			},
			confirm(){
				this.$utils.navigateTo("order/confirm_delivery",{
					id: this.orderId
				});
			},
			refund(){
				this.$utils.navigateTo("order/refund",{
					id: this.orderId
				});
			},
			evaluate(){
				this.$utils.navigateTo("order/evaluate",{
					id: this.orderId
				});
			},
			cancel(){
				this.$utils.showLoading();
				this.$http.getOrderDetailCancel({
					order_id: this.orderId
				}).then(res=>{
					this.$utils.hideLoading();
					if(res.status){
						this.$utils.msg(res.info);
						this.$utils.navigateTo("order/list",{ id: 1 });
					}else{
						this.$utils.msg(res.info);
					}
				}).catch(err=>{
					this.$utils.hideLoading();
					this.$utils.msg("网络出错，请检查网络是否连接");
				});
			}
		},
	}
</script>

<style lang="scss" scoped>
	.shop-address {
	    width: 92%; margin: 0 auto;
	    padding: 20rpx 0 0 0; font-size: 28rpx; color: #333;
	    text { padding-right: 20rpx; }
	}
	.shop-box {
	    width: 100%; background-color: #fff;
	    .qrcode {
	        width: 80%;
	        height: 370rpx;
	        background-color: #f5eff0;
	        margin: 40rpx auto 0 auto;
	        border-radius: 20rpx;
	        position: relative;
	        image {
	            position: absolute;
	            top: 50%; left: 50%;
	            transform: translate(-50%,-50%);
	            margin: 0 auto;
	            display: block;
	            width: 300rpx; height: 300rpx;
	        }
	    }
	    .code {
	        width: 80%;
	        height: 100rpx;
	        line-height: 100rpx;
	        font-size: 50rpx;
	        text-align: center;
	        margin: 0 auto 20rpx auto;
	        background-color: #1b43c4;
	        color: #fff;
	        border-radius: 20rpx;
	        position: relative;
	        &:before {
	            width: 34rpx; height: 34rpx; display: inline-block;
	            content: " "; position: absolute; right: -18rpx; top: -14rpx;
	            border-radius: 50%; background-color: #fff;
	        }
	        &:after {
	            width: 34rpx; height: 34rpx; display: inline-block;
	            content: " "; position: absolute; left: -18rpx; top: -14rpx;
	            border-radius: 50%; background-color: #fff;
	        }
	    }
	}
	.top{
	    background-color: #fff;
	    position: relative;
	    &:before{
	        position: absolute;
	        right: 0;
	        bottom: 0;
	        left: 0;
	        height: 4rpx;
	        background: -webkit-repeating-linear-gradient(135deg,#ff6c6c 0,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);
	        background: repeating-linear-gradient(-45deg,#ff6c6c 0,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);
	        background-size: 160rpx;
	        content: '';
	    }
	    .status{
	        width: 100%;
	        margin: 0 auto;
			padding: 10rpx 0;
	    }
	    .address{
	        font-size: 28rpx;
	        width: 92%;
	        margin: 0 auto;
	        .info{
	            height: 60rpx;
	            line-height: 60rpx;
	            span:first-child{
	                padding-right: 20rpx;
	            }
	            span:last-child{
	
	            }
	        }
	        .address-info{
	            height: 60rpx;
	            line-height: 40rpx;
	        }
	    }
	}
	
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
	        border-bottom: 2rpx solid #eee;
	        text {
	            padding-left: 20rpx;
	        }
	    }
	    .goods-box{
	        padding: 0 32rpx;
	        .goods-item {
	            padding-top: 20rpx;
	            .goods-img {
	                width: 154rpx;
	                height: 154rpx;
	                display: inline-block;
	                float: left;
	                image{
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
		.title{
			width: 100%;
			margin: 0 auto;
			color: #666;
			font-size: 30rpx;
			height: 80rpx;
			line-height: 80rpx;
			border-bottom: 2rpx solid #eee;
			text {
				padding-left: 30rpx;
			}
		}
		.list {
			width: 100%;
			.list-box{
				width: 92%;
				height: auto !important;
				height: 80rpx;
				min-height: 80rpx;
				line-height: 80rpx;
				margin: 0 auto;
				font-size: 26rpx; color: #333;
				border-bottom: 2rpx solid #ebedf0;
				view{ display: inline-block; }
				view:first-child { float: left; }
				view:last-child { float: right; }
				textarea { height: 150rpx; }
			}
		}
	}
	.operation-placeholder{
	    width: 100%;
	    height: 140rpx;
	    line-height: 140rpx;
	}
	.operation{
	    width: 100%;
	    height: 110rpx;
	    line-height: 110rpx;
	    text-align: right;
	    background-color: #fff;
	    position: fixed;
	    left: 0;
	    bottom: 0;
	    border-top: 2rpx solid #eee;
	    text{
	        font-size: 28rpx;
	        text-align: center;
	        border-radius: 30rpx;
	        background-color: #fff;
	        padding: 16rpx 30rpx;
	        margin-right: 20rpx;
	    }
	    text.cancel{
	        color: #333;
	        border: 2rpx solid #ddd;
	    }
	    text.pay {
	        background-color: #e93323;
	        color: #fff;
	    }
	    .confirm{
	        color: #fff;
	        background-color: #1b43c4;
	    }
	    .refund{
	        color: #1b43c4;
	        background-color: #fff;
			border: 1px solid #1b43c4;
	    }
	    .evaluate{
	        color: #fff;
	        background-color: #009688;
	    }
	}
	
	
.payment-box{
	.payment-item{
		padding: 20rpx 32rpx;
		border-bottom: 2rpx solid #eee;
		view { display: inline; }
		view:first-child{
			font-size: 28rpx;
			text{
				width: 40rpx;
				height: 40rpx;
				line-height: 40rpx;
				text-align: center;
				border-radius:50%;
				padding: 4rpx;
			}
		}
		view:nth-child(2){
			font-size: 28rpx;
			padding-left: 20rpx;
			i{
				font-size: 24rpx;
				font-style: normal;
				color: #999;
				padding-left: 20rpx;
			}
		}
		view:nth-child(3){
			float: right;
			display: none;
			color: #999;
		}
		view.active{
			display: block;
		}
		view.activeColor{
			color: red;
		}
	}
	#wechat{
		position: relative;
		top: 2rpx;
		width: 40rpx;
		height: 40rpx;
		display: inline-block;
		color: #fff;
		background-color: #41b035;
	} 
	#alipay{
		position: relative;
		top: 2rpx;
		width: 40rpx;
		height: 40rpx;
		display: inline-block;
		color: #fff;
		background-color: #1296db;
	}
	#appleiap{
		position: relative;
		top: 2rpx;
		width: 40rpx;
		height: 40rpx;
		display: inline-block;
		color: #333;
		background-color: #fff;
		border: 1px solid #eee;
	} 
	#balance{
		position: relative;
		top: 2rpx;
		width: 40rpx;
		height: 40rpx;
		background-repeat: no-repeat; 
		background-size: 40rpx 40rpx;
		display: inline-block;
		color: #fff;
		background-color: #fe960f;
	}
	.check { 
		position: relative;
		top: 12rpx;
		width: 40rpx;
		height: 40rpx;
		display: inline-block;
	}
}
</style>
