<template>
	<view>
		<navbar v-model="screenHeight" :iSimmersive="false" :placeholder="true"  title="资讯列表"></navbar>
		<mescroll-body 
		ref="mescrollRef" 
		@init="mescrollInit" 
		@down="downCallback" 
		@up="upCallback"
		:height="screenHeight+'px'"
		>
			<view class="news-list-box">
				<view class="news-wrap">
					<view
						v-for="(item,index) in result"
						:key="index"
						class="news-item-box clear"
						@click="$utils.navigateTo('news/view',{id: item.id})"
					>
						<view class="news-box">
							<text>{{item.title}}</text>
							<text>{{item.create_time}}</text>
						</view>
						<view class="pic">
							<image :src="item.photo">
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
				result: [],
				cat_id: 0
			}
		},
		onLoad(options) {
			this.cat_id = options.id;
		},
		methods: {
			downCallback(){
				setTimeout(()=>{
					this.mescroll.resetUpScroll();
				},200);
			},
			// 主动触发下拉刷新
			triggerDownScroll(){
				this.mescroll.triggerDownScroll();
			},
			upCallback(page) {
				this.$http.getNewsList({
					page: page.num,
					cat_id: this.cat_id
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
    .news-list-box{
            width: 100%;display: flex; flex-direction: row;flex-wrap: wrap;
            .news-wrap{
                width: 95%;
                margin: 0 auto;
                position: relative;
            }
            .news-item-box{
				position: relative;
                border-radius: 20rpx;
                background-color: #fff;
                height: 210rpx;
				margin: 20rpx 0;
                .news-box{
                    padding-right: 270rpx;
                    position: relative;
                    height: 210rpx;
                    text:first-child{
                        display: block;
                        margin-left: 30rpx;
                        line-height: 50rpx;
                        padding-top: 10rpx;
                        font-size: 28rpx;
                        color: #666;
                        display: -webkit-box;
                        overflow: hidden;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                    }
                    text:last-child{
                        width: 100%;
                        position: absolute;
                        bottom: 10rpx;
                        left: 0;
                        height: 30rpx;
                        line-height: 30rpx;
                        text-indent: 30rpx;
                        font-size: 26rpx;
                        color: #999;
                    }
                }
                .pic {
                    position: absolute;
                    top: 16rpx;
                    right: 20rpx;
                    image {
                        width: 180rpx;
                        height: 180rpx;
                    }
                }
            }
        }
</style>
