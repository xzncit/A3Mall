<template>
	<view>
		<navbar :iSimmersive="false" :placeholder="isPlaceholder" :isPrev="false" :isNavTitle="isNavTitle" titleColor="#ffffff" :isSearch="true" :isShow="isNavbar" background="#1b43c4" :title="navTitle"></navbar>
		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="upCallback">
			<view v-if="!isLoading && isError == false">
				<view class="swiper-box">
					<swiper
						:circular="true"
						:indicator-dots="swiper.indicatorDots" 
						:autoplay="swiper.autoplay" 
						interval="5000"
						:duration="swiper.duration"
						indicator-active-color="#1b43c4"
					 >
						<swiper-item 
							class="have-none" 
							v-for="(image, index) in images" 
							:key="index"
							@click="sliderJump(image.url)"
						>
							<image 
							 :src="image.photo" 
							 mode="scaleToFill"
							 ></image>
						</swiper-item>
					</swiper>
				</view>
				
				<view class="notice-box" @click="$utils.navigateTo('news/view',{ id: notice.id })" v-if="notice.title">
					<view class="notice-icon iconfont" style="color: #1b43c4;">&#xea80;</view>
					<view class="notice-text" style="color: #1b43c4;">{{ notice.title }}</view>
				</view>
				
				<view class="grid-box">
					<view
						class="grid-box-item"
						v-for="(value,i) in category"
						:key="i"
						@click="catJump(value)"
					>
						<view class="grid-box-item-img">
							<image :src="value.image"></image>
						</view>
						<text class="grid-box-item-text">{{ value.name }}</text>
					</view>
				</view>
				
				<view class="m-1" v-if="img_1.image">
					<image :src="img_1.image"></image>
				</view>
				
				<view class="host-box">
					<view class="host-title">
						<text class="l">热销排行</text>
					</view>
					<view class="host-middle">
						<view 
							v-for="(item,index) in hot" 
							:key="index" 
							class="host-middle-box" 
							@click="$utils.navigateTo('goods/view',{ id: item.id })">
							<view><image :src="item.image"></image></view>
							<view>{{item.name||'此处显示商品名称'}}</view>
							<view>￥{{item.price}}</view>
						</view>
					</view>
				</view>
				<view style="width: 100%; height: 20rpx;"></view>
				<view class="image-wrap image-group-4 clear" v-if="img_2.length">
					<view class="display">
						<view class="image-group-left">
							<image :src="img_2[0].image" mode="scaleToFill"></image>
						</view>
						<view class="image-group-right">
							<view class="image-group-right1">
								<image :src="img_2[1].image" mode="scaleToFill"></image>
							</view>
							<view class="image-group-right2">
								<view class="left">
									<image :src="img_2[2].image" mode="scaleToFill"></image>
								</view>
								<view class="right">
									<image :src="img_2[3].image" mode="scaleToFill"></image>
								</view>
							</view>
						</view>
					</view>
				</view>
				
				<view style="width: 100%; height: 20rpx;"></view>
				<view class="goods-wrap" v-if="recommend.length">
					<view class="goods-head">
						<view>
							<text class="l">精品推荐</text>
						</view>
					</view>
					<view class="goods-slider">
						<scroll-view scroll-x>
							<view
								class="goods-slider-list"
								:style="'width: '+((140*recommend.length))+'px;'"
							>
								<view class="goods-slider-item"
									 v-for="(item,index) in recommend"
									 :key="index"
									 @click="$utils.navigateTo('goods/view',{ id: item.id })"
								>
									<view><image :src="item.image"></image></view>
									<view>{{item.name||'此处显示商品名称'}}</view>
									<view>￥{{item.price}}</view>
								</view>
							</view>
						</scroll-view>
					</view>
				</view>
				
				<view v-if="list.length" class="goods-list">
					<view class="goods-title">
						猜你喜欢
					</view>
				
					<view class="goods-list-box">
						<view class="goods-list-item-box"
							 v-for="(item,index) in list"
							 :key="index"
							 @click="$utils.navigateTo('goods/view',{ id: item.id })"
						>
							<view class="goods-list-item-wrap">
								<view><image :src="item.photo"></view>
								<view>{{ item.title }}</view>
								<view>￥{{ item.price }}</view>
							</view>
						</view>
					</view>
				
				</view>
				
			</view>
		</mescroll-body>
		
		<loading v-if="isLoading"></loading>
		<empty-box type="service" v-if="isError && isLoading == false" @onEvents="onJump"></empty-box>
	</view>
