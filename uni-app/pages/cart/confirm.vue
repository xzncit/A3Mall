<template>
	<view>
		<navbar :scroll="scrollNum" title-color="#ffffff" background="#1b43c4" :iSimmersive="false" :placeholder="true" title="订单详情"></navbar>
		
		<view class="distribution" v-if="isShipping">
			<text @click="onShipping(1)" :class="{active: shippingType == 1}">快递配送</text>
			<text @click="onShipping(2)" :class="{active: shippingType == 2}">到店自提</text>
		</view>
		<view v-if="isShipping" class="distribution-placeholder"></view>
				
		<view class="top" v-if="shippingType == 1">
			<view class="top-map">
				<text class="iconfont">&#xe61e;</text>
			</view>
			<view class="address" @click="isAddressStatus = true">
				<view class="info" v-if="address.tel">
					<text v-if="address.name">收件人：{{ address.name }} </text>
					<text v-if="address.tel">手机号：{{ address.tel }}</text>
				</view>
				<view class="info" v-if="!address.tel">
					<text style="position: relative; top: -10rpx;">请选择地址</text>
				</view>
				<view class="address-info" v-if="address.address">{{ address.address }}</view>
			</view>
			<view class="arrow-right">
				<text class="iconfont">&#xe60d;</text>
			</view>
		</view>
		
		<view class="top" v-if="shippingType == 2">
			<view class="top-map">
				<text class="iconfont">&#xe61e;</text>
			</view>
			<view class="address" @click="isStoreStatus = true">
				<view class="info">
					<text v-if="store.name">门店名称：{{ store.name }}</text>
				</view>
				<view class="info" v-if="!store.tel">
					<text style="position: relative; top: -10rpx;">请选择地址</text>
				</view>
				<view class="address-info">
					<view v-if="store.tel">手机号：{{ store.tel }}</view>
					<view v-if="store.address">{{ store.address }}</view>
				</view>
			</view>
			<view class="arrow-right">
				<text class="iconfont">&#xe60d;</text>
			</view>
		</view>
		
		<view class="goods">
			<view class="title">
				<text>共{{orderData.item.length}}件商品</text>
			</view>
			<view class="goods-box">
				<view class="goods-item clear" v-for="(item,index) in orderData.item" :key="index">

					<view class="goods-img">
						<image :src="item.thumb_image"></image>
					</view>

					<view class="goods-info">
						<view class="t">
							<text>{{item.title}}</text>
							<text>￥{{item.sell_price}}</text>
						</view>
						<view class="b">
							<view v-if="item.goods_array">
								<text v-for="(value,j) in item.goods_array" :key="j">
									{{value.name}}：{{value.value}}&nbsp;&nbsp;
								</text>
							</view>
							<text class="goods-nums">× {{item.goods_nums}}</text>
						</view>
					</view>

				</view>

			</view>
		</view>
		
		<view class="order">
			<view class="title">
				<text>订单信息</text>
			</view>
			<view class="list clear">
				<view class="list-box clear" @click="isCouponStatus = !isCouponStatus">
					<view>优惠劵：</view>
					<view>{{bonusText}}</view>
				</view>
				<view class="list-box clear">
					<view>商品金额：</view>
					<view>￥{{orderData.real_amount}}</view>
				</view>
				<view class="list-box clear">
					<view>运费金额：</view>
					<view>￥{{orderData.real_freight}}</view>
				</view>
				<view class="list-box clear" v-if="orderData.real_point > 0">
					<view>需要积分：</view>
					<view class="money">{{orderData.real_point}}积分</view>
				</view>
				<view class="list-box clear">
					<view>订单总额：</view>
					<view class="money">￥{{orderData.payable_amount}}</view>
				</view>
			</view>
		</view>
		
		<view class="order">
			<view class="title">
				<text>备注内容</text>
			</view>
			<view class="list clear">
				<view style="padding: 20rpx 25rpx;">
					<textarea :value="remarks" placeholder="请输入留言" style="width: 100%; height: 100rpx;"></textarea>
				</view>
			</view>
		</view>
		
		
		<view class="order">
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
					<view :class="{activeColor:payment == item.id}">{{item.name}}<text v-if="item.id == 'balance'" style="padding-left: 15rpx; padding-top: 2rpx; font-size: 24rpx;">可用余额: ￥{{orderData.users_price}}元</text></view>
					<view :class="{active:payment == item.id}"><text class="iconfont">&#xe631;</text></view>
				</view>
			</view>
			
		</view>
		
		<coupon-list
			v-model="isCouponStatus"
			:coupons="coupons"
			@coupon-event="onCoupons"
		></coupon-list>
		
		<address-list
			v-model="isAddressStatus"
			:array="addressList"
			@onAdd="onAdd"
			@address-event="onSelectedAddress"
		>
		</address-list>
		
		<address-list
			v-model="isStoreStatus"
			:array="storeList"
			:add="false"
			tips="暂无自提门店"
			@address-event="onSelectedStore"
		>
		</address-list>
		
		<view class="operation-placeholder"></view>
		<view class="operation">
			<view class="amount">
				<text>合计：</text>
				<text v-if="orderData.order_amount">￥{{orderData.order_amount}}</text>
				<text v-else>￥{{orderData.payable_amount}}</text>
			</view>
			<view class="pay" @click="onOrderSubmit">提交订单</view>
		</view>
		
		
		<loading v-if="isLoading" :color="loadingColor" :text="loadingText" :layer="true"></loading>
	</view>
