<template>
	<view>
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="物流信息"></navbar>
		<info v-if="isError"></info>
		<view v-if="!isError">
			<view class="top">
				<view class="address">
					<view class="info">
						<text>收件人：{{order.accept_name}}</text>
						<text>手机号：{{order.mobile}}</text>
					</view>
					<view class="address-info">
						{{order.region}} {{order.address}}
					</view>
				</view>
			</view>

			<view class="order">
				<view class="title">
					<text>订单信息</text>
				</view>
				
				<view class="list clear">
					<view class="list-box clear">
						<view>订单编号：</view>
						<view class="money">{{order.order_no}}</view>
					</view>
					<view class="list-box clear" v-if="order.express.expName">
						<view>快递名称：</view>
						<view>{{order.express.expName||''}}</view>
					</view>
					<view class="list-box clear">
						<view>快递单号：</view>
						<view>{{order.express.number||''}}</view>
					</view>
					<view class="list-box clear">
						<view>物流耗时：</view>
						<view class="money">{{order.express.takeTime||''}}</view>
					</view>
					<view class="list-box clear">
						<view>更新时间：</view>
						<view class="money">{{order.express.updateTime||''}}</view>
					</view>
				</view>
				
				<uni-steps 
					:options="stepsOptions" 
					direction="column"
					active-color="#07c160" 
					:active="active"
				/>
			</view>

		</view>
	</view>
</template>

<script>
	import info from '@/components/tool/info.vue'
	import uniSteps from '@/components/uni-steps/uni-steps.vue'
	export default {
		components:{
			info,uniSteps
		},
		data(){
			return {
				isError: false,
				active: 0,
				orderId: 0,
				order:{
					express:{
						expName: "",
						number: "",
						takeTime: "",
						updateTime: ""
					},
					accept_name: "",
					address: "",
					create_time: "",
					mobile: "",
					order_no: "",
					region: ""
				},
				stepsOptions:[]
			};
		},
		onLoad(options) {
			this.isError = false;
			this.orderId = options.id;
			
			this.$http.getOrderExpress({ id: this.orderId }).then((res)=>{
				if(res.status){
					this.order = res.data;
					res.data.express.list.forEach((item,index)=>{
						this.stepsOptions.push({
							title: item.time,
							desc: item.status
						});
					});
				}else{
					this.$utils.msg(res.info);
				}
			}).catch((err)=>{
				this.isError = true;
				this.$utils.msg("网络出错，请检查网络是否连接");
			});
		},
		methods: {
			
		}
	}
</script>

<style lang="scss" scoped>
    .money{ color: #fc4141; }
    .top{
        background-color: #fff;
        position: relative;
        &:before{
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 4rpx;
            background: -webkit-repeating-linear-gradient(135deg,#ff6c6c 0,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);
            background: repeating-linear-gradient(-45deg,#ff6c6c 0,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);
            background-size: 160rpx;
            content: '';
        }
        .status{
            width: 95%;
            margin: 0 auto;
        }
        .address{
            font-size: 28rpx;
            width: 92%;
            margin: 0 auto;
            .info{
                height: 60rpx;
                line-height: 60rpx;
                text:first-child{
                    padding-right: 20rpx;
                }
                text:last-child{

                }
            }
            .address-info{
                height: 60rpx;
                line-height: 40rpx;
            }
        }
    }
    
    .order{
    	background-color: #fff;
    	margin-top: 30rpx;
    	padding-bottom: 20rpx;
    	.title{
    		width: 100%;
    		margin: 0 auto;
    		color: #666;
    		font-size: 30rpx;
    		height: 80rpx;
    		line-height: 80rpx;
    		border-bottom: 2rpx solid #eee;
    		text {
    			padding-left: 30rpx;
    		}
    	}
    	.list {
    		width: 100%;
    		.list-box{
    			width: 92%;
    			height: auto !important;
    			height: 80rpx;
    			min-height: 80rpx;
    			line-height: 80rpx;
    			margin: 0 auto;
    			font-size: 26rpx; color: #333;
    			border-bottom: 2rpx solid #ebedf0;
    			view{ display: inline-block; }
    			view:first-child { float: left; }
    			view:last-child { float: right; }
    			textarea { height: 150rpx; }
    		}
    	}
    }
</style>