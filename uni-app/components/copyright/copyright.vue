<template>
	<view>
		<!-- #ifdef H5 -->
		<view v-if="result.is_h5==1">
			<view class="copyright">
				<view v-if="result.icp">&nbsp;&nbsp;<span>备案信息：<a target="_blank" href="https://beian.miit.gov.cn/">{{result.icp||''}}</a></span></view>
				<view v-if="result.gov_record">&nbsp;&nbsp;<span class="gov-icon" :style="'background: url('+$static+'yui_123.png) no-repeat;'"><a target="_blank" :href="result.gov_link||''">公安备案：{{result.gov_record||''}}</a></span></view>
				<view v-if="result.supervision_url">&nbsp;&nbsp;<span>{{result.supervision_url||''}}</span></view>
			</view>
		</view>
		<!-- #endif -->
	</view>
</template>

<script>
	export default {
		data(){
			return {
				result:[]
			};
		},
		mounted() {
			// #ifdef H5
			this.$http.getCopyData().then(res=>{
				this.result = res.data;
			});
			// #endif
		},
		methods: {
			
		}
	}
</script>

<style lang="scss" scoped>
.copyright {
	width: 100%; text-align: center;
	view {
		color: #888;
		display: inline-block;
		font-size: 13px;
		padding: 10px 5px;
		a {
			color: #888;
			font-size: 13px;
			text-decoration: none;
		}
	}
	.gov-icon {
		padding-left: 20px;
	}
}
</style>
