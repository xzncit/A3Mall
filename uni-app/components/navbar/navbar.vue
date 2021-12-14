<template>
	<view>
		<view v-if="isNavbar || isPrev" class="navbar-box" :style="{ height: menuClientRect.height + statusBar + menuClientRect.searchHeight + 'px','background': bg }">
			<view class="status-bar" :style="{ height: statusBar + 'px' }"></view>
			<view v-if="isNavTitle" class="navbar" :style="{ 'color':fontColor,height: menuClientRect.height + 'px','line-height': menuClientRect.height + 'px' }">
				<view v-if="isPrev" class="iconfont prevPage" :style="{ 'color':fontColor,'line-height': menuClientRect.height + 'px' }" :class="{'backPage': iSimmersive && !isTitle,'statusLine': iSimmersive && scroll < 10}" @click="prev"><text :style="{ 'color':fontColor}">&#xe609;</text></view>
				<view v-if="isTitle" class="title" :style="{ 'color':fontColor,height: menuClientRect.height + 'px','line-height': menuClientRect.height + 'px' }">{{ title }}</view>
			</view>
			<view v-if="isSearch && isTitle" class="search-box" :style="{ 'background': bg }">
				<view class="iconfont search-input" @click="onJumpSearch">
					<text>请输入关键字</text>
				</view>
			</view>
		</view>
		<view v-if="placeholder" class="placeholder-box" style="width: 100%;" :style="{ height: menuClientRect.height - 1 + menuClientRect.searchHeight + statusBar + 'px' }"></view>
	</view>
</template>

<script>
	import storage from "../../common/sessionStorage"
	export default {
		props: {
			value: {
			  type: [String,Number],
			  default: 0
			},
			scroll: {
			  type: [String,Number],
			  default: 0
			},
			placeholder: {
				type:Boolean,
				default: false
			},
			isShow: {
				type:Boolean,
				default: true
			},
			isPrev: {
				type:Boolean,
				default: true
			},
			isSearch: {
				type:Boolean,
				default: false
			},
			isNavTitle: {
				type:Boolean,
				default: true
			},
			// 导航栏标题
			title: {
			    type: String,
			    default: ''
			},
			// 标题的颜色
			titleColor: {
			    type: String,
			    default: '#000000'
			},
			// 对象形式，因为用户可能定义一个纯色，或者线性渐变的颜色
			background: {
			    type: String,
				default: 'transparent'
			},
			iSimmersive: {
				type:Boolean,
				default: false
			},
			onBack: {
			    type: Function,
			    default: null
			}
		},
		data(){
			return {
				statusBar: 10,
				menuClientRect: { height: 35, searchHeight: 0 },
				bg: "",
				fontColor: "",
				isTitle: true,
				isNavbar: true
			};
		},
		mounted() {
			let systemInfoSync = uni.getSystemInfoSync();
			// #ifdef MP || APP-PLUS
			this.statusBar = systemInfoSync.statusBarHeight;
			// #endif
			
			// #ifdef MP
			this.menuClientRect = uni.getMenuButtonBoundingClientRect();
			// #endif
			
			this.isNavbar = this.isShow;
			this.bg = this.background;
			this.fontColor = this.titleColor;
			if(this.iSimmersive){
				this.isTitle = false;
				this.isNavbar = false;
				this.setNavigationBarColor("#ffffff");
			}else{
				this.bg = this.background != 'transparent' ? this.background : "#ffffff";
				this.setNavigationBarColor(this.titleColor);
			}
			
			if(!this.isNavTitle){
				this.menuClientRect.height = 0;
			}
			
			// #ifdef H5
			this.statusBar = 0;
			// #endif 
			
			// #ifdef APP-PLUS || MP
			this.menuClientRect.height += 10;
			// #endif
			
			if(this.isSearch){
				this.menuClientRect.searchHeight = 45;
			}
			
			let navHeight = this.menuClientRect.height + this.statusBar;
			
			// #ifdef H5
			this.$emit("input",systemInfoSync.screenHeight - navHeight - systemInfoSync.windowBottom)
			// #endif
			
			// #ifdef APP-PLUS
			this.$emit("input",systemInfoSync.windowHeight - navHeight - systemInfoSync.windowBottom)
			// #endif
			
			// #ifdef MP
			this.$emit("input",systemInfoSync.screenHeight - navHeight)
			// #endif
		},
		methods: {
			onJumpSearch(){
				this.$utils.navigateTo("search/index");
			},
			prev(){
				if(this.onBack){
					this.onBack();
				}else{
					// #ifdef H5
					let canNavBack = getCurrentPages();  
				    if(canNavBack && canNavBack.length>1) {  
						uni.navigateBack();  
				    } else {
						if(canNavBack.length <= 1){
							this.$utils.switchTab("index/index");
						}
				    }
					// #endif
					
					// #ifndef H5
					uni.navigateBack();
					// #endif
				}
			},
			setNavigationBarColor(color){
				this.fontColor = color;
				// #ifdef MP-WEIXIN
				wx.setNavigationBarColor({
				  frontColor:color,
				  backgroundColor:'#ff0000',
				  animation:{
				    duration: 200,
				    timingFunc:'easeIn'
				  }
				});
				// #endif
				
				// #ifdef APP-PLUS
				setTimeout(()=>{
					if(color == "#000000"){
						plus.navigator.setStatusBarStyle("dark");
					}else{
						plus.navigator.setStatusBarStyle("light");
					}
				},200);
				// #endif
			},
			color2Rgb(string){
				let color = string.toLowerCase();
				if(/^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/.test(color)){
					if (color.length === 4) {
					  let colorNew = "#";
					  for (let i = 1; i < 4; i += 1) {
						colorNew += color.slice(i, i + 1).concat(color.slice(i, i + 1));
					  }
					  color = colorNew;
					}
					
					let array = [];
					for (let i = 1; i < 7; i += 2) {
					  array.push(parseInt("0x" + color.slice(i, i + 2)));
					}
					
					return array.join(",");
				}else{
					return string;
				}
			}
		},
		watch:{
			scroll:{
				handler(newValue, oldValue){
					if(!this.iSimmersive){
						return false;
					}
					
					let bg = "#ffffff";
					let fontColor = "#000000";
					if(this.background != 'transparent'){
						bg = this.background;
						fontColor = "#ffffff";
					}
					
					let rgba = this.color2Rgb(bg);
					if(newValue >= 10 && newValue <= 50){
						this.bg = "rgba("+rgba+",.3)";
						this.setNavigationBarColor(fontColor);
						this.isTitle = true;
						this.isNavbar = true;
					}else if(newValue >= 51 && newValue <= 99){
						this.bg = "rgba("+rgba+",.7)";
						this.setNavigationBarColor(fontColor);
						this.isTitle = true;
						this.isNavbar = true;
					}else if(newValue >= 100){
						this.bg = "rgba("+rgba+",1)";
						this.setNavigationBarColor(fontColor);
						this.isTitle = true;
						this.isNavbar = true;
					}else if(newValue < 10){
						this.bg = "rgba("+rgba+",0)";
						this.setNavigationBarColor("#ffffff");
						this.isTitle = false;
						this.isNavbar = false;
					}
				},
				deep: true
			}
		}
	}
