<template>
	<view>
		<navbar v-model="screenHeight" title-color="#ffffff" background="#1b43c4" :iSimmersive="false" :placeholder="true" title="我的积分"></navbar>
		<mescroll-body 
		ref="mescrollRef" 
		@init="mescrollInit" 
		@down="downCallback" 
		@up="upCallback"
		:height="screenHeight+'px'"
		>
			<view class="top">
				<view class="top-box">
					<text>可用积分</text>
					<text>{{ point }}</text>
				</view>
			</view>
			
			<view class="list-wrap">
				<view class="point-list">
					<view class="point-item clear" v-for="(item,index) in result" :key="index">
						<view class="t">
							<text>{{item.time}}</text>
							<text>类型：{{item.operation}}</text>
						</view>
						<view class="m">
							{{item.description}}
						</view>
						<view class="b">
							<text>{{item.point}}</text>
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
				point: 0,
				result: []
			}
		},
		onShow() {
			let users = this.$storage.getJson("users");	
			this.point = users.point;
		},
		methods: {
			downCallback(){
				setTimeout(()=>{
					this.mescroll.resetUpScroll();
				},1200);
			},
			triggerDownScroll(){
				this.mescroll.triggerDownScroll();
			},
			upCallback(page) {
				this.$http.getUcenterPointList({
					page: page.num
				}).then((result)=>{
					this.mescroll.endByPage(result.data.list.length, result.data.total);
					if(result.status==1){
						if(page.num == 1) this.result = [];
						this.point = result.data.point;
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
	.online-box{ width: 90%; margin: 0 5%; margin-top: 20px; }
	.top{
	    width: 100%;
	    height: 270rpx;
	    background-color: #fff;
	    padding-top: 20rpx;
		border-top: 1px solid #ebedf0;
	    .top-box{
	        background-image: url(~@/static/images/point-bg.png);
	        background-size: 100%;
	        background-repeat: no-repeat;
	        width: 90%;
	        height: 250rpx;
	        margin: 0 auto;
	        border-radius: 20rpx;
	        text-align: center;
	        color: #fff;
	        text {
	            display: block;
	        }
	        text:first-child {
	            padding-top: 60rpx;
	            font-size: 28rpx;
	        }
	        text:last-child{
	            padding-top: 40rpx;
	            font-size: 50rpx;
	        }
	    }
	}
	.list-wrap{
	    margin-top: 20rpx;
	    .point-list{
	        display: flex;
	        flex-wrap: wrap;
	        flex-direction: column;
	        font-size: 28rpx;
	        .point-item{
	            width: 95%;
	            margin: 0 auto;
	            margin-bottom: 20rpx;
	            border-radius: 12rpx;
	            height: auto !important;
	            height: 260rpx;
	            min-height: 260rpx;
	            background-color: #fff;
	            border-bottom: 2rpx solid #eee;
	            .t{
	                height: 80rpx;
	                line-height: 80rpx;
	                border-bottom: 2rpx solid #eee;
	                text:first-child{
	                    float: left;
	                    padding-left: 20rpx;
	                }
	                text:last-child{
	                    float: right;
	                    padding-right: 20rpx;
	                }
	            }
	            .m{
	                padding: 10rpx 20rpx;
	                display: block;
	                height: auto !important;
	                height: 100rpx;
	                min-height: 100rpx;
	            }
	            .b{
	                height: 60rpx;
	                line-height: 60rpx;
	                border-top: 2rpx solid #eee;
	                text{
	                    float: right;
	                    margin-right: 20rpx;
	                }
	            }
	        }
	    }
	}
</style>