</template>

<script>
	import MallInfo from '@/components/tool/info.vue'
	import loading from '../../components/tool/loading'
	import navbar from "@/components/navbar/navbar";
	import subscribe from "@/common/subscribe";
	import payment from '@/common/payment';
	export default {
		components:{
			MallInfo,loading,navbar
		},
		data() {
			return {
				scrollNum: 0,
				shippingType: 1,
				isShipping: 0,
				storeList: [],
				store:{
					id: "",
					name: "",
					tel: "",
					address: ""
				},
				isLoading: true,
				loadingColor: "rgba(255,255,255,1)",
				loadingText: "正在加载订单中",
				chosenStoreId: '0',
				isStoreStatus: false,
				isCouponStatus: false,
				isAddressStatus: false,
				bonusText: "请选择",
				address:{
					id: "",
					name: "",
					tel: "",
					address: ""
				},
				chosenAddressId: '0',
				bonusId: '0',
				addressList: [],
				orderData: {
					item:{},
					real_amount: 0.00,
					real_freight: 0.00,
					payable_amount: 0.00,
					order_amount: 0.00,
					users_price:0.00,
					real_point: 0,
					users_point: 0,
					type: 0
				},
				remarks: "",
				payment: "wechat",
				coupons: [],
				params:null,
				orderBtnFlag:false,
				providerList: []
			};
		},
		onLoad(options) {
			let type = options.type;
			let id = options.id;
			let params = {
				id: id,type: type
			};
			
			if(this.$utils.in_array(type,["buy","point","second","regiment","special","group"])){
				params.sku_id = options.sku_id;
				params.num = options.num;
				if(options.kid){
					params.kid = options.kid;
				}
			}
			
			params.shipping_type = this.shippingType;
			this.params = params;
			
			payment.getPaymentList().then(res=>{
				this.providerList = res;
			})
			
			subscribe.order();
		},
		onShow() {
			this.$nextTick(()=>{
				let users = this.$storage.getJson("users");
				if(users == null){
					this.$utils.navigateTo('public/login');
				}else{
					this.onLoadOrder();
				}
			});
		},
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
		},
		methods: {
			onShipping(type){
				this.shippingType = type;
				this.params.shipping_type = type;
				if(type == 1){
					this.onSelectedAddress(this.address);
				}else{
					this.onSelectedStore(this.store);
				}
			},
			onSelectedStore(value){
				this.isStoreStatus = false;
				this.chosenStoreId = value.id;
				this.params.store_id = this.chosenStoreId;
				this.store = value;
				delete this.params.address_id;
				this.onLoadOrder();
			},
			onSelectedAddress(value){
				this.isAddressStatus = false;
				this.chosenAddressId = value.id;
				this.params.address_id = this.chosenAddressId;
				this.address = value;
				delete this.params.store_id;
				this.onLoadOrder();
			},
			onLoadOrder(){
				this.$http.getCartConfirm(this.params).then((res)=>{
					if(res.status){
						this.orderData = res.data;
						this.isShipping = parseInt(res.data.is_shipping);
						this.storeList = res.data.store;
						this.addressList = res.data.address.list;
						
						// address
						if(res.data.address.default == undefined || res.data.address.default.length <= 0){
							if(res.data.address.list[0] != undefined){
								this.address = res.data.address.list[0];
								this.chosenAddressId = this.address.id;
							}
						}else{
							this.chosenAddressId = res.data.address.default.id;
							this.address = res.data.address.default;
						}
						
						if(this.storeList.length && this.store.id == ""){
							this.store = this.storeList[0];
							this.chosenStoreId = this.store.id;
						}
						
						this.coupons = res.data.bonus;
						if(this.bonusText == '请选择'){
							this.bonusText = res.data.bonus.length <= 0 ? "暂无优惠劵" : res.data.bonus.length + "张可用"
						}
						
						this.isLoading = false;
					}else{
						this.isLoading = false;
						this.$storage.set("order_msg",res.info);
						this.$utils.redirectTo("cart/msg");
					}
				});
			},
			onOrderSubmit(){
				if(this.orderBtnFlag){
					return false;
				}
				
				if(this.orderData.real_point > this.orderData.users_point){
					this.$utils.msg("您的积分不足，不能购买此商品");
					return false;
				}
				
				if(this.shippingType == 2 && this.store.id == ""){
					this.$utils.msg("请先选择自提门店");
					return false;
				}
				
				this.orderBtnFlag = true;
				this.isLoading = true;
				this.loadingColor = 'rgba(255,255,255,0.3)';
				this.loadingText = "正在提交订单中";
				
				let params = {};
				Object.assign(params,{
					id: this.params.id,
					type: this.params.type,
					address_id: this.chosenAddressId,
					store_id: this.chosenStoreId,
					shipping_type: this.shippingType,
					bonus_id: this.bonusId,
					payment: this.payment,
					remarks: this.remarks,
					source: payment.getPaymentType(),
					// #ifdef H5
					url: document.location.href
					// #endif
				},this.params);
				
				this.$http.createOrder(params).then((res)=>{
					this.isLoading = false;
					if(res.status){
						payment.crreateOrder(res.data,true);
					}else{
						this.$utils.msg(res.info);
					}
					this.orderBtnFlag = false;
				}).catch((err)=>{
					this.isLoading = false;
					this.$utils.msg("网络连接错误，请检查网络是否可用");
					this.orderBtnFlag = false;
				});
			},
			onShipping(type){
				this.shippingType = type;
				this.params.shipping_type = type;
				if(type == 1){
					this.onSelectedAddress(this.address);
				}else{
					this.onSelectedStore(this.store);
				}
			},
			selectPayment(value){
				this.payment = value;
			},
			onCoupons(value) {
				this.isCouponStatus = false;
				this.params.bonus_id = value.id;
				this.bonusText = value.value;
				this.bonusId = value.id;
				this.onLoadOrder();
			},
			onSelectedStore(value){
				this.isStoreStatus = false;
				this.chosenStoreId = value.id;
				this.params.store_id = this.chosenStoreId;
				this.store = value;
				delete this.params.address_id;
				this.onLoadOrder();
			},
			onSelectedAddress(value){
				this.isAddressStatus = false;
				this.chosenAddressId = value.id;
				this.params.address_id = this.chosenAddressId;
				this.address = value;
				delete this.params.store_id;
				this.onLoadOrder();
			},
			onAdd() {
				this.$storage.set("ORDER_CONFIRM_SELECT",true);
				this.$utils.navigateTo("ucenter/address_editor");
			}
		}
	}
