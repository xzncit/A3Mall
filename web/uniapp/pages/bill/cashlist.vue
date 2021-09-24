<template>
	<view>
		<navbar v-model="screenHeight" :scroll="scrollNum" :iSimmersive="false" :placeholder="true" title="提现记录"></navbar>
		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="upCallback" :height="screenHeight+'px'">
			<view class="top">
				<text>总余额：￥{{amount}}</text>
				<text @click="$utils.navigateTo('bill/withdraw')">去提现</text>
			</view>
			
			<view class="list-wrap">
				<view class="list-box clear">
					<view class="list-item clear" v-for="(item, index) in result" :key="index">
						<view class="t">
							<text>转帐</text>
							<text>-￥{{item.amount}}</text>
						</view>
						<view class="box">
							<view class="box-item">
								<view><text class="icon iconfont">&#xe619;</text>申请时间</view>
								<view>{{item.time}}</view>
							</view>
							<view class="box-item">
								<view><text class="icon iconfont">&#xe610;</text>申请状态</view>
								<view :class="{'c-1': item.status==0,'c-2': item.status==1,'c-3': item.status==2}">{{item.text}}</view>
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
				amount: '',
				result: []
			};
		},
		onShow() {
			let users = this.$storage.getJson("users");
			this.amount = users.amount;
			this.$http.getUcenter().then((res)=>{
				if(res.status){
					this.amount = users.amount = res.data.amount;
					this.$store.commit("UPDATEUSERS",users);
				}
			});
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
				this.$http.getWalletCashlist({
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
    .top{
        height: 140rpx;
        line-height: 140rpx;
        background-color: #b91922;
        color: #fff;
        padding: 0 30rpx;font-size: 32rpx;
        text:first-child { float: left; }
        text:last-child { font-size: 30rpx; margin-top: 40rpx; float: right; width: 200rpx; height: 60rpx; line-height: 60rpx; text-align: center; border-radius: 60rpx; border: 2rpx solid #fff; }
    }
    .list-wrap{
        width: 100%;
        margin-top: 20rpx;
        .list-item{
            width: 100%;
            height: auto !important;
            height: 220rpx;
            background-color: #fff;
            font-size: 26rpx;
            margin-bottom: 20rpx;
            .t {
                height: 80rpx;
                line-height: 80rpx;
                border-bottom: 2rpx solid #ebebeb;
                text { font-size: 32rpx; color: #333; }
                text:first-child {
                    padding-left: 32rpx; float: left;
                }
                text:last-child {
                    padding-right: 32rpx; float: right;
                }
            }
            .box {
                height: 136rpx;
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
                    .c-1 { color: #888; }
                    .c-3 { color: #b91922; }
                    .c-2 { color: green; }

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