<template>
	<view>
		<view class="coupon-action" :class="{'coupon-show': value==true}" style="background-color: #f8f8f8">
			<view class="coupon-title">选择优惠劵</view>
			<view class="coupon-body" :style="{'max-height':maxHeight+'px'}">

				<view v-if="coupons.length <= 0" class="coupon-empty">
					暂无优惠劵
				</view>

				<view class="coupon-list" v-if="coupons.length">
					<view class="coupon-box"
						 v-for="(item,index) in coupons" :key="index"
						 @click="onCoupon(item)"
					>
						<view class="coupon-l-box">
							<view class="coupon-amount">
								{{ item.price }}
								<text>元</text>
							</view>
							<view class="coupon-condition">{{ item.condition }}</view>
						</view>
						<view class="coupon-r-box">
							<view class="coupon-name">{{ item.name }}</view>
							<view class="coupon-valid">{{ item.startAt }} - {{ item.endAt }}</view>
						</view>
						<view class="coupon-corner-checkbox">
							<text class="iconfont" :class="{ active: active == item.id }">&#xe641;</text>
						</view>
					</view>

				</view>

				<view style="width: 100%;height: 60px; float: left"></view>
			</view>
			<text class="iconfont close" @click.stop="onClose">&#xe600;</text>
			<view class="coupon-button" @click="onCancelBonus"><text>不使用优惠劵</text></view>
		</view>
		<popup v-model="value"></popup>
	</view>
</template>

<script>
	export default {
		props: {
			value: {
				type: Boolean,
				default: false
			},
			coupons: {
				type: Array,
				default: function() {
					return []
				}
			}
		},
		data(){
			return {
				maxHeight:0,
				active: 0
			};
		},
		mounted() {
			let info = this.$utils.getSystemInfo();
			this.maxHeight = info.h - this.$utils.px2rpx(200);
		},
		methods: {
			onClose(){
				this.$emit("input",!this.value)
			},
			onCoupon(value){
				this.active = value.id;
				this.$emit("coupon-event",{
					id: value.id,
					value: "-￥" + value.valueDesc + value.unitDesc
				});
			},
			onCancelBonus(){
				this.active = 0;
				this.$emit("coupon-event",{
					id: 0,
					value: this.coupons.length <= 0 ? "暂无优惠劵" : this.coupons.length + "张可用"
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
.coupon-action{
	position: fixed;
	left: 0;
	bottom: 0;
	background-color: #fff;
	width: 100%;
	border-radius: 20rpx 20rpx 0 0;
	display: flex;
	flex-direction: column;
	align-items: stretch;
	min-height: 50%;
	max-height: 80%;
	font-size: 28rpx;
	z-index: 9999;
	overflow: hidden;
	transition:all .3s cubic-bezier(.65,.7,.7,.9);
	transform:translate3d(0,100%,0);
	.coupon-title { font-size: 32rpx; text-align: center; width: 100%; height: 100rpx; background-color: #fff; line-height: 100rpx; }
	.coupon-button {
		width: 100%; height: 120rpx; line-height: 120rpx; position: absolute; left: 0; bottom: 0; background-color: #fff;
		text {
			text-align: center; background-color: #1b43c4;
			width: 90%; height: 100rpx; line-height: 100rpx;
			margin: 5px auto; display: block;
			font-size: 30rpx; color: #fff; border-radius: 40rpx;
		}
	}
	.coupon-body {
		flex: 1 1 auto;
		min-height: 88rpx;
		overflow-y: scroll;
		-webkit-overflow-scrolling: touch;
		.coupon-empty { width: 100%; text-align: center; font-size: 36rpx; height: 100rpx; line-height: 100rpx; position: absolute; top: 50%; transform: translateY(-50%) }
		.coupon-list {
			width: 95%;
			margin-left: 2.5%;
			float: left;
			margin-top: 10px;

			.coupon-box {
				float: left;
				margin-bottom: 20rpx;
				width: 100%;
				height: 170rpx;
				background-color: #fff;
				border-radius: 20rpx;
				position: relative;
				.coupon-l-box {
					position: absolute;
					left: 0;
					top: 0;
					height: 170rpx;
					width: 220rpx;
					padding: 0 10rpx;
					.coupon-amount {
						width: 100%;
						text-align: center;
						padding-top: 30rpx;
						font-size: 50rpx;
						color: #c21313;
						text { font-size: 28rpx; }
					}
					.coupon-condition { text-align: center; color: #c21313; width: 100%; padding-top: 10rpx; }
				}
				.coupon-r-box {
					float: left;
					margin-left: 260rpx;
					height: 170rpx;
					position: relative;
					.coupon-name {
						font-size: 30rpx;
						padding-top: 36rpx;
					}
					.coupon-valid{
						padding-top: 24rpx;
						font-size: 28rpx;
					}
				}
				.coupon-corner-checkbox{
					position: absolute;
					color: #999;
					right: 30rpx;
					height: 40rpx;
					top: 50%;
					transform: translateY(-50%);
					text { font-size: 40rpx; }
					.active { color: #c21313; }
				}
			}
		}

	}
	.close { position: absolute; top: 30rpx; right: 30rpx; z-index: 1; color: #c8c9cc; font-size: 44rpx; cursor: pointer; }
}
.coupon-show{
	transform:translate3d(0,0,0);
}
</style>
