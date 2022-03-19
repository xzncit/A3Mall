<template>
	<view>
		<navbar v-model="screenHeight" title-color="#ffffff" background="#1b43c4" :iSimmersive="false" :placeholder="true" title="订单列表"></navbar>
		<view class="">
			<view class="menu">
				<view class="menu-wrap">
					<text @click="go('1')" :class="{active: activeId == '1'}">待付款</text>
					<text @click="go('2')" :class="{active: activeId == '2'}">待发货</text>
					<text @click="go('3')" :class="{active: activeId == '3'}">待收货</text>
					<text @click="go('4')" :class="{active: activeId == '4'}">待评价</text>
					<text @click="go('5')" :class="{active: activeId == '5'}">已完成</text>
				</view>
			</view>
			<view class="placeholder-box"></view>
		</view>
		<mescroll-body 
		ref="mescrollRef" 
		@init="mescrollInit" 
		@down="downCallback" 
		@up="upCallback"
		:height="(screenHeight-50)+'px'"
		>
			<view class="list-wrap">
				<view class="list-box">
					<view class="list-item-box" v-for="(item,index) in result" :key="index">
						<view class="top">
							<text class="order-type">{{item.type}}</text>
							<text class="time">{{item.create_time}}</text>
							<text class="satus">{{item.order_status}}</text>
						</view>
						<view class="goods-box" @click="$utils.navigateTo('order/detail',{ id: item.order_id })">
							<view class="goods-item clear" v-for="(value,j) in item.item" :key="j">

								<view class="goods-img">
									<image :src="value.thumb_image">
								</view>

								<view class="goods-info">
									<view class="t">
										<text>{{value.title}}</text>
										<text>￥{{value.price}}</text>
									</view>
									<view class="b">
										<text>{{value.spec}}</text>
										<text>× {{value.nums}}</text>
									</view>
								</view>

							</view>

						</view>
						<view class="order" :class="{addBorder:item.active==6}">
							<view class="total">
								共{{item.item.length}}件商品，总金额
								<view>￥<text>{{item.order_amount}}</text></view>
							</view>
						</view>
						<view class="botttom" v-if="item.active!=6">
							<text class="cancel" v-if="item.active == 1" @click="cancel(item.order_id)">取消订单</text>
							<text class="pay" v-if="item.active == 1" @click="$utils.navigateTo('order/detail',{ id: item.order_id })">立即付款</text>

							<text class="cancel" v-if="item.active == 2 || item.active==3 || item.active==4" @click="$utils.navigateTo('order/refund',{ id: item.order_id })">申请退款</text>
							<text class="cancel" v-if="item.active==3 || item.active==4" @click="$utils.navigateTo('order/express',{ id: item.order_id })">查看物流</text>
							<text class="pay" v-if="item.active == 2 || item.active==3 || item.active==4" @click="$utils.navigateTo('order/confirm_delivery',{ id: item.order_id })">确认收货</text>

							<text class="pay" v-if="item.active==5" @click="$utils.navigateTo('order/evaluate',{ id: item.order_id })">待评价</text>
						</view>
					</view>

				</view>
			</view>
		</mescroll-body>
	</view>
</template>

<script>
	import MescrollMixin from "@/uni_modules/mescroll-uni/components/mescroll-uni/mescroll-mixins.js";
	import navbar from "@/components/navbar/navbar";
	export default {
		mixins: [MescrollMixin],
		components: {
			navbar
		},
		data() {
			return {
				screenHeight: 0,
				activeId: 1,
				result: []
			};
		},
		onLoad(options){
			this.activeId = options.id
		},
		onBackPress(e) {
			this.$utils.switchTab('ucenter/index');
			return true;
		},
		methods: {
			go(id){
				this.activeId = id;
				this.result = [];
				this.mescroll.triggerDownScroll();
			},
			cancel(order_id){
				this.$utils.showLoading();
				
				this.$http.getOrderListCancel({
					order_id: order_id
				}).then(res=>{
					this.$utils.hideLoading();
					if(res.status){
						let index = this.result.findIndex((value)=>{
							return value.order_id == order_id;
						});
	
						this.result.splice(index,1);
						this.$utils.msg(res.info);
					}else{
						this.$utils.msg(res.info);
					}
				}).catch(err=>{
					this.$utils.hideLoading();
					this.$utils.msg("网络出错，请检查网络是否连接");
				});
			},
			downCallback(){
				setTimeout(()=>{
					this.mescroll.resetUpScroll();
				},1200);
			},
			triggerDownScroll(){
				this.mescroll.triggerDownScroll();
			},
			upCallback(page) {
				this.$http.getOrderList({
					type: this.activeId,
					page: page.num
				}).then((result)=>{
					this.mescroll.endByPage(result.data.list.length, result.data.total);
					if(result.status==1){
						if(page.num == 1) this.result = [];
						this.result = this.result.concat(result.data.list);
					}else if(result.status == -1){
						this.mescroll.endErr();
					}
				}).catch(error=>{
					this.mescroll.endErr();
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
.placeholder-box{
    width: 100%;
    height: 100rpx;
}
.addBorder{
    border-top: 2rpx solid #eee;
}
.menu{
    background-color: #1b43c4;
    height: 100rpx;
    line-height: 100rpx;
    position: fixed;
    width: 100%;
	top: calc(44px + env(safe-area-inset-top)) + rpx;
	left: 0;
	font-size: 30rpx;
	z-index: 999999;
    .menu-wrap{
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
        text {
            flex: 1;
            text-align: center;
            position: relative;
            color: #fff;
        }
        .active {
            color: #fff000;
        }
    }
}
.list-wrap{
    margin-top: 20rpx;
}
.list-box{
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    .list-item-box {
        width: 95%;
        margin: 20rpx 2.5%;
        background-color: #fff;
        border-radius: 12rpx;
        .top{
            height: 90rpx;
            line-height: 90rpx;
            font-size: 30rpx;
            border-bottom: 2rpx solid #eee;
            .order-type{
                font-size: 28rpx;
                margin-right: 10rpx;
                color: #666;
            }
            text:first-child{
                float: left;
                padding-left: 20rpx;
            }
            text:last-child{
                font-size: 28rpx;
                float: right;
                padding-right: 20rpx;
            }
        }
        .goods-box{
            padding: 0 20rpx;
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
        .order{
            width: 100%;
            height: 90rpx;
            line-height: 90rpx;
            border-bottom: 2rpx solid #eee;
            .total {
				height: 90rpx;
				line-height: 90rpx;
                text-align: right;
                font-size: 28rpx;
                padding-right: 20rpx;
                view {
					display: inline-block;
                    color: red;
                    text{
                        font-style: normal;
                        font-size: 32rpx;
                    }
                }
            }
        }
        .botttom{
            width: 100%;
            height: 110rpx;
            line-height: 110rpx;
            text-align: right;
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
        }
    }
}
</style>