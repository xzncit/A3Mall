<template>
    <view> 
		<navbar :iSimmersive="false" :placeholder="true" title="搜索"></navbar>
		<view class="search-wrap">
			<form @submit="onSubmit">
				<view class="field-box iconfont">
					<input type="text" class="uni-input" name="key" :value="value" placeholder="请输入关键字" />
				</view>
				<view class="field-btn">
					<button :disabled="isSubmit" plain form-type="submit">搜索</button>
				</view>
			</form>
		</view>
		
        <view class="search-host-list clear">
            <view class="host-list clear">
                <view class="title">热门搜索</view>
                <view class="list">
                    <text v-for="(item,i) in keywords" :key="i" @click="onSearch(item)">{{ item }}</text>
                </view>
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
            value:"",
            keywords:[],
			isSubmit: false
        };
    },
    onLoad() {
        this.$http.getSearchKeywords().then((result)=>{
            if(result.status){
                this.keywords = result.data;
            }
        });
    },
    methods: {
		onSubmit(e){
			let formData = e.detail.value;
			uni.navigateTo({
				url: `/pages/search/list?keywords=${formData.key}`
			});
		},
        onSearch(val){
            if(typeof val == 'string'){
                this.value = val;
            }
			
			uni.navigateTo({
				url: `/pages/search/list?keywords=${val}`
			});
        }
    },
}
</script>

<style lang="scss" scoped>
.search-wrap{
	width: 100%;
	height: 90rpx;
	border-top: 1px solid #ddd;
	background: #fff;
	.field-box {
		position: relative;
		z-index: 1;
		input{
			width: 610rpx;
			float: left;
			height: 64rpx;
			line-height: 64rpx;
			border:1px solid #bfbfbf;
			border-radius: 15rpx;
			background-color: #fff;
			position: relative;
			top: 12rpx;
			left: 20rpx;
			text-indent: 70rpx;
			font-size: 26rpx;
			color: #333;
		}
		
		&::before {
			z-index: 10;
			position: absolute;
			content: "\e629";
			left: 40rpx;
			top: 25rpx;
			font-size: 38rpx;
			color: #aaa;
		}
	}
	.field-btn {
		button {
			float: right;
			background-color: #fff;
			border: none;
			font-size: 28rpx;
			width: 110rpx;
			height: 64rpx;
			line-height: 64rpx;
			position: relative;
			top: 12rpx;
		}
	}
}
.search-host-list {
    width: 100%; margin-top: 10px;
    height: auto !important;
    height: 100px; min-height: 100px;
    padding: 10px 0;
    background-color: #fff;
    .host-list{
        .title{
            float: left;
            color: #666;
            font-size: 16px;
            width: 100%;
            height: 45px;
            line-height: 45px;
            text-indent: 10px;
        }
        .list{
            text {
                font-size: 14px;
                padding: 5px 10px;
                background-color: #f1f1f1;
                color: #333;
                margin: 5px 10px;
                border-radius: 10px;
                float: left;
            }
        }
    }
}

</style>