</script>

<style lang="scss">
.distribution {
    width: 100%;
    height: 90rpx;
    line-height: 90rpx;
    background-color: #fff;
    text {
        display: inline-block; font-size: 30rpx; width: 50%;
        height: 90rpx; line-height: 90rpx; text-align: center;
        &.active { color: #b91922; }
    }
}
.distribution-placeholder { width: 100%; height: 12rpx; }
.money{ color: #fc4141; }
.van-address-item__edit { display: none; }
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
    .top-map{
        width: 60rpx;
        height: 60rpx;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 20rpx;
		text {
			font-size: 32rpx;
		}
    }
    .arrow-right{
        width: 60rpx;
        height: 60rpx;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 0px;
		text {
			transform: rotate(180deg); display: inline-block;
		}
    }
    .address{
        font-size: 28rpx;
        width: 85%;
        margin: 0 auto;
        padding: 20rpx 0px;
        padding-left: 40rpx;
        position: relative;
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
            height: auto !important;
            height: 60rpx;
            min-height: 40rpx;
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
                text{
                    font-style: normal;
                }
                .t {
                    width: 100%;
                    height: 45px;
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
                    height: 40px;
                    font-size: 13px;
                    view{
						float: left;
						color: #999;
					}
					.goods-nums { 
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
		border-bottom: 1px solid #eee;
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
	z-index: 11111;
	.amount{
		float: left;
		padding-top: 0;
		font-size: 28rpx;
		text-align: center;
		background-color: #fff;
		padding: 6rpx 30rpx;
		display: inline;
		margin-right: 20rpx;
		text{
			font-style: normal;
			font-size: 32rpx;
			color: #555;
		}
		text:last-child{
			color: #db1111;
			font-size: 34rpx;
			position: relative;
			top: 2rpx;
		}
	}
	.pay {
		font-size: 28rpx;
		text-align: center;
		border-radius: 30rpx;
		background-color: #fff;
		padding: 16rpx 30rpx;
		display: inline;
		background-color: #1b43c4;
		margin-right: 20rpx;
		color: #fff;
	}
}
</style>