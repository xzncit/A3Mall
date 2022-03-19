<template>
    <view>
		<navbar v-model="screenHeight" title-color="#ffffff" background="#1b43c4" :iSimmersive="false" :placeholder="true" title="领劵中心"></navbar>
		<mescroll-body ref="mescrollRef" 
			@init="mescrollInit" 
			@down="downCallback" 
			@up="upCallback"
			:down="downOption"
			:up="upOption" 
			:height="screenHeight+'px'"
		>
			<view class="list-wrap">
				
				<view class="list-box">
					<view class="list-item" v-for="(item, index) in result" :key="index">
						<view class="l">
							<view>￥<text>{{item.amount}}</text></view>
							<view v-if="item.price > 0">满{{item.price}}可用</view>
							<view v-else>无门槛</view>
						</view> 
						<view class="m">
							<text>{{item.name}}</text>
							<text>到期：{{ item.end_time }}</text>
						</view>
						<view class="r" :class="{'active':item.is_receive == 1,'disable': item.is_receive==2}">
							<text @click="onReceive(index)">{{item.is_receive ? (item.is_receive == 1 ? "已领" : "领完") : "领劵"}}</text>
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
				scrollNum: 0,
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
			}
		},
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
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
				setTimeout(()=>{
					this.mescroll.resetUpScroll();
				},200);
			},
			triggerDownScroll(){
				this.mescroll.triggerDownScroll();
			},
			onReceive(index){
				if(this.$utils.in_array(this.result[index].is_receive,[1,2])){
					return ;
				}
	
				this.$http.getCouponList({
					id: this.result[index].id
				}).then(res=>{
					if(res.status){
						this.$utils.msg(res.info);
					}else{
						this.$utils.msg(res.info);
					}
	
					this.result[index].is_receive = res.data;
				}).catch(err=>{
					this.$utils.msg("网络出错，请检查是否已连接");
				});
			},
			upCallback(page) {
				this.$http.getCouponLoad({
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
        width: 92%;
        height: 200rpx;
        border-radius: 10rpx;
        background-color: #fff;
        margin: 0 auto 20rpx auto;
        font-size: 26rpx;
        position: relative;
        .l{
            position: absolute;
            width: 220rpx;
            height: 160rpx;
            top: 20rpx;
            left: 0;
            border-right: 1px dashed #cccccc;
            view{
                color: #1b43c4;
                display: block;
                text-align: center;
                height: 60rpx;
                line-height: 40rpx;
            }
            view:first-child{
                font-size: 32rpx;
                height: 100rpx;
                line-height: 120rpx;
                color: #1b43c4;
                text{
                    font-size: 50rpx;
                    font-style: normal;
                    font-weight: bold;
                }
            }

        }
        .m{
            padding: 0 110rpx 0 220rpx;
            height: 160rpx;
            text-align: center;
            text{
                display: block;
            }
            text:first-child{
                padding-top: 50rpx;
                line-height: 50rpx;
                font-size: 32rpx;
                color: #333;
            }
            text:last-child{
                height: 50rpx;
                line-height: 50rpx;
                font-size: 25rpx; color: #999;
            }
        }
        .r {
            &:before {
                z-index: 11;
                content: " ";
                position: absolute;
                top: -16rpx;
                left: -16rpx;
                width: 32rpx;
                height: 32rpx;
                background-color: #f6f6f6;
                border-radius: 100rpx;
            }
            &:after {
                z-index: 11;
                content: " ";
                position: absolute;
                bottom: -16rpx;
                left: -16rpx;
                width: 32rpx;
                height: 32rpx;
                background-color: #f6f6f6;
                border-radius: 100rpx;
            }
            z-index: 1;
            position: absolute;
            right: 0;
            top: 0;
            width: 110rpx;
            height: 200rpx;
            line-height: 200rpx;
            float: right;
            text-align: center;
            background-color: #1b43c4;
            background-image: url(~@/static/images/coupon-circle.png);
            background-repeat: repeat-y;
            background-position: -4rpx center;
            background-size: 12rpx;
            text{
                font-size: 30rpx;
                color: #fff;
                display: block;
                text-align: center;
            }
        }
        .active{
            background-color: #6b8df9;
        }
        .disable{
            background-color: #dbdadd;
        }
    }

}
</style>