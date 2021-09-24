<template>
	<view>
		<navbar :iSimmersive="false" titleColor="#000000" :placeholder="true" :isPrev="false" title="商品分类"></navbar>
		<view v-if="!isLoading && isError == false">
			<view class="search-wrap">
				<view @click="onSearch" class="iconfont search-box">请输入关键字</view>
			</view>
			
			<scroll-view
				class="menu" 
				style="overflow: hidden;"
				:style="{'height': wHeight+'px'}" 
				scroll-with-animation="true" 
				scroll-y="true"
				:scroll-top="menuTop"
				:scroll-into-view="menuIndex"
			>
				<view
					v-for="(data,i) in category"
					:key="i"
					class="menu-item"
					:class="{active:currentIndex==i}"
					@click="onMenu(i)"
					:id="'menu-'+i"
					ref="cat"
				>
					{{ data.title }}
				</view>
			</scroll-view>
			
			<view class="content" style="overflow: hidden;" :style="{'height': wHeight+'px'}">
				<scroll-view 
					:scroll-into-view="tabindex"
					scroll-with-animation="true" 
					scroll-y="true"
					:style="{'height': wHeight+'px'}" 
					@scroll="onScroll"
				>
					<view 
						v-for="(data,i) in category" :key="i"
						class="s-list goods-item" 
						:id="'item-'+i"
						ref="item"
						>
						<text class="s-item">{{data.title}}</text>
						<view class="t-list">
							<view 
								class="t-item" 
								v-for="(children, index) in data.children"
								:key="index"
							>
								<navigator :url="'../goods/list?id='+children.id" hover-class="none">
									<image :src="children.thumb_img"></image>
									<view class="children-text"><text>{{children.name}}</text></view>
								</navigator>
							</view>
						</view>
					</view>
				</scroll-view>
			</view>
		</view>
		<loading v-if="isLoading"></loading>
		<empty-box type="service" v-if="isError && isLoading == false" @onEvents="onJump"></empty-box>
	</view>
</template>

<script>
	import loading from '../../components/tool/loading'
	export default {
		components: {
			loading
		},
		data() {
			return {
				isLoading:true,
				isError: false,
				currentIndex: 0,
				wHeight:0,
				menuTop: 0,
				scrollTop: 0,
				tabindex:'item-0',
				menuIndex:'menu-0',
				selectId: "",
				goodsHeight: [],
				menuHeight: [],
				category: []
			}
		},
		mounted() {
			let menuClientRect = { height: 35, searchHeight: 0 };
			let systemInfoSync = uni.getSystemInfoSync();
			
			let statusBar = 0;
			// #ifdef MP || APP-PLUS
			statusBar = systemInfoSync.statusBarHeight;
			// #endif
			
			// #ifdef MP
			menuClientRect = uni.getMenuButtonBoundingClientRect();
			// #endif
			
			// #ifdef APP-PLUS || MP
			menuClientRect.height += 10;
			// #endif
			
			let navHeight = menuClientRect.height + statusBar;
			
			// #ifdef H5
			this.wHeight = (systemInfoSync.screenHeight - navHeight - systemInfoSync.windowBottom) - 46;
			// #endif
			
			// #ifdef APP-PLUS
			this.wHeight = (systemInfoSync.windowHeight - navHeight - systemInfoSync.windowBottom) - 46;
			console.log(systemInfoSync.windowBottom)
			// #endif
			
			// #ifdef MP
			this.wHeight = (systemInfoSync.windowHeight - navHeight) - 46;
			// #endif
			
			this.onLoadData();
		},
		onShow() {
			// #ifdef APP-PLUS
			setTimeout(()=>{
				plus.navigator.setStatusBarStyle("dark");
			},230);
			// #endif
		},
		methods: {
			onLoadData(){
				this.$http.getCategoryList().then((result)=>{
					if(result.status){
						this.isLoading = false;
						this.category = result.data;
						this.$nextTick(()=>{
							setTimeout(()=>{
								this.setScrollHeight();
							},1500);
						});
						this.isLoading = false;
						this.isError = false;
					}else{
						this.isLoading = false;
						this.isError = true;
					}
				}).catch(err=>{
					this.isLoading = false;
					this.isError = true;
				});
			},
			onJump(){
				this.onLoadData();
			},
			onSearch(){
				uni.navigateTo({
					url:"../search/index"
				})
			},
			onMenu(i){
				this.currentIndex = i;
				this.tabindex = 'item-'+i;
			},
			onScroll(event){
				if (this.goodsHeight.length == 0) {
				  return;
				}
				
				let scrollTop = event.detail.scrollTop;
				let current = this.currentIndex;
				
				if (scrollTop >= this.scrollTop) {
				  if (current + 1 < this.goodsHeight.length && scrollTop >= this.goodsHeight[current]) {
					this.currentIndex = current + 1;
				  }
				} else {
				  if (current - 1 >= 0 && scrollTop < this.goodsHeight[current - 1]) {
					this.currentIndex = current - 1;
				  }
				}
				
				this.menuIndex = 'menu-'+this.currentIndex;
				this.scrollTop = scrollTop;
			},
			setScrollHeight(){
				let h = 0;
				const query = uni.createSelectorQuery();
				query.selectAll('.goods-item').boundingClientRect().exec((res)=>{
				  res[0].forEach((item) => {
					h += item.height;
					this.goodsHeight.push(h);
				  })
				});
				
				this.menuHeight = [];
				let m = 0;
				query.select(".menu-item").boundingClientRect().exec((res) => {
				  res[0].forEach((item) => {
					m += item.height;
					this.menuHeight.push(m);
				  })
				});
				
			}
		}
	}
