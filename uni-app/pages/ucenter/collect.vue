<template>
	<view class="collect clear">
		<navbar v-model="screenHeight" title-color="#ffffff" background="#1b43c4" :iSimmersive="false" :placeholder="true" title="我的收藏"></navbar>
		<mescroll-body 
		ref="mescrollRef" 
		@init="mescrollInit" 
		@down="downCallback" 
		@up="upCallback"
		:down="downOption"
		:up="upOption" 
		:height="screenHeight+'px'"
		>
			<view class="list-box">
				<view
					class="list-item"
					v-for="item in result"
					:key="item.id"
					@click="$utils.navigateTo('goods/view',{ id: item.goods_id })"
				>
					<view class="left-pic">
						<view class="pic">
							<image :src="item.thumb"></image>
						</view>
					</view>
					<view class="goods">
						<view class="t">{{ item.title }}</view>
						<view class="m">{{ item.attr }}</view>
						<view class="b">
							<view>￥{{ item.price }}</view>
							<view class="iconfont del" @click="delCollect(item)">
								&#xe652;
							</view>
						</view>
					</view>
				</view>
			</view>
		</mescroll-body>
		
		<authorize v-model="isAuthShow"></authorize>
	</view>
</template>

<script>
	import MescrollMixin from "@/uni_modules/mescroll-uni/components/mescroll-uni/mescroll-mixins.js";
	import navbar from "@/components/navbar/navbar";
	import authorize from "@/components/authorize/authorize";
	
	export default {
		mixins: [MescrollMixin],
		components: {
			navbar,authorize
		},
		data() {
			return {
				screenHeight: 0,
				isAuthShow: false,
				downOption: {
					auto: false
				},
				upOption: {
					use: true, // 是否启用上拉加载; 默认true
					auto: false, // 是否在初始化完毕之后自动执行上拉加载的回调; 默认true
					isLock: false, // 是否锁定上拉加载,默认false;
					isBoth: true, // 上拉加载时,如果滑动到列表顶部是否可以同时触发下拉刷新;默认true,两者可同时触发;
					page: {
						num: 0, // 当前页码,默认0,回调之前会加1,即callback(page)会从1开始
						size: 10, // 每页数据的数量
						time: null // 加载第一页数据服务器返回的时间; 防止用户翻页时,后台新增了数据从而导致下一页数据重复;
					},
					noMoreSize: 3, // 如果列表已无数据,可设置列表的总数量要大于等于5条才显示无更多数据;避免列表数据过少(比如只有一条数据),显示无更多数据会不好看
					offset: 80, // 距底部多远时,触发upCallback(仅mescroll-uni生效, 对于mescroll-body则需在pages.json设置"onReachBottomDistance")
					bgColor: "transparent", // 背景颜色 (建议在pages.json中再设置一下backgroundColorTop)
					textColor: "gray", // 文本颜色 (当bgColor配置了颜色,而textColor未配置时,则textColor会默认为白色)
					textLoading: '加载中 ...', // 加载中的提示文本
					textNoMore: '-- END --', // 没有更多数据的提示文本
				},
				result: []
			};
		},
		onShow() {
			this.$store.dispatch("usersStatus").then(()=>{
				this.isAuthShow = false;
				this.downCallback();
			}).catch(()=>{
				this.isAuthShow = true;
				this.mescroll.showEmpty();
			});
		},
		methods: {
			downCallback(){
				this.mescroll.resetUpScroll();
			},
			triggerDownScroll(){
				this.mescroll.triggerDownScroll();
			},
			upCallback(page) {
				this.$http.getCollectList({
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
			},
			delCollect(data){
				let arr = [];
				this.result = this.result.filter((item)=>{
					if(item.id == data.id){
						arr.push(item);
					}
					
					return !(item.id == data.id);
				});
				
				this.$http.deleteCollect({
					id: data.id
				});
				
				if(this.result <= 0){
					this.mescroll.showEmpty();
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
/deep/ .mescroll-body-content { background-color: #fafafa; }
.collect{
	width: 100%;
	height: 100vh;
	background-color: #fafafa;
}
.list-box{
	display: flex;
	flex-wrap: wrap;
	flex-direction: column;
	background: #fafafa;
	.list-item{
		width: 88%;
		height: 180rpx;
		margin: 0 auto;
		background-color: #fff;
		margin-bottom: 20rpx;
		border-radius: 10rpx;
		font-size: 28rpx;
		padding: 20rpx 20rpx;
		position: relative;
		&:first-child{
			margin-top: 20rpx;
		}
		.left-pic {
			position: absolute;
			left: 0; top:0; z-index: 999;
			width: 160rpx; height: 220rpx;
			.pic{
				float: right; margin-top: 30rpx;
				width: 160rpx;
				image {
					width: 160rpx;
					height: 160rpx;
				}
			}
		}
		.goods{
			padding-left: 160rpx;
			font-size: 28rpx;
			color: #333;
			.t{
				width: 100%;
				min-height: 50rpx;
				max-height: 80rpx;
				display: -webkit-box;
				overflow: hidden;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
			}
			.m{
				color: #666;
				font-size: 26rpx;
				display: -webkit-box;
				overflow: hidden;
				-webkit-line-clamp: 1;
				-webkit-box-orient: vertical;
			}
			.b{
				height: 60rpx;
				padding-top: 8rpx;
				view:first-child{
					float: left;
					line-height: 60rpx;
					color: red; font-size: 34rpx;
				}
				view:last-child{
					float: right;
				}
				.del {
					margin-top: 15rpx; color: #666; text-align: center; border-radius: 50rpx;
					width: 50rpx; height: 50rpx; line-height: 50rpx; font-size: 28rpx;
					background-color: #eee; display: inline-block;
				}
			} 
		}
	}

}
</style>