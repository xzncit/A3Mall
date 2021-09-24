<template>
	<view>
		<navbar v-model="screenHeight" :iSimmersive="false" :placeholder="true" title="商品评论"></navbar>
		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="upCallback" :height="screenHeight+'px'">
			<view class="goods-comments clear">
				<view class="comments-empty" v-if="comments.length <= 0">该商品还没有评论哦！</view>
				<view class="goods-comments-list clear">
					<view class="goods-comments-box clear" v-for="(item,index) in comments" :key="index">
						<view class="t">
							<view class="u">
								<view><image :src="item.avatar"></view>
								<view>{{item.username}}</view>
							</view>
							<view class="time">{{item.time}}</view>
						</view>
						<view class="c">{{item.content}}</view>
						<view class="d" v-if="item.reply_content">
							<view class="d-1">商家回复</view>
							<view class="d-2">{{item.reply_content}}</view>
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
				options: null,
				comments: []
			}
		},
		onLoad(options){
			this.options = options;
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
				this.$http.getGoodsComments({
					page: page.num,
					type: this.options.type,
					id: this.options.id
				}).then((result)=>{
					this.mescroll.endByPage(result.data.list.length, result.data.total);
					if(result.status==1){
						if(page.num == 1) this.comments = [];
						this.comments = this.comments.concat(result.data.list);
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
    .goods-comments{
        margin-top: 20rpx;
        background-color: #fff;
        height: auto;
        .title {
            height: 80rpx;
            line-height: 80rpx;
            font-size: 32rpx;
            width: 100%;
            border-bottom: 2rpx solid #e8e8e8;
            view:nth-child(1){
                float: left;
                color: #333;
                padding-left: 30rpx;
            }
            view:nth-child(2){
                float: right;
                color: #999;
                padding-right: 30rpx;
            }
        }
        .comments-empty { padding: 100rpx 30rpx; text-align: center; font-size: 32rpx; color: #666; }
        .goods-comments-list{
            .goods-comments-box{
                border-bottom: 1px solid #e8e8e8;
                min-height: 240rpx;
                background-color: #fff;
                padding-bottom: 40rpx;
                .t {
                    padding: 0 30rpx;
                    height: 170rpx;
                    line-height: 160rpx;
                    color: #666;
                    .u{
                        float: left;
                        font-size: 30rpx;
						view { float: left; }
                        view:first-child{
                            width: 96rpx; height: 96rpx;
                            overflow: hidden; border-radius: 50%;
                            background-color: #eee; display: inline-block;
                            position: relative; top: 30rpx;
                            image {
                                width: 96rpx; height: 96rpx;
                            }
                        }
                        view:last-child { position: relative; left: 20rpx; }
                    }
                    .time{
                        float: right;
                        font-size: 28rpx;
                    }
                }
                .c{
                    padding: 0 30rpx 10rpx 30rpx;
                    font-size: 30rpx; color: #333;
                }
                .d {
                    background-color: #f7f7f7;
                    margin: 0 30rpx;
                    .d-1 {
                        padding: 10rpx 30rpx 0 30rpx;
                        font-size: 30rpx;
                    }
                    .d-2 {
                        padding: 20rpx 30rpx 20rpx 30rpx;
                        font-size: 28rpx;
                    }
                }
            }
        }
    }
</style>
