<template>
	<view>
		<navbar v-model="screenHeight" title-color="#ffffff" background="#1b43c4" :iSimmersive="false" :placeholder="true" title="售后列表"></navbar>
		<mescroll-body 
		ref="mescrollRef" 
		@init="mescrollInit" 
		@down="downCallback" 
		@up="upCallback"
		:height="screenHeight+'px'"
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
							<text class="pay" @click="$utils.navigateTo('order/detail',{ id: item.order_id })">订单详情</text>
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
				result: []
			};
		},
		onLoad(options){
			
		},
		methods: {
			downCallback(){
				this.$utils.showLoading();
				setTimeout(()=>{
					this.mescroll.resetUpScroll();
				},1200);
			},
			triggerDownScroll(){
				this.mescroll.triggerDownScroll();
			},
			upCallback(page) {
				this.$utils.hideLoading();
				this.$http.getOrderService({
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