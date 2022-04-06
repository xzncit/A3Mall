<template>
	<view class="wrap">
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="会员设置"></navbar>
		<view class="avatar" @click="upload">
			<view>
				<image :src="avatar">
				<view class="file">上传</view>
			</view>
		</view>
		
		<view class="theForm">
			<form @submit="formSubmit">
				<view class="fields-box">
					<view>昵称</view>
					<view>
						<input type="text" class="uni-input" name="username" :value="username" placeholder="昵称" />
					</view>
				</view>
				<view class="fields-box">
					<view>性别</view>
					<view>
						<picker @change="bindSexPickerChange" :value="sexIndex" :range="sexArray" range-key="name">
							<input type="text" class="uni-input" name="sex" disabled="true" :value="sexArray[sexIndex].name" placeholder="性别" />
						</picker>
					</view>
					
				</view>
				<view class="fields-box">
					<view>生日</view>
					<view>
						<picker mode="date" :value="birthday" :start="startDate" :end="endDate" @change="bindDateChange">
							<input type="text" class="uni-input" name="birthday" disabled="true" :value="birthday" placeholder="生日" />
						</picker>
					</view>
				</view>
				
				<view class="btn">
					<button form-type="submit">提 交</button>
				</view>
				
				<view class="logout">
					<button @click="logout">退出登录</button>
				</view>
			</form>
		</view>
		
		
	</view>
</template>

<script>  
	function getDate(type) {
		const date = new Date();
	
		let year = date.getFullYear();
		let month = date.getMonth() + 1;
		let day = date.getDate();
	
		if (type === 'start') {
			year = year - 60;
		} else if (type === 'end') {
			year = year + 2;
		}
		month = month > 9 ? month : '0' + month;;
		day = day > 9 ? day : '0' + day;
	
		return `${year}-${month}-${day}`;
	}
	import config from "@/config";
	import navbar from "@/components/navbar/navbar";
    export default {
		components: {
			navbar
		},
		data(){
			return {
				avatar: "",
				username: "",
				sex:"男",
				birthday:'',
				startDate:getDate('start'),
				endDate:getDate('end'),
				sexIndex: 0,
				sexArray: [{name:'男'},{name: '女'}],
			};
		},
		onShow() {
			let users = this.$storage.getJson("users");
			if(users == null){
				this.$utils.navigateTo('public/login');
			}else{
				this.$http.getUserInfo().then((res)=>{
					if(res.status){
						this.username  = res.data.nickname;
						this.sex  = res.data.sex;
						this.birthday  = res.data.birthday;
						this.avatar = res.data.avatar;
					}
				});
			}
		},
        methods:{
			bindDateChange: function(e) {
				this.birthday = e.detail.value
			},
			bindSexPickerChange: function(e) {
				this.sexIndex = e.detail.value
			},
			formSubmit(e){
				let formdata = e.detail.value;
				if(formdata.username.length <= 0){
					this.$utils.msg("请填写用户名");
					return ;
				}
				this.$http.editUserInfo(formdata).then((res)=>{
					this.$utils.msg(res.info);
				});
			},
			logout(){
				this.$store.commit("DELETEUSERS","users");
				this.$utils.navigateTo('public/login');
			},
			upload(){
				let users = this.$storage.getJson("users");
				let that = this;
				uni.chooseImage({
					count: 1,
				    success: (chooseImageRes) => {
				        const tempFilePaths = chooseImageRes.tempFilePaths;
				        uni.uploadFile({
				            url: config.uni_app_web_api_url + '' + '/ucenter/avatar',
				            filePath: tempFilePaths[0],
				            name: 'file',
				            header: { "Auth-Token" : users.token },
				            success: (uploadFileRes) => {
								let res = JSON.parse(uploadFileRes.data);
				                that.avatar = res.data;
				            }
				        });
				    }
				});	
			}
		} 
    }  
</script>

<style lang="scss" scoped>
	.wrap{
	    width: 100%;
	    height: 100vh;
	    background-color: #fff;
	    .avatar{
	        width: 100%;
	        height: 80px;
	        text-align: center;
	        padding: 20px 0;
	        view {
	            position: relative;
	            height: 80px;
	            width: 80px;
	            text-align: center;
	            display: inline-block;
	            &:before {
	                width: 80px;
	                height: 80px;
	                display: block;
	                content: " ";
	                top: 50%;
	                left: 50%;
	                transform: translate(-50%,-50%);
	                position: absolute;
	                z-index: 99;
	                background: rgba(0,0,0,0.2);
	                border-radius: 50%;
	            }
	            .file {
	                top: 50%;
	                left: 50%;
	                transform: translate(-50%,-50%);
	                z-index: 1111;
	                color: #333;
	                position: absolute;
					display:inline-block; background: #fff;
	                width: 40px;
	                height: 20px;
	                line-height: 20px;
	                 overflow: hidden;
	                text-decoration: none;
	                text-indent: 0; cursor: pointer;
	                border-radius: 5px;
	                font-size: 13px;
	            }
	
	            image {
	                width: 80px;
	                height: 80px;
	                overflow: hidden;
	                border-radius: 50%;
	                position: absolute;
	                left: 50%;
	                transform: translateX(-50%);
	            }
	        }
	    }
	}
	
	.theForm{
		width: 650rpx;
		margin: 0 auto;
		.fields-box{
			width: 100%;
			float: left;
			font-size: 31rpx;
			height: 110rpx;
			line-height: 110rpx;
			border: 1px solid #e0e0e0;
			border-left: 0;
			border-right: 0;
			&:nth-child(1){
				border-bottom: 0;
			}
			&:nth-child(2){
				border-bottom: 0;
			}
			view:first-child {
				float: left;
				width: 160rpx;
				color: #999;
			}
			view:last-child {
				float: right;
				width: 490rpx;
				color: #333;
				input {
					height: 110rpx;
					line-height: 110rpx;
				}
			}
		}
		.btn{
			float: left;
			margin: 25rpx 0;
			button {
				width: 650rpx;
				height: 100rpx;
				line-height: 100rpx;
				text-align: center;
				background-color: #1b43c4;
				border: 1px solid #1b43c4;
				color: #fff;
				font-size: 33rpx;
			}
		}
		.logout {
			float: left;
			button {
				width: 650rpx;
				height: 100rpx;
				line-height: 100rpx;
				text-align: center;
				background-color: #ffffff;
				color: #a1a1a1;
				font-size: 33rpx;
				border: 0px solid #d6d6d6;
			}
		}
	}
</style>