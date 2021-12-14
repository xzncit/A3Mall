<template>
	<view>
		<view v-if="isShow" class="shop-update">
			<view class="upgrade">
				<view class="content">
					<view class="title">
						<text>{{upgrading ? "正在升级" : "发现新版本"}}</text>
					</view>
					<view class="container">
						<view class="descriptions">
							<text>{{upgrading ? "正在为您下载,请耐心等待" : "本次版本更新描述内容:"}}</text>
						</view>
						<view class="details" v-if="!upgrading">
							<view class="item">
								<rich-text v-html="intro"></rich-text>
							</view>
						</view>
						<view v-else class="prpgroess">
							<progress :percent="downloadProgress" active-mode="forwards" activeColor="red" active
								stroke-width="4" show-info />
						</view>
					</view>
					<view v-if="!upgrading" class="btn-group">
						<view class="cancel" @click="cancel">
							<text>取消</text>
						</view>
						<view class="confirm" @click="onConfirm">
							<text>更新</text>
						</view>
					</view>
				</view>
			</view>
		</view>
		<popup v-model="isShow"></popup>
	</view>
</template>

<script>
	export default {
		data(){
			return {
				isShow: false,
				// 是否下载
				upgrading: false,
				// 下载时间
				downloadProgress: 0,
				// 下载任务
				downloadTask: null,
				platform: 'android',
				version: '1.0.0',
				intro: "",
				url: ""
			};
		},
		mounted() {
			this.upgrading = false;
			this.isShow = false;
			// #ifdef APP-PLUS
			this.platform = uni.getSystemInfoSync().platform;
			this.version = plus.runtime.version;
			plus.runtime.getProperty(plus.runtime.appid,wgtinfo=>{
				this.version = wgtinfo.version;
				this.$http.getUpdateAPK({ 
					ver: this.version,
					type: this.platform == 'android' ? 1 : 2
				}).then(res=>{
					if(res.status){
						this.url = res.data.url;
						this.intro = res.data.content;
						this.isShow = true;
					}
				}).catch(err=>{
					this.upgrading = false;
					this.isShow = false;
				});
			});
			// #endif
		},
		methods: {
			cancel(){
				this.isShow = false;
			},
			onConfirm(){
				if (!this.upgrading) {
					this.upgrading = true
					if (this.platform == 'android') {
						this.downloadApplications()
					}
					if (this.platform == 'ios') {
						plus.runtime.openURL(this.url)
					}
				}
			},
			downloadApplications(){
				let that = this
				// 建立下载任务
				that.downloadTask = uni.downloadFile({
					// 下载地址
					url: that.url,
					success: (res) => {
						if (res.statusCode === 200) {
							// 把当前app保存下载
							uni.saveFile({
								tempFilePath: res.tempFilePath,
								success: (resp) => {
									let savedFilePath = resp.savedFilePath;
									let installPath = plus.io.convertLocalFileSystemURL(savedFilePath);
									// 安装
									that.installApplications({
										filePath: installPath,
										success: (res) => {
											plus.runtime.restart();
										},
										error: (err) => {
											that.isShow = false;
											uni.showToast({
												icon: 'none',
												title: '更新失败，请稍后再试~~~'
											})
										}
									})
								},
								fail: (err) => {
									that.isShow = false;
								}
							});
						} else {
							that.isShow = false;
							uni.showToast({
								icon: 'none',
								title: '更新失败，请稍后再试~~~'
							})
						}
					},
				});
				
				this.downloadTask.onProgressUpdate((res) => {
					// 下载进度
					this.downloadProgress = res.progress
				});
			},
			//安装
			installApplications({filePath,success,error}) {
				plus.runtime.install(
					filePath, {
						force: true
					},
					success,
					error
				);
			}
		}
	}
</script>

<style lang="scss" scoped>
	.shop-update{
		z-index: 9999;
		position: fixed;
		left: 50%; top: 50%; transform: translate(-50%,-50%);
		background: #fff;
		width: 468rpx;
		min-height: 238rpx;
		border-radius: 20rpx;
		.upgrade {
			.content {
				.container {
					color: #666;
				}
				.title {
					text-align: center;
					font-size: 34rpx;
					font-weight: bold;
					height: 80rpx; line-height: 80rpx;
				}
				.descriptions {
					padding: 0rpx 30rpx;
					text-align: center;
					font-size: 30rpx;
				}
				.details,.prpgroess {
					padding: 16rpx 46rpx;
					box-sizing: border-box;
					font-size: 24rpx;
				}
				.btn-group {
					display: flex;
					justify-content: center;
					align-items: center;
				}
				.btn-group view {
					width: 200rpx;
					height: 68rpx;
					display: flex;
					justify-content: center;
					align-items: center;
					margin: 14rpx;
					font-size: 24rpx;
					border-radius: 16rpx;
					line-height: 1.5;
				}
			}
		}
		
	}
</style>
