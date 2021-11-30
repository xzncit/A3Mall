<template>
    <view>
		<navbar :scroll="scrollNum" :iSimmersive="false" :placeholder="true" title="商品详情"></navbar>
        <view v-if="!isEmpty" class="main clear">
            <view class="title">{{data.title}}</view>
            <view class="info">
                <text>{{data.cat_name}}</text>
                <text class="iconfont">&#xe623; {{data.create_time}}</text>
                <text class="iconfont">&#xe62a; {{data.hits}}</text>
            </view>
            <view class="content clear" v-html="data.content">
				{{ data.content }}
			</view>
        </view>

    </view>
</template>

<script>
	import navbar from "@/components/navbar/navbar";
    export default {
		components: {
			navbar
		},
        data() {
            return {
				scrollNum: 0,
                isEmpty: false,
                data:{}
            };
        },
        onLoad(options) {
            this.$http.getNewsDetail({
                id: options.id
            }).then(res=>{
                this.isEmpty = false;
                if(res.status){
                    this.data = res.data;
                }else{
                    this.isEmpty = true;
                }
            }).catch(err=>{
                this.isEmpty = true;
                
            });
        },
		onPageScroll(obj){
			this.scrollNum = obj.scrollTop;
		},
        methods: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .main{
        background-color: #fff;
        width: 95%;
        margin: 0 auto;
        margin-top: 20rpx;
        .title{
            width: 100%;
            height: 80rpx;
            line-height: 50rpx;
            padding: 0 20rpx;
            padding-top: 20rpx;
			font-size: 32rpx;
        }
        .info{
            width: 100%;
            border-bottom: 2rpx solid #eee;
            padding-bottom: 5px;
            font-size: 26rpx;
            float: left;
            text{
                float: left;
            }
            text:nth-child(1){
                color: #ff3700;
                border: 1px solid #ff3700;
                font-size: 20rpx;
                padding: 2rpx 2rpx;
                position: relative;
                top: 8rpx;
                margin-left: 20rpx;
				padding: 0 5rpx;
            }
            text:nth-child(2){
                margin-left: 20rpx;
            }
            text:nth-child(3){
                margin-left: 20rpx;
            }
        }
        .content{
            padding: 20rpx;
            float: left;
			font-size: 28rpx;
        }
    }

</style>
