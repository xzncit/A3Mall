<template>
	<view>
		<empty-box type="service" btnText="首页" @onEvents="goHome"></empty-box>
	</view>
</template>

<script>
	export default {
		methods: {
			goHome(){
				this.$utils.switchTab("index/index");
			}
		}
	}
</script>

<style>
</style>
