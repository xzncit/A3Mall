<script>
	export default {
		onLaunch: function(options) {
			this.autoLogin();
			// #ifdef H5
			uni.getSystemInfo({
				success(e) {
					if (e.windowWidth > 420 && !/iOS|Android/i.test(e.system)) {
						window.location.pathname = '/wap/static/html/home.html';
					}
				}
			})
			// #endif
		},
		onShow: function() {
			let users = this.$storage.getJson("users");
			if(users){
				uni.setTabBarBadge({ index: 3, text: users.shop_count.toString() });
			}else{
				uni.removeTabBarBadge({ index: 3 })
			}
		},
		onHide: function() {
			console.log('App Hide')
		},
		methods: {
			autoLogin(){
				let users = this.$storage.getJson("users");
				if(users != null){
					this.$http.autoLogin().then(result=>{
						if(result.status===1000){
							this.$store.commit("UPDATEUSERS",result.data);
						}else{
							this.$storage.remove("users");
						}
					});
				}
			}
		}
	}
</script>

<style>
	@import '~@/style.css';
	page { background-color: #f8f8f8; }
	.clear:after { content:" "; font-size:0; display:block; height:0; clear:both; visibility:hidden; }
	uni-checkbox .uni-checkbox-input { border-radius: 50rpx; }
	// #ifdef H5
	uni-toast { z-index: 10099 !important; }
	uni-modal { z-index: 10099 !important; }
	// #endif
	::-webkit-scrollbar {
	  display: none;
	  width: 0 !important;  
	  height: 0 !important;  
	  -webkit-appearance: none;  
	  background: transparent;  
	}
</style>
