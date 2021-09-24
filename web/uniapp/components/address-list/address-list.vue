<template>
	<view>
		<view class="address-action" :class="{'address-show': value==true}" style="background-color: #f8f8f8">
			<view class="address-title">请选择地址</view>
			<view class="address-body" :style="{'max-height':maxHeight+'px'}">

				<view v-if="array.length <= 0" class="address-empty">
					{{tips}}
				</view>

				<view class="address-list" v-if="array.length">

					<view class="address-box"
						 v-for="(item,index) in array" :key="index"
						 @click="onSelect(item)"
					>
						<view class="address-r-box">
							<view class="address-name">{{ item.name }} {{ item.tel }}</view>
							<view class="address-valid">{{ item.address }}</view>
						</view>
						<view class="address-corner-checkbox">
							<text class="iconfont" :class="{ active: active == item.id }">&#xe641;</text>
						</view>
					</view>

				</view>

				<view style="width: 100%;height: 60px; float: left"></view>
			</view>
			<text class="iconfont close" @click.stop="onClose">&#xe600;</text>
			<view v-if="add" class="address-button" @click="onAddAddress"><text>新增地址</text></view>
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
			add: {
				type: Boolean,
				default: true
			},
			tips: {
			  type: String,
			  default: "您还没有添加地址哦"
			},
			array: {
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
			onSelect(value){
				this.active = value.id;
				this.$emit("address-event",value);
			},
			onAddAddress(){
				this.$emit("onAdd",{});
			}
		}
	}
</script>

<style lang="scss">
	.address-action{
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
		.address-title { font-size: 32rpx; text-align: center; width: 100%; height: 100rpx; background-color: #fff; line-height: 100rpx; }
		.address-button {
			width: 100%; height: 120rpx; line-height: 120rpx; position: absolute; left: 0; bottom: 0; background-color: #fff;
			text {
				text-align: center; background-color: #c21313;
				width: 90%; height: 100rpx; line-height: 100rpx;
				margin: 10rpx auto; display: block;
				font-size: 30rpx; color: #fff; border-radius: 40rpx;
			}
		}
		.address-body {
			flex: 1 1 auto;
			min-height: 88rpx;
			overflow-y: scroll;
			-webkit-overflow-scrolling: touch;
			.address-empty { width: 100%; text-align: center; font-size: 36rpx; height: 100rpx; line-height: 100rpx; position: absolute; top: 50%; transform: translateY(-50%) }
			.address-list {
				width: 95%;
				margin-left: 2.5%;
				float: left;
				margin-top: 20rpx;

				.address-box {
					float: left;
					margin-bottom: 20rpx;
					width: 100%;
					height: 170rpx;
					background-color: #fff;
					border-radius: 20rpx;
					position: relative;
					.address-r-box {
						float: left;
						margin-left: 60rpx;
						height: 170rpx;
						position: relative;
						.address-name {
							font-size: 30rpx;
							padding-top: 36rpx;
						}
						.address-valid{
							padding-top: 24rpx;
							font-size: 28rpx;
						}
					}
					.address-corner-checkbox{
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
	.address-show{
		transform:translate3d(0,0,0);
	}
</style>
