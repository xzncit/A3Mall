<template>
	<view>
		<navbar v-model="screenHeight" :scroll="scrollNum" :iSimmersive="false" :placeholder="true"  title="资金明细"></navbar>
		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="upCallback" :height="screenHeight+'px'">
			<view class="list-wrap clear">
				<view class="list-box clear">
					<view class="list-item clear" v-for="(item, index) in result" :key="index">
						<view class="t">
							<view>{{item.action}}</view>
							<view></view>
						</view>
						<view class="box clear">
							<view class="box-item">
								<view><text class="icon iconfont">&#xe619;</text>申请时间</view>
								<view>{{item.time}}</view>
							</view>
							<view  class="box-item">
								<view><text class="icon iconfont">&#xe610;</text>操作状态</view>
								<view>{{item.operation}}￥{{item.amount}}</view>
							</view>
							<view class="box-item clear" v-if="item.description">{{item.description}}</view>
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
				scrollNum: 0,
				result: []
			};
		},
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
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
				this.$http.getWalletFund({
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
        width: 100%;
        margin-top: 20rpx;
        .list-item{
            width: 100%;
            height: auto !important;
            height: 220rpx;
            min-height: 220rpx;
            background-color: #fff;
            font-size: 26rpx;
            margin-bottom: 20rpx;
            .t {
                height: 80rpx;
                line-height: 80rpx;
                border-bottom: 2rpx solid #ebebeb;
                view { display:inline-block; font-size: 32rpx; color: #333; }
                view:first-child {
                    padding-left: 32rpx; float: left;
                }
                view:last-child {
                    padding-right: 32rpx; float: right;
                }
            }
            .box {
                height: auto !important;
                height: 136rpx;
                min-height: 136rpx;
                width: 100%;
                .box-item {
                    width: 100%;
                    height: 32rpx;
                    float: left;
                    font-size: 28rpx; color: #888;
                    padding-top: 20rpx;
                    view {
						display: inline-block;
                        text { padding-right: 10rpx; position: relative; top: 2rpx; }
                    }
					
                    view:first-child { padding-left: 32rpx; float: left; }
                    view:last-child { padding-right: 32rpx; float: right; }

                    &:nth-child(3) {
                        width: 90%;
                        padding: 20rpx 32rpx;
                        height: auto !important;
                        height: 60rpx;
                        min-height: 60rpx;
						font-size: 26rpx;
                    }
                }

            }
        }
    }
</style>