</script>

<style lang="scss" scoped>
	.placeholder-box { height: 35px; }
	.navbar-box {
		position: fixed; z-index: 100000; topL: 0; left: 0; width: 100%;
		height: 35px;
		.status-bar { width: 100%; float: left; }
		.search-box {
			width: 100%; height: 45px; float: left;
			.search-input { 
				position: relative;
				color: #fff;
				height: 35px; line-height: 35px; border-radius: 50rpx;
				margin: 10rpx 20rpx; background-color: #fff; color: #666; 
				&::before {
					position: absolute;
					content: "\e629";
					left: 30rpx;
					top: 0rpx;
					font-size: 38rpx;
					color: #aaa;
				}
				text { padding-left: 90rpx; font-size: 30rpx; }
			}
		}
		.navbar {
			float: left; width: 100%;
			position: relative;
			.title {
				width: 100%; text-align: center; 
				font-size:33rpx;
				/* #ifdef H5 */
				font-size: 29rpx;
				/* #endif */
			}
			.prevPage { 
				position: absolute;
				left: 20rpx; top: 2%;
				width: 60rpx; height: 60rpx;
				text {
					color: #666; font-size: 65rpx; font-weight: bold;
				}
			}
			.backPage {
				background-color: rgba(0, 0, 0, 0.5);
				 border-radius: 50%;
				text{
					color: #fff;
					position: absolute; left: 30%; top: 50%; transform: translate(-30%,-50%);
				}
			}
			/* #ifdef H5 */
			.statusLine {
				top: 20rpx;
			}
			/* #endif */
		}
	}
	
</style>
