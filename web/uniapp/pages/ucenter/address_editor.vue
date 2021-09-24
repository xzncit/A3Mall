<template>
	<view class="wrap">
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="编辑地址"></navbar>
		<view class="theForm">
			<form @submit="formSubmit">
				<view class="fields-box">
					<view>姓名</view>
					<view>
						<input type="text" class="uni-input" name="name" :value="name" placeholder="收货人姓名" />
					</view>
				</view>
				
				<view class="fields-box">
					<view>电话</view>
					<view>
						<input type="text" class="uni-input" name="tel" :value="tel" placeholder="收货人手机号" />
					</view>
				</view>
				
				<view class="fields-box">
					<view>地区</view>
					<view>
						<lb-picker ref="picker"
						  v-model="area"
						  mode="multiSelector"
						  :list="list"
						  :level="3"
						  @change="handleChange"
						  @confirm="handleConfirm"
						  @cancel="handleCancel">
						</lb-picker>
						<input @click="handleTap('picker')" type="text" class="uni-input" :value="area_name" disabled="true" placeholder="请选择您所在地区" />
					</view>
				</view>
				
				<view class="fields-box">
					<view>地址</view>
					<view>
						<input type="text" class="uni-input" name="addressDetail" :value="addressDetail" placeholder="请填写您所在地址" />
					</view>
				</view>
				
				<view class="switch-box">
					<view>默认地址</view>
					<view>
						<switch :checked="is_default" @change="switchChange" />
					</view>
				</view>
				
				<view class="btn">
					<button form-type="submit">提 交</button>
				</view>
				
			</form>
		</view>
		
		
	</view>
</template>

<script> 
	import areaData from '@/common/area-data-min'
	import * as check from '@/common/check'
	import navbar from "@/components/navbar/navbar";
    export default {
		components: {
			navbar
		},
		data(){
			return {
				id: 0,
				name: "",
				tel: "",
				province: "",
				county: "",
				city: "",
				areaCode: [],
				addressDetail: "",
				is_default: false,
				list: areaData,
				area_name: '',
				area: [],
				isSelected: false
			};
		},
		onLoad(options) {
			this.id = options.id != undefined ? options.id : 0;
			this.isSelected = this.$storage.get("ORDER_CONFIRM_SELECT") ? true : false;
		},
		onShow() {
			let users = this.$storage.getJson("users");
			if(users == null){
				this.$utils.navigateTo('public/login');
			}else{
				this.$http.getAddressData({ id: this.id,client_type: "0" }).then(res=>{
					if(res.data.area_name != undefined){
						let arr = res.data.area_name.split(",");
						this.name = res.data.name;
						this.tel = res.data.tel;
						this.province = arr[0];
						this.county = arr[2];
						this.city = arr[1];
					}
					this.areaCode = [res.data.province,res.data.county,res.data.city];
					this.addressDetail = res.data.addressDetail;
					this.is_default = res.data.isDefault ? true : false;
					this.area_name = res.data.area_name;
					this.area = [res.data.province,res.data.county,res.data.city];
				});
			}
		},
		onBackPress(e) {
			this.$storage.remove("ORDER_CONFIRM_SELECT");
			return false;
		},
        methods:{
			switchChange(e){
				this.is_default = e.target.value;
			},
			formSubmit(e){
				let formdata = e.detail.value;
				if(formdata.name.length <= 0){
					this.$utils.msg("请填写用户名");
					return ;
				}
				
				if(!check.checkPhone(formdata.tel)){
					this.$utils.msg("您填写的手机号码不正确！");
					return ;
				}
				
				if(formdata.addressDetail.length <= 0){
					this.$utils.msg("请填写收货地址！");
					return ;
				}else if(formdata.addressDetail.length >= 120){
					this.$utils.msg("您填写的收货地址过长，请勿超过120个字符！");
					return ;
				}
				
				if(this.areaCode.length <= 0){
					this.$utils.msg("请选择所在地区！");
					return ;
				}
				
				let params = {
					id: this.id,
					client_type: "0",
					name: formdata.name,
					tel: formdata.tel,
					province: this.province,
					county: this.county,
					city: this.city,
					areaCode: this.areaCode,
					addressDetail: formdata.addressDetail,
					is_default: this.is_default ? 1 : 0,
				};
				this.$http.editorAddress(params).then((res)=>{
					if(res.status){
						if(this.isSelected){
							params.id = res.data;
							this.onReturnOrderAddress(params);
						}else{
							this.$utils.navigateTo("ucenter/address");
						}
					}else{
						this.$utils.msg(res.info);
					}
				});
			},
			onReturnOrderAddress(data){
				this.$utils.prePage().address = {
					id: data.id,
					name: data.name,
					tel: data.tel,
					address: data.addressDetail
				};
				
				this.$utils.prePage().params.address_id = data.id;
				this.$utils.prePage().chosenAddressId = data.id;
				this.$utils.prePage().isAddressStatus = false;
				this.$storage.remove("ORDER_CONFIRM_SELECT");
				this.$utils.navigateBack();
			},
			handleTap (picker) {
				this.$refs[picker].show()
			},
			handleChange (item) {
				//console.log('change::', item)
			},
			handleConfirm (data) {
				// this.province = data.value[0];
				// this.county = data.value[1];
				// this.city = data.value[2] != undefined ? data.value[2] : 0;
				let arr = [];
				for(let i in data.item){
					arr.push(data.item[i].label);
				}
				// console.log(arr);
				this.province = arr[0];
				this.city = arr[1];
				this.county = arr[2] != undefined ? arr[2] : 0;
				
				if(arr.length > 0){
					this.area_name = arr.join(",");
				}
				
				this.areaCode = data.value;
			},
			handleCancel (item) {
				//console.log('cancel::', item)
			}
		} 
    }  
</script>

<style lang="scss" scoped>
	.wrap{
	    width: 100%;
	    height: 100vh;
	    background-color: #fff;
	}
	
	.theForm{
		width: 650rpx;
		padding-top: 50rpx;
		margin: 0 auto;
		.fields-box{
			width: 100%;
			float: left;
			font-size: 31rpx;
			height: 110rpx;
			line-height: 110rpx;
			border: 2rpx solid #e0e0e0;
			border-left: 0;
			border-right: 0;
			&:nth-child(1){
				border-top: 0;
				border-bottom: 0;
			}
			&:nth-child(2){
				border-bottom: 0;
			}
			&:nth-child(3){
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
		.switch-box{
			background-color: #fff;
			width: 100%;
			float: left;
			font-size: 31rpx;
			height: 110rpx;
			line-height: 110rpx;
			view:first-child {
				float: left;
				color: #999;
			}
			view:last-child {
				float: right;
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
	}
</style>