</script>

<style lang='scss' scoped>
	page { background-color: #fff; }
	.search-wrap{
		width: 100%;
		height: 90rpx;
		border-top: 1px solid #ddd;
		background: #fafafa;
		.search-box{
			width: 92%;
			margin: 0 auto;
			height: 64rpx;
			line-height: 64rpx;
			border:1px solid #bfbfbf;
			border-radius: 15rpx;
			background-color: #fff;
			background-size: 34rpx 33rpx;
			background-position: 20rpx center;
			position: relative;
			top: 12rpx;
			text-indent: 70rpx;
			font-size: 26rpx;
			color: #333;
			&::before {
				position: absolute;
				content: "\e629";
				left: -55rpx;
				top: -1rpx;
				font-size: 38rpx;
				color: #aaa;
			}
		}
	}
	.menu{
		width: 178rpx;
		float: left;
		background: #f3f4f6;
		view{
		  width: 100%;
		  text-align: center;
		  height: 100rpx;
		  line-height: 100rpx;
		  background: #f3f4f6;
		  font-size: 27rpx;
		  color: #333;
		  border-bottom: 1px solid #dadbdd;
		}
		view.active{
		  background: #fff; 
		}
	}
	.content{
	  float: right;
	  width: 552rpx;
	  padding: 0 10rpx;
	  .s-item{
	  	display: flex;
	  	align-items: center;
	  	height: 70rpx;
	  	padding-top: 8rpx;
	  	font-size: 28rpx;
	  	color: #333;
	  }
	  .goods-item {
		  &:last-child { padding-bottom: 100rpx; }
	  }
	  .t-list{
	  	display: flex;
	  	flex-wrap: wrap;
	  	width: 100%;
	  	background: #fff;
	  	padding-top: 12rpx;
	  	&:after{
	  		content: '';
	  		flex: 99;
	  		height: 0;
	  	}
	  }
	  .t-item{
	  	flex-shrink: 0;
	  	display: flex;
	  	justify-content: center;
	  	align-items: center;
	  	flex-direction: column;
	  	width: 176rpx;
	  	font-size: 26rpx;
	  	color: #666;
	  	padding-bottom: 20rpx;
	  	.children-text{
			text-align: center;
		}
	  	image{
	  		width: 140rpx;
	  		height: 140rpx;
	  	}
	  }
	}
	
</style>