</template>

<script>
	import MescrollMixin from "@/uni_modules/mescroll-uni/components/mescroll-uni/mescroll-mixins.js";
	import loading from '../../components/tool/loading';
	import {checkPlatformAgent} from '../../common/check.js';
	import navbar from "@/components/navbar/navbar";
	
	export default {
		mixins: [MescrollMixin],
		components:{
			loading,navbar
		},
		data() {
			return {
				swiper: {
					indicatorDots: true,
					autoplay: true,
					duration: 500,
				},
				isNavbar: false,
				isPlaceholder: false,
				isNavTitle: false,
				isLoading:true,
				isError: false,
				navTitle: "",
				result: [],
				goods: [],
				images: [],
				category: [],
				img_1: [],
				img_2: [],
				hot: [],
				recommend: [],
				notice: [],
				list: []
			}
		},
		onLoad() {
			let platformAgent = this.$utils.platformAgent();
			this.isNavbar = true;
			this.isPlaceholder = true;
			this.navTitle = "首页"
			
			// #ifdef MP
			this.isNavTitle = true;
			// #endif
		},
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
		},
		methods: {
			sliderJump(url){
				uni.navigateTo({
					url: url
				})
			},
			catJump(data){
				if(data.url == 'category/index'){
					this.$utils.switchTab(data.url);
				}else{
					this.$utils.navigateTo(data.url);
				}
			},
			onJump(){
				this.loadData();
				this.mescroll.resetUpScroll();
			},
			loadData(){
				this.$http.getHomeData().then(res=>{
					if(res.status){
						this.result = [];
						this.images = res.data.banner;
						this.category = res.data.nav;
						this.img_1 = res.data.img_1;
						this.img_2 = res.data.img_2;
						this.hot = res.data.hot;
						this.recommend = res.data.recommend;
						this.notice = res.data.notice;
						this.isLoading = false;
						this.isError = false;
					}else{
						this.isLoading = false;
						this.isError = true;
					}
					this.mescroll.endDownScroll();
				}).catch(err=>{
					 this.isLoading = false;
					 this.isError = true;
					 this.mescroll.endErr();
				});
			},
			downCallback(){
				setTimeout(()=>{
					this.loadData();
				},200);
			},
			upCallback(page) {
				this.isLoading = false;
				this.$http.getHomeList({
					page: page.num,
				}).then((result)=>{
					this.mescroll.endByPage(result.data.list.length, result.data.total);
					if(result.status==1){
						if(page.num == 1) this.list = [];
						this.list = this.list.concat(result.data.list);
					}else if(result.status == -1){
						this.mescroll.endErr(); 
						this.mescroll.removeEmpty();
					}
				}).catch(error=>{
					this.mescroll.endErr();
					this.mescroll.removeEmpty();
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.swiper-box{
		width: 100%; margin: 0 auto; 
		image {
			display: block;width: 100%;height: 300rpx;background-color: #fff;pointer-events: none;
		}
	}
    .notice-box {
    	padding: 0 30rpx;
    	height: 60rpx;
    	line-height: 60rpx;
    	position: relative;
    	.notice-icon {
    		font-size: 33rpx;
    		color: rgb(185, 25, 34);
    		float: left;
    		top: 1rpx;
    		position: absolute;
    	}
    	.notice-text {
    		font-size: 28rpx;
    		text-indent: 45rpx;
    		color: rgb(185, 25, 34);
    		white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    	}
    }
    .grid-box{
    	background: #fff;
    	display: flex;
    	flex-direction: row;
    	flex-wrap: wrap;
    	padding-top: 20rpx;
    	.grid-box-item { 
    		width: 25%;
    		height: auto !important;
    		height: 150rpx;
    		min-height: 150rpx;
    		text-align: center; 
    		.grid-box-item-img { 
    			display: block;
    			image {
    				width: 90rpx;
    				height: 90rpx;
    				border-radius: 50%;
    			}
    		}
    		.grid-box-item-text {
    			display: block;
    			font-size: 26rpx;
    			color: #666;
    			width: 100%;
    			overflow: hidden;
    			text-overflow: ellipsis;
    			white-space: nowrap;
    		}
    	}
    }
    .host-box { width: 100%; height: 520rpx; background-color: #1b43c4; }
    .host-box .host-title { color: #fff; width: 92%; height: 90rpx; line-height: 90rpx; margin: 0 auto; }
    .host-box .host-title text:nth-child(1) { font-size: 32rpx; float: left; font-weight: bold; }
    .host-box .host-title text:nth-child(2) { position: relative; font-size: 26rpx; float: right; padding-right: 30rpx; }
    .host-box .host-title text:nth-child(2):after { position: absolute; right: 0; top: -1px; content: '>'; }
    .host-middle { overflow: hidden; padding-bottom: 20rpx; width: 92%; margin: 0 auto; background: #fff; border-radius: 10rpx; display: flex; justify-content: center; flex-wrap: nowrap; flex-direction: row; }
    .host-middle-box { width: 31%; padding: 0 1%; padding-top: 20rpx; text-align: center; }
    .host-middle-box view { display: block; width: 100%; font-size: 28rpx; }
    .host-middle-box view:nth-child(1) image { width: 220rpx; height: 220rpx; border-radius: 10rpx; }
    .host-middle-box view:nth-child(2) { height: 80rpx; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
    .host-middle-box view:nth-child(3) { color: red; text-align: left; }
    .m-1{height: 200rpx;margin: 20rpx 0;}
    .m-1 image{display: block;width: 100%;height: 100%;}
    .image-wrap{
        width: 100%;
        height: auto;
        .image-group{
            float: left;
            box-sizing: border-box;
        }
    	image { width: 100%; height: 100%; }
    	.display { 
    		height: 0; width: 100%; margin: 0; padding-bottom: 50%; position: relative; 
    		.image-group-left { width: 40%; height: 100%; position: absolute; top: 0; left: 0; }
    		.image-group-right { 
    			width: 60%; height: 100%; position: absolute; top: 0; left: 40%; 
    			.image-group-right1 { width: 100%; height: 50%; position: absolute; top: 0; left: 0; }
    			.image-group-right2 { width: 100%; height: 50%; position: absolute; top: 50%; left: 0; }
    			.image-group-right2 .left { width: 50%; height: 100%; position: absolute; top: 0; left: 0; }
    			.image-group-right2 .right { width: 50%; height: 100%; position: absolute; top: 0; left: 50%; }
    		}
    	}
    	.image-group-2 .image-group { width: 50%; height: 150px; }
    	.image-group-3 .image-group { width: 33%; height: 150px; }
    	.image-group-4 .image-group { width: 25%; height: 150px; }
    }
    .goods-wrap{
        width: 100%;
        background-color: #fff;
        .goods-head{
            width: 100%;
            height: 100rpx;
            line-height: 100rpx;
            view{
                width: 92%;
                margin:0 auto;
                .l{
                    float: left;
                    font-size: 34rpx;
                    color: #333;
                }
                .r{
                    float: right;
                    font-size: 34rpx;
                    color: #888;
                }
            }
        }
        .goods-list{
            width: 92%;
            margin: 0 auto;
            display: flex; flex-direction: row;flex-wrap: wrap;
            .goods-box { float: left; }
            .goods-image image { width: 100%; height: 100%; }
            .goods-box.column-1 {
                width: 100%;
                height: 230rpx;
                position: relative;
                background-color: #fff;
                margin-bottom: 20rpx;
                .goods-image {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 220rpx;
                    height: 230rpx;
                    text-align: center;
                    image { padding-top: 5%; width: 90%; height: 90%; }
                }
                .goods-detail{
                    padding-left: 230rpx;
                    .goods-name{
                        padding: 0 20rpx;
                        position: relative;
                        top: 20px;
                        color: #888;
                        font-size: 26rpx;
                        display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical;
                    }
                    .goods-price{
                        padding: 0 20rpx;
                        position: relative;
                        top: 80rpx;
                        font-size: 26rpx;
                        color: #b91922;
                    }
                }
            }
            .goods-box.column-2 {
                width: 50%;
                margin-bottom: 20rpx;
                .goods-image {
                    height: 360rpx;
                }
                .goods-item-box{ background-color: #fff; height: auto; }
                &:nth-child(2n+1) .goods-item-box {
                    margin-left: 0px; margin-right: 10rpx;
                }
                &:nth-child(2n) .goods-item-box {
                    margin-left: 10rpx; margin-right: 0px;
                }
                span{ display: block }
                .goods-name{
                    margin: 10rpx 0;
                    padding: 0 10rpx;
                    font-size: 26rpx;
                    float: left;
                    display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical;
                }
                .goods-price{
                    margin: 10rpx 0 20rpx 0;
                    padding: 0 20rpx;
                    font-size: 28rpx; color: #b91922;
                    float: left;
                }
            }
            .goods-box.column-3 {
                width: 33.333%;
                margin-bottom: 20rpx;
                .goods-image {
                    height: 220rpx;
                }
                .goods-item-box{ background-color: #fff; height: auto; }
                &:nth-child(2n+1) .goods-item-box {
                    margin-left: 10rpx; margin-right: 10rpx;
                }
                &:nth-child(2n) .goods-item-box {
                    margin-left: 10rpx; margin-right: 10rpx;
                }
                span{ display: block }
                .goods-name{
                    margin: 10rpx 0;
                    padding: 0 10rpx;
                    font-size: 26rpx;
                    float: left;
                    display: -webkit-box; overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical;
                }
                .goods-price{
                    margin: 10rpx 0 20rpx 0;
                    padding: 0 20rpx;
                    font-size: 28rpx; color: #b91922;
                    float: left;
                }
            }
            &.goods-3 { width: 96%; }
        }
        .goods-slider-list {
            background-color: #fff;
            display: flex; flex-wrap: nowrap; flex-direction: row; font-size: 28rpx;
        }
        .goods-slider-item { float: left; width: 260rpx; margin-right: 20rpx; }
        .goods-slider-item:first-child { margin-left: 20rpx; }
        .goods-slider-item view { display: block; text-align: center; }
        .goods-slider-item view:nth-child(1) { height: 260rpx; }
        .goods-slider-item view:nth-child(1) image { width: 100%; height: 260rpx; display: block; }
        .goods-slider-item view:nth-child(2) { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .goods-slider-item view:nth-child(3) { color: red; }
    }
    .goods-list-box{ width: 100%;display: flex; flex-direction: row;flex-wrap: wrap; }
    .goods-title{ color: #666; width: 100%; height: 100rpx; line-height: 100rpx; text-align: center; font-size: 34rpx; font-weight: bold; }
    .goods-title:before { content: "::"; position: relative; top: -2rpx; left: -4rpx; font-size: 36rpx; }
    .goods-title:after { content: "::"; position: relative; top: -2rpx; right: -4rpx; font-size: 36rpx; }
    .goods-list-item-box{width: 50%; margin-bottom: 20rpx; }
    .goods-list-item-box:nth-child(2n+1) .goods-list-item-wrap { margin-left: 20rpx; margin-right: 10rpx; }
    .goods-list-item-box:nth-child(2n) .goods-list-item-wrap { margin-left: 10rpx; margin-right: 20rpx; }
    .goods-list-item-wrap{ height: 520rpx; background: #fff; }
    .goods-list-item-wrap view { display: block; }
    .goods-list-item-wrap view:nth-child(1) { height: 370rpx; }
    .goods-list-item-wrap view:nth-child(1) image { padding: 20rpx 5%; width: 90%; height: 330rpx; }
    .goods-list-item-wrap view:nth-child(2) { font-size: 28rpx; padding: 0 20rpx; height: 80rpx; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 2;-webkit-box-orient: vertical; }
    .goods-list-item-wrap view:nth-child(3){ font-size: 30rpx; padding: 10rpx; color: red; }
</style>