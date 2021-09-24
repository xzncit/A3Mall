<template>
	<view>
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="帮助中心"></navbar>
		<view class='help'>
		
		  <view 
		  class='item'
		  v-if="list.length > 0"
		  v-for="(item,index) in list" 
		  :key="index"
		  >
		    <view class='title' @click='panel(item.id)'>
		      <view class="a">{{ item.title }}</view>
		      <view class="b">
				  <text 
					class="iconfont iconarrow-top"
					:class="{ 'down': active == item.id, 'up': active != item.id }"
				  ></text>
				  </view>
		    </view>
		    <view class="content" v-if="active == item.id">
		      <view v-html="item.content"></view>
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
		data(){
			return {
				active:0,
				list: []
			};
		},
		onLoad() {
			this.$http.gethelp().then((res)=>{
			  if(res.status){
				  this.list = res.data;
				  this.active = res.data[0] != undefined ? res.data[0].id : 0;
			  }else{
				this.$utils.msg(res.info);
			  }
			}).catch((error)=>{
			  this.$utils.msg("网络出错，请检查网络是否连接");
			});
		},
		methods: {
			panel: function (index) {
				if (index != this.active) {
					this.active = index;
				} else {
					this.active = 0;
				}
		  }
		}
	}
</script>

<style lang="scss">
.help {
  width: 100%;
  margin: 0 auto;
  background-color: #fff;
}
.help .item {
  width: 95%;
  margin: 0 auto;
}
.help .item .title {
  width: 100%;
  font-size: 30rpx;
  height: 80rpx;
  line-height: 80rpx;
  border-bottom: 1px solid #ebedf0;
}
.help .item:last-child .title {
  border-bottom: 0;
}

.help .item .title .a {
  float: left;
}
.help .item .title .b {
  display: inline-block;
  float: right;
  position: relative;
  color: #969799;
}
.help .item .content {
  font-size: 26rpx;
  line-height: 40rpx;
  color: #969799;
  padding: 20rpx 0;
}

.up {
  position: absolute;
  right: 0;
  transform:rotate(180deg);
}

.down {
  position: absolute;
  right: 0;
  transform:rotate(360deg);
}
</style>
