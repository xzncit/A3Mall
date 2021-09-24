<template>
    <view>
		<navbar v-model="screenHeight" :iSimmersive="false" :placeholder="true" title="商品列表"></navbar>
        <view class="navbar">
            <view class="nav-item" :class="{current: filterIndex === 0}" @click="tabClick(0)">
                综合排序
            </view>
            <view class="nav-item" :class="{current: filterIndex === 1}" @click="tabClick(1)">
                销量优先
            </view>
            <view class="nav-item" :class="{current: filterIndex === 2}" @click="tabClick(2)">
                <view>价格</view>
                <view class="arrow-box">
                    <text :class="{active: priceOrder === 1 && filterIndex === 2,'icon-arrow-up-active':priceOrder === 1 && filterIndex === 2}" class="icon iconfont icon-arrow-up">&#xe61c;</text>
                    <text :class="{active: priceOrder === 2 && filterIndex === 2,'icon-arrow-down-active':priceOrder === 2 && filterIndex === 2}" class="icon iconfont icon-arrow-down">&#xe61c;</text>
                </view>
            </view>
        </view>

        <view style="height: 100rpx; background-color: #b91922"></view>

		<mescroll-body 
		ref="mescrollRef" 
		@init="mescrollInit" 
		@down="downCallback" 
		@up="upCallback"
		:height="(screenHeight-50)+'px'"
		>
			<view class="goods-list-box">
				<view
					class="goods-list-item-box"
					v-for="(item,index) in result"
					:key="index"
					@click="go(keywords,item.id)"
				>
					<view class="goods-list-item-wrap">
						<view><image :src="item.photo"></view>
						<view>{{ item.title }}</view>
						<view>￥{{ item.price }}</view>
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
				filterIndex: 0,
				priceOrder: 1,
				result: [],
				keywords: ""
			}
		},
		onLoad(options) {
			this.keywords = options.keywords;
		},
		methods: {
			go(keywords,goods_id){
				this.$http.searchKeywords({
					keywords:keywords,
					goods_id:goods_id,
					client_type: 2
				}).then(res=>{
					this.$utils.navigateTo("goods/view",{ id: goods_id  });
				}).catch(err=>{
					this.$utils.msg("请求失败，请检查网络是否连接");
				})
			},
			tabClick(index){
				if(this.filterIndex === index && index !== 2){
					return;
				}
				this.filterIndex = index;
				if(index === 2){
					this.priceOrder = this.priceOrder === 1 ? 2: 1;
				}else{
					this.priceOrder = 0;
				}

				this.mescroll.resetUpScroll();
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
				this.$http.getSearchList({
					page: page.num,
					keywords: this.keywords,
					type: this.filterIndex,
					sort: this.priceOrder
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
    .navbar{
        position: fixed;
        /* #ifdef APP-PLUS */
        left: 0;
        top: 0rpx;
        /* #endif */
        /* #ifdef H5 */
        left: 0;
        top: calc(44px + env(safe-area-inset-top)) + rpx;
        /* #endif */
        display: flex;
        width: 100%;
        height: 100rpx;
        background: #b91922;
        z-index: 10;
        .nav-item{
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            font-size: 28rpx;
            color: #fff;
            position: relative;
            &.current{ color: #fff000; }
            .arrow-box{
                display: flex;
                flex-direction: column;
                .icon{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 38rpx;
                    height: 10rpx;
                    line-height: 10rpx;
                    margin-left: 0px;
                    font-size: 30rpx;
                    color: #fff;
                    text-align: center;
                    &.active{
                        color: #fff000;
                    }
                }
                .icon-arrow-up {}
                .icon-arrow-down {
                    transform:rotate(-180deg);
                }
            }
        }
    }

    .goods-list-box{ margin-top: 20rpx; width: 100%;display: flex; flex-direction: row;flex-wrap: wrap; }
    .goods-list-item-box{ width: 50%; margin-bottom: 20rpx; }
    .goods-list-item-box:nth-child(2n+1) .goods-list-item-wrap { margin-left: 20rpx; margin-right: 10rpx; }
    .goods-list-item-box:nth-child(2n) .goods-list-item-wrap { margin-left: 10rpx; margin-right: 20rpx; }
    .goods-list-item-wrap{ height: 520rpx; background: #fff; overflow: hidden; border-radius: 16rpx; }
    .goods-list-item-wrap view { display: block; }
    .goods-list-item-wrap view:nth-child(1) { height: 370rpx; }
    .goods-list-item-wrap view:nth-child(1) image { padding: 20rpx 5%; width: 90%; height: 330rpx; }
    .goods-list-item-wrap view:nth-child(2) { height: 80rpx; font-size: 30rpx; padding: 0 20rpx; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
    .goods-list-item-wrap view:nth-child(3){ font-size: 26rpx; padding: 10rpx; color: red; }
</